<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli'||!preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo QA temporal.'); }
require __DIR__.'/../../../premia/lib/ExpiryReminders.php';
require __DIR__.'/../../../premia/lib/ReminderMailer.php';
$db=new PDO('mysql:unix_socket='.$socket.';charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
$database='chapitour_mail_qa_'.bin2hex(random_bytes(4));
$db->exec('CREATE DATABASE '.$database.' CHARACTER SET utf8mb4');
$db->exec('USE '.$database);
$now=strtotime('2026-10-05 12:00:00 UTC');
$db->exec('SET timestamp='.$now);
$checks=0;$next=0;$sent=[];
function q(string $sql,array $args=[]):PDOStatement { global $db; $s=$db->prepare($sql); $s->execute($args); return $s; }
function check(bool $ok,string $message):void { global $checks; if (!$ok) { throw new RuntimeException($message); } $checks++; }
function award(int $seconds,array $options=[]):int {
    global $next,$now,$db;
    $id=++$next;
    q('INSERT INTO cp_clientes(id,nombre,email,password_hash,activo) VALUES (?,?,?,\'qa\',?)',[$id,$options['name']??'Ana María QA',$options['email']??'qa-'.$id.'@example.invalid',$options['active']??1]);
    if (empty($options['guest'])) { q('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$id,$id]); }
    q('INSERT INTO cp_premios(id,oportunidad_id,visitante_id,campana_id,negocio_id,promocion_id,codigo,solicitud_id,titulo,descripcion,condiciones,creado_at,vence_at,redimido_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
        [$id,$id,$id,1,1,1,'QA-REMINDER-'.$id,'qa-'.$id,'Oferta QA','Café & postre <b>QA</b>','Válido de 14:00 a 18:00',gmdate('Y-m-d H:i:s',$now-48*3600),gmdate('Y-m-d H:i:s',$now+$seconds),empty($options['redeemed'])?null:gmdate('Y-m-d H:i:s',$now)]);
    return $id;
}
function status(int $id) { return q('SELECT estado FROM cp_panel_recordatorios WHERE premio_id=?',[$id])->fetchColumn(); }
function capture(array $prize,string $token):void { global $sent; $sent[]=['prize'=>$prize,'token'=>$token]; }

try {
    // Copy only schema into a disposable database; no customer records are copied.
    foreach (['cp_clientes','cp_cliente_visitantes','cp_premios','cp_negocios','cp_premio_detalles'] as $table) {
        $db->exec('CREATE TABLE '.$table.' LIKE chapitour_panels_qa.'.$table);
    }
    q("INSERT INTO cp_negocios(id,slug,nombre,categoria) VALUES (1,'qa','Negocio actual QA','QA')");
    $job=new ChapitourExpiryReminders($db,'capture');
    check(!$job->ready(),'El esquema comienza sin instalar.');
    $due=award(86400); $early=award(86401); $expired=award(0); $redeemed=award(300,['redeemed'=>true]);
    $guest=award(500,['guest'=>true]); $inactive=award(500,['active'=>0]); $invalid=award(500,['email'=>"bad\r\nBcc: stranger@example.invalid"]);
    q('INSERT INTO cp_premio_detalles(premio_id,negocio) VALUES (?,?)',[$due,'Negocio del premio & QA']);
    $preview=$job->preview();
    check($preview['eligible']===1 && $preview['invalid_email']===1,'Frontera de 24 horas, cuentas activas, invitados, vencidos y redimidos.');
    check(!$job->ready(),'La simulación no instala ni escribe.');
    $job->install(); $job->install();
    $result=$job->run();
    check($result['sent']===1 && $result['skipped']===1,'Envía solo el premio válido.');
    check(status($due)==='sent' && status($invalid)==='skipped','Estados persistidos.');
    check($sent[0]['prize']['cliente_email']==='qa-'.$due.'@example.invalid','Destinatario correcto.');
    check($sent[0]['prize']['negocio_nombre']==='Negocio del premio & QA','Conserva el negocio guardado en el premio.');
    check($job->run()['sent']===0 && count($sent)===1,'Ejecuciones repetidas no duplican el recordatorio.');
    check(!status($guest) && !status($early) && !status($expired) && !status($redeemed) && !status($inactive),'Premios excluidos no generan envíos.');
    q('UPDATE cp_premios SET vence_at=? WHERE id=?',[gmdate('Y-m-d H:i:s',$now+86400),$early]);
    check($job->run()['sent']===1,'La siguiente ejecución alcanza el umbral de 24 horas.');
    q('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$guest,$guest]);
    check($job->run()['sent']===1,'Un premio invitado puede avisar cuando se vincula a una cuenta.');
    $late=award(1);
    check($job->run()['sent']===1 && status($late)==='sent','Recupera una ejecución retrasada mientras el premio siga vigente.');

    $retry=award(1000);
    $failing=new ChapitourExpiryReminders($db,static function(){throw new ChapitourReminderDeliveryError(true);});
    check($failing->run()['retry']===1 && status($retry)==='retry','Error previo a DATA permite reintentar.');
    check($job->run()['sent']===0,'El reintento espera quince minutos.');
    q('UPDATE cp_panel_recordatorios SET proximo_intento_at=UTC_TIMESTAMP() WHERE premio_id=?',[$retry]);
    check($job->run()['sent']===1 && (int)q('SELECT intentos FROM cp_panel_recordatorios WHERE premio_id=?',[$retry])->fetchColumn()===2,'El reintento seguro entrega una sola vez.');
    $exhausted=award(1000);
    for ($i=0;$i<3;$i++) { $failing->run(); q('UPDATE cp_panel_recordatorios SET proximo_intento_at=UTC_TIMESTAMP() WHERE premio_id=?',[$exhausted]); }
    check((int)q('SELECT intentos FROM cp_panel_recordatorios WHERE premio_id=?',[$exhausted])->fetchColumn()===3 && $job->run()['sent']===0,'Tres intentos como máximo.');
    $unknown=award(1000);
    $uncertain=new ChapitourExpiryReminders($db,static function(){throw new ChapitourReminderDeliveryError(false);});
    check($uncertain->run()['uncertain']===1 && status($unknown)==='uncertain','Un resultado SMTP ambiguo no se reenvía.');
    $interrupted=award(1000);
    q("INSERT INTO cp_panel_recordatorios(premio_id,token,estado,intentos) VALUES (?,?,'sending',1)",[$interrupted,bin2hex(random_bytes(16))]);
    check($job->run()['uncertain']===1 && status($interrupted)==='uncertain','Interrupción del proceso requiere revisión, sin duplicar.');
    $sqlFailure=award(1000);
    $unexpected=new ChapitourExpiryReminders($db,static function(){throw new RuntimeException('simulated crash after SMTP');});
    check($unexpected->run()['uncertain']===1 && status($sqlFailure)==='uncertain','Fallo inesperado después de posible entrega tampoco reenvía.');

    $first=award(100); $second=award(200);
    $race=new ChapitourExpiryReminders($db,static function($prize,$token)use($first,$second){capture($prize,$token);if((int)$prize['id']===$first)q('UPDATE cp_premios SET redimido_at=UTC_TIMESTAMP() WHERE id=?',[$second]);});
    $result=$race->run();
    check($result['sent']===1 && $result['skipped']===1 && status($second)==='skipped','Revalida un premio redimido después de seleccionar candidatos.');
    $lock='chapitour-reminder-'.substr(hash('sha256',$database),0,40);
    $other=new PDO('mysql:unix_socket='.$socket.';dbname='.$database,'root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $s=$other->prepare('SELECT GET_LOCK(?,0)'); $s->execute([$lock]);
    check($job->run()['busy']===true,'Solo una ejecución puede enviar por base de datos.');
    $s=$other->prepare('SELECT RELEASE_LOCK(?)'); $s->execute([$lock]); $other=null;
    $last=award(1000,['name'=>'Ana <img src=x onerror=alert(1)>']);
    $job->run(); $prize=end($sent)['prize'];
    $message=ChapitourReminderMessage::build($prize);
    check(strpos($message['html'],'<img src=x')===false && strpos($message['html'],'&lt;img')!==false,'Escapa nombres y contenido HTML.');
    check(strpos($message['text'],'05/10/2026 a las 07:16')!==false,'Vencimiento convertido a Bogotá.');
    check(strpos($message['html'],'https://chapitour.co/#mis-promociones')!==false && strpos($message['text'],$prize['codigo'])!==false,'Enlace y código correctos.');
    check(strpos($message['text'],'últimas 24 horas')!==false && strpos($message['text'],'mañana')===false,'El texto sigue siendo correcto en envíos tardíos.');
    file_put_contents('/private/tmp/chapitour-reminder-preview.html',$message['html']);

    class QaReminderSmtp extends ChapitourReminderSmtp {
        public string $mode='ok'; public bool $open=false; public string $mime=''; public array $recipients=[]; public array $tls=[];
        public function connected(){return $this->open;}
        public function connect($host,$port=null,$timeout=30,$options=[]){$this->tls=$options;return $this->open=$this->mode!=='connect';}
        public function hello($host=''){return true;}
        public function authenticate($username,$password,$authtype=null,$OAuth=null){return true;}
        public function getServerExt($name){return false;}
        public function mail($from){return true;}
        public function recipient($address,$dsn=''){$this->recipients[]=$address;return $this->mode!=='recipient';}
        public function data($msg_data){$this->dataStarted=true;$this->mime=$msg_data;return $this->mode!=='data';}
        public function quit($close_on_error=true){$this->open=false;return true;}
        public function close(){$this->open=false;}
        public function reset(){return true;}
    }
    $config=require __DIR__.'/../../../premia/config/mail.example.php';$config['password']='synthetic-only';
    foreach (['ok','connect','recipient','data'] as $mode) {
        $smtp=new QaReminderSmtp();$smtp->mode=$mode;
        $mailer=new ChapitourReminderMailer($config,static fn()=>$smtp);
        try {$mailer($prize,bin2hex(random_bytes(16)));check($mode==='ok','Fallos SMTP no pueden contarse como entregados.');}
        catch(ChapitourReminderDeliveryError $e){check($mode!=='ok' && $e->safeToRetry===($mode!=='data'),'Clasifica correctamente fallos previos y posteriores a DATA.');}
        if ($mode==='ok') {
            check(strpos($smtp->mime,'admin@chapitour.co')!==false && strpos($smtp->mime,'multipart/alternative')!==false,'Remitente correcto y versiones HTML/texto.');
            check($smtp->recipients===[$prize['cliente_email']],'Un único destinatario, sin copias a otros clientes.');
            check($smtp->tls['ssl']['verify_peer']===true && $smtp->tls['ssl']['verify_peer_name']===true,'Verifica el certificado SMTP.');
            file_put_contents('/private/tmp/chapitour-reminder-preview.eml',$smtp->mime);
        }
    }
    $smtp=new QaReminderSmtp();
    (new ChapitourReminderMailer($config,static fn()=>$smtp))->sendTest();
    check($smtp->recipients===[$config['from_email']],'La prueba se envía únicamente al remitente configurado.');
    check(strpos($smtp->mime,'Subject: Prueba de recordatorios de Chapitour')!==false,'La prueba tiene un asunto inequívoco.');
    check(strpos($smtp->mime,$prize['codigo'])===false,'La prueba no usa códigos ni datos de premios reales.');
    foreach ([['password'=>''],['encryption'=>''],['from_email'=>"admin@chapitour.co\r\nBcc: other@example.invalid"],['host'=>'ssl://smtp.hostinger.com;other.example']] as $invalidConfig) {
        try {new ChapitourReminderMailer(array_replace($config,$invalidConfig));throw new LogicException('Aceptó configuración inválida.');}
        catch(RuntimeException $e){check($e->getMessage()==='MAIL_CONFIGURATION','No expone credenciales en errores.');}
    }
    echo "PASS $checks comprobaciones: umbral 24h, exclusiones, destinatarios, duplicados, reintentos, concurrencia, interrupciones, HTML, Bogotá y SMTP simulado. Sin enviar correos reales.\n";
} finally {
    if ($db->inTransaction()) { $db->rollBack(); }
    $db->exec('DROP DATABASE '.$database);
}
