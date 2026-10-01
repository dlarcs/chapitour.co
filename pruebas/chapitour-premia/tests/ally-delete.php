<?php
declare(strict_types=1);
// Run after panel-fixtures.php setup, exclusively against its temporary database.
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
function verifyAlly(bool $ok,string $message): void {
    global $checks;
    if (!$ok) { throw new RuntimeException($message); }
    $checks++;
}
function rejectedAlly(callable $fn,int $status): void {
    try { $fn(); } catch (PanelError $e) { verifyAlly($e->getCode()===$status,'Código de rechazo incorrecto.'); return; }
    throw new RuntimeException('La eliminación debía ser rechazada.');
}
$db->exec("SET time_zone='+00:00'");
$db->exec('UPDATE cp_usuarios SET cambiar_password=0 WHERE id=100');
$panel=new ChapitourPanel($db);
$panel->installSchema();
$delete=['id'=>'1','confirm'=>'1'];
foreach ([null,['kind'=>'client','id'=>100,'version'=>1],['kind'=>'staff','id'=>101,'version'=>1]] as $auth) {
    $_SESSION=['csrf'=>'qa-only','auth'=>$auth];
    rejectedAlly(fn()=>$panel->handle('delete_business',$delete),403);
}
$_SESSION=['csrf'=>'qa-only','auth'=>['kind'=>'staff','id'=>100,'version'=>1]];
rejectedAlly(fn()=>$panel->handle('delete_business',['id'=>'1','confirm'=>'wrong']),422);
verifyAlly((int)$db->query('SELECT activo FROM cp_negocios WHERE id=1')->fetchColumn()===1,'Confirmación inválida modificó el negocio.');
$prizes=$db->query('SELECT * FROM cp_premios ORDER BY id')->fetchAll();
$details=$db->query('SELECT * FROM cp_premio_detalles ORDER BY premio_id')->fetchAll();
$db->exec("UPDATE cp_promociones SET activa=1 WHERE negocio_id=1");
$db->exec("UPDATE cp_panel_promociones m JOIN cp_promociones p ON p.id=m.promocion_id SET m.publicacion='approved',m.aprobada_por=100,m.aprobada_at=UTC_TIMESTAMP() WHERE p.negocio_id=1");
$state=$panel->handle('delete_business',$delete);
verifyAlly(!in_array('1',array_column($state['businesses'],'id'),true),'El aliado sigue en el listado.');
verifyAlly(!in_array('1',array_column($state['promotions'],'business_id'),true),'Las ofertas siguen en el listado.');
verifyAlly((int)$db->query('SELECT activo FROM cp_negocios WHERE id=1')->fetchColumn()===0,'El negocio no quedó inactivo.');
verifyAlly((int)$db->query('SELECT activo FROM cp_usuarios WHERE id=101')->fetchColumn()===0,'El acceso sigue activo.');
verifyAlly((int)$db->query('SELECT version_sesion FROM cp_usuarios WHERE id=101')->fetchColumn()===2,'No se revocaron las sesiones.');
verifyAlly((int)$db->query('SELECT MAX(activa) FROM cp_promociones WHERE negocio_id=1')->fetchColumn()===0,'Una promoción sigue activa.');
verifyAlly((int)$db->query("SELECT COUNT(*) FROM cp_panel_promociones m JOIN cp_promociones p ON p.id=m.promocion_id WHERE p.negocio_id=1 AND (m.publicacion<>'draft' OR m.aprobada_por IS NOT NULL OR m.aprobada_at IS NOT NULL)")->fetchColumn()===0,'No se retiró la aprobación.');
verifyAlly($prizes===$db->query('SELECT * FROM cp_premios ORDER BY id')->fetchAll(),'Cambió el historial de premios.');
verifyAlly($details===$db->query('SELECT * FROM cp_premio_detalles ORDER BY premio_id')->fetchAll(),'Cambió el beneficio de los códigos.');
verifyAlly(count($state['codes'])===count($prizes),'El administrador perdió el historial.');
verifyAlly((int)$db->query("SELECT COUNT(*) FROM cp_auditoria WHERE accion='aliado_eliminado' AND entidad_id=1 AND usuario_id=100")->fetchColumn()===1,'Falta la auditoría.');
verifyAlly((int)$db->query('SELECT activo FROM cp_usuarios WHERE id=100')->fetchColumn()===1,'Se modificó el administrador.');
$_SESSION=['csrf'=>'qa-only','auth'=>['kind'=>'staff','id'=>101,'version'=>1]];
verifyAlly($panel->actor()===null,'La sesión anterior del aliado sigue válida.');
$_SESSION=['csrf'=>'qa-only','auth'=>['kind'=>'staff','id'=>100,'version'=>1]];
verifyAlly((int)$db->query("SELECT COUNT(*) FROM cp_usuarios WHERE negocio_id=2 AND rol='aliado'")->fetchColumn()===0,'El caso sin cuenta necesita un negocio sin usuario.');
$state=$panel->handle('delete_business',['id'=>'2','confirm'=>'2']);
verifyAlly(!in_array('2',array_column($state['businesses'],'id'),true),'No se puede retirar un aliado sin cuenta.');
$_SESSION=['csrf'=>'qa-only'];
verifyAlly(!array_intersect(['1','2'],array_column($panel->state()['businesses'],'id')),'El catálogo público muestra aliados eliminados.');
echo "PASS eliminar aliado: $checks comprobaciones (permisos, confirmación, cuentas, promociones e historial).\n";
