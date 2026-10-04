<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo QA temporal.'); }
ini_set('session.save_path',dirname($socket));session_start();
require __DIR__.'/../../../premia/lib/Panel.php';require __DIR__.'/../../../premia/vendor/autoload.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
$db->exec("SET time_zone='+00:00'");
$private=openssl_pkey_new(['private_key_bits'=>2048,'private_key_type'=>OPENSSL_KEYTYPE_RSA]);
$rsa=openssl_pkey_get_details($private)['rsa'];
$b64=static function($s){return rtrim(strtr(base64_encode($s),'+/','-_'),'=');};
$keys=['keys'=>[['kty'=>'RSA','kid'=>'qa','alg'=>'RS256','use'=>'sig','n'=>$b64($rsa['n']),'e'=>$b64($rsa['e'])]]];
$clientId='123-qa.apps.googleusercontent.com';
$google=new ChapitourGoogleAuth($clientId,static function()use($keys){return $keys;});
$panel=new ChapitourPanel($db,$google);$_SERVER['REMOTE_ADDR']='qa-google-'.bin2hex(random_bytes(5));$_SESSION['csrf']='qa';
$checks=0;
function check($ok,string $message):void {global $checks;if(!$ok)throw new RuntimeException($message);$checks++;}
function query(string $sql,array $args=[]):PDOStatement {global $db;$s=$db->prepare($sql);$s->execute($args);return $s;}
function rejected(callable $fn,int $status):void {try{$fn();throw new RuntimeException('La acción debió rechazarse');}catch(PanelError $e){check($e->getCode()===$status,$e->getMessage());}}
function token(array $changes=[]):string {
    global $google,$private,$clientId,$subject,$email;
    $settings=$google->settings();
    return \Firebase\JWT\JWT::encode(array_replace(['iss'=>'https://accounts.google.com','aud'=>$clientId,'exp'=>time()+600,'iat'=>time()-5,'sub'=>$subject,'email'=>$email,'email_verified'=>true,'name'=>'Cliente Google QA','nonce'=>$settings['nonce']],$changes),$private,'RS256','qa');
}
$panel->handle('login',['email'=>'laurazoro@gmail.com','password'=>'Qa-admin-new-456!']);$panel->handle('logout',[]);
$_COOKIE['CHAPITOUR_WELCOME']=bin2hex(random_bytes(32));$guestCookie=$_COOKIE['CHAPITOUR_WELCOME'];
$beforeVisitors=query('SELECT COUNT(*) FROM cp_visitantes')->fetchColumn();
$state=$panel->state();
check($state['campaign']['can_spin'] && $state['campaign']['ticket_id']==='guest-welcome','Invitado puede girar sin registro');
check(query('SELECT COUNT(*) FROM cp_visitantes')->fetchColumn()===$beforeVisitors,'Leer no crea visitantes');
$beforePrizes=(int)query('SELECT COUNT(*) FROM cp_premios')->fetchColumn();$beforeStock=(int)query('SELECT SUM(entregados) FROM cp_promociones')->fetchColumn();
$won=$panel->handle('spin',['ticket_id'=>'guest-welcome']);$guestCode=$won['won_code'];$guestPrize=$won['codes'][0];
check(count($won['codes'])===1 && !$won['campaign']['can_spin'],'Un premio de bienvenida');
check($guestPrize['expires_at']-$guestPrize['created_at']===72*3600,'Vigencia de 72 horas');
check(preg_match('/^CHAPI-[A-Z]{3}-[1-9][0-9]{2,}$/',$guestCode)===1,'Código con mes y cifras');
check($panel->handle('spin',['ticket_id'=>'guest-welcome'])['won_code']===$guestCode,'Reintento invitado devuelve premio original');
check((int)query('SELECT COUNT(*) FROM cp_premios')->fetchColumn()===$beforePrizes+1,'No duplica premio');
check((int)query('SELECT SUM(entregados) FROM cp_promociones')->fetchColumn()===$beforeStock+1,'No consume doble cupo');
$wa=$panel->handle('whatsapp',['code'=>$guestCode]);
check(strpos(rawurldecode($wa['whatsapp_url']),$guestCode)!==false && $wa['codes'][0]['status']==='Activo','WhatsApp prepara mensaje sin redimir');
foreach(['visit','profile','save_promotion','redeem'] as $action)rejected(static function()use($panel,$action,$guestCode){$panel->handle($action,['code'=>$guestCode,'confirm'=>$guestCode]);},403);
$_COOKIE['CHAPITOUR_WELCOME']=bin2hex(random_bytes(32));
check(!$panel->state()['codes'],'Otro navegador no ve el código');
rejected(static function()use($panel,$guestCode){$panel->handle('whatsapp',['code'=>$guestCode]);},404);
rejected(static function()use($panel){$panel->handle('spin',['ticket_id'=>'123']);},422);
$_COOKIE['CHAPITOUR_WELCOME']=$guestCookie;
$panel->handle('logout',[]);
check($panel->state()['codes'][0]['code']===$guestCode && !$panel->state()['campaign']['can_spin'],'La cookie conserva el premio entre sesiones');
$subject='guest-google-'.bin2hex(random_bytes(10));$email=$subject.'@example.invalid';
$registered=$panel->handle('google_login',['credential'=>token()]);$guestClientId=(int)substr($registered['user']['id'],7);
check(count($registered['codes'])===1 && $registered['codes'][0]['code']===$guestCode,'Google vincula premio de invitado');
check(!$registered['campaign']['can_spin'],'Registrarse no duplica bienvenida');
$community=new ChapitourCommunity($db);$initialName=$registered['user']['community']['public_name'];
check($registered['user']['community']['visible'] && $initialName==='Cliente Google QA','Registro entra automáticamente con el nombre de Google');
$allNames=[];for($page=1;$page<=100;$page++){$ranking=$community->leaderboard($page,20);$allNames=array_merge($allNames,array_column($ranking['entries'],'name'));if(!$ranking['has_more'])break;}
check(in_array($initialName,$allNames,true),'Cuenta recién registrada aparece en la lista pública');
$profile=['name'=>'María Fernanda Pérez QA','email'=>$registered['user']['email'],'city'=>'','ranking_visible'=>'1'];
$updated=$panel->handle('profile',$profile);
check($updated['user']['community']['public_name']===$profile['name'],'Actualizar el nombre del perfil actualiza el ranking');
$panel->handle('profile',array_replace($profile,['ranking_visible'=>'0']));
$db->beginTransaction();$community->registerMember($guestClientId);$db->commit();
check(!$community->member($guestClientId)['visible'] && $community->member($guestClientId)['public_name']===$profile['name'],'Inicialización idempotente respeta nombre y preferencia de ocultarse');

