<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
define('CHAPI_PROMOS',true);
require dirname(__DIR__).'/promos/src/bootstrap.php';
$root=dirname(__DIR__);
$admin=new PDO(getenv('CHAPI_TEST_ADMIN_DSN')?:'mysql:host=127.0.0.1;charset=utf8mb4',getenv('CHAPI_TEST_ADMIN_USER')?:'root',getenv('CHAPI_TEST_ADMIN_PASSWORD')?:'',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$schema='chapitour_test_'.bin2hex(random_bytes(5));
$admin->exec('CREATE DATABASE `'.$schema.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$admin->exec('USE `'.$schema.'`');
$keep=in_array('--keep-for-browser',$argv,true);
$passed=0;
function check(bool $ok,string $name): void { global $passed; if (!$ok) throw new RuntimeException('FAIL: '.$name); $passed++; echo 'OK '.$name."\n"; }
function rejects(callable $fn,string $code,string $name): void { try { $fn(); } catch (ChapiError $e) { check($e->errorCode===$code,$name); return; } throw new RuntimeException('FAIL: '.$name.' no rechazó la operación'); }
function requestId(): string { return substr(bin2hex(random_bytes(18)),0,36); }
try {
    foreach (['001_schema.sql','002_negocios.sql'] as $file) foreach (explode(';',file_get_contents($root.'/promos/database/'.$file)) as $sql) if(trim($sql)!=='') $admin->exec($sql);
    $config=chapi_config(); $config['dsn']=(getenv('CHAPI_TEST_ADMIN_DSN')?:'mysql:host=127.0.0.1;charset=utf8mb4').';dbname='.$schema; $config['db_user']=getenv('CHAPI_TEST_ADMIN_USER')?:'root'; $config['db_password']=getenv('CHAPI_TEST_ADMIN_PASSWORD')?:''; $config['base_url']='http://127.0.0.1:8774';
    $config['reglas']['max_bienvenidas_por_ip_dia']=100;
    $model=new ChapiPromocionModel($admin,$config); $panel=new ChapiPanelModel($admin,$config);
    $admin->exec("UPDATE cp_promociones SET titulo='10 % de descuento (PRUEBA)',descripcion='Oferta exclusiva de la base de pruebas',condiciones='Prueba automática. No válida en establecimientos reales.',porcentaje=10,activa=1");
    $q=$admin->prepare('INSERT INTO cp_usuarios(negocio_id,usuario,password_hash,rol,cambiar_password) VALUES (?,?,?,?,0)');
    $testPassword='ChapiTest-only-4829!';
    $q->execute([null,'admin.test',password_hash($testPassword,PASSWORD_DEFAULT),'admin']);
    $adminUser=$panel->user((int)$admin->lastInsertId());
    for($i=1;$i<=6;$i++) $q->execute([$i,'aliado'.$i.'.test',password_hash($testPassword,PASSWORD_DEFAULT),'aliado']);
    $v=$model->visitor(hash('sha256','visitor-a'),hash('sha256','ip-a')); $id=(int)$v['id'];
    $s=$model->initialize($id,hash('sha256','ip-a'));
    check($s['oportunidades']===1 && $s['abrir_automaticamente'],'primera oportunidad y apertura');
    $model->shown($id,$s['oportunidad_mostrar']);
    check(!$model->initialize($id,hash('sha256','ip-a'))['abrir_automaticamente'],'no reaparece tras marcar vista');
    check((int)$admin->query('SELECT visitas FROM cp_participaciones WHERE visitante_id='.$id)->fetchColumn()===1,'recargar no incrementa visitas');
    $nonce=requestId(); $prize=$model->spin($id,$nonce);
    check($prize['estado']==='activo' && abs(strtotime($prize['vence_at'])-strtotime($prize['creado_at'])-259200)<1,'premio activo durante 72 horas exactas');
    check($model->spin($id,$nonce)['codigo']===$prize['codigo'],'reintento devuelve el mismo código');
    rejects(fn()=>$model->spin($id,requestId()),'NO_OPPORTUNITY','no emite otro premio sin oportunidad');
    $owner=$panel->user(1+$prize['negocio_id']); $foreign=$panel->user(1+($prize['negocio_id']%6+1));
    rejects(fn()=>$panel->lookup($foreign,$prize['codigo']),'PRIZE_NOT_FOUND','otro negocio no puede consultar el premio');
    rejects(fn()=>$panel->redeem($foreign,$prize['codigo']),'INVALID_REQUEST','otro negocio no puede redimir');
    check($panel->lookup($owner,$prize['codigo'])['estado']==='activo','el aliado correcto consulta el código');
    $redeemed=$panel->redeem($owner,$prize['codigo']);
    check($redeemed['premio']['estado']==='redimido' && $model->state($id)['oportunidades']===1,'tick redime y concede exactamente un giro');
    rejects(fn()=>$panel->redeem($owner,$prize['codigo']),'ALREADY_REDEEMED','doble redención rechazada');
    check($model->state($id)['oportunidades']===1,'doble tick no duplica oportunidades');
    $p2=$model->spin($id,requestId()); $admin->exec("UPDATE cp_premios SET vence_at=DATE_SUB(UTC_TIMESTAMP(),INTERVAL 1 SECOND) WHERE id=".$p2['id']);
    rejects(fn()=>$panel->redeem($adminUser,$p2['codigo']),'EXPIRED','código vencido no redimible');
    $admin->exec("UPDATE cp_promociones SET titulo='Título nuevo' WHERE id=".$p2['promocion_id']);
    check($panel->lookup($adminUser,$p2['codigo'])['titulo']===$p2['titulo'],'el premio conserva las condiciones originales');
    rejects(fn()=>$model->confirmReferral($id,hash('sha256','ip-a'),$v['referido_token']),'SELF_REFERRAL','autoinvitación rechazada');
    $friends=[];
    for($i=0;$i<5;$i++) { $ip=hash('sha256','friend-ip-'.$i); $f=$model->visitor(hash('sha256','friend-'.$i),$ip); $friends[]=$f; $model->confirmReferral((int)$f['id'],$ip,$v['referido_token']); }
    check($model->state($id)['oportunidades']===1 && $model->state($id)['referidos_total']===5,'cinco visitas válidas desbloquean un giro');
    rejects(fn()=>$model->confirmReferral((int)$friends[0]['id'],hash('sha256','different-ip'),$v['referido_token']),'REFERRAL_DUPLICATE','referido repetido no cuenta');
    $same=$model->visitor(hash('sha256','same-network'),hash('sha256','ip-a'));
    rejects(fn()=>$model->confirmReferral((int)$same['id'],hash('sha256','ip-a'),$v['referido_token']),'REFERRAL_NETWORK','misma IP no multiplica referidos');
    $admin->exec('UPDATE cp_promociones SET activa=0');
    rejects(fn()=>$model->spin($id,requestId()),'NO_PROMOTIONS','no inventa premios sin ofertas activas');
    check($model->state($id)['oportunidades']===1,'sin ofertas se conserva el giro');
    $admin->exec('UPDATE cp_promociones SET activa=1');
    $configVisit=$config; $configVisit['reglas']['frecuencia']='cada_visitas'; $configVisit['reglas']['min_segundos_entre_visitas']=0;
    $visits=new ChapiPromocionModel($admin,$configVisit); $vv=$visits->visitor(hash('sha256','visits-rule'),hash('sha256','visits-ip'));
    for($i=0;$i<5;$i++) $st=$visits->initialize((int)$vv['id'],hash('sha256','visits-ip'));
    check($st['oportunidades']===1,'antes de cinco visitas adicionales no concede giro');
    check($visits->initialize((int)$vv['id'],hash('sha256','visits-ip'))['oportunidades']===2,'cada cinco visitas adicionales concede giro');
    $configDays=$config; $configDays['reglas']['frecuencia']='cada_dias'; $configDays['reglas']['min_segundos_entre_visitas']=0;
    $days=new ChapiPromocionModel($admin,$configDays);
    $admin->exec('UPDATE cp_participaciones SET ultima_programada_at=DATE_SUB(UTC_TIMESTAMP(),INTERVAL 8 DAY) WHERE visitante_id='.$vv['id']);
    check($days->initialize((int)$vv['id'],hash('sha256','visits-ip'))['oportunidades']===3,'frecuencia por días configurable');
    $report=$panel->dashboard($owner,[]);
    check(count($report['negocios'])===1 && (int)$report['negocios'][0]['id']===$prize['negocio_id'],'dashboard aislado por negocio');
    check(count($panel->dashboard($adminUser,[])['negocios'])===6,'administrador ve seis negocios');
    rejects(fn()=>$panel->saveBusiness($owner,[]),'INVALID_REQUEST','aliado no modifica promociones administrativas');
    rejects(fn()=>$panel->login('admin.test','incorrecta'),'LOGIN_FAILED','contraseña incorrecta rechazada');
    $panel->changePassword($adminUser,$testPassword,'ChapiTest-new-9284!');
    check((int)$panel->user((int)$adminUser['id'])['version_sesion']===2,'cambio de contraseña invalida sesiones previas');
    $panel->changePassword($adminUser,'ChapiTest-new-9284!',$testPassword);
    $admin->exec("UPDATE cp_promociones SET titulo='10 % de descuento (PRUEBA)',activa=1");
    // Draw distribution should differ by at most one after a new campaign begins.
    $admin->exec("INSERT INTO cp_campanas(id,nombre) VALUES (2,'Equidad de pruebas')"); $balancedConfig=$config; $balancedConfig['campana_id']=2; $balanced=new ChapiPromocionModel($admin,$balancedConfig);
    for($i=0;$i<18;$i++) { $ip=hash('sha256','balance-ip-'.$i); $a=$balanced->visitor(hash('sha256','balance-'.$i),$ip); $balanced->initialize((int)$a['id'],$ip); $balanced->spin((int)$a['id'],requestId()); }
    $counts=$admin->query('SELECT COUNT(*) FROM cp_premios WHERE campana_id=2 GROUP BY negocio_id')->fetchAll(PDO::FETCH_COLUMN);
    check(count($counts)===6 && max($counts)-min($counts)<=1,'reparto equilibrado entre seis aliados');
    $admin->exec('UPDATE cp_campanas SET activa=0 WHERE id=1');
    rejects(fn()=>$model->spin($id,requestId()),'CAMPAIGN_PAUSED','campaña pausada rechaza giros');
    $admin->exec('UPDATE cp_campanas SET activa=1 WHERE id=1');
    echo "RESULTADO: $passed comprobaciones correctas.\n";
    if ($keep) {
        file_put_contents('/private/tmp/chapi-test-environment.json',json_encode(['database'=>$schema,'dsn'=>$config['dsn'],'usuario'=>'admin.test','password'=>$testPassword,'base_url'=>$config['base_url']]));
        chmod('/private/tmp/chapi-test-environment.json',0600);
        echo "Fixture aislada disponible en /private/tmp/chapi-test-environment.json\n";
    }
} finally {
    if (!$keep) $admin->exec('DROP DATABASE `'.$schema.'`');
}
