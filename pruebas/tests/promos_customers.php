<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
ob_start();
ini_set('session.save_path','/private/tmp');
session_start();
define('CHAPI_PROMOS',true);
require dirname(__DIR__).'/promos/src/bootstrap.php';
$dsn=getenv('CHAPI_TEST_ADMIN_DSN')?:'mysql:unix_socket=/private/tmp/chapitour-dashboard-mysql.sock;charset=utf8mb4';
$db=new PDO($dsn,'root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
$schema='chapitour_customer_test_'.bin2hex(random_bytes(5));
$db->exec('CREATE DATABASE `'.$schema.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$db->exec('USE `'.$schema.'`'); $db->exec("SET time_zone='+00:00'");
$keep=in_array('--keep-for-browser',$argv,true); $passed=0;
function ok(bool $value,string $message): void { global $passed; if (!$value) throw new RuntimeException('FAIL '.$message); $passed++; echo 'OK '.$message."\n"; }
function deniedCustomer(callable $fn,int $status,string $message): void { try { $fn(); } catch (ChapiError $e) { ok($e->status===$status,$message); return; } throw new RuntimeException('FAIL '.$message); }
function nonce(): string { return bin2hex(random_bytes(18)); }
function visitor(string $name,string $network=''): array { global $model; return $model->visitor(hash('sha256',$name),hash('sha256',$network?:$name)); }
function spinTest(int $id): array { global $model; $model->preview($id,nonce()); return $model->spin($id,nonce()); }
try {
    foreach (['001_schema.sql','002_negocios.sql'] as $file) foreach (explode(';',file_get_contents(dirname(__DIR__).'/promos/database/'.$file)) as $sql) if (trim($sql)!=='') $db->exec($sql);
    $config=chapi_config(); $config['dsn']=$dsn.';dbname='.$schema; $config['base_url']='http://127.0.0.1:8785/pruebas'; $config['reglas']['max_bienvenidas_por_ip_dia']=100;
    $model=new ChapiPromocionModel($db,$config); $panel=new ChapiPanelModel($db,$config); $customers=new ChapiCustomerModel($db,$config);
    $db->exec("UPDATE cp_promociones SET titulo='15 % para descubrir Chapinero',descripcion='Una promoción de la base de pruebas.',condiciones='Solo pruebas locales. No canjeable en establecimientos reales.',porcentaje=15,activa=1");
    $q=$db->prepare('INSERT INTO cp_usuarios(negocio_id,usuario,password_hash,rol,cambiar_password) VALUES (?,?,?,?,0)');
    $password='ChapiTest-only-4829!'; $q->execute([null,'admin.test',password_hash($password,PASSWORD_DEFAULT),'admin']);
    $admin=$panel->user((int)$db->lastInsertId());
    for ($i=1;$i<=6;$i++) $q->execute([$i,'aliado'.$i.'.test',password_hash($password,PASSWORD_DEFAULT),'aliado']);

    $v=visitor('customer-a','shared-ip'); $id=(int)$v['id'];
    $state=$model->initialize($id,hash('sha256','shared-ip'));
    ok($state['oportunidades']===1 && $state['abrir_automaticamente'],'bienvenida disponible una vez');
    $key=nonce(); $prize=$model->spin($id,$key);
    ok((bool)preg_match('/^CHAPI-[A-Z0-9]{1,6}-[0-9]{9,}$/D',$prize['codigo']),'código con prefijo del negocio y número');
    $parts=explode('-',$prize['codigo']); ok((int)end($parts)===$prize['id'],'número respaldado por ID único de base de datos');
    ok(strtotime($prize['vence_at'])-strtotime($prize['creado_at'])===72*3600,'72 horas exactas desde la emisión');
    ok($model->spin($id,$key)['codigo']===$prize['codigo'],'reintento de giro conserva el mismo código');
    parse_str(parse_url($prize['url_whatsapp'],PHP_URL_QUERY),$query);
    ok($query['text']===$prize['mensaje_whatsapp'],'WhatsApp lleva el mismo mensaje que la ventana y panel');
    foreach ([$prize['negocio'],$prize['titulo'],$prize['codigo'],$prize['direccion'],$prize['condiciones'],'72 horas'] as $text) ok(str_contains($query['text'],$text),'mensaje incluye '.($text==='72 horas'?'vigencia':'información de la promoción'));
    $snapshot=$prize['direccion'];
    $db->exec("UPDATE cp_negocios SET direccion='Dirección modificada' WHERE id=".$prize['negocio_id']);
    ok($panel->lookup($admin,$prize['codigo'])['direccion']===$snapshot,'el código conserva la dirección emitida');
    $db->exec("UPDATE cp_negocios SET direccion=".$db->quote($snapshot).' WHERE id='.$prize['negocio_id']);
    $model->track($id,'reclamar_whatsapp',$prize['id']);
    ok($panel->lookup($admin,$prize['codigo'])['whatsapp_aperturas']==1,'panel sigue aperturas de WhatsApp');
    ok($panel->lookup($admin,$prize['codigo'])['estado']==='activo','abrir WhatsApp no redime el código');
    for ($i=2;$i<=5;$i++) $state=$model->initialize($id,hash('sha256','shared-ip'));
    ok($state['oportunidades']===0 && !$state['abrir_automaticamente'],'antes de cinco visitas adicionales no abre ni concede giro');
    $state=$model->initialize($id,hash('sha256','shared-ip'));
    ok($state['oportunidades']===1 && $state['abrir_automaticamente'],'quinta visita adicional concede giro y abre promoción');
    $model->shown($id,$state['oportunidad_mostrar']);
    $db->exec("UPDATE cp_promociones SET titulo=CONCAT(titulo,'') WHERE id=".$prize['promocion_id']);
    ok(!$model->state($id)['abrir_automaticamente'],'editar oferta no borra progreso ni reabre oportunidades vistas');
    $owner=$panel->user(1+$prize['negocio_id']);
    $foreign=$panel->user(1+($prize['negocio_id']%6+1));
    deniedCustomer(fn()=>$panel->redeem($admin,$prize['codigo']),403,'administrador observa pero redención la confirma el negocio');
    deniedCustomer(fn()=>$panel->redeem($foreign,$prize['codigo']),404,'otro negocio no redime el código');
    $result=$panel->redeem($owner,$prize['codigo']);
    ok($result['premio']['estado']==='redimido' && count($result['premio']['seguimiento'])===2,'negocio redime y deja seguimiento fechado');
    deniedCustomer(fn()=>$panel->redeem($owner,$prize['codigo']),409,'un código solo se redime una vez');
    $expired=spinTest($id);
    $db->exec("UPDATE cp_premios SET creado_at=DATE_SUB(UTC_TIMESTAMP(),INTERVAL 73 HOUR),vence_at=DATE_SUB(UTC_TIMESTAMP(),INTERVAL 1 HOUR) WHERE id=".$expired['id']);
    $expired=$panel->lookup($admin,$expired['codigo']);
    ok($expired['estado']==='vencido' && $expired['url_whatsapp']===null && $expired['seguimiento'][1]['estado']==='vencido','vence automáticamente y muestra seguimiento');
    deniedCustomer(fn()=>$panel->redeem($panel->user(1+$expired['negocio_id']),$expired['codigo']),409,'negocio no redime un código vencido');
    $active=spinTest($id);
    ok(count(array_unique([$prize['codigo'],$expired['codigo'],$active['codigo']]))===3,'cada giro nuevo emite código distinto');
    $dashboard=$customers->dashboard($id,[],null);
    ok((int)$dashboard['resumen']['activos']===1 && (int)$dashboard['resumen']['redimidos']===1 && (int)$dashboard['resumen']['vencidos']===1,'cliente ve activos redimidos y vencidos');
    foreach (['activo','redimido','vencido'] as $status) ok(count($customers->dashboard($id,['estado'=>$status],null)['premios'])===1,'filtro '.$status.' del cliente');
    $sameIp=visitor('other-guest','shared-ip');
    ok($customers->dashboard((int)$sameIp['id'],[],null)['total']===0,'misma IP no mezcla códigos de dos visitantes');
    deniedCustomer(fn()=>$model->track((int)$sameIp['id'],'reclamar_whatsapp',$active['id']),404,'no permite consultar códigos ajenos por ID');
    $_SESSION=[];
    $account=$customers->register($id,['nombre'=>'Cliente de prueba','email'=>'cliente.prueba@example.test','password'=>$password]);
    $scoped=$config; $scoped['_visitantes']=$customers->visitorIds($account['id']);
    $customerPanel=new ChapiCustomerModel($db,$scoped);
    ok($customerPanel->dashboard($id,[],$account)['total']===3,'registro vincula historial anónimo completo');
    ok(!isset($account['password_hash']),'registro no expone hash');
    $hash=$db->query('SELECT password_hash FROM cp_clientes WHERE id='.$account['id'])->fetchColumn();
    ok(password_verify($password,$hash) && $hash!==$password,'contraseña de cliente protegida con hash');
    unset($_SESSION['cliente']);
    deniedCustomer(fn()=>$customers->login((int)$sameIp['id'],['email'=>'cliente.prueba@example.test','password'=>'incorrecta']),401,'sesión requiere contraseña correcta');
    $guestPrize=spinTest((int)$sameIp['id']);
    $customers->login((int)$sameIp['id'],['email'=>'cliente.prueba@example.test','password'=>$password]);
    $scoped['_visitantes']=$customers->visitorIds($account['id']);
    $customerPanel=new ChapiCustomerModel($db,$scoped); $accountModel=new ChapiPromocionModel($db,$scoped);
    ok($customerPanel->dashboard($id,[],$account)['total']===4,'inicio de sesión vincula códigos de otro navegador');
    ok(count($accountModel->state($id)['premios'])===4,'ruleta y dashboard comparten códigos de la cuenta');
    deniedCustomer(fn()=>$accountModel->confirmReferral($id,hash('sha256','other-network'),$sameIp['referido_token']),409,'cuenta no confirma enlaces propios de otros navegadores');
    $before=$accountModel->state($id)['oportunidades'];
    for ($i=0;$i<7;$i++) { $f=visitor('friend-'.$i); $model->confirmReferral((int)$f['id'],hash('sha256','friend-'.$i),$v['referido_token']); }
    ok($accountModel->state($id)['oportunidades']===$before,'siete referidos todavía no conceden giro');
    $eighth=visitor('friend-7'); $model->confirmReferral((int)$eighth['id'],hash('sha256','friend-7'),$v['referido_token']);
    ok($accountModel->state($id)['oportunidades']===$before+1 && $accountModel->state($id)['referidos_total']===8,'ocho referidos conceden exactamente un giro');
    deniedCustomer(fn()=>$model->confirmReferral((int)$eighth['id'],hash('sha256','friend-other-ip'),$v['referido_token']),409,'referido duplicado no aumenta la meta');
    $model->track($id,'compartir_whatsapp',null);
    ok($accountModel->state($id)['referidos_total']===8,'abrir compartir no inventa mensajes enviados');
    $_SESSION=[];
    $other=visitor('second-account');
    $otherAccount=$customers->register((int)$other['id'],['nombre'=>'Otro cliente','email'=>'otro@example.test','password'=>$password]);
    unset($_SESSION['cliente']);
    deniedCustomer(fn()=>$customers->login((int)$other['id'],['email'=>'cliente.prueba@example.test','password'=>$password]),409,'otra cuenta no puede apropiarse de un historial vinculado');
    $disabled=$config; $disabled['reglas']['modo_pruebas']=false;
    deniedCustomer(fn()=>(new ChapiPromocionModel($db,$disabled))->preview($id,nonce()),404,'botón de prueba exige modo de pruebas');
    for ($i=0;$i<10;$i++) spinTest($id);
    $customerPanel=new ChapiCustomerModel($db,$scoped);
    ok($customerPanel->dashboard($id,['pagina'=>1],$account)['total']===14 && count($customerPanel->dashboard($id,['pagina'=>2],$account)['premios'])===2,'historial completo paginado sin cortar códigos');
    $codes=$db->query('SELECT codigo FROM cp_premios')->fetchAll(PDO::FETCH_COLUMN);
    $numbers=array_map(fn($c)=>(int)substr($c,strrpos($c,'-')+1),$codes);
    ok(count($numbers)===count(array_unique($numbers)),'ningún número se repite en los códigos emitidos');
    $prefixes=[1=>'SG',2=>'CQ',3=>'JF',4=>'GDB',5=>'PCB',6=>'GCC'];
    foreach ($db->query('SELECT codigo,negocio_id FROM cp_premios')->fetchAll() as $issued) if (!str_starts_with($issued['codigo'],'CHAPI-'.$prefixes[$issued['negocio_id']].'-')) throw new RuntimeException('Prefijo incorrecto del negocio');
    ok(true,'prefijos conservan iniciales de negocios con tildes y símbolos');
    ok($customerPanel->dashboard($id,[],$account)['siguiente_nivel']['nombre']==='Gold','ruta de niveles refleja metas de redención');
    for ($i=8;$i<16;$i++) { $f=visitor('friend-'.$i); $model->confirmReferral((int)$f['id'],hash('sha256','friend-'.$i),$v['referido_token']); }
    ok($accountModel->state($id)['referidos_total']===16 && (int)$db->query("SELECT COUNT(*) FROM cp_oportunidades WHERE visitante_id=$id AND origen='referidos'")->fetchColumn()===2,'cada grupo de ocho concede otro giro sin repetir premios');
    $left=visitor('merge-ref-left'); $right=visitor('merge-ref-right');
    foreach ([$left,$right] as $index=>$inviter) for ($i=0;$i<4;$i++) { $f=visitor('merge-friend-'.$index.'-'.$i); $model->confirmReferral((int)$f['id'],hash('sha256','merge-friend-'.$index.'-'.$i),$inviter['referido_token']); }
    unset($_SESSION['cliente']);
    $merged=$customers->register((int)$left['id'],['nombre'=>'Cuenta combinada','email'=>'combinada@example.test','password'=>$password]);
    unset($_SESSION['cliente']); $customers->login((int)$right['id'],['email'=>'combinada@example.test','password'=>$password]);
    $mergedConfig=$config; $mergedConfig['_visitantes']=$customers->visitorIds($merged['id']);
    $mergedModel=new ChapiPromocionModel($db,$mergedConfig); $mergedState=$mergedModel->state((int)$left['id']);
    ok($mergedState['referidos_total']===8 && $mergedState['oportunidades']===1,'cuatro invitaciones de cada navegador se suman al vincular la cuenta');
    unset($_SESSION['cliente']); $customers->login((int)$right['id'],['email'=>'combinada@example.test','password'=>$password]);
    ok($mergedModel->state((int)$left['id'])['oportunidades']===1,'iniciar sesión otra vez no duplica el giro ganado');
    echo "RESULTADO CLIENTES: $passed comprobaciones correctas.\n";
    if ($keep) {
        file_put_contents('/private/tmp/chapi-customers-environment.json',json_encode(['database'=>$schema,'dsn'=>$config['dsn'],'base_url'=>$config['base_url']]));
        chmod('/private/tmp/chapi-customers-environment.json',0600);
        echo "Base temporal lista para la vista de pruebas.\n";
    }
} finally {
    if (!$keep) $db->exec('DROP DATABASE `'.$schema.'`');
    session_write_close(); ob_end_flush();
}