check((int)query('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=? AND premio_id IS NOT NULL',[$guestClientId])->fetchColumn()===1,'Bienvenida de cuenta consumida por premio previo');
$panel->handle('logout',[]);
check(!$panel->state()['codes'] && !$panel->state()['campaign']['can_spin'],'Después de vincular exige cuenta');
rejected(static function()use($panel){$panel->handle('spin',['ticket_id'=>'guest-welcome']);},403);
$registered=$panel->handle('google_login',['credential'=>token()]);
check(count($registered['codes'])===1 && !$registered['campaign']['can_spin'],'Reingresar conserva un premio');
check(!$registered['user']['community']['visible'] && $registered['user']['community']['public_name']===$profile['name'],'Reingresar respeta privacidad y nombre editado');
$panel->handle('logout',[]);
$panel->handle('login',['email'=>'laurazoro@gmail.com','password'=>'Qa-admin-new-456!']);
$redeemed=$panel->handle('redeem',['code'=>$guestCode,'confirm'=>$guestCode]);
check(query('SELECT redimido_at FROM cp_premios WHERE codigo=?',[$guestCode])->fetchColumn()!==null,'Administrador redime premio de invitado');
rejected(static function()use($panel,$guestCode){$panel->handle('redeem',['code'=>$guestCode,'confirm'=>$guestCode]);},409);
$panel->handle('logout',[]);
// A new browser registering before spinning gets one account welcome.
$_COOKIE['CHAPITOUR_WELCOME']=bin2hex(random_bytes(32));
$subject='qa-'.bin2hex(random_bytes(10));$email=$subject.'@example.invalid';
foreach ([['aud'=>'other.apps.googleusercontent.com'],['iss'=>'https://attacker.invalid'],['exp'=>time()-1],['email_verified'=>false],['nonce'=>'wrong'],['sub'=>'']] as $invalid) {
    rejected(static function()use($google,$invalid){$google->verify(token($invalid));},401);
}
$good=token();$parts=explode('.',$good);$parts[2]=str_repeat('a',strlen($parts[2]));
rejected(static function()use($google,$parts){$google->verify(implode('.',$parts));},401);
$state=$panel->handle('google_login',['credential'=>$good]);$id=(int)substr($state['user']['id'],7);$welcome=$state['campaign']['ticket_id'];
check($state['user']['role']==='client' && $state['user']['google_linked'],'Google solo crea clientes vinculados');
check($state['campaign']['ticket_kind']==='welcome' && $welcome!==null,'Giro de bienvenida');
check(!array_intersect(['new_visit_after','visits_per_reward','monthly_visit_reset'],array_keys($state['campaign'])),'Regla privada ausente del estado público');
$panel->handle('visit',[]);$panel->handle('visit',[]);
check((int)query('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=?',[$id])->fetchColumn()===0,'El registro no cuenta para el siguiente ciclo');
check((int)query('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=?',[$id])->fetchColumn()===1,'Una sola bienvenida');
rejected(static function()use($panel,$good){$panel->handle('google_login',['credential'=>$good]);},401);
$panel->handle('logout',[]);$state=$panel->handle('google_login',['credential'=>token()]);
check($state['user']['id']==='client-'.$id && $state['campaign']['ticket_id']===$welcome,'Volver con Google conserva la cuenta y el giro');
$won=$panel->handle('spin',['ticket_id'=>$welcome]);$code=$won['won_code'];
check($panel->handle('spin',['ticket_id'=>$welcome])['won_code']===$code,'Reintento de bienvenida idempotente');
$created=(int)strtotime(query('SELECT ultima_visita_at FROM cp_panel_visitas WHERE cliente_id=?',[$id])->fetchColumn().' UTC');
// Keep this fixture away from a month boundary when exercising eight return visits.
$start=strtotime('2026-10-05 12:00:00 UTC');query("UPDATE cp_panel_visitas SET ultima_visita_at=FROM_UNIXTIME(?),mes='2026-10' WHERE cliente_id=?",[$start,$id]);
$db->exec('SET timestamp='.($start+14399));$panel->handle('visit',[]);
check((int)query('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=?',[$id])->fetchColumn()===0,'Antes de cuatro horas no cuenta');
for($i=1;$i<=7;$i++){$db->exec('SET timestamp='.($start+$i*14400));$panel->handle('visit',[]);$panel->handle('visit',[]);}
check((int)query('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=?',[$id])->fetchColumn()===1,'Siete visitas no dan otro giro');
$db->exec('SET timestamp='.($start+8*14400));$state=$panel->handle('visit',[]);
check($state['campaign']['ticket_kind']==='visits' && $state['campaign']['ticket_id']!==$welcome,'La octava visita posterior da un giro nuevo');
check((int)query('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=?',[$id])->fetchColumn()===0,'El ciclo vuelve a cero');
$visitTicket=$state['campaign']['ticket_id'];
query("UPDATE cp_panel_visitas SET visitas_ciclo=5,ultima_visita_at='2026-11-01 04:59:00' WHERE cliente_id=?",[$id]);
$db->exec('SET timestamp='.strtotime('2026-11-01 05:01:00 UTC'));$state=$panel->handle('visit',[]);
check((int)query('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=?',[$id])->fetchColumn()===0,'El cambio de mes reinicia sin saltarse las cuatro horas');
check($state['campaign']['ticket_id']===$visitTicket,'El giro ganado se conserva entre meses');$db->exec('SET timestamp=0');
rejected(static function()use($panel){$panel->handle('profile',['name'=>'QA','email'=>'another@example.invalid','city'=>'']);},422);
$panel->handle('logout',[]);
rejected(static function()use($panel){$panel->handle('google_login',['credential'=>token(['email'=>'laurazoro@gmail.com','sub'=>'staff-collision-qa'])]);},409);
rejected(static function()use($panel){$panel->handle('register',['email'=>'manual@example.invalid','password'=>'not-allowed']);},422);
// An existing email is not enough to link/take over a password account.
$legacyEmail='legacy-'.bin2hex(random_bytes(6)).'@example.invalid';
query('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)',['QA existente',$legacyEmail,password_hash('Qa-existing-456!',PASSWORD_BCRYPT)]);
$legacyId=(int)$db->lastInsertId();
rejected(static function()use($panel,$legacyEmail){$panel->handle('google_login',['credential'=>token(['email'=>$legacyEmail,'sub'=>'legacy-'.$legacyEmail])]);},409);
$panel->handle('login',['email'=>$legacyEmail,'password'=>'Qa-existing-456!']);
$linked=$panel->handle('google_login',['credential'=>token(['email'=>$legacyEmail,'sub'=>'legacy-'.$legacyEmail])]);
check($linked['user']['id']==='client-'.$legacyId && $linked['user']['google_linked'],'Vinculación requiere la sesión existente');
check((int)query('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=?',[$legacyId])->fetchColumn()===0,'No concede bienvenida retroactiva al vincular');
$panel->handle('delete_account',['confirm'=>'client-'.$legacyId]);
rejected(static function()use($panel,$legacyEmail){$panel->handle('google_login',['credential'=>token(['email'=>$legacyEmail,'sub'=>'legacy-'.$legacyEmail])]);},403);
echo "PASS $checks comprobaciones de producción, invitados, vinculación, registro automático en ranking y regla privada: firma y claims Google, nonce, permisos, vinculación, bienvenida única, cuatro horas, ocho regresos, reinicio mensual y reintentos.\n";
