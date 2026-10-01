<?php
declare(strict_types=1);
// Only the isolated database created by panel-fixtures.php is allowed.
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) {
    throw new RuntimeException('Solo se permite la instancia temporal de QA.');
}
require __DIR__.'/../lib/Panel.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES=>false
]);
$checks=0;
function verifyPublication(bool $ok,string $message): void {
    global $checks;
    if (!$ok) { throw new RuntimeException($message); }
    $checks++;
}
function rejectPublication(callable $fn,int $status): string {
    try { $fn(); } catch (PanelError $e) { verifyPublication($e->getCode()===$status,'Respuesta de validación incorrecta.'); return $e->getMessage(); }
    throw new RuntimeException('La operación debía rechazarse.');
}
$db->exec("SET time_zone='+00:00'");
$panel=new ChapitourPanel($db);
$admin=['kind'=>'staff','id'=>100,'version'=>1];
$_SESSION=['csrf'=>'qa-only','auth'=>$admin];
$suffix=bin2hex(random_bytes(4));
$name='Confirmación QA '.$suffix;
$s=$db->prepare("INSERT INTO cp_negocios(slug,nombre,categoria) VALUES (?,?,'Prueba local sin valor comercial')");
$s->execute(['confirmacion-qa-'.$suffix,$name]);
$business=(string)$db->lastInsertId();
$input=['business_id'=>$business,'description'=>'Oferta sintética QA, sin valor comercial','publication'=>'draft'];
$state=$panel->handle('save_promotion',$input);
$p=array_values(array_filter($state['promotions'],fn($p)=>$p['business_id']===$business))[0];
$input['id']=$p['id'];
verifyPublication($p['publication']==='draft','El borrador no se guardó.');
foreach ([null,['kind'=>'client','id'=>100,'version'=>1],['kind'=>'staff','id'=>102,'version'=>1]] as $auth) {
    $_SESSION=['csrf'=>'qa-only','auth'=>$auth];
    rejectPublication(fn()=>$panel->handle('save_promotion',$input),403);
}
$_SESSION=['csrf'=>'qa-only','auth'=>$admin];
$approved=array_merge($input,['publication'=>'approved']);
$message=rejectPublication(fn()=>$panel->handle('save_promotion',$approved),422);
foreach (['WhatsApp del negocio','Productos o servicios incluidos','Horarios','Restricciones','marcar la confirmación'] as $label) {
    verifyPublication(strpos($message,$label)!==false,'Falta indicar el campo pendiente: '.$label);
}
verifyPublication((int)$db->query('SELECT activa FROM cp_promociones WHERE id='.(int)$p['id'])->fetchColumn()===0,'Se activó una oferta incompleta.');
$approved=array_merge($approved,['whatsapp'=>'10000000','included'=>'Servicio sintético QA','hours'=>'Horario QA','restrictions'=>'Solo prueba local, sin valor comercial']);
rejectPublication(fn()=>$panel->handle('save_promotion',$approved),422);
$approved['confirmed']=true;
$prizes=$db->query('SELECT * FROM cp_premios ORDER BY id')->fetchAll();
$state=$panel->handle('save_promotion',$approved);
$saved=array_values(array_filter($state['promotions'],fn($p)=>$p['business_id']===$business))[0];
verifyPublication($saved['publication']==='approved','La promoción no cambió a confirmada.');
verifyPublication(in_array($business,$state['campaign']['wheel_business_ids'],true),'La promoción confirmada no está disponible en la ruleta.');
$record=$db->query('SELECT p.activa,m.publicacion,m.aprobada_por,m.aprobada_at FROM cp_promociones p JOIN cp_panel_promociones m ON m.promocion_id=p.id WHERE p.id='.(int)$p['id'])->fetch();
verifyPublication((int)$record['activa']===1 && $record['publicacion']==='approved','Los dos estados de base de datos no coinciden.');
verifyPublication((int)$record['aprobada_por']===100 && $record['aprobada_at']!==null,'No se registró la confirmación del administrador.');
$fresh=(new ChapitourPanel($db))->state();
verifyPublication(array_values(array_filter($fresh['promotions'],fn($p)=>$p['business_id']===$business))[0]['publication']==='approved','Se pierde el estado al consultar de nuevo.');
verifyPublication($prizes===$db->query('SELECT * FROM cp_premios ORDER BY id')->fetchAll(),'Cambió el historial de premios.');
// Leave a complete draft for the browser test, even though its checkbox was checked.
$state=$panel->handle('save_promotion',array_merge($approved,['publication'=>'draft']));
verifyPublication(array_values(array_filter($state['promotions'],fn($p)=>$p['business_id']===$business))[0]['publication']==='draft','Guardar borrador con la casilla marcada aprobó la oferta.');
verifyPublication(!in_array($business,$state['campaign']['wheel_business_ids'],true),'El borrador sigue disponible en la ruleta.');
echo "PASS publicación: $checks comprobaciones.\nFixture para interfaz: $name; promoción {$p['id']}.\n";
