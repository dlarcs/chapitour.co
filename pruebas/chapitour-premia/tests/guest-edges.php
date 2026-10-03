<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if(PHP_SAPI!=='cli'||!preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket))throw new RuntimeException('Solo QA temporal.');
require __DIR__.'/../../../premia/lib/Panel.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);$db->exec("SET time_zone='+00:00'");
$rewards=new ChapitourRewards($db);$_COOKIE['CHAPITOUR_WELCOME']=$argv[3]??bin2hex(random_bytes(32));
if(($argv[2]??'')==='spin'){
 try{echo $rewards->spinGuest('guest-welcome');}catch(PanelError $e){echo 'ERROR-'.$e->getCode();}exit;
}
function check($ok,$msg){if(!$ok)throw new RuntimeException($msg);}
function parallel(array $cookies):array{
 global $socket;$jobs=[];
 foreach($cookies as $cookie){$pipes=[];$proc=proc_open([PHP_BINARY,__FILE__,$socket,'spin',$cookie],[0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']],$pipes);fclose($pipes[0]);$jobs[]=[$proc,$pipes];}
 $out=[];foreach($jobs as [$proc,$pipes]){$out[]=stream_get_contents($pipes[1]);$err=stream_get_contents($pipes[2]);fclose($pipes[1]);fclose($pipes[2]);check(proc_close($proc)===0,$err);}return $out;
}
$before=(int)$db->query('SELECT COUNT(*) FROM cp_premios')->fetchColumn();
$codes=parallel(array_fill(0,6,bin2hex(random_bytes(32))));
check(count(array_unique($codes))===1 && strncmp($codes[0],'CHAPI-',6)===0,'Concurrencia debe devolver un único código');
check((int)$db->query('SELECT COUNT(*) FROM cp_premios')->fetchColumn()===$before+1,'Solo un premio en solicitudes paralelas');
$offers=$db->query('SELECT id,activa,cupo_total FROM cp_promociones')->fetchAll(PDO::FETCH_ASSOC);
$eligible=$rewards->offers();check((bool)$eligible,'Se requiere una oferta QA');$id=$eligible[0]['id'];
try{
 $db->exec('UPDATE cp_promociones SET activa=0');
 try{$rewards->spinGuest('guest-welcome');throw new RuntimeException('No debería premiar sin ofertas');}catch(PanelError $e){check($e->getCode()===422,'Sin ofertas se conserva giro');}
 check($rewards->guestVisitorId()===null,'Sin oferta no persiste visitante parcial');
 $s=$db->prepare('UPDATE cp_promociones SET activa=1,cupo_total=entregados+1 WHERE id=?');$s->execute([$id]);
 $codes=parallel([bin2hex(random_bytes(32)),bin2hex(random_bytes(32))]);
 check(count(array_filter($codes,static fn($v)=>strncmp($v,'CHAPI-',6)===0))===1,'Último cupo solo admite un ganador');
 check(in_array('ERROR-422',$codes,true),'El otro invitado conserva su oportunidad');
}finally{
 $s=$db->prepare('UPDATE cp_promociones SET activa=?,cupo_total=? WHERE id=?');foreach($offers as $o)$s->execute([$o['activa'],$o['cupo_total'],$o['id']]);
}
$code=$rewards->spinGuest('guest-welcome');
$s=$db->prepare('UPDATE cp_premios SET vence_at=DATE_SUB(UTC_TIMESTAMP(),INTERVAL 1 SECOND) WHERE codigo=?');$s->execute([$code]);
$_SESSION=['csrf'=>'qa'];$panel=new ChapitourPanel($db);
check($panel->state()['codes'][0]['status']==='Vencido','Caduca sin reactivación');
try{$panel->handle('whatsapp',['code'=>$code]);throw new RuntimeException('No debería abrir vencido');}catch(PanelError $e){check($e->getCode()===409,'Vencido se rechaza');}
check($rewards->spinGuest('guest-welcome')===$code,'Reintento vencido devuelve el mismo código, sin otro premio');
echo "PASS concurrencia, último cupo, sin ofertas, reversión, vencimiento y reintento sin duplicados.\n";
