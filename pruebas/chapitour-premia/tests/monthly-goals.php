<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo QA temporal.'); }
ini_set('session.save_path',dirname($socket));session_start();
require __DIR__.'/../lib/Panel.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
$db->exec("SET time_zone='+00:00'");
$checks=0;
function check($ok,string $message):void {global $checks;if(!$ok)throw new RuntimeException($message);$checks++;}
function query(string $sql,array $args=[]):PDOStatement {global $db;$s=$db->prepare($sql);$s->execute($args);return $s;}
function reject(callable $fn,int $code):void {try{$fn();throw new RuntimeException('Debió rechazarse');}catch(PanelError $e){check($e->getCode()===$code,$e->getMessage());}}
function auth(?int $id,string $kind='client'):void {$_SESSION=['csrf'=>'qa'];if($id!==null){$table=$kind==='client'?'cp_clientes':'cp_usuarios';$_SESSION['auth']=['kind'=>$kind,'id'=>$id,'version'=>(int)query('SELECT version_sesion FROM '.$table.' WHERE id=?',[$id])->fetchColumn()];}}
function goal(array $s,string $type):array {foreach($s['challenges'] as $c){if($c['type']===$type)return $c;}throw new RuntimeException('Meta ausente');}
function client(string $name):array {$email='metas-'.$name.'-'.bin2hex(random_bytes(6)).'@example.invalid';query('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)',['QA metas '.$name,$email,password_hash('Qa-metas-456!',PASSWORD_BCRYPT)]);return [(int)$GLOBALS['db']->lastInsertId(),$email];}
$panel=new ChapitourPanel($db);auth(100,'staff');$panel->state();
check((new ChapitourChallenges($db))->ready(),'Instalación aditiva desde sesión administradora');
$before=[query('SELECT COUNT(*) FROM cp_premios')->fetchColumn(),query('SELECT COUNT(*) FROM cp_panel_giros')->fetchColumn(),query('SELECT COUNT(*) FROM cp_panel_progreso')->fetchColumn()];
[$one]=$a=client('uno');[$two]=client('dos');[$ui,$uiEmail]=client('interfaz');
auth($one);$state=$panel->state();$photo=goal($state,'fotografia');$questions=goal($state,'preguntas');$month=$photo['month'];
check(array_column($state['challenges'],'type')===['compartir','fotografia','preguntas'],'Tres metas independientes y ordenadas');
check($photo['target']===3 && $photo['progress']===0 && count($photo['places'])===6,'3/3 entre seis negocios disponibles');
check($questions['target']===5 && $questions['progress']===0,'Cinco preguntas abiertas');
check(array_column($questions['places'],'name')===['Pregunta 1','Pregunta 2','Pregunta 3','Pregunta 4','Pregunta 5'],'Preguntas sin nombres de negocios');
check(strpos(json_encode($questions['places']),'rules')===false,'No publica soluciones');
$ids=array_column($photo['places'],'id');$input=['month'=>$month,'business_id'=>$ids[0]];
foreach([[null,'client'],[100,'staff'],[101,'staff']] as [$id,$kind]) {auth($id,$kind);reject(static function()use($panel,$input){$panel->handle('record_photo',$input);},403);}
auth($one);
foreach([['business_id'=>'999999'],['business_id'=>[]],['month'=>'2000-01-01']] as $bad) {reject(static function()use($panel,$input,$bad){$panel->handle('record_photo',array_replace($input,$bad));},isset($bad['month'])?409:422);}
$state=$panel->handle('record_photo',$input+['cliente_id'=>$two,'progress'=>99]);check(goal($state,'fotografia')['progress']===1,'Suma solo al usuario autenticado');
$panel->handle('record_photo',$input);check(goal((new ChapitourPanel($db))->state(),'fotografia')['progress']===1,'Reintento y nueva instancia conservan un punto');
auth($two);check(goal($panel->state(),'fotografia')['progress']===0,'Aislamiento entre cuentas');auth($one);
foreach(array_slice($ids,1) as $id){$panel->handle('record_photo',['month'=>$month,'business_id'=>$id]);}
$state=$panel->state();check(goal($state,'fotografia')['progress']===3,'Límite de tres puntos, incluso enviando los seis negocios');
check(goal($state,'preguntas')['progress']===0 && goal($state,'compartir')['progress']===null,'Fotografías no completan preguntas ni compartir');
$answer=['month'=>$month,'business_id'=>$ids[0],'question_id'=>'r3','version'=>'1','answer_respuesta'=>'Cóctel indicado por el cliente'];
$state=$panel->handle('answer_questions',$answer);check(goal($state,'preguntas')['progress']===1,'Guarda respuesta abierta sin inventar una carta');
check(query('SELECT respuestas FROM cp_panel_meta_preguntas WHERE cliente_id=?',[$one])->fetchColumn()===json_encode(['respuesta'=>'Cóctel indicado por el cliente'],JSON_UNESCAPED_UNICODE),'Conserva el texto de la respuesta');
$panel->handle('answer_questions',$answer);check(goal($panel->state(),'preguntas')['progress']===1,'La pregunta cuenta una sola vez');
foreach([['question_id'=>'x'],['version'=>'9'],['answer_respuesta'=>''],['answer_respuesta'=>[]]] as $bad){reject(static function()use($panel,$answer,$bad){$panel->handle('answer_questions',array_replace($answer,$bad));},isset($bad['question_id'])||isset($bad['version'])?409:422);}
$flags=array_replace($answer,['question_id'=>'r4','answer_pais_1'=>'Perú','answer_pais_2'=>'PERU','answer_pais_3'=>'Chile']);
reject(static function()use($panel,$flags){$panel->handle('answer_questions',$flags);},422);
$flags['answer_pais_2']='Colombia';$panel->handle('answer_questions',$flags);
foreach(['r1','r2','r5'] as $id){$panel->handle('answer_questions',array_replace($answer,['question_id'=>$id,'answer_respuesta'=>'Respuesta del cliente, pendiente de valoración.']));}
$state=$panel->state();check(goal($state,'preguntas')['progress']===5 && goal($state,'fotografia')['progress']===3,'Metas separadas completas');
$next=(new DateTimeImmutable($month.' 00:00:00',new DateTimeZone('America/Bogota')))->modify('+1 month')->getTimestamp();
$db->exec('SET timestamp='.($next-1));check(goal($panel->state(),'fotografia')['progress']===3,'Antes de medianoche en Bogotá conserva progreso');
$db->exec('SET timestamp='.$next);$state=$panel->state();check(goal($state,'fotografia')['progress']===0 && goal($state,'preguntas')['progress']===0,'El nuevo mes comienza sin progreso anterior');
reject(static function()use($panel,$input){$panel->handle('record_photo',$input);},409);
$db->exec('SET timestamp=0');
check($before===[query('SELECT COUNT(*) FROM cp_premios')->fetchColumn(),query('SELECT COUNT(*) FROM cp_panel_giros')->fetchColumn(),query('SELECT COUNT(*) FROM cp_panel_progreso')->fetchColumn()],'No genera premios, giros ni altera progreso anterior');
auth($one);$panel->handle('delete_account',['confirm'=>'client-'.$one]);
check((int)query('SELECT COUNT(*) FROM cp_panel_meta_preguntas WHERE cliente_id=?',[$one])->fetchColumn()===0 && (int)query('SELECT COUNT(*) FROM cp_panel_meta_fotos WHERE cliente_id=?',[$one])->fetchColumn()===0,'Baja elimina respuestas y lugares registrados');
file_put_contents(dirname($socket).'/monthly-goals-ui.json',json_encode(['email'=>$uiEmail,'id'=>$ui]));
echo "PASS $checks comprobaciones: tres metas, 3/3, deduplicación, permisos, respuestas abiertas, persistencia, cambio mensual y conservación de premios.\n";
