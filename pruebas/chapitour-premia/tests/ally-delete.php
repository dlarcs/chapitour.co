<?php
declare(strict_types=1);
// Synthetic records only, in the isolated QA database. Test both copies in separate processes.
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) {
    throw new RuntimeException('Solo se permite la instancia temporal de QA.');
}
$production=($argv[2]??'production')==='production';
require $production?__DIR__.'/../../../premia/lib/Panel.php':__DIR__.'/../lib/Panel.php';
ini_set('session.save_path',dirname($socket));session_start();
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false
]);
$db->exec("SET time_zone='+00:00'");$checks=0;
function q(string $sql,array $args=[]): PDOStatement { global $db;$s=$db->prepare($sql);$s->execute($args);return $s; }
function verifyAlly(bool $ok,string $message): void { global $checks;if(!$ok){throw new RuntimeException($message);}$checks++; }
function rejectedAlly(callable $fn,int $status): void {
    try{$fn();}catch(PanelError $e){verifyAlly($e->getCode()===$status,'Rechazo incorrecto: '.$e->getMessage());return;}
    throw new RuntimeException('La acción debía ser rechazada.');
}
function authAlly(?int $id,string $kind='staff',int $version=1): void {
    $_SESSION=['csrf'=>'qa-only'];if($id!==null){$_SESSION['auth']=['kind'=>$kind,'id'=>$id,'version'=>$version];}
}
function businessAlly(array $state,string $id): array {return array_values(array_filter($state['businesses'],fn($b)=>$b['id']===$id))[0];}
$tag=bin2hex(random_bytes(6));$adminEmail='access-admin-'.$tag.'@example.invalid';$email='access-ally-'.$tag.'@example.invalid';
$password='Qa-access-old-456!';$newPassword='Qa-access-new-456!';$hash=password_hash($password,PASSWORD_BCRYPT);
q("INSERT INTO cp_usuarios(usuario,password_hash,rol,activo,cambiar_password) VALUES (?,?,'admin',1,0)",[$adminEmail,$hash]);$admin=(int)$db->lastInsertId();
q('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)',['Cliente QA acceso','access-client-'.$tag.'@example.invalid',$hash]);$client=(int)$db->lastInsertId();
authAlly($admin);$panel=new ChapitourPanel($db);$panel->installSchema();
$state=$panel->handle('save_business',['name'=>'Negocio QA acceso '.$tag,'email'=>$email,'password'=>$password]);
$b=array_values(array_filter($state['businesses'],fn($b)=>$b['email']===$email))[0];$businessId=$b['id'];$accountId=$b['account_id'];
verifyAlly((int)$accountId>0,'Falta la identidad de la cuenta en administración.');
q('UPDATE cp_negocios SET pagina=?,logo=? WHERE id=?',['/Capital_Queer/','/qa-logo.svg',$businessId]);
$panel->handle('save_promotion',['business_id'=>$businessId,'description'=>'Oferta sintética QA acceso','publication'=>'approved','whatsapp'=>'10000000','included'=>'Servicio QA','hours'=>'Horario QA','restrictions'=>'Solo QA aislado','confirmed'=>true]);
$businessBefore=q('SELECT * FROM cp_negocios WHERE id=?',[$businessId])->fetch();
$promosBefore=q('SELECT * FROM cp_promociones WHERE negocio_id=?',[$businessId])->fetchAll();
$metaBefore=q('SELECT m.* FROM cp_panel_promociones m JOIN cp_promociones p ON p.id=m.promocion_id WHERE p.negocio_id=?',[$businessId])->fetchAll();
// A redeemed prize and legacy audit payloads must survive physical removal of their actor.
q('INSERT INTO cp_visitantes(identidad_hash,ip_hash,referido_token) VALUES (?,?,?)',[hash('sha256','visitor-'.$tag),hash('sha256','ip-'.$tag),bin2hex(random_bytes(16))]);
$visitor=(int)$db->lastInsertId();$campaign=(int)q('SELECT id FROM cp_campanas ORDER BY id LIMIT 1')->fetchColumn();
q('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$client,$visitor]);
q("INSERT INTO cp_oportunidades(visitante_id,campana_id,origen,origen_clave) VALUES (?,?,'access-qa',?)",[$visitor,$campaign,$tag]);$opportunity=(int)$db->lastInsertId();
$redeemedCode='ACCESS-'.$tag;
q("INSERT INTO cp_premios(oportunidad_id,visitante_id,campana_id,negocio_id,promocion_id,codigo,solicitud_id,titulo,descripcion,condiciones,creado_at,vence_at,redimido_at,redimido_por) VALUES (?,?,?,?,?,?,?,'Premio QA','Beneficio QA','Solo pruebas',UTC_TIMESTAMP(),DATE_ADD(UTC_TIMESTAMP(),INTERVAL 72 HOUR),UTC_TIMESTAMP(),?)",[$opportunity,$visitor,$campaign,$businessId,$promosBefore[0]['id'],$redeemedCode,$tag,$accountId]);
$redeemedId=(int)$db->lastInsertId();
foreach (['{"origen":"qa","conservar":true}','["evento previo"]','texto legado no JSON'] as $payload) {
    q("INSERT INTO cp_auditoria(usuario_id,accion,entidad_id,datos) VALUES (?,'qa_historial',?,?)",[$accountId,$redeemedId,$payload]);
}
$prizesBefore=q('SELECT * FROM cp_premios ORDER BY id')->fetchAll();$detailsBefore=q('SELECT * FROM cp_premio_detalles ORDER BY premio_id')->fetchAll();
$delete=['id'=>$businessId,'account_id'=>$accountId,'confirm'=>$businessId];
q('UPDATE cp_usuarios SET cambiar_password=0 WHERE id=?',[$accountId]);
foreach([null,'client','ally'] as $role){authAlly($role===null?null:($role==='client'?$client:(int)$accountId),$role==='client'?'client':'staff');rejectedAlly(fn()=>$panel->handle('delete_business',$delete),403);}
authAlly(null);$publicBefore=businessAlly($panel->state(),$businessId);
verifyAlly(!array_key_exists('account_id',$publicBefore)&&!array_key_exists('email',$publicBefore),'La portada expone datos del acceso.');
authAlly($admin);
rejectedAlly(fn()=>$panel->handle('delete_business',array_merge($delete,['confirm'=>'wrong'])),422);
rejectedAlly(fn()=>$panel->handle('delete_business',['id'=>$businessId,'confirm'=>$businessId]),422);
rejectedAlly(fn()=>$panel->handle('delete_business',array_merge($delete,['account_id'=>(string)$admin])),409);
rejectedAlly(fn()=>$panel->handle('save_business',['name'=>'Duplicado QA','email'=>strtoupper($email),'password'=>$password]),409);
$state=$panel->handle('delete_business',$delete);$b=businessAlly($state,$businessId);
verifyAlly($b['email']===''&&$b['account_id']===null,'El panel no ofrece crear un nuevo acceso.');
verifyAlly($businessBefore===q('SELECT * FROM cp_negocios WHERE id=?',[$businessId])->fetch(),'Se modificó la página o publicación del negocio.');
verifyAlly($promosBefore===q('SELECT * FROM cp_promociones WHERE negocio_id=?',[$businessId])->fetchAll(),'Se modificaron las promociones.');
verifyAlly($metaBefore===q('SELECT m.* FROM cp_panel_promociones m JOIN cp_promociones p ON p.id=m.promocion_id WHERE p.negocio_id=?',[$businessId])->fetchAll(),'Se retiró una aprobación.');
foreach($prizesBefore as &$prize){if((string)$prize['redimido_por']===$accountId){$prize['redimido_por']=null;}}unset($prize);
verifyAlly($prizesBefore===q('SELECT * FROM cp_premios ORDER BY id')->fetchAll(),'Cambió el beneficio, estado o vigencia de los premios.');
verifyAlly(array_values(array_filter($state['codes'],fn($c)=>$c['code']===$redeemedCode))[0]['status']==='Redimido','El código volvió a estar activo.');
rejectedAlly(fn()=>$panel->handle('redeem',['code'=>$redeemedCode,'confirm'=>$redeemedCode]),409);
verifyAlly($detailsBefore===q('SELECT * FROM cp_premio_detalles ORDER BY premio_id')->fetchAll(),'Cambió el beneficio de los códigos.');
verifyAlly(!q('SELECT id FROM cp_usuarios WHERE id=?',[$accountId])->fetch(),'La cuenta debe desaparecer físicamente.');
$history=q("SELECT usuario_id,datos FROM cp_auditoria WHERE accion='qa_historial' AND entidad_id=? ORDER BY id",[$redeemedId])->fetchAll();
verifyAlly(count($history)===3,'Se borró el historial.');
foreach($history as $event){$data=json_decode($event['datos'],true);verifyAlly($event['usuario_id']===null&&(int)$data['usuario_eliminado_id']===(int)$accountId,'Falta la identidad histórica del acceso eliminado.');}
verifyAlly(json_decode($history[0]['datos'],true)['conservar']===true,'Cambió un dato de auditoría.');
verifyAlly(json_decode($history[1]['datos'],true)['datos_previos']==='["evento previo"]','Cambió una auditoría antigua.');
verifyAlly(json_decode($history[2]['datos'],true)['datos_previos']==='texto legado no JSON','Cambió un registro antiguo no JSON.');
verifyAlly((int)q("SELECT COUNT(*) FROM cp_auditoria WHERE accion='cuenta_aliado_eliminada' AND entidad_id=? AND usuario_id=?",[$accountId,$admin])->fetchColumn()===1,'Falta la auditoría de eliminación.');
authAlly((int)$accountId);verifyAlly($panel->actor()===null,'La sesión anterior del aliado sigue válida.');
authAlly(null);verifyAlly($publicBefore===businessAlly($panel->state(),$businessId),'El negocio dejó de aparecer igual en la portada.');
rejectedAlly(fn()=>$panel->handle('login',['email'=>$email,'password'=>$password]),401);
authAlly($admin);$state=$panel->handle('save_business',['business_id'=>$businessId,'email'=>$email,'password'=>$newPassword]);$newAccount=businessAlly($state,$businessId)['account_id'];
verifyAlly($newAccount!==$accountId,'Se reutilizó la identidad histórica en vez de crear una cuenta nueva.');
rejectedAlly(fn()=>$panel->handle('delete_business',$delete),409);
verifyAlly((int)q('SELECT activo FROM cp_usuarios WHERE id=?',[$newAccount])->fetchColumn()===1,'Una confirmación antigua eliminó la cuenta nueva.');
authAlly(null);rejectedAlly(fn()=>$panel->handle('login',['email'=>$email,'password'=>$password]),401);
$state=$panel->handle('login',['email'=>$email,'password'=>$newPassword]);
verifyAlly($state['user']['role']==='ally'&&$state['user']['must_change_password'],'El nuevo acceso no inicia sesión con cambio de contraseña obligatorio.');
$state=$panel->handle('change_password',['current_password'=>$newPassword,'new_password'=>'Qa-access-final-456!','confirm_password'=>'Qa-access-final-456!']);
verifyAlly(array_column($state['businesses'],'id')===[$businessId],'La cuenta nueva ve otro negocio.');
authAlly((int)$accountId);verifyAlly($panel->actor()===null,'Recrear el acceso reactivó una sesión vieja.');
authAlly($admin);$panel->handle('delete_business',array_merge($delete,['account_id'=>$newAccount]));
$state=$panel->handle('save_business',['name'=>'Otro negocio QA '.$tag,'email'=>$email,'password'=>$password]);$other=array_values(array_filter($state['businesses'],fn($b)=>$b['email']===$email))[0];
verifyAlly($other['id']!==$businessId,'El correo no se puede reutilizar con otro negocio.');
verifyAlly($businessBefore===q('SELECT * FROM cp_negocios WHERE id=?',[$businessId])->fetch(),'Reasignar el correo modificó el negocio anterior.');
// Compatibility: older deleted allies still occupy their original email.
$legacyEmail='legacy-ally-'.$tag.'@example.invalid';
q("INSERT INTO cp_usuarios(negocio_id,usuario,password_hash,rol,activo,cambiar_password) VALUES (?,?,?,'aliado',0,0)",[$businessId,$legacyEmail,$hash]);$legacy=(int)$db->lastInsertId();
rejectedAlly(fn()=>$panel->handle('save_business',['business_id'=>$other['id'],'email'=>$legacyEmail,'password'=>$password]),409);
verifyAlly(q('SELECT usuario FROM cp_usuarios WHERE id=?',[$legacy])->fetchColumn()===$legacyEmail,'Un intento fallido no revirtió el archivado.');
$state=$panel->handle('save_business',['business_id'=>$businessId,'email'=>$legacyEmail,'password'=>$newPassword]);
verifyAlly(businessAlly($state,$businessId)['email']===$legacyEmail,'No se reutilizó el correo de una eliminación anterior.');
verifyAlly(!q('SELECT id FROM cp_usuarios WHERE id=?',[$legacy])->fetch(),'La cuenta antigua sigue en la base de datos.');
foreach([$adminEmail,'access-client-'.$tag.'@example.invalid'] as $reserved){rejectedAlly(fn()=>$panel->handle('save_business',['name'=>'Conflicto QA','email'=>$reserved,'password'=>$password]),409);}
$inactiveAdminEmail='inactive-admin-'.$tag.'@example.invalid';
q("INSERT INTO cp_usuarios(usuario,password_hash,rol,activo,cambiar_password) VALUES (?,?,'admin',0,0)",[$inactiveAdminEmail,$hash]);
rejectedAlly(fn()=>$panel->handle('save_business',['name'=>'Conflicto QA','email'=>$inactiveAdminEmail,'password'=>$password]),409);
verifyAlly((int)q('SELECT activo FROM cp_usuarios WHERE id=?',[$admin])->fetchColumn()===1,'Cambió el acceso del administrador.');
file_put_contents(dirname($socket).'/ally-access-ui.json',json_encode(['admin_email'=>$adminEmail,'password'=>$password,'business_id'=>$businessId,'business_name'=>$businessBefore['nombre'],'email'=>$legacyEmail,'account_password'=>$newPassword,'base'=>$production?'/':'/pruebas/chapitour-premia/'],JSON_UNESCAPED_SLASHES));
echo 'PASS '.($production?'principal':'pruebas').": $checks comprobaciones de permisos, correo reutilizable, sesiones, portada, promociones e historial.\n";
