<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
final class ChapiPanelModel extends ChapiModel
{
    public function login(string $name,string $password): array
    {
        $user=$this->one('SELECT * FROM cp_usuarios WHERE usuario=? AND activo=1',[$name]);
        // Constant-cost hash verification also for an unknown username.
        $dummy='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
        $ok=password_verify($password,$user['password_hash']??$dummy);
        if (!$user || !$ok) throw new ChapiError('Usuario o contraseña incorrectos.',401,'LOGIN_FAILED');
        if (password_needs_rehash($user['password_hash'],PASSWORD_DEFAULT)) $this->query('UPDATE cp_usuarios SET password_hash=? WHERE id=?',[password_hash($password,PASSWORD_DEFAULT),$user['id']]);
        $this->audit((int)$user['id'],'inicio_sesion');
        return $user;
    }

    public function user(int $id): ?array
    {
        return $this->one('SELECT u.id,u.usuario,u.rol,u.negocio_id,u.cambiar_password,u.version_sesion,n.nombre AS negocio FROM cp_usuarios u LEFT JOIN cp_negocios n ON n.id=u.negocio_id WHERE u.id=? AND u.activo=1',[$id]);
    }

    public function changePassword(array $user,string $current,string $new): void
    {
        $hash=$this->query('SELECT password_hash FROM cp_usuarios WHERE id=?',[$user['id']])->fetchColumn();
        if (!password_verify($current,$hash)) throw new ChapiError('La contraseña actual es incorrecta.',403);
        if (strlen($new)<12 || strlen($new)>72) throw new ChapiError('Usa una contraseña de 12 a 72 caracteres.');
        if (hash_equals($current,$new)) throw new ChapiError('Elige una contraseña diferente.');
        $this->transaction(function () use ($user,$new) {
            $this->query('UPDATE cp_usuarios SET password_hash=?,cambiar_password=0,version_sesion=version_sesion+1 WHERE id=?',[password_hash($new,PASSWORD_DEFAULT),$user['id']]);
            $this->audit((int)$user['id'],'password_cambiado');
        });
    }

    private function scope(array $user,string $alias='p'): array
    {
        $base=$alias==='p' ? $alias.'.campana_id=?' : '1=1';
        $params=$alias==='p' ? [$this->campaign] : [];
        if ($user['rol']==='admin') return [$base,$params];
        if ($user['rol']!=='aliado' || !$user['negocio_id']) throw new ChapiError('No tienes un negocio asignado.',403);
        return [$base.' AND '.$alias.'.negocio_id=?',array_merge($params,[(int)$user['negocio_id']])];
    }

