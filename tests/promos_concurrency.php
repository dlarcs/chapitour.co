<?php
if(PHP_SAPI!=='cli') { http_response_code(404); exit; }
define('CHAPI_PROMOS',true);require dirname(__DIR__).'/promos/src/bootstrap.php';
$f=json_decode(file_get_contents('/private/tmp/chapi-test-environment.json'),true);if(!preg_match('/^chapitour_test_[a-f0-9]{10}$/D',$f['database']??''))exit(1);
$db=new PDO($f['dsn'],'root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);$config=chapi_config();$model=new ChapiPromocionModel($db,$config);
function race(string $action,int $id,string $key):array {global $f; $jobs=[];for($i=0;$i<2;$i++){ $p=proc_open([PHP_BINARY,__DIR__.'/promos_worker.php',$f['database'],$action,(string)$id,$key],[1=>['pipe','w'],2=>['pipe','w']],$pipes);$jobs[]=[$p,$pipes];} $out=[];foreach($jobs as [$p,$pipes]){$out[]=json_decode(stream_get_contents($pipes[1]),true);$errors=stream_get_contents($pipes[2]);fclose($pipes[1]);fclose($pipes[2]);if(proc_close($p)!==0)throw new RuntimeException($errors);}return $out;}
$salt=bin2hex(random_bytes(5));$v=$model->visitor(hash('sha256','race'.$salt),hash('sha256','race-ip'.$salt));$model->initialize((int)$v['id'],hash('sha256','race-ip'.$salt));
$out=race('spin',(int)$v['id'],substr(bin2hex(random_bytes(18)),0,36));
if(empty($out[0]['codigo']) || $out[0]['codigo']!==($out[1]['codigo']??null))throw new RuntimeException('Doble solicitud no idempotente.');echo "OK dos giros simultáneos devuelven un único premio\n";
$out=race('redeem',1,$out[0]['codigo']);$success=count(array_filter($out,fn($r)=>!empty($r['redimido'])));$conflict=count(array_filter($out,fn($r)=>($r['error']??'')==='ALREADY_REDEEMED'));
if($success!==1 || $conflict!==1 || $model->state((int)$v['id'])['oportunidades']!==1)throw new RuntimeException('Doble redención no protegida.');echo "OK dos redenciones simultáneas conceden exactamente un giro\n";
