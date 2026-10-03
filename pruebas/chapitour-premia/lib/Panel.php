<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

class PanelError extends RuntimeException {}
require_once __DIR__.'/Rewards.php';
require_once __DIR__.'/GoogleAuth.php';
require_once __DIR__.'/Challenges.php';

final class ChapitourPanel
{
    private $db;
    private $schema;
    private $rewards;
    private $googleAuth;
    private $googleSchema;
    private $monthlyChallenges;
    private $community;
    public function __construct(PDO $db, ?ChapitourGoogleAuth $googleAuth=null) { $this->db = $db; $this->googleAuth=$googleAuth??new ChapitourGoogleAuth(); }
    private function googleSchemaReady(): bool {
        if ($this->googleSchema===null) { $this->googleSchema=(bool)$this->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='cp_panel_google'")->fetchColumn(); }
        return $this->googleSchema;
    }
    private function installGoogleSchema(): void {
        if ($this->googleSchemaReady()) { return; }
        $this->db->exec(file_get_contents(__DIR__.'/../database/google_cp.sql'));$this->googleSchema=true;
    }
    private function rewards(): ChapitourRewards {
        if (!$this->rewards) { $this->rewards=new ChapitourRewards($this->db); }
        return $this->rewards;
    }
    private function monthlyChallenges(): ChapitourChallenges {
        if (!$this->monthlyChallenges) { $this->monthlyChallenges=new ChapitourChallenges($this->db); }
        return $this->monthlyChallenges;
    }
    private function community(): ChapitourCommunity {
        if (!$this->community) { $this->community=new ChapitourCommunity($this->db); }
        return $this->community;
    }
    private function query(string $sql, array $args = []): PDOStatement {
        $s = $this->db->prepare($sql); $s->execute($args); return $s;
    }
    private function row(string $sql, array $args = []): ?array {
        $r = $this->query($sql, $args)->fetch(PDO::FETCH_ASSOC); return $r ?: null;
    }
    private function error(string $message, int $status = 422): void { throw new PanelError($message, $status); }
    private function text(array $input, string $key, int $max, bool $required = true): string {
        $s = $input[$key] ?? '';
        if (!is_string($s) || !mb_check_encoding($s, 'UTF-8')) { $this->error('Revisa el campo '.$key.'.'); }
        $s = trim($s);
        if (($required && $s === '') || mb_strlen($s) > $max) { $this->error('Revisa el campo '.$key.'.'); }
        return $s;
    }
    private function password(array $input, string $key = 'password', bool $new = true): string {
        $s = $input[$key] ?? '';
        if (!is_string($s) || strlen($s) > 72 || ($new ? strlen($s) < 8 : $s === '')) {
            $this->error('La contraseña debe tener entre 8 y 72 bytes.');
        }
        return $s;
    }
    private function id(array $input, string $key): int {
        $s = $this->text($input, $key, 20);
        if (!ctype_digit($s) || (int)$s < 1) { $this->error('Identificador no válido.'); }
        return (int)$s;
    }
    private function transaction(callable $fn) {
        $this->db->beginTransaction();
        try { $r = $fn(); $this->db->commit(); return $r; }
        catch (Throwable $e) { if ($this->db->inTransaction()) { $this->db->rollBack(); } throw $e; }
    }
    private function accountLock(callable $fn) {
        if ((int)$this->query("SELECT GET_LOCK('chapitour_panel_accounts',5)")->fetchColumn() !== 1) {
            $this->error('Otra cuenta se está actualizando. Intenta de nuevo.', 409);
        }
        try { return $fn(); } finally { $this->query("SELECT RELEASE_LOCK('chapitour_panel_accounts')"); }
    }
    private function uniqueEmail(string $email, ?int $exceptClient = null): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $this->error('Escribe un correo válido.'); }
        if ($this->row('SELECT id FROM cp_usuarios WHERE usuario=?', [$email]) ||
            $this->row('SELECT id FROM cp_clientes WHERE email=? AND id<>?', [$email, $exceptClient ?? 0])) {
            $this->error('Este correo ya tiene una cuenta.', 409);
        }
    }
    private function audit(?array $actor, string $action, ?int $id = null): void {
        $this->query('INSERT INTO cp_auditoria(usuario_id,accion,entidad_id,datos) VALUES (?,?,?,?)', [
            $actor && $actor['kind'] === 'staff' ? $actor['db_id'] : null,
            $action, $id, json_encode(['origen'=>'paneles', 'cliente_id'=>$actor && $actor['kind']==='client' ? $actor['db_id'] : null])
        ]);
    }
    public function schemaReady(): bool {
        if ($this->schema === null) {
            $count = $this->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name IN ('cp_panel_clientes','cp_panel_promociones','cp_panel_retos','cp_panel_progreso')")->fetchColumn();
            $this->schema = (int)$count === 4;
        }
        return $this->schema;
    }
    public function installSchema(): void {
        // Called only after administrator credentials have been verified, never by a public request.
        if ($this->schemaReady()) { return; }
        if ((int)$this->query("SELECT GET_LOCK('chapitour_panel_schema',10)")->fetchColumn() !== 1) {
            $this->error('La configuración se está actualizando. Intenta de nuevo.', 503);
        }
        try {
            $sql = file_get_contents(__DIR__.'/../database/paneles_cp.sql');
            $sql = preg_replace('/^\s*--.*$/m', '', $sql);
            foreach (explode(';', $sql) as $statement) {
                if (trim($statement) !== '') { $this->db->exec($statement); }
            }
            $this->schema = true;
        } finally { $this->query("SELECT RELEASE_LOCK('chapitour_panel_schema')"); }
    }
    public function actor(bool $lock = false): ?array {
        $auth = $_SESSION['auth'] ?? null;
        if (!is_array($auth) || !in_array($auth['kind'] ?? '', ['client','staff'], true)) { return null; }
        $table = $auth['kind'] === 'client' ? 'cp_clientes' : 'cp_usuarios';
        $r = $this->row('SELECT * FROM '.$table.' WHERE id=?'.($lock ? ' FOR UPDATE' : ''), [$auth['id']]);
        if (!$r || !(int)$r['activo'] || (int)$r['version_sesion'] !== (int)$auth['version']) {
            unset($_SESSION['auth']); return null;
        }
        if ($auth['kind'] === 'staff' && !in_array($r['rol'], ['admin','aliado'], true)) { unset($_SESSION['auth']); return null; }
        if ($auth['kind'] === 'staff' && $r['rol'] === 'aliado' &&
            !$this->row('SELECT id FROM cp_negocios WHERE id=? AND activo=1', [$r['negocio_id']])) {
            unset($_SESSION['auth']); return null;
        }
        $client = $auth['kind'] === 'client';
        $city = $client && $this->schemaReady() ? $this->row('SELECT ciudad FROM cp_panel_clientes WHERE cliente_id=?', [$r['id']]) : null;
        return [
            'id'=>($client?'client-':'staff-').$r['id'], 'db_id'=>(int)$r['id'], 'kind'=>$auth['kind'],
            'name'=>$client ? $r['nombre'] : $r['usuario'], 'email'=>$client ? $r['email'] : $r['usuario'],
            'role'=>$client ? 'client' : ($r['rol']==='admin'?'admin':'ally'),
            'business_id'=>$client || $r['negocio_id']===null ? null : (string)$r['negocio_id'],
            'city'=>$city['ciudad'] ?? '', 'created_at'=>$client ? strtotime($r['creado_at'].' UTC') : null,
            'demo'=>false, 'must_change_password'=>!$client && (bool)$r['cambiar_password'],
            'version'=>(int)$r['version_sesion'], 'password_hash'=>$r['password_hash']
        ];
    }
    private function requireActor(array $roles, bool $lock = false, bool $allowPasswordChange = false): array {
        $a = $this->actor($lock);
        if (!$a || !in_array($a['role'], $roles, true)) { $this->error('No tienes permiso para realizar esta acción.', 403); }
        if ($a['must_change_password'] && !$allowPasswordChange) { $this->error('Cambia tu contraseña temporal para continuar.', 403); }
        return $a;
    }
    private function signIn(string $kind, array $row): void {
        session_regenerate_id(true);
        $_SESSION = ['csrf'=>bin2hex(random_bytes(32)), 'auth'=>['kind'=>$kind, 'id'=>(int)$row['id'], 'version'=>(int)$row['version_sesion']]];
    }
    private function limit(string $key, int $max, int $minutes): void {
        $key = hash('sha256', 'paneles-v1:'.$key);
        $this->query('INSERT INTO cp_limites(clave,cantidad,vence_at) VALUES (?,1,DATE_ADD(UTC_TIMESTAMP(),INTERVAL '.$minutes.' MINUTE)) ON DUPLICATE KEY UPDATE cantidad=IF(vence_at<=UTC_TIMESTAMP(),1,cantidad+1), vence_at=IF(vence_at<=UTC_TIMESTAMP(),VALUES(vence_at),vence_at)', [$key]);
        if ((int)$this->query('SELECT cantidad FROM cp_limites WHERE clave=?', [$key])->fetchColumn() > $max) {
            $this->error('Demasiados intentos. Espera unos minutos antes de volver a intentar.', 429);
        }
    }
    private function login(array $input): void {
        $username = mb_strtolower($this->text($input, 'email', 190));
        $password = $this->password($input, 'password', false);
        $this->limit('login-ip:'.($_SERVER['REMOTE_ADDR'] ?? 'local'), 60, 10);
        $this->limit('login-user:'.$username, 12, 10);
        $staff = $this->row('SELECT * FROM cp_usuarios WHERE usuario=?', [$username]);
        $client = $this->row('SELECT * FROM cp_clientes WHERE email=?', [$username]);
        $row = $staff ?: $client;
        // A fixed valid bcrypt hash also makes missing-account requests perform password verification.
        $hash = $row['password_hash'] ?? '$2y$12$KgZZoGYbZIiQGWmSYebuf.rnl83bMMAA8BLWpeLcVmxEQPS5Ch6ly';
        if (!password_verify($password, $hash) || !$row || !(int)$row['activo'] || ($staff && $client)) {
            $this->error('Correo o usuario y contraseña incorrectos.', 401);
        }
        if ($staff && $staff['rol']==='aliado' && !$this->row('SELECT id FROM cp_negocios WHERE id=? AND activo=1', [$staff['negocio_id']])) {
            $this->error('Correo o usuario y contraseña incorrectos.', 401);
        }
        if ($staff && $staff['rol']==='admin') { $this->installSchema(); $this->rewards()->install(); $this->installGoogleSchema(); $this->monthlyChallenges()->install(); $this->community()->install(); }
        $this->signIn($staff ? 'staff' : 'client', $row);
        $this->audit($this->actor(), 'inicio_sesion');
    }
    private function createClient(string $email, string $name): array {
        $this->uniqueEmail($email);
        // Google-only accounts have no user-known password; existing password accounts remain supported.
        $hash=password_hash(bin2hex(random_bytes(32)),PASSWORD_BCRYPT);
        $this->query('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)',[$name,$email,$hash]);
        $id=(int)$this->db->lastInsertId();
        $this->query("INSERT INTO cp_panel_clientes(cliente_id,ciudad) VALUES (?,'')",[$id]);
        $this->query('INSERT INTO cp_visitantes(identidad_hash,ip_hash,referido_token) VALUES (?,?,?)',[hash('sha256',random_bytes(32)),hash('sha256',random_bytes(32)),bin2hex(random_bytes(16))]);
        $visitor=(int)$this->db->lastInsertId();
        $this->query('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$id,$visitor]);
        $this->rewards()->welcome($id);
        $this->community()->registerMember($id);
        return ['id'=>$id,'version_sesion'=>1];
    }
    private function googleLogin(array $input): void {
        if (!$this->googleSchemaReady()) { $this->error('Un administrador debe terminar de configurar el acceso con Google.',503); }
        $this->limit('google-ip:'.($_SERVER['REMOTE_ADDR']??'local'),30,10);
        $identity=$this->googleAuth->verify($this->text($input,'credential',12000));
        $current=$this->actor();
        if ($current && $current['role']!=='client') { $this->error('Cierra la sesión de administrador o aliado antes de entrar como cliente.',403); }
        $row=$this->accountLock(function () use ($identity,$current) {
            return $this->transaction(function () use ($identity,$current) {
                $linked=$this->row('SELECT c.* FROM cp_clientes c JOIN cp_panel_google g ON g.cliente_id=c.id WHERE g.subject_hash=? FOR UPDATE',[$identity['subject_hash']]);
                if ($linked) {
                    if (!(int)$linked['activo']) { $this->error('Esta cuenta está desactivada.',403); }
                    if ($current && $current['db_id']!==(int)$linked['id']) { $this->error('La cuenta de Google corresponde a otro cliente.',409); }
                    return $linked;
                }
                if ($this->row('SELECT id FROM cp_usuarios WHERE usuario=?',[$identity['email']])) { $this->error('Este correo corresponde a un administrador o aliado. Ingresa con tu contraseña.',409); }
                $existing=$this->row('SELECT * FROM cp_clientes WHERE email=? FOR UPDATE',[$identity['email']]);
                if ($existing) {
                    if (!$current || $current['db_id']!==(int)$existing['id']) { $this->error('Ya tienes una cuenta con este correo. Ingresa con tu contraseña y vincula Google desde Mi perfil.',409); }
                    $this->requireActor(['client'],true);
                    if ($this->row('SELECT cliente_id FROM cp_panel_google WHERE cliente_id=?',[$current['db_id']])) { $this->error('Esta cuenta ya está vinculada a otra identidad de Google.',409); }
                    $row=$existing;
                } else {
                    if ($current) { $this->error('Selecciona la cuenta de Google con el mismo correo de tu perfil.',409); }
                    $row=$this->createClient($identity['email'],$identity['name']);
                }
                $this->query('INSERT INTO cp_panel_google(cliente_id,subject_hash) VALUES (?,?)',[$row['id'],$identity['subject_hash']]);
                return $row;
            });
        });
        $this->signIn('client',$row);
        $this->audit($this->actor(),'inicio_sesion_google');
    }
    private function changePassword(array $input): void {
        $a = $this->requireActor(['client','ally','admin'], false, true);
        $current = $this->password($input, 'current_password', false);
        $next = $this->password($input, 'new_password');
        if (!password_verify($current, $a['password_hash'])) { $this->error('La contraseña actual no coincide.'); }
        if ($current === $next) { $this->error('Elige una contraseña diferente a la temporal.'); }
        if ($next !== ($input['confirm_password'] ?? null)) { $this->error('Las contraseñas nuevas no coinciden.'); }
        $hash = password_hash($next, PASSWORD_BCRYPT);
        $this->transaction(function () use ($a, $hash, $current) {
            $fresh = $this->requireActor(['client','ally','admin'], true, true);
            if (!password_verify($current, $fresh['password_hash'])) { $this->error('La contraseña cambió. Vuelve a iniciar sesión.',409); }
            $table = $a['kind']==='client' ? 'cp_clientes' : 'cp_usuarios';
            $this->query('UPDATE '.$table.' SET password_hash=?,version_sesion=version_sesion+1'.($a['kind']==='staff'?',cambiar_password=0':'').' WHERE id=?', [$hash,$a['db_id']]);
            $this->audit($a, 'password_cambiado');
        });
        $this->signIn($a['kind'], ['id'=>$a['db_id'],'version_sesion'=>$a['version']+1]);
    }
    private function profile(array $input): void {
        $a = $this->requireActor(['client']);
        $email = mb_strtolower($this->text($input, 'email', 150));
        $name = $this->text($input, 'name', 80); $city = $this->text($input, 'city', 80, false);
        $preferences=array_key_exists('public_name',$input)||array_key_exists('ranking_visible',$input);
        $alias=$preferences?$this->text($input,'public_name',40,false):'';
        $visible=$input['ranking_visible']??'0';
        if ($preferences && !in_array($visible,['0','1'],true)) { $this->error('Revisa tu preferencia para aparecer en el ranking.'); }
        $this->accountLock(function () use ($a, $email, $name, $city, $preferences, $alias, $visible) {
            $this->transaction(function () use ($a, $email, $name, $city, $preferences, $alias, $visible) {
                $this->requireActor(['client'], true); $this->uniqueEmail($email, $a['db_id']);
                if ($email!==$a['email'] && $this->googleSchemaReady() && $this->row('SELECT cliente_id FROM cp_panel_google WHERE cliente_id=?',[$a['db_id']])) { $this->error('El correo de esta cuenta está vinculado a Google. Puedes actualizar tu nombre y ciudad.'); }
                $this->query('UPDATE cp_clientes SET nombre=?,email=? WHERE id=?', [$name,$email,$a['db_id']]);
                $this->query('INSERT INTO cp_panel_clientes(cliente_id,ciudad) VALUES (?,?) ON DUPLICATE KEY UPDATE ciudad=VALUES(ciudad)', [$a['db_id'],$city]);
                if ($preferences) { $this->community()->savePreferences($a['db_id'],$alias,$visible==='1'); }
            });
        });
    }
    private function profilePhoto(bool $remove): void {
        $a=$this->requireActor(['client']);
        $this->limit('photo-client:'.$a['db_id'],20,10);
        $photo=$remove?null:$this->community()->prepareUpload(is_array($_FILES['avatar']??null)?$_FILES['avatar']:[]);
        $this->transaction(function () use ($a,$photo) {
            $this->requireActor(['client'],true);
            $this->community()->savePhoto($a['db_id'],$photo);
            $this->audit($a,$photo===null?'foto_perfil_eliminada':'foto_perfil_actualizada');
        });
    }
    private function deleteAccount(array $input): void {
        $a = $this->requireActor(['client']);
        if (($input['confirm'] ?? '') !== $a['id']) { $this->error('Confirma la cuenta que quieres eliminar.'); }
        $this->transaction(function () use ($a) {
            $this->requireActor(['client'], true);
            // Retain prize/redemption references, remove identifying profile data and revoke every session.
            $this->query("UPDATE cp_clientes SET nombre='Cuenta eliminada',email=?,password_hash=?,activo=0,version_sesion=version_sesion+1 WHERE id=?", ['eliminada-'.$a['db_id'].'-'.bin2hex(random_bytes(8)).'@deleted.invalid',password_hash(bin2hex(random_bytes(24)),PASSWORD_BCRYPT),$a['db_id']]);
            $this->query('DELETE FROM cp_panel_clientes WHERE cliente_id=?', [$a['db_id']]);
            $this->query('DELETE FROM cp_panel_progreso WHERE cliente_id=?', [$a['db_id']]);
            $this->monthlyChallenges()->deleteProgress($a['db_id']);
            $this->community()->delete($a['db_id']);
            $this->audit($a, 'cuenta_eliminada', $a['db_id']);
        });
        $this->logout();
    }
    private function logout(): void { $_SESSION = ['csrf'=>bin2hex(random_bytes(32))]; session_regenerate_id(true); }
    private function retireAllyAccess(int $accountId): void {
        // Keep the account ID for redemption/audit references, but release its email and revoke access.
        $this->query("UPDATE cp_usuarios SET usuario=?,password_hash=?,activo=0,version_sesion=version_sesion+1 WHERE id=? AND rol='aliado'", [
            'eliminada-'.$accountId.'-'.bin2hex(random_bytes(8)).'@deleted.invalid',
            password_hash(bin2hex(random_bytes(24)),PASSWORD_BCRYPT),$accountId
        ]);
    }
    private function saveBusiness(array $input): void {
        $a = $this->requireActor(['admin']);
        $email = mb_strtolower($this->text($input, 'email', 100));
        $hash = password_hash($this->password($input), PASSWORD_BCRYPT);
        $existing = $this->text($input, 'business_id', 20, false);
        $name = $this->text($input, 'name', 80, $existing==='');
        $this->accountLock(function () use ($a, $input, $email, $hash, $existing, $name) {
            $this->transaction(function () use ($a, $input, $email, $hash, $existing, $name) {
                $this->requireActor(['admin'], true);
                // Accounts deleted by earlier versions kept their email; release only inactive ally accesses.
                $previous=$this->row("SELECT id FROM cp_usuarios WHERE usuario=? AND rol='aliado' AND activo=0 FOR UPDATE",[$email]);
                if ($previous) { $this->retireAllyAccess((int)$previous['id']); $this->audit($a,'acceso_aliado_archivado',(int)$previous['id']); }
                $this->uniqueEmail($email);
                if ($existing !== '') {
                    $id = $this->id($input,'business_id');
                    if (!$this->row('SELECT id FROM cp_negocios WHERE id=? AND activo=1 FOR UPDATE', [$id])) { $this->error('Negocio no encontrado.',404); }
                    if ($this->row("SELECT id FROM cp_usuarios WHERE negocio_id=? AND rol='aliado' AND activo=1", [$id])) { $this->error('Este negocio ya tiene una cuenta activa.',409); }
                } else {
                    $this->query("INSERT INTO cp_negocios(slug,nombre,categoria) VALUES (?,?,'Aliado de Chapitour')", ['aliado-'.bin2hex(random_bytes(10)),$name]);
                    $id = (int)$this->db->lastInsertId();
                }
                $this->query("INSERT INTO cp_usuarios(negocio_id,usuario,password_hash,rol,activo,cambiar_password) VALUES (?,?,?,'aliado',1,1)", [$id,$email,$hash]);
                $this->audit($a, 'aliado_creado', $id);
            });
        });
    }
    private function deleteBusiness(array $input): void {
        $a = $this->requireActor(['admin']); $id = $this->id($input,'id');
        if ((string)($input['confirm'] ?? '') !== (string)$id) { $this->error('Confirma la cuenta de acceso que quieres eliminar.'); }
        $accountId=$this->id($input,'account_id');
        $this->accountLock(function () use ($a,$id,$accountId) {
            $this->transaction(function () use ($a,$id,$accountId) {
                $this->requireActor(['admin'],true);
                if (!$this->row('SELECT id FROM cp_negocios WHERE id=? FOR UPDATE', [$id])) { $this->error('Negocio no encontrado.',404); }
                // Bind the confirmation to this access, so a stale dialog cannot delete its replacement.
                if (!$this->row("SELECT id FROM cp_usuarios WHERE id=? AND negocio_id=? AND rol='aliado' AND activo=1 FOR UPDATE",[$accountId,$id])) {
                    $this->error('Esta cuenta ya no está disponible. Actualiza el panel.',409);
                }
                $this->retireAllyAccess($accountId);
                $this->audit($a,'cuenta_aliado_eliminada',$accountId);
            });
        });
    }
    private function savePromotion(array $input): void {
        $a = $this->requireActor(['admin']); $business = $this->id($input,'business_id');
        $id = $this->text($input,'id',20,false); if ($id !== '') { $id = $this->id($input,'id'); }
        $description = $this->text($input,'description',500);
        $publication = $this->text($input,'publication',20);
        if (!in_array($publication,['draft','approved'],true)) { $this->error('Selecciona borrador o aprobada.'); }
        $included = $this->text($input,'included',350,false); $hours = $this->text($input,'hours',250,false); $restrictions = $this->text($input,'restrictions',350,false);
        $phone = preg_replace('/[\s+()-]/','',$this->text($input,'whatsapp',25,false));
        if ($phone!=='' && !preg_match('/^[1-9][0-9]{7,14}$/',$phone)) { $this->error('Revisa el WhatsApp e incluye el indicativo del país.'); }
        if ($publication==='approved') {
            $missing=[];
            foreach (['WhatsApp del negocio'=>$phone,'Productos o servicios incluidos'=>$included,'Horarios'=>$hours,'Restricciones'=>$restrictions] as $label=>$value) {
                if ($value==='') { $missing[]=$label; }
            }
            if (($input['confirmed'] ?? false)!==true) { $missing[]='marcar la confirmación con el negocio'; }
            if ($missing) { $this->error('Para aprobar esta promoción falta: '.implode('; ',$missing).'.'); }
        }
        $conditions = 'Productos o servicios: '.$included."\nHorarios: ".$hours."\nRestricciones: ".$restrictions;
        if (mb_strlen($conditions)>1000) { $this->error('Las condiciones completas no pueden superar 1000 caracteres.'); }
        $this->transaction(function () use ($a,$business,$id,$description,$publication,$included,$hours,$restrictions,$phone,$conditions) {
            $this->requireActor(['admin'],true);
            if (!$this->row('SELECT id FROM cp_negocios WHERE id=? AND activo=1 FOR UPDATE',[$business])) { $this->error('Selecciona un negocio disponible.'); }
            $existing = $this->row('SELECT p.*,m.publicacion FROM cp_promociones p LEFT JOIN cp_panel_promociones m ON m.promocion_id=p.id WHERE p.negocio_id=? FOR UPDATE',[$business]);
            if ($id!=='' && (!$existing || (int)$existing['id']!==$id || $existing['publicacion']==='archived')) { $this->error('Promoción no encontrada en este negocio.',404); }
            if ($id==='' && $existing && $existing['publicacion']!=='archived') { $this->error('Este negocio ya tiene una promoción. Utiliza Editar.',409); }
            // Preserve the legacy unique business index and all issued prize snapshots.
            if ($existing) {
                $id=(int)$existing['id'];
                $this->query('UPDATE cp_promociones SET titulo=?,descripcion=?,condiciones=?,porcentaje=NULL,activa=?,actualizada_at=UTC_TIMESTAMP() WHERE id=?', [mb_substr($description,0,160),$description,$conditions,$publication==='approved'?1:0,$id]);
            } else {
                $this->query('INSERT INTO cp_promociones(negocio_id,titulo,descripcion,condiciones,activa) VALUES (?,?,?,?,?)', [$business,mb_substr($description,0,160),$description,$conditions,$publication==='approved'?1:0]);
                $id=(int)$this->db->lastInsertId();
            }
            $approved = $publication==='approved';
            $this->query('INSERT INTO cp_panel_promociones(promocion_id,publicacion,beneficio,incluidos,horarios,restricciones,whatsapp_confirmado,aprobada_por,aprobada_at) VALUES (?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE publicacion=VALUES(publicacion),beneficio=VALUES(beneficio),incluidos=VALUES(incluidos),horarios=VALUES(horarios),restricciones=VALUES(restricciones),whatsapp_confirmado=VALUES(whatsapp_confirmado),aprobada_por=VALUES(aprobada_por),aprobada_at=VALUES(aprobada_at)', [$id,$publication,$description,$included,$hours,$restrictions,$approved?$phone:'',$approved?$a['db_id']:null,$approved?gmdate('Y-m-d H:i:s'):null]);
            $this->query('UPDATE cp_negocios SET whatsapp=? WHERE id=?',[$phone,$business]);
            $this->audit($a,$approved?'promocion_aprobada':'promocion_guardada',$id);
        });
    }
    private function deletePromotion(array $input): void {
        $a=$this->requireActor(['admin']); $id=$this->id($input,'id');
        if ((string)($input['confirm']??'')!==(string)$id) { $this->error('Confirma la promoción que quieres eliminar.'); }
        $this->transaction(function () use ($a,$id) {
            $this->requireActor(['admin'],true);
            $p=$this->row('SELECT * FROM cp_promociones WHERE id=? FOR UPDATE',[$id]);
            if (!$p) { $this->error('Promoción no encontrada.',404); }
            $this->query('UPDATE cp_promociones SET activa=0 WHERE id=?',[$id]);
            $this->query("INSERT INTO cp_panel_promociones(promocion_id,publicacion,beneficio) VALUES (?,'archived',?) ON DUPLICATE KEY UPDATE publicacion='archived',aprobada_por=NULL,aprobada_at=NULL",[$id,$p['titulo']]);
            $this->audit($a,'promocion_eliminada',$id);
        });
    }
    private function codeQuery(array $a, ?string $code = null, bool $lock = false): PDOStatement {
        $sql='SELECT p.*,COALESCE(d.negocio,n.nombre) AS negocio_nombre,COALESCE(NULLIF(d.whatsapp,\'\'),n.whatsapp) AS telefono FROM cp_premios p JOIN cp_negocios n ON n.id=p.negocio_id LEFT JOIN cp_premio_detalles d ON d.premio_id=p.id WHERE 1=1'; $args=[];
        if ($a['role']==='ally') { $sql.=' AND p.negocio_id=?'; $args[]=$a['business_id']; }
        if ($a['role']==='client') { $sql.=' AND EXISTS (SELECT 1 FROM cp_cliente_visitantes cv WHERE cv.visitante_id=p.visitante_id AND cv.cliente_id=?)'; $args[]=$a['db_id']; }
        if ($code!==null) { $sql.=' AND p.codigo=?'; $args[]=$code; }
        $sql.=' ORDER BY p.creado_at DESC,p.id DESC'.($lock?' FOR UPDATE':'');
        return $this->query($sql,$args);
    }
    private function redeem(array $input): void {
        $code=$this->text($input,'code',40);
        if (($input['confirm']??'')!==$code) { $this->error('Confirma el código que deseas redimir.'); }
        $this->transaction(function () use ($code) {
            $a=$this->requireActor(['ally','admin'],true);
            $p=$this->codeQuery($a,$code,true)->fetch(PDO::FETCH_ASSOC);
            if (!$p) { $this->error('Código no encontrado.',404); }
            $changed=$this->query('UPDATE cp_premios SET redimido_at=UTC_TIMESTAMP(),redimido_por=? WHERE id=? AND redimido_at IS NULL AND vence_at>UTC_TIMESTAMP()',[$a['db_id'],$p['id']])->rowCount();
            if ($changed!==1) { $this->error('Este código ya fue redimido o está vencido.',409); }
            $this->audit($a,'premio_redimido',(int)$p['id']);
        });
    }
    private function whatsapp(array $input): array {
        $a=$this->requireActor(['client']);
        $p=$this->codeQuery($a,$this->text($input,'code',40))->fetch(PDO::FETCH_ASSOC);
        if (!$p) { $this->error('Código no encontrado.',404); }
        $c=$this->codeDto($p);
        if ($c['status']!=='Activo') { $this->error('Este código ya fue redimido o está vencido.',409); }
        if (!preg_match('/^[1-9][0-9]{7,14}$/',$p['telefono'])) { $this->error('El WhatsApp del negocio está pendiente de confirmar.'); }
        $message='Hola, '.$c['business_name'].'. Quiero redimir la promoción: '.$c['description'].' Mi código único es '.$c['code'].'.';
        return ['whatsapp_url'=>'https://wa.me/'.$p['telefono'].'?text='.rawurlencode($message)];
    }
    private function codeDto(array $p): array {
        $expires=strtotime($p['vence_at'].' UTC'); $redeemed=$p['redimido_at']===null ? null : strtotime($p['redimido_at'].' UTC');
        return ['code'=>$p['codigo'],'business_id'=>(string)$p['negocio_id'],'business_name'=>$p['negocio_nombre'],'promotion_id'=>(string)$p['promocion_id'],
            'description'=>$p['descripcion']!==''?$p['descripcion']:$p['titulo'],'conditions'=>$p['condiciones'],
            'created_at'=>strtotime($p['creado_at'].' UTC'),'expires_at'=>$expires,'redeemed_at'=>$redeemed,
            'status'=>$redeemed!==null?'Redimido':($expires<=time()?'Vencido':'Activo'),'demo'=>false,
            'whatsapp_available'=>(bool)preg_match('/^[1-9][0-9]{7,14}$/',$p['telefono'])];
    }
    private function safePath(string $path): string {
        if ($path==='' || preg_match('/[\x00-\x20<>"\'\\\\]/',$path) || strpos($path,'..')!==false || strpos($path,':')!==false || $path[0]==='/') { return ''; }
        return $path;
    }
    private function challenges(array $a): array {
        $month=$this->monthlyChallenges()->month();
        $this->query('INSERT IGNORE INTO cp_panel_retos(mes,tipo,titulo,objetivo) VALUES (?,?,?,?)',[$month,'compartir','Envía esta página a 20 personas',20]);
        $share=$this->query('SELECT r.tipo AS type,r.titulo AS title,r.objetivo AS target,r.mes AS month,CASE WHEN r.criterio_verificacion IS NOT NULL AND TRIM(r.criterio_verificacion)<>\'\' AND p.cantidad_verificada<=r.objetivo THEN p.cantidad_verificada ELSE NULL END AS progress FROM cp_panel_retos r LEFT JOIN cp_panel_progreso p ON p.reto_id=r.id AND p.cliente_id=? WHERE r.mes=? AND r.tipo=\'compartir\'',[$a['db_id'],$month])->fetchAll(PDO::FETCH_ASSOC);
        return array_merge($share,$this->monthlyChallenges()->state($a['db_id']));
    }
    private function completeChallenge(string $action,array $input): void {
        $this->requireActor(['client']);
        $this->transaction(function () use ($action,$input) {
            $a=$this->requireActor(['client'],true);
            $changed=$action==='record_photo' ? $this->monthlyChallenges()->recordPhoto($a['db_id'],$input) : $this->monthlyChallenges()->answer($a['db_id'],$input);
            if ($changed) { $this->audit($a,$action==='record_photo'?'meta_foto_declarada':'meta_preguntas_completada'); }
        });
    }
    public function state(): array {
        $a=$this->actor(); $ready=$this->schemaReady();
        // An already authenticated administrator can apply this additive update without signing out.
        if ($ready && $a && $a['role']==='admin' && !$a['must_change_password']) { $this->rewards()->install(); $this->installGoogleSchema(); $this->monthlyChallenges()->install(); $this->community()->install(); }
        $base=['csrf'=>$_SESSION['csrf'],'user'=>null,'businesses'=>[],'promotions'=>[],'codes'=>[],'challenges'=>[],
            'storage'=>'mysql','setup_required'=>!$ready,'server_time'=>time(),'campaign'=>$this->rewards()->status($a),'google_auth'=>$this->googleAuth->settings()];
        if (!$this->googleSchemaReady()) { $base['google_auth']['enabled']=false; }
        if ($a) { $base['user']=$a; unset($base['user']['db_id'],$base['user']['kind'],$base['user']['version'],$base['user']['password_hash']); }
        if ($a && $a['role']==='client') { $base['user']['google_linked']=$this->googleSchemaReady() && (bool)$this->row('SELECT cliente_id FROM cp_panel_google WHERE cliente_id=?',[$a['db_id']]); }
        if ($a && $a['must_change_password']) { return $base; }
        $base['leaderboard']=$this->community()->leaderboard();
        if ($a && $a['role']==='client') {
            $base['user']['community']=$this->community()->member($a['db_id']);
            $base['user']['photo_url']=$base['user']['community']['photo_url'];
        }
        $args=[]; $where='WHERE n.activo=1';
        if ($a && $a['role']==='ally') { $where.=' AND n.id=?'; $args[]=$a['business_id']; }
        $rows=$this->query('SELECT n.* FROM cp_negocios n '.$where.' ORDER BY n.id',$args)->fetchAll(PDO::FETCH_ASSOC);
        $styles=['street-grill'=>['flame','pink'],'capital-queer'=>['sparkles','pink'],'gran-chela'=>['beer','yellow'],'garage-disco-bar'=>['music','purple'],'pictogramas'=>['coffee','cyan'],'jimar-factory'=>['target','cyan']];
        foreach ($rows as $b) {
            $style=$styles[$b['slug']]??['store','purple'];
            $item=['id'=>(string)$b['id'],'name'=>$b['nombre'],'category'=>$b['categoria'],'slug'=>$b['slug'],'icon'=>$style[0],'color'=>$style[1],
                'path'=>$this->safePath($b['pagina']),'image'=>$this->safePath($b['logo']),'whatsapp'=>$b['whatsapp']];
            if ($a && $a['role']==='admin') {
                $account=$this->row("SELECT id,usuario FROM cp_usuarios WHERE negocio_id=? AND rol='aliado' AND activo=1 ORDER BY id LIMIT 1",[$b['id']]);
                $item['email']=$account['usuario']??'';
                $item['account_id']=$account?(string)$account['id']:null;
            }
            $base['businesses'][]=$item;
        }
        if ($ready) {
            $sql="SELECT p.*,m.publicacion,m.beneficio,m.incluidos,m.horarios,m.restricciones FROM cp_promociones p JOIN cp_negocios n ON n.id=p.negocio_id LEFT JOIN cp_panel_promociones m ON m.promocion_id=p.id WHERE n.activo=1 AND (m.publicacion IS NULL OR m.publicacion<>'archived')"; $args=[];
            if (!$a || $a['role']==='client') { $sql.=" AND p.activa=1 AND m.publicacion='approved'"; }
            if ($a && $a['role']==='ally') { $sql.=' AND p.negocio_id=?'; $args[]=$a['business_id']; }
            foreach ($this->query($sql.' ORDER BY p.id',$args)->fetchAll(PDO::FETCH_ASSOC) as $p) {
                $base['promotions'][]=['id'=>(string)$p['id'],'business_id'=>(string)$p['negocio_id'],'description'=>$p['beneficio']??$p['titulo'],
                    'publication'=>($p['publicacion']==='approved' && (int)$p['activa']===1)?'approved':'draft','included'=>$p['incluidos']??'','hours'=>$p['horarios']??'','restrictions'=>$p['restricciones']??''];
            }
        }
        if ($a) { foreach ($this->codeQuery($a)->fetchAll(PDO::FETCH_ASSOC) as $p) { $base['codes'][]=$this->codeDto($p); } }
        if ($a && $a['role']==='client' && $ready) { $base['challenges']=$this->challenges($a); }
        return $base;
    }
    public function handle(string $action, array $input): array {
        if (!in_array($action,['state','login','logout','change_password'],true)) {
            if (!$this->schemaReady()) { $this->error('Un administrador debe iniciar sesión para preparar los paneles.',503); }
            $a=$this->actor(); if ($a && $a['must_change_password']) { $this->error('Cambia tu contraseña temporal para continuar.',403); }
        }
        switch ($action) {
            case 'state': break;
            case 'login': $this->login($input); break;
            case 'logout': $this->logout(); break;
            case 'register': $this->error('Crea tu cuenta con Google para verificar tu correo.',422); break;
            case 'google_login': $this->googleLogin($input); break;
            case 'change_password': $this->changePassword($input); break;
            case 'profile': $this->profile($input); break;
            case 'upload_avatar': $this->profilePhoto(false); break;
            case 'remove_avatar': $this->profilePhoto(true); break;
            case 'leaderboard':
                $page=$this->id($input,'page');
                if ($page>10000) { $this->error('Esta página no está disponible.'); }
                return array_merge($this->state(),['ranking_page'=>$this->community()->leaderboard($page,20)]);
            case 'delete_account': $this->deleteAccount($input); break;
            case 'save_business': $this->saveBusiness($input); break;
            case 'delete_business': $this->deleteBusiness($input); break;
            case 'save_promotion': $this->savePromotion($input); break;
            case 'delete_promotion': $this->deletePromotion($input); break;
            case 'redeem': $this->redeem($input); break;
            case 'whatsapp': return array_merge($this->state(),$this->whatsapp($input));
            case 'visit': $this->rewards()->visit($this->requireActor(['client'])); break;
            case 'record_photo':
            case 'answer_questions': $this->completeChallenge($action,$input); break;
            case 'prepare_spin':
                $a=$this->requireActor(['client']);
                if (!$this->rewards()->status($a)['can_spin']) { $this->error('Aún no hay un giro disponible con promociones aprobadas.'); }
                break;
            case 'spin':
                $a=$this->requireActor(['client']);
                $code=$this->rewards()->spin($a,$this->text($input,'ticket_id',20));
                return array_merge($this->state(),['won_code'=>$code]);
            default: $this->error('Acción no disponible.',404);
        }
        return $this->state();
    }
}
