<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

class PanelError extends RuntimeException {}

final class ChapitourPanel
{
    private $db;
    private $schema;
    public function __construct(PDO $db) { $this->db = $db; }
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
        if ($staff && $staff['rol']==='admin') { $this->installSchema(); }
        $this->signIn($staff ? 'staff' : 'client', $row);
        $this->audit($this->actor(), 'inicio_sesion');
    }
    private function register(array $input): void {
        $this->limit('register:'.($_SERVER['REMOTE_ADDR'] ?? 'local'), 10, 60);
        $email = mb_strtolower($this->text($input, 'email', 150));
        $name = $this->text($input, 'name', 80);
        $city = $this->text($input, 'city', 80, false);
        $hash = password_hash($this->password($input), PASSWORD_BCRYPT);
        $row = $this->accountLock(function () use ($email, $name, $city, $hash) {
            return $this->transaction(function () use ($email, $name, $city, $hash) {
                $this->uniqueEmail($email);
                $this->query('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)', [$name,$email,$hash]);
                $id = (int)$this->db->lastInsertId();
                $this->query('INSERT INTO cp_panel_clientes(cliente_id,ciudad) VALUES (?,?)', [$id,$city]);
                // An account-owned visitor supports the existing cp_ relationships without importing anonymous cookies.
                $this->query('INSERT INTO cp_visitantes(identidad_hash,ip_hash,referido_token) VALUES (?,?,?)', [hash('sha256',random_bytes(32)),hash('sha256',random_bytes(32)),bin2hex(random_bytes(16))]);
                $visitor = (int)$this->db->lastInsertId();
                $this->query('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)', [$id,$visitor]);
                return ['id'=>$id,'version_sesion'=>1];
            });
        });
        $this->signIn('client', $row);
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
        $this->accountLock(function () use ($a, $email, $name, $city) {
            $this->transaction(function () use ($a, $email, $name, $city) {
                $this->requireActor(['client'], true); $this->uniqueEmail($email, $a['db_id']);
                $this->query('UPDATE cp_clientes SET nombre=?,email=? WHERE id=?', [$name,$email,$a['db_id']]);
                $this->query('INSERT INTO cp_panel_clientes(cliente_id,ciudad) VALUES (?,?) ON DUPLICATE KEY UPDATE ciudad=VALUES(ciudad)', [$a['db_id'],$city]);
            });
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
            $this->audit($a, 'cuenta_eliminada', $a['db_id']);
        });
        $this->logout();
    }
    private function logout(): void { $_SESSION = ['csrf'=>bin2hex(random_bytes(32))]; session_regenerate_id(true); }
    private function saveBusiness(array $input): void {
        $a = $this->requireActor(['admin']);
        $email = mb_strtolower($this->text($input, 'email', 100));
        $hash = password_hash($this->password($input), PASSWORD_BCRYPT);
        $existing = $this->text($input, 'business_id', 20, false);
        $name = $this->text($input, 'name', 80, $existing==='');
        $this->accountLock(function () use ($a, $input, $email, $hash, $existing, $name) {
            $this->transaction(function () use ($a, $input, $email, $hash, $existing, $name) {
                $this->requireActor(['admin'], true); $this->uniqueEmail($email);
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
        if ((string)($input['confirm'] ?? '') !== (string)$id) { $this->error('Confirma la cuenta del aliado.'); }
        $this->transaction(function () use ($a,$id) {
            $this->requireActor(['admin'],true);
            if (!$this->row('SELECT id FROM cp_negocios WHERE id=? FOR UPDATE', [$id])) { $this->error('Negocio no encontrado.',404); }
            $this->query("UPDATE cp_usuarios SET activo=0,version_sesion=version_sesion+1 WHERE negocio_id=? AND rol='aliado'", [$id]);
            $this->query('UPDATE cp_promociones SET activa=0 WHERE negocio_id=?', [$id]);
            $this->query("UPDATE cp_panel_promociones m JOIN cp_promociones p ON p.id=m.promocion_id SET m.publicacion='draft',m.aprobada_por=NULL,m.aprobada_at=NULL WHERE p.negocio_id=? AND m.publicacion<>'archived'", [$id]);
            $this->audit($a,'aliado_eliminado',$id);
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
        if ($publication==='approved' && (!$phone || !$included || !$hours || !$restrictions || ($input['confirmed'] ?? false)!==true)) {
            $this->error('Confirma el beneficio, incluidos, horarios, restricciones y WhatsApp con el negocio.');
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
        $month=(new DateTimeImmutable('now',new DateTimeZone('America/Bogota')))->format('Y-m-01');
        $seeds=[['visitar','Visita 3 negocios',3],['compartir','Envía esta página a 20 personas',20],['fotografia','Tómate una foto en 2 lugares y etiquétanos',2]];
        foreach ($seeds as $seed) { $this->query('INSERT IGNORE INTO cp_panel_retos(mes,tipo,titulo,objetivo) VALUES (?,?,?,?)',array_merge([$month],$seed)); }
        return $this->query('SELECT r.tipo AS type,r.titulo AS title,r.objetivo AS target,r.mes AS month,CASE WHEN r.criterio_verificacion IS NOT NULL AND TRIM(r.criterio_verificacion)<>\'\' AND p.cantidad_verificada<=r.objetivo THEN p.cantidad_verificada ELSE NULL END AS progress FROM cp_panel_retos r LEFT JOIN cp_panel_progreso p ON p.reto_id=r.id AND p.cliente_id=? WHERE r.mes=? ORDER BY r.id',[$a['db_id'],$month])->fetchAll(PDO::FETCH_ASSOC);
    }
    public function state(): array {
        $a=$this->actor(); $ready=$this->schemaReady();
        $base=['csrf'=>$_SESSION['csrf'],'user'=>null,'businesses'=>[],'promotions'=>[],'codes'=>[],'challenges'=>[],
            'storage'=>'mysql','setup_required'=>!$ready,'server_time'=>time(),'campaign'=>['enabled'=>false,'visits_per_reward'=>null,'new_visit_after'=>null,'replaces_previous_rule'=>null,'monthly_visit_reset'=>null]];
        if ($a) { $base['user']=$a; unset($base['user']['db_id'],$base['user']['kind'],$base['user']['version'],$base['user']['password_hash']); }
        if ($a && $a['must_change_password']) { return $base; }
        $args=[]; $where='WHERE n.activo=1';
        if ($a && $a['role']==='ally') { $where.=' AND n.id=?'; $args[]=$a['business_id']; }
        $rows=$this->query('SELECT n.* FROM cp_negocios n '.$where.' ORDER BY n.id',$args)->fetchAll(PDO::FETCH_ASSOC);
        $styles=['street-grill'=>['flame','pink'],'capital-queer'=>['sparkles','pink'],'gran-chela'=>['beer','yellow'],'garage-disco-bar'=>['music','purple'],'pictogramas'=>['coffee','cyan'],'jimar-factory'=>['target','cyan']];
        foreach ($rows as $b) {
            $style=$styles[$b['slug']]??['store','purple'];
            $item=['id'=>(string)$b['id'],'name'=>$b['nombre'],'category'=>$b['categoria'],'slug'=>$b['slug'],'icon'=>$style[0],'color'=>$style[1],
                'path'=>$this->safePath($b['pagina']),'image'=>$this->safePath($b['logo']),'whatsapp'=>$b['whatsapp']];
            if ($a && $a['role']==='admin') {
                $account=$this->row("SELECT usuario FROM cp_usuarios WHERE negocio_id=? AND rol='aliado' AND activo=1 ORDER BY id LIMIT 1",[$b['id']]);
                $item['email']=$account['usuario']??'';
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
            case 'register': $this->register($input); break;
            case 'change_password': $this->changePassword($input); break;
            case 'profile': $this->profile($input); break;
            case 'delete_account': $this->deleteAccount($input); break;
            case 'save_business': $this->saveBusiness($input); break;
            case 'delete_business': $this->deleteBusiness($input); break;
            case 'save_promotion': $this->savePromotion($input); break;
            case 'delete_promotion': $this->deletePromotion($input); break;
            case 'redeem': $this->redeem($input); break;
            case 'whatsapp': return array_merge($this->state(),$this->whatsapp($input));
            case 'prepare_spin': case 'spin':
                $this->requireActor(['client']);
                $this->error('La entrega de premios sigue pendiente de confirmar las reglas de visitas y las ofertas.'); break;
            default: $this->error('Acción no disponible.',404);
        }
        return $this->state();
    }
}
