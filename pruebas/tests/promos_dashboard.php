<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
define('CHAPI_PROMOS',true);
require dirname(__DIR__).'/promos/src/bootstrap.php';
$db=new PDO(getenv('CHAPI_TEST_ADMIN_DSN')?:'mysql:host=127.0.0.1;charset=utf8mb4',getenv('CHAPI_TEST_ADMIN_USER')?:'root',getenv('CHAPI_TEST_ADMIN_PASSWORD')?:'',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$schema='chapitour_test_'.bin2hex(random_bytes(5));
$db->exec('CREATE DATABASE `'.$schema.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$db->exec('USE `'.$schema.'`');
$checks=0;
function verifyDashboard(bool $ok,string $message): void { global $checks; if (!$ok) throw new RuntimeException('FAIL '.$message); $checks++; echo 'OK '.$message."\n"; }
function denied(callable $fn,int $status,string $message): void { try { $fn(); } catch (ChapiError $e) { verifyDashboard($e->status===$status,$message); return; } throw new RuntimeException('FAIL '.$message); }
function runSql(PDO $db,string $sql): void { foreach (explode(';',preg_replace('/^--.*$/m','',$sql)) as $statement) if (trim($statement)!=='') $db->query($statement)->closeCursor(); }
try {
    $root=dirname(__DIR__).'/promos/database/';
    // Reproduce el esquema anterior de Hostinger, incluida la restricción de una oferta.
    $schemaSql=file_get_contents($root.'001_schema.sql');
    $schemaSql=str_replace('KEY cp_promociones_negocio (negocio_id)','UNIQUE KEY negocio_id (negocio_id)',$schemaSql);
    runSql($db,$schemaSql); runSql($db,file_get_contents($root.'002_negocios.sql'));
    $db->exec("INSERT INTO cp_usuarios(usuario,password_hash,rol,cambiar_password) VALUES ('admin.test','fixture','admin',0)");
    $config=chapi_config(); $panel=new ChapiPanelModel($db,$config); $promos=new ChapiPromocionModel($db,$config); $admin=$panel->user(1);
    $db->exec("UPDATE cp_promociones SET titulo='Oferta original',activa=1");
    $visitor=$promos->visitor(hash('sha256','dashboard-visitor'),hash('sha256','dashboard-ip'));
    $promos->initialize((int)$visitor['id'],hash('sha256','dashboard-ip'));
    $prize=$promos->spin((int)$visitor['id'],str_repeat('a',36));
    $before=$db->query('SELECT * FROM cp_premios')->fetchAll();
    $oldPromotions=$db->query('SELECT * FROM cp_promociones ORDER BY id')->fetchAll();
    runSql($db,file_get_contents($root.'005_dashboard_promociones.sql'));
    runSql($db,file_get_contents($root.'005_dashboard_promociones.sql'));
    verifyDashboard($before===$db->query('SELECT * FROM cp_premios')->fetchAll(),'migración repetible conserva premios existentes');
    verifyDashboard($oldPromotions===$db->query('SELECT * FROM cp_promociones ORDER BY id')->fetchAll(),'migración conserva promociones y cupos');
    runSql($db,file_get_contents($root.'002_negocios.sql'));
    verifyDashboard((int)$db->query('SELECT COUNT(*) FROM cp_promociones')->fetchColumn()===6,'semillas repetidas no duplican promociones');

    $venue=$panel->saveBusiness($admin,['nombre'=>'Café de prueba','categoria'=>'Café','whatsapp'=>'573001234567','direccion'=>'Dirección de prueba','activo'=>true]);
    verifyDashboard(count($panel->dashboard($admin,[])['negocios'])===7,'negocio nuevo aparece aunque no tenga promociones');
    $created=$panel->createOwner($admin,['usuario'=>'dueno.nuevo','negocio_id'=>$venue['id'],'rol'=>'admin']);
    $owner=$panel->user($created['id']);
    verifyDashboard($owner['rol']==='aliado' && (int)$owner['negocio_id']===$venue['id'],'crear dueño asigna negocio e ignora escalada de rol');
    verifyDashboard((int)$owner['cambiar_password']===1,'dueño debe cambiar contraseña inicial');
    $stored=$db->query('SELECT password_hash FROM cp_usuarios WHERE id='.$created['id'])->fetchColumn();
    verifyDashboard($stored!==$created['password_temporal'] && password_verify($created['password_temporal'],$stored),'contraseña temporal se guarda como hash');
    verifyDashboard($panel->login('dueno.nuevo',$created['password_temporal'])['id']==$owner['id'],'credencial temporal permite iniciar sesión');
    denied(fn()=>$panel->createOwner($admin,['usuario'=>'DUENO.NUEVO','negocio_id'=>$venue['id']]),409,'usuario duplicado rechazado sin distinguir mayúsculas');
    denied(fn()=>$panel->createOwner($owner,['usuario'=>'otro.dueno','negocio_id'=>1]),403,'dueño no puede crear usuarios');
    denied(fn()=>$panel->createOwner($admin,['usuario'=>'x','negocio_id'=>1]),400,'validación de nombre de usuario');
    denied(fn()=>$panel->createOwner($admin,['usuario'=>'otro.dueno','negocio_id'=>999999]),404,'negocio inexistente rechazado');

    $controller=new ChapiController($db,$config);
    $_SESSION=[];
    foreach (['guardar_negocio','guardar_promocion','crear_dueno','estado_dueno'] as $action) denied(fn()=>$controller->dispatch(['action'=>$action]),401,'acción '.$action.' exige sesión');
    $_SESSION['panel']=['id'=>$owner['id'],'version'=>$owner['version_sesion'],'last'=>time()];
    denied(fn()=>$controller->dispatch(['action'=>'guardar_promocion']),403,'contraseña inicial bloquea creación de promociones');
    $panel->changePassword($owner,$created['password_temporal'],'Clave-prueba-nueva-782!');
    denied(fn()=>$controller->requireUser(),401,'cambio de contraseña invalida versión de sesión anterior');
    $owner=$panel->user($created['id']);
    $_SESSION['panel']=['id'=>$owner['id'],'version'=>$owner['version_sesion'],'last'=>time()];

    $offer=['negocio_id'=>$venue['id'],'titulo'=>'15 % en café','descripcion'=>'Oferta de prueba','condiciones'=>'Solo pruebas, no canjeable.','porcentaje'=>15,'cupo_total'=>10,'activa'=>true];
    $first=$controller->dispatch(['action'=>'guardar_promocion']+$offer);
    $second=$panel->savePromotion($owner,array_merge($offer,['titulo'=>'Postre de cortesía','porcentaje'=>null]));
    verifyDashboard($first['id']!==$second['id'],'dueño crea varias promociones en su negocio');
    $report=$panel->dashboard($owner,[]);
    verifyDashboard(count($report['negocios'])===1 && count($report['promociones'])===2 && !isset($report['usuarios']),'dashboard del dueño solo muestra su negocio y sus promociones');
    verifyDashboard(!isset($report['usuario']['password_hash']),'dashboard no expone hashes de contraseñas');
    denied(fn()=>$panel->savePromotion($owner,array_merge($offer,['negocio_id'=>1])),403,'dueño no crea ofertas en otro negocio');
    denied(fn()=>$panel->savePromotion($owner,array_merge($offer,['id'=>1])),404,'dueño no edita otra promoción falsificando el ID');
    denied(fn()=>$panel->saveBusiness($owner,[]),403,'dueño no edita negocios ajenos ni su asignación');
    denied(fn()=>$panel->setOwnerActive($owner,['id'=>1,'activo'=>false]),403,'dueño no modifica accesos');
    denied(fn()=>$panel->resetPassword($owner,1),403,'dueño no restablece otras contraseñas');
    denied(fn()=>$panel->savePromotion($owner,array_merge($offer,['porcentaje'=>101])),400,'porcentaje fuera de rango rechazado');
    denied(fn()=>$panel->savePromotion($owner,array_merge($offer,['cupo_total'=>-1])),400,'cupo negativo rechazado');
    $panel->savePromotion($owner,array_merge($offer,['id'=>$first['id'],'activa'=>false]));
    verifyDashboard((int)$db->query('SELECT activa FROM cp_promociones WHERE id='.$first['id'])->fetchColumn()===0,'dueño puede pausar su promoción');
    $panel->savePromotion($admin,array_merge($offer,['id'=>$first['id'],'activa'=>true]));
    verifyDashboard((int)$db->query('SELECT activa FROM cp_promociones WHERE id='.$first['id'])->fetchColumn()===1,'superadministrador edita promociones de cualquier negocio');
    $panel->savePromotion($admin,array_merge($offer,['negocio_id'=>1]));
    verifyDashboard(count($promos->businesses())===7,'ruleta no duplica negocios con varias ofertas');
    $db->exec("INSERT INTO cp_campanas(id,nombre) VALUES (2,'Equidad con varias promociones')");
    $balancedConfig=$config; $balancedConfig['campana_id']=2; $balanced=new ChapiPromocionModel($db,$balancedConfig);
    for ($i=0;$i<14;$i++) {
        $ip=hash('sha256','dashboard-balance-ip-'.$i);
        $v=$balanced->visitor(hash('sha256','dashboard-balance-'.$i),$ip);
        $balanced->initialize((int)$v['id'],$ip); $balanced->spin((int)$v['id'],bin2hex(random_bytes(18)));
    }
    $counts=$db->query('SELECT COUNT(*) FROM cp_premios WHERE campana_id=2 GROUP BY negocio_id')->fetchAll(PDO::FETCH_COLUMN);
    verifyDashboard(count($counts)===7 && max($counts)-min($counts)<=1,'varias ofertas mantienen el reparto equilibrado entre negocios');
    $db->exec('UPDATE cp_promociones SET entregados=5 WHERE id='.$first['id']);
    denied(fn()=>$panel->savePromotion($owner,array_merge($offer,['id'=>$first['id'],'cupo_total'=>4])),400,'cupo no puede bajar de premios emitidos');
    $snapshot=$panel->lookup($admin,$prize['codigo']);
    $panel->savePromotion($admin,array_merge($offer,['id'=>$prize['promocion_id'],'negocio_id'=>$prize['negocio_id'],'titulo'=>'Beneficio modificado']));
    verifyDashboard($panel->lookup($admin,$prize['codigo'])['titulo']===$snapshot['titulo'],'editar una oferta conserva el beneficio de códigos previos');

    $panel->setOwnerActive($admin,['id'=>$owner['id'],'activo'=>false]);
    denied(fn()=>$controller->requireUser(),401,'desactivar al dueño invalida sesiones existentes');
    denied(fn()=>$panel->login('dueno.nuevo','Clave-prueba-nueva-782!'),401,'dueño desactivado no inicia sesión');
    $panel->setOwnerActive($admin,['id'=>$owner['id'],'activo'=>true]);
    verifyDashboard($panel->user((int)$owner['id'])!==null,'superadministrador reactiva dueño');
    denied(fn()=>$panel->setOwnerActive($admin,['id'=>1,'activo'=>false]),404,'gestión de dueños no permite desactivar superadministrador');
    $noPhone=$panel->saveBusiness($admin,['nombre'=>'Sin teléfono','categoria'=>'Café','activo'=>true]);
    denied(fn()=>$panel->savePromotion($admin,array_merge($offer,['negocio_id'=>$noPhone['id']])),400,'activación requiere WhatsApp configurado');
    $draft=$panel->savePromotion($admin,array_merge($offer,['negocio_id'=>$noPhone['id'],'activa'=>false]));
    verifyDashboard($draft['id']>0,'puede guardar borrador antes de configurar WhatsApp');
    $audit=json_encode($db->query('SELECT * FROM cp_auditoria')->fetchAll());
    verifyDashboard(!str_contains($audit,$created['password_temporal']),'auditoría no guarda contraseñas temporales');
    echo "RESULTADO DASHBOARD: $checks comprobaciones correctas.\n";
} finally {
    $db->exec('DROP DATABASE `'.$schema.'`');
}