    public function dashboard(array $user,array $filter): array
    {
        [$scope,$params]=$this->scope($user);
        $from=$filter['desde']??gmdate('Y-m-d',time()-29*86400);
        $to=$filter['hasta']??gmdate('Y-m-d');
        foreach ([$from,$to] as $date) {
            if (!is_string($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/D',$date) || !checkdate((int)substr($date,5,2),(int)substr($date,8,2),(int)substr($date,0,4))) throw new ChapiError('Rango de fechas inválido.');
        }
        if ($from>$to) throw new ChapiError('La fecha inicial debe ser anterior a la final.');
        // UI dates are Bogotá calendar days; DB stores UTC.
        $start=(new DateTimeImmutable($from.' 00:00:00',new DateTimeZone('America/Bogota')))->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        $end=(new DateTimeImmutable($to.' 00:00:00',new DateTimeZone('America/Bogota')))->modify('+1 day')->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        $where=$scope.' AND p.creado_at>=? AND p.creado_at<?';
        $args=array_merge($params,[$start,$end]);
        $stats=$this->one('SELECT COUNT(*) AS emitidos,COALESCE(SUM(p.redimido_at IS NOT NULL),0) AS redimidos,COALESCE(SUM(p.redimido_at IS NULL AND p.vence_at>UTC_TIMESTAMP()),0) AS activos,COALESCE(SUM(p.redimido_at IS NULL AND p.vence_at<=UTC_TIMESTAMP()),0) AS vencidos FROM cp_premios p WHERE '.$where,$args);
        $stats['whatsapp']=(int)$this->query("SELECT COUNT(*) FROM cp_eventos e JOIN cp_premios p ON p.id=e.premio_id WHERE e.tipo='reclamar_whatsapp' AND ".$where,$args)->fetchColumn();
        $status=$filter['estado']??'';
        if (!in_array($status,['','activo','redimido','vencido'],true)) throw new ChapiError('Estado inválido.');
        if ($status==='activo') $where.=' AND p.redimido_at IS NULL AND p.vence_at>UTC_TIMESTAMP()';
        if ($status==='redimido') $where.=' AND p.redimido_at IS NOT NULL';
        if ($status==='vencido') $where.=' AND p.redimido_at IS NULL AND p.vence_at<=UTC_TIMESTAMP()';
        $search=ChapiSecurity::text($filter,'buscar',40,false);
        if ($search!=='') { $where.=' AND p.codigo LIKE ?'; $args[]='%'.str_replace(['\\','%','_'],['\\\\','\\%','\\_'],$search).'%'; }
        $page=max(1,min(10000,(int)($filter['pagina']??1)));
        $count=(int)$this->query('SELECT COUNT(*) FROM cp_premios p WHERE '.$where,$args)->fetchColumn();
        $prizes=array_map(fn($p)=>$this->prizeData($p),$this->query($this->prizeSelect().'WHERE '.$where.' ORDER BY p.id DESC LIMIT 25 OFFSET '.(($page-1)*25),$args)->fetchAll());
        $businessArgs=$user['rol']==='admin' ? [] : [(int)$user['negocio_id']];
        $businesses=$this->query('SELECT n.* FROM cp_negocios n'.($businessArgs ? ' WHERE n.id=?' : '').' ORDER BY n.nombre',$businessArgs)->fetchAll();
        [$promotionScope,$promotionArgs]=$this->scope($user,'pr');
        $promotions=$this->query('SELECT pr.*,n.nombre AS negocio,n.activo AS negocio_activo FROM cp_promociones pr JOIN cp_negocios n ON n.id=pr.negocio_id WHERE '.$promotionScope.' ORDER BY pr.id DESC',$promotionArgs)->fetchAll();
        $distribution=$this->query('SELECT n.nombre,COUNT(*) AS emitidos,COALESCE(SUM(p.redimido_at IS NOT NULL),0) AS redimidos FROM cp_premios p JOIN cp_negocios n ON n.id=p.negocio_id WHERE '.$scope.' AND p.creado_at>=? AND p.creado_at<? GROUP BY n.id,n.nombre ORDER BY emitidos DESC',array_merge($params,[$start,$end]))->fetchAll();
        $result=['usuario'=>$user,'resumen'=>$stats,'premios'=>$prizes,'total'=>$count,'pagina'=>$page,'paginas'=>max(1,(int)ceil($count/25)),'negocios'=>$businesses,'promociones'=>$promotions,'distribucion'=>$distribution,'desde'=>$from,'hasta'=>$to];
        if ($user['rol']==='admin') {
            $result['usuarios']=$this->query('SELECT u.id,u.usuario,u.rol,u.activo,n.nombre AS negocio FROM cp_usuarios u LEFT JOIN cp_negocios n ON n.id=u.negocio_id ORDER BY u.id')->fetchAll();
            $result['eventos']=$this->query('SELECT tipo,COUNT(*) AS total FROM cp_eventos WHERE creado_at>=? AND creado_at<? GROUP BY tipo ORDER BY total DESC',[$start,$end])->fetchAll();
            $result['auditoria']=$this->query('SELECT a.accion,a.entidad_id,a.creado_at,u.usuario FROM cp_auditoria a LEFT JOIN cp_usuarios u ON u.id=a.usuario_id ORDER BY a.id DESC LIMIT 30')->fetchAll();
            $result['reglas']=$this->config['reglas'];
        }
        return $result;
    }

    public function lookup(array $user,string $code): array
    {
        [$scope,$params]=$this->scope($user);
        $row=$this->one($this->prizeSelect().'WHERE p.codigo=? AND '.$scope,array_merge([strtoupper($code)],$params));
        if (!$row) throw new ChapiError('Código no encontrado para tu establecimiento.',404,'PRIZE_NOT_FOUND');
        $this->audit((int)$user['id'],'codigo_consultado',(int)$row['id']);
        return $this->prizeData($row);
    }

    public function redeem(array $user,string $code): array
    {
        if ($user['rol']!=='aliado') throw new ChapiError('La redención debe confirmarla el dueño del negocio.',403,'OWNER_REQUIRED');
        return $this->transaction(function () use ($user,$code) {
            [$scope,$params]=$this->scope($user);
            $row=$this->one($this->prizeSelect().'WHERE p.codigo=? AND '.$scope.' FOR UPDATE',array_merge([strtoupper($code)],$params));
            if (!$row) throw new ChapiError('Código no encontrado para tu establecimiento.',404);
            if ($row['redimido_at']) throw new ChapiError('Este código ya fue redimido.',409,'ALREADY_REDEEMED');
            if (strtotime($row['vence_at'].' UTC')<=time()) throw new ChapiError('Este código está vencido.',409,'EXPIRED');
            $this->query('UPDATE cp_premios SET redimido_at=UTC_TIMESTAMP(),redimido_por=? WHERE id=? AND redimido_at IS NULL',[$user['id'],$row['id']]);
            if ($this->config['reglas']['premiar_redencion']) $this->grant((int)$row['visitante_id'],'redencion','redencion:'.$row['id']);
            $this->audit((int)$user['id'],'premio_redimido',(int)$row['id']);
            $this->event((int)$row['visitante_id'],'premio_redimido',(int)$row['id']);
            $updated=$this->one($this->prizeSelect().'WHERE p.id=?',[$row['id']]);
            return ['premio'=>$this->prizeData($updated),'nueva_oportunidad'=>(bool)$this->config['reglas']['premiar_redencion']];
        });
    }

    public function saveBusiness(array $user,array $data): array
    {
        if ($user['rol']!=='admin') throw new ChapiError('Solo el superadministrador puede gestionar negocios.',403);
        $id=empty($data['id']) ? null : ChapiSecurity::integer($data,'id');
        $name=ChapiSecurity::text($data,'nombre',120);
        $category=ChapiSecurity::text($data,'categoria',80);
        $phone=ChapiSecurity::text($data,'whatsapp',20,false);
        if ($phone!=='' && !preg_match('/^[1-9][0-9]{7,14}$/D',$phone)) throw new ChapiError('WhatsApp debe incluir indicativo de país, sin + ni espacios.');
        $address=ChapiSecurity::text($data,'direccion',200,false);
        $active=($data['activo']??false)===true;
        return $this->transaction(function () use ($user,$id,$name,$category,$phone,$address,$active) {
            if ($id) {
                if (!$this->one('SELECT id FROM cp_negocios WHERE id=? FOR UPDATE',[$id])) throw new ChapiError('Negocio no encontrado.',404);
                if ($phone==='' && $this->one('SELECT id FROM cp_promociones WHERE negocio_id=? AND activa=1 LIMIT 1',[$id])) throw new ChapiError('Conserva el WhatsApp mientras haya promociones activas.');
                $this->query('UPDATE cp_negocios SET nombre=?,categoria=?,whatsapp=?,direccion=?,activo=? WHERE id=?',[$name,$category,$phone,$address,$active?1:0,$id]);
                $action='negocio_actualizado';
            } else {
                $this->query('INSERT INTO cp_negocios(slug,nombre,categoria,whatsapp,direccion,activo) VALUES (?,?,?,?,?,?)',['negocio-'.bin2hex(random_bytes(8)),$name,$category,$phone,$address,$active?1:0]);
                $id=(int)$this->db->lastInsertId(); $action='negocio_creado';
            }
            $this->audit((int)$user['id'],$action,$id);
            return ['id'=>$id];
        });
    }

    public function savePromotion(array $user,array $data): array
    {
        $this->scope($user,'pr');
        $id=empty($data['id']) ? null : ChapiSecurity::integer($data,'id');
        $business=ChapiSecurity::integer($data,'negocio_id');
        if ($user['rol']!=='admin' && $business!==(int)$user['negocio_id']) throw new ChapiError('Solo puedes gestionar las promociones de tu negocio.',403,'FORBIDDEN');
        $title=ChapiSecurity::text($data,'titulo',160);
        $description=ChapiSecurity::text($data,'descripcion',500,false);
        $terms=ChapiSecurity::text($data,'condiciones',1000);
        $percent=$data['porcentaje']??null;
        if ($percent!==null && $percent!=='') {
            if (!is_numeric($percent) || (float)$percent<=0 || (float)$percent>100) throw new ChapiError('El porcentaje debe estar entre 0 y 100.');
            $percent=round((float)$percent,2);
        } else $percent=null;
        $cap=$data['cupo_total']??null;
        if ($cap!==null && $cap!=='') { $cap=filter_var($cap,FILTER_VALIDATE_INT); if ($cap===false || $cap<1) throw new ChapiError('Cupo inválido.'); } else $cap=null;
        $active=($data['activa']??false)===true;
        return $this->transaction(function () use ($user,$id,$business,$title,$description,$terms,$percent,$cap,$active) {
            $venue=$this->one('SELECT id,whatsapp,activo FROM cp_negocios WHERE id=? FOR UPDATE',[$business]);
            if (!$venue) throw new ChapiError('Negocio no encontrado.',404);
            if ($active && (!$venue['activo'] || $venue['whatsapp']==='' || $title==='Beneficio por configurar')) throw new ChapiError('Para activar, el negocio debe estar habilitado y tener WhatsApp y un beneficio configurados.');
            if ($id) {
                $existing=$this->one('SELECT * FROM cp_promociones WHERE id=? AND negocio_id=? FOR UPDATE',[$id,$business]);
                if (!$existing) throw new ChapiError('Promoción no encontrada para este negocio.',404,'PROMOTION_NOT_FOUND');
                if ($cap!==null && $cap<(int)$existing['entregados']) throw new ChapiError('El cupo no puede ser menor que los premios ya emitidos.');
                $this->query('UPDATE cp_promociones SET titulo=?,descripcion=?,condiciones=?,porcentaje=?,cupo_total=?,activa=?,actualizada_at=UTC_TIMESTAMP() WHERE id=?',[$title,$description,$terms,$percent,$cap,$active?1:0,$id]);
                $action='promocion_actualizada';
            } else {
                $this->query('INSERT INTO cp_promociones(negocio_id,titulo,descripcion,condiciones,porcentaje,cupo_total,activa) VALUES (?,?,?,?,?,?,?)',[$business,$title,$description,$terms,$percent,$cap,$active?1:0]);
                $id=(int)$this->db->lastInsertId(); $action='promocion_creada';
            }
            $this->audit((int)$user['id'],$action,$id,['negocio_id'=>$business,'activa'=>$active,'titulo'=>$title,'condiciones'=>$terms,'porcentaje'=>$percent,'cupo_total'=>$cap]);
            return ['id'=>$id];
        });
    }

    public function createOwner(array $user,array $data): array
    {
        if ($user['rol']!=='admin') throw new ChapiError('Solo el superadministrador puede crear dueños.',403,'FORBIDDEN');
        $name=ChapiSecurity::text($data,'usuario',100);
        if (!preg_match('/^[a-zA-Z0-9._-]{3,100}$/D',$name)) throw new ChapiError('El usuario debe tener entre 3 y 100 letras, números, puntos, guiones o guiones bajos.');
        $business=ChapiSecurity::integer($data,'negocio_id');
        return $this->transaction(function () use ($user,$name,$business) {
            if (!$this->one('SELECT id FROM cp_negocios WHERE id=? AND activo=1 FOR UPDATE',[$business])) throw new ChapiError('Selecciona un negocio habilitado.',404);
            if ($this->one('SELECT id FROM cp_usuarios WHERE usuario=?',[$name])) throw new ChapiError('Ese nombre de usuario ya existe.',409,'USER_EXISTS');
            $password=bin2hex(random_bytes(10));
            // El rol viene del servidor; nunca se acepta un rol enviado por el navegador.
            $this->query("INSERT INTO cp_usuarios(negocio_id,usuario,password_hash,rol,cambiar_password) VALUES (?,?,?,'aliado',1)",[$business,$name,password_hash($password,PASSWORD_DEFAULT)]);
            $id=(int)$this->db->lastInsertId();
            $this->audit((int)$user['id'],'dueno_creado',$id,['negocio_id'=>$business]);
            return ['id'=>$id,'usuario'=>$name,'password_temporal'=>$password];
        });
    }

    public function setOwnerActive(array $user,array $data): void
    {
        if ($user['rol']!=='admin') throw new ChapiError('Acceso restringido.',403,'FORBIDDEN');
        $id=ChapiSecurity::integer($data,'id');
        if (!isset($data['activo']) || !is_bool($data['activo'])) throw new ChapiError('Estado inválido.');
        $this->transaction(function () use ($user,$data,$id) {
            $owner=$this->one("SELECT id,activo FROM cp_usuarios WHERE id=? AND rol='aliado' FOR UPDATE",[$id]);
            if (!$owner) throw new ChapiError('Dueño no encontrado.',404);
            if ((bool)$owner['activo']===$data['activo']) return;
            $this->query('UPDATE cp_usuarios SET activo=?,version_sesion=version_sesion+1 WHERE id=?',[$data['activo']?1:0,$id]);
            $this->audit((int)$user['id'],$data['activo']?'dueno_activado':'dueno_desactivado',$id);
        });
    }

    public function resetPassword(array $user,int $target): array
    {
        if ($user['rol']!=='admin') throw new ChapiError('Acceso restringido.',403);
        if ($target===(int)$user['id']) throw new ChapiError('Usa Cambiar contraseña para tu propia cuenta.');
        $password=bin2hex(random_bytes(10));
        $this->transaction(function () use ($user,$target,$password) {
            if (!$this->user($target)) throw new ChapiError('Usuario no encontrado.',404);
            $this->query('UPDATE cp_usuarios SET password_hash=?,cambiar_password=1,version_sesion=version_sesion+1 WHERE id=?',[password_hash($password,PASSWORD_DEFAULT),$target]);
            $this->audit((int)$user['id'],'password_restablecido',$target);
        });
        return ['password_temporal'=>$password];
    }
}
