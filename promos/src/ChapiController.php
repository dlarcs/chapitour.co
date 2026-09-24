<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
final class ChapiController
{
    private PDO $db;
    private array $config;
    private ChapiPromocionModel $promos;
    private ChapiPanelModel $panel;
    public function __construct(PDO $db,array $config)
    {
        $this->db=$db; $this->config=$config;
        $this->promos=new ChapiPromocionModel($db,$config); $this->panel=new ChapiPanelModel($db,$config);
    }
    public function dispatch(array $data): array
    {
        $action=ChapiSecurity::text($data,'action',40);
        $ip=ChapiSecurity::ipHash($this->config);
        ChapiSecurity::limit($this->db,$this->config,'api:'.$ip,240,60);
        if ($action==='login') {
            $name=ChapiSecurity::text($data,'usuario',100);
            ChapiSecurity::limit($this->db,$this->config,'login-ip:'.$ip,20,900);
            ChapiSecurity::limit($this->db,$this->config,'login-user:'.mb_strtolower($name),12,900);
            $user=$this->panel->login($name,ChapiSecurity::text($data,'password',72));
            session_regenerate_id(true);
            $_SESSION['panel']=['id'=>(int)$user['id'],'version'=>(int)$user['version_sesion'],'last'=>time()];
            return ['usuario'=>$this->panel->user((int)$user['id'])];
        }
        if ($action==='logout') { unset($_SESSION['panel']); session_regenerate_id(true); return []; }
        if (in_array($action,['panel','consultar_codigo','redimir','guardar_negocio','guardar_promocion','crear_dueno','estado_dueno','password','reset_password'],true)) {
            $user=$this->requireUser();
            if ($action==='password') {
                $this->panel->changePassword($user,ChapiSecurity::text($data,'actual',72),ChapiSecurity::text($data,'nueva',72));
                $_SESSION['panel']['version']=(int)$user['version_sesion']+1;
                session_regenerate_id(true);
                return ['usuario'=>$this->panel->user((int)$user['id'])];
            }
            if ($user['cambiar_password']) throw new ChapiError('Cambia tu contraseña inicial para continuar.',403,'PASSWORD_CHANGE_REQUIRED');
            switch ($action) {
                case 'panel': return $this->panel->dashboard($user,$data);
                case 'consultar_codigo': return ['premio'=>$this->panel->lookup($user,ChapiSecurity::text($data,'codigo',40))];
                case 'redimir':
                    if (($data['confirmado']??false)!==true) throw new ChapiError('Confirma que el cliente está presente y utilizó la promoción.');
                    return $this->panel->redeem($user,ChapiSecurity::text($data,'codigo',40));
                case 'guardar_negocio': return $this->panel->saveBusiness($user,$data);
                case 'guardar_promocion': return $this->panel->savePromotion($user,$data);
                case 'crear_dueno': return $this->panel->createOwner($user,$data);
                case 'estado_dueno': $this->panel->setOwnerActive($user,$data); return [];
                case 'reset_password': return $this->panel->resetPassword($user,ChapiSecurity::integer($data,'id'));
            }
        }
        if (!in_array($action,['iniciar','estado','girar','mostrado','evento','confirmar_referido'],true)) throw new ChapiError('Acción desconocida.',404);
        $visitor=$this->promos->visitor(ChapiSecurity::identity($this->config),$ip);
        $id=(int)$visitor['id'];
        switch ($action) {
            case 'iniciar':
                $ref=ChapiSecurity::text($data,'ref',32,false);
                if ($ref!=='' && preg_match('/^[a-f0-9]{32}$/D',$ref) && (!isset($_SESSION['referido']) || $_SESSION['referido']['token']!==$ref)) $_SESSION['referido']=['token'=>$ref,'inicio'=>time()];
                $state=$this->promos->initialize($id,$ip);
                $state['referido_pendiente']=isset($_SESSION['referido']);
                $state['referido_espera']=isset($_SESSION['referido']) ? max(0,(int)$this->config['reglas']['min_segundos_referido']-(time()-$_SESSION['referido']['inicio'])) : 0;
                return $state;
            case 'estado': return $this->promos->state($id);
            case 'girar':
                $key=ChapiSecurity::text($data,'solicitud_id',36);
                if (!preg_match('/^[a-f0-9-]{36}$/D',$key)) throw new ChapiError('Solicitud de giro inválida.');
                $prize=$this->promos->spin($id,$key);
                return ['premio'=>$prize,'estado'=>$this->promos->state($id)];
            case 'mostrado': $this->promos->shown($id,ChapiSecurity::integer($data,'oportunidad_id')); return [];
            case 'evento':
                $prize=isset($data['premio_id']) && $data['premio_id']!==null ? ChapiSecurity::integer($data,'premio_id') : null;
                return $this->promos->track($id,ChapiSecurity::text($data,'tipo',50),$prize);
            case 'confirmar_referido':
                $ref=$_SESSION['referido']??null;
                if (!$ref || time()-$ref['inicio']<(int)$this->config['reglas']['min_segundos_referido']) throw new ChapiError('Explora la página unos segundos antes de confirmar la invitación.',409);
                $result=$this->promos->confirmReferral($id,$ip,$ref['token']); unset($_SESSION['referido']); return $result;
        }
        throw new ChapiError('Acción desconocida.',404);
    }
    public function requireUser(): array
    {
        $session=$_SESSION['panel']??null;
        if (!$session || time()-$session['last']>(int)$this->config['reglas']['sesion_panel_minutos']*60) { unset($_SESSION['panel']); throw new ChapiError('Inicia sesión para continuar.',401,'AUTH_REQUIRED'); }
        $user=$this->panel->user((int)$session['id']);
        if (!$user || (int)$user['version_sesion']!==(int)$session['version']) { unset($_SESSION['panel']); throw new ChapiError('Tu sesión venció. Ingresa nuevamente.',401,'AUTH_REQUIRED'); }
        $_SESSION['panel']['last']=time();
        return $user;
    }
}
