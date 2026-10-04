<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo QA temporal.'); }
ini_set('session.save_path',dirname($socket));session_start();require __DIR__.'/../lib/Panel.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);$db->exec("SET time_zone='+00:00'");
$checks=0;
function check($ok,string $message):void {global $checks;if(!$ok)throw new RuntimeException($message);$checks++;}
function query(string $sql,array $args=[]):PDOStatement {global $db;$s=$db->prepare($sql);$s->execute($args);return $s;}
function reject(callable $fn,int $code):void {try{$fn();throw new RuntimeException('Debió rechazarse');}catch(PanelError $e){check($e->getCode()===$code,$e->getMessage());}}
function auth(?int $id,string $kind='client'):void {$_SESSION=['csrf'=>'qa'];if($id!==null){$table=$kind==='client'?'cp_clientes':'cp_usuarios';$_SESSION['auth']=['kind'=>$kind,'id'=>$id,'version'=>(int)query('SELECT version_sesion FROM '.$table.' WHERE id=?',[$id])->fetchColumn()];}}
function client(string $name):array {$email='comunidad-'.bin2hex(random_bytes(6)).'@example.invalid';query('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)',[$name,$email,password_hash('Qa-comunidad-456!',PASSWORD_BCRYPT)]);return ['id'=>(int)$GLOBALS['db']->lastInsertId(),'email'=>$email,'name'=>$name];}
$panel=new ChapitourPanel($db);auth(100,'staff');$panel->state();$community=new ChapitourCommunity($db);check($community->ready(),'Instalación aditiva');
$a=client('Nombre privado QA');$b=client('Otra persona privada');$ui=client('Perfil QA');$other=client('Otro perfil QA');
auth($a['id']);$state=$panel->state();check($state['user']['community']['visible'] && $state['user']['photo_url']===null && $state['user']['community']['public_name']===$a['name'],'Perfil registrado con nombre de cuenta y sin foto');
check($state['user']['community']['public_name']===$state['user']['name'],'Usa el nombre guardado de la cuenta');
$profile=['email'=>$a['email'],'name'=>$a['name'],'city'=>'Bogotá','ranking_visible'=>'1'];
$state=$panel->handle('profile',$profile);check($state['user']['community']['visible'],'Participación pública voluntaria');
$public=$community->leaderboard();foreach($public['entries'] as $entry){check(array_keys($entry)===['name','score'],'Solo nombre y puntaje de cuentas registradas');}
$names=[];for($page=1;$page<=100;$page++){$ranking=$community->leaderboard($page,20);$names=array_merge($names,array_column($ranking['entries'],'name'));if(!$ranking['has_more'])break;}check(in_array($a['name'],$names,true),'Nombre aparece en la lista paginada');
reject(static function()use($panel,$profile){$panel->handle('profile',array_replace($profile,['name'=>'No debe guardarse','ranking_visible'=>'invalid']));},422);
check($panel->state()['user']['name']===$a['name'],'Preferencias inválidas no guardan cambios parciales');
$month=$community->month();$start=strtotime(substr($month,0,7).'-05 12:00:00 UTC');$prizes=query('SELECT COUNT(*) FROM cp_premios')->fetchColumn();
for($i=0;$i<12;$i++){
    $db->exec('SET timestamp='.($start+$i*14400));$panel->handle('visit',[]);$panel->handle('visit',[]);
}
check($community->member($a['id'])['breakdown']['visits']===10,'Visitas válidas limitadas a 10 puntos sin contar recargas');
check((int)query('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=?',[$a['id']])->fetchColumn()===1,'Mantiene un giro por ocho visitas, no por puntaje');
$db->exec('SET timestamp='.($start+12*14400-1));$before=query('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=?',[$a['id']])->fetchColumn();$panel->handle('visit',[]);check(query('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=?',[$a['id']])->fetchColumn()===$before,'Antes de cuatro horas no cuenta una visita nueva');
$state=$panel->state();$photo=$state['challenges'][1];$questions=$state['challenges'][2];
foreach(array_slice($photo['places'],0,3) as $p){$panel->handle('record_photo',['month'=>$month,'business_id'=>$p['id']]);}
foreach($questions['places'] as $q){$input=['month'=>$month,'business_id'=>$photo['places'][0]['id'],'question_id'=>$q['id'],'version'=>'1'];foreach($q['questions'] as $field){$input['answer_'.$field['id']]='Respuesta '.$field['id'];}$panel->handle('answer_questions',$input);$panel->handle('answer_questions',$input);}
$score=$community->member($a['id']);check($score['score']===80 && $score['breakdown']===['visits'=>10,'questions'=>25,'photos'=>45,'sharing'=>0],'Puntaje de actividades limitado y sin duplicados');
check(query('SELECT COUNT(*) FROM cp_premios')->fetchColumn()===$prizes,'El puntaje no emite premios');
$reto=query("SELECT id,criterio_verificacion FROM cp_panel_retos WHERE mes=? AND tipo='compartir'",[$month])->fetch(PDO::FETCH_ASSOC);
query('INSERT INTO cp_panel_progreso(cliente_id,reto_id,cantidad_verificada) VALUES (?,?,20)',[$a['id'],$reto['id']]);
check($community->member($a['id'])['score']===80,'Veinte entregas sin criterio no puntúan');
query('UPDATE cp_panel_retos SET criterio_verificacion=? WHERE id=?',['Validación sintética exclusiva QA',$reto['id']]);
check($community->member($a['id'])['score']===100,'Reto confirmado suma los 20 puntos reservados');
query('UPDATE cp_panel_retos SET criterio_verificacion=? WHERE id=?',[$reto['criterio_verificacion'],$reto['id']]);
auth($b['id']);$panel->handle('profile',['email'=>$b['email'],'name'=>$b['name'],'city'=>'','ranking_visible'=>'1']);
query('INSERT INTO cp_panel_puntos_visitas(cliente_id,mes,cantidad) VALUES (?,?,10)',[$b['id'],$month]);
foreach(['cp_panel_meta_fotos','cp_panel_meta_preguntas'] as $table){$columns=$table==='cp_panel_meta_fotos'?'mes,negocio_id,creado_at':'mes,negocio_id,version,lugar_reportado_id,respuestas,creado_at';query('INSERT INTO '.$table.'(cliente_id,'.$columns.') SELECT ?,'.$columns.' FROM '.$table.' WHERE cliente_id=?',[$b['id'],$a['id']]);}
check($community->member($a['id'])['position']===$community->member($b['id'])['position'],'Los empates comparten posición');
auth($a['id']);$panel->handle('profile',array_replace($profile,['ranking_visible'=>'0']));check(!in_array($a['name'],array_column($community->leaderboard()['entries'],'name'),true),'Ocultarse elimina el nombre público sin perder puntos');check($community->member($a['id'])['score']===80,'Conserva el puntaje privado');
for($i=0;$i<22;$i++){$c=client('Participante privado');$community->savePreferences($c['id'],true);}
$first=$community->leaderboard(1,20);$next=$community->leaderboard(2,20);check(count($first['entries'])===20 && $first['has_more'] && count($next['entries'])>0,'Lista paginada');
$nextMonth=(new DateTimeImmutable($month.' 00:00:00',new DateTimeZone('America/Bogota')))->modify('+1 month')->getTimestamp();
$db->exec('SET timestamp='.($nextMonth-1));check($community->member($a['id'])['score']===80,'Conserva puntos hasta medianoche de Bogotá');$db->exec('SET timestamp='.$nextMonth);check($community->member($a['id'])['score']===0,'Nuevo mes reinicia la calificación');$db->exec('SET timestamp=0');
auth(null);reject(static function()use($panel){$panel->handle('remove_avatar',[]);},403);auth(100,'staff');reject(static function()use($panel){$panel->handle('upload_avatar',[]);},403);
auth($a['id']);$panel->handle('delete_account',['confirm'=>'client-'.$a['id']]);check((int)query('SELECT COUNT(*) FROM cp_panel_comunidad WHERE cliente_id=?',[$a['id']])->fetchColumn()===0,'Eliminar cuenta borra perfil público y fotografía');check((int)query('SELECT COUNT(*) FROM cp_panel_puntos_visitas WHERE cliente_id=?',[$a['id']])->fetchColumn()===0,'Eliminar cuenta borra puntos de visitas');
file_put_contents(dirname($socket).'/community-ui.json',json_encode(['client'=>$ui,'other'=>$other]));
echo "PASS $checks comprobaciones: ranking, nombres, privacidad, puntos, visitas válidas, límites, empates, paginación, reinicio mensual y baja de cuenta.\n";
