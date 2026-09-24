<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
final class ChapiCustomerModel extends ChapiModel
{
    public function current(): ?array
    {
        $session=$_SESSION['cliente']??null;
        if (!$session) return null;
        $user=$this->one('SELECT id,nombre,email,version_sesion FROM cp_clientes WHERE id=? AND activo=1',[$session['id']]);
        if (!$user || (int)$user['version_sesion']!==(int)$session['version'] || time()-$session['last']>(int)$this->config['reglas']['sesion_cliente_minutos']*60) {
            unset($_SESSION['cliente']); return null;
        }
        $_SESSION['cliente']['last']=time();
        return $user;
    }

    public function linked(int $visitor): bool
    {
        return $this->one('SELECT cliente_id FROM cp_cliente_visitantes WHERE visitante_id=?',[$visitor])!==null;
    }

    public function visitorIds(int $customer): array
    {
        return array_map('intval',$this->query('SELECT visitante_id FROM cp_cliente_visitantes WHERE cliente_id=? ORDER BY visitante_id',[$customer])->fetchAll(PDO::FETCH_COLUMN));
    }

    private function link(int $customer,int $visitor): void
    {
        // La cookie acredita el navegador, nunca se vinculan historiales solo por IP.
        $link=$this->one('SELECT cliente_id FROM cp_cliente_visitantes WHERE visitante_id=? FOR UPDATE',[$visitor]);
        if ($link && (int)$link['cliente_id']!==$customer) throw new ChapiError('Este historial ya pertenece a otra cuenta.',409,'HISTORY_CLAIMED');
        if (!$link) {
            $this->query('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$customer,$visitor]);
            (new ChapiPromocionModel($this->db,$this->config))->reconcileReferralRewards($this->visitorIds($customer));
        }
    }

    private function beginSession(array $user): array
    {
        if (session_status()===PHP_SESSION_ACTIVE) session_regenerate_id(true);
        $_SESSION['cliente']=['id'=>(int)$user['id'],'version'=>(int)$user['version_sesion'],'last'=>time()];
        return ['id'=>(int)$user['id'],'nombre'=>$user['nombre'],'email'=>$user['email']];
    }

    public function register(int $visitor,array $data): array
    {
        if ($this->current()) throw new ChapiError('Ya tienes una sesión de cliente abierta.',409);
        $name=ChapiSecurity::text($data,'nombre',120);
        $email=mb_strtolower(ChapiSecurity::text($data,'email',190));
        $password=ChapiSecurity::text($data,'password',72);
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) throw new ChapiError('Revisa tu correo electrónico.');
        if (strlen($password)<12 || strlen($password)>72) throw new ChapiError('Usa una contraseña de 12 a 72 caracteres.');
        $user=$this->transaction(function () use ($visitor,$name,$email,$password) {
            if ($this->one('SELECT id FROM cp_clientes WHERE email=?',[$email])) throw new ChapiError('No se puede crear la cuenta con ese correo. Si ya tienes una, inicia sesión.',409,'EMAIL_EXISTS');
            $this->query('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)',[$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
            $id=(int)$this->db->lastInsertId();
            $this->link($id,$visitor);
            $this->event($visitor,'cliente_registrado');
            return ['id'=>$id,'nombre'=>$name,'email'=>$email,'version_sesion'=>1];
        });
        return $this->beginSession($user);
    }

    public function login(int $visitor,array $data): array
    {
        $email=mb_strtolower(ChapiSecurity::text($data,'email',190));
        $password=ChapiSecurity::text($data,'password',72);
        $user=$this->one('SELECT * FROM cp_clientes WHERE email=? AND activo=1',[$email]);
        $dummy='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
        $valid=password_verify($password,$user['password_hash']??$dummy);
        if (!$user || !$valid) throw new ChapiError('Correo o contraseña incorrectos.',401,'CUSTOMER_LOGIN_FAILED');
        $this->transaction(function () use ($user,$visitor) {
            $this->link((int)$user['id'],$visitor);
            $this->event($visitor,'cliente_inicio_sesion');
        });
        return $this->beginSession($user);
    }

    public function dashboard(int $visitor,array $filter,?array $customer): array
    {
        [$scope,$args]=$this->visitorScope($visitor);
        $summary=$this->one('SELECT COUNT(*) AS total,COALESCE(SUM(p.redimido_at IS NOT NULL),0) AS redimidos,COALESCE(SUM(p.redimido_at IS NULL AND p.vence_at>UTC_TIMESTAMP()),0) AS activos,COALESCE(SUM(p.redimido_at IS NULL AND p.vence_at<=UTC_TIMESTAMP()),0) AS vencidos FROM cp_premios p WHERE '.$scope,$args);
        $status=$filter['estado']??'';
        if (!in_array($status,['','activo','redimido','vencido'],true)) throw new ChapiError('Estado inválido.');
        $where=$scope;
        if ($status==='activo') $where.=' AND p.redimido_at IS NULL AND p.vence_at>UTC_TIMESTAMP()';
        if ($status==='redimido') $where.=' AND p.redimido_at IS NOT NULL';
        if ($status==='vencido') $where.=' AND p.redimido_at IS NULL AND p.vence_at<=UTC_TIMESTAMP()';
        $total=(int)$this->query('SELECT COUNT(*) FROM cp_premios p WHERE '.$where,$args)->fetchColumn();
        $pages=max(1,(int)ceil($total/12));
        $page=max(1,min($pages,(int)($filter['pagina']??1)));
        $prizes=array_map(fn($p)=>$this->prizeData($p),$this->query($this->prizeSelect().'WHERE '.$where.' ORDER BY p.id DESC LIMIT 12 OFFSET '.(($page-1)*12),$args)->fetchAll());
        $levels=$this->config['reglas']['niveles']; $level=$levels[0]; $next=null;
        foreach ($levels as $candidate) { if ((int)$summary['redimidos']>=$candidate['redenciones']) $level=$candidate; else { $next=$candidate; break; } }
        $state=(new ChapiPromocionModel($this->db,$this->config))->state($visitor);
        return ['cliente'=>$customer?['nombre'=>$customer['nombre'],'email'=>$customer['email']]:null,'resumen'=>$summary,'premios'=>$prizes,'total'=>$total,'pagina'=>$page,'paginas'=>$pages,
            'nivel'=>$level,'siguiente_nivel'=>$next,'niveles'=>$levels,'redenciones_para_siguiente'=>$next?max(0,$next['redenciones']-(int)$summary['redimidos']):0,
            'oportunidades'=>$state['oportunidades'],'visitas'=>$state['visitas'],'visitas_para_proxima'=>$state['visitas_para_proxima'],'cada_visitas'=>$state['cada_visitas'],
            'invitacion_url'=>$state['invitacion_url'],'referidos_progreso'=>$state['referidos_progreso'],'amigos_requeridos'=>$state['amigos_requeridos'],'referidos_total'=>$state['referidos_total'],'ahora'=>gmdate('c')];
    }
}
