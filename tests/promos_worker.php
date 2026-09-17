<?php
if(PHP_SAPI!=='cli') { http_response_code(404); exit; }
define('CHAPI_PROMOS',true); require dirname(__DIR__).'/promos/src/bootstrap.php';
$schema=$argv[1]??''; if(!preg_match('/^chapitour_test_[a-f0-9]{10}$/D',$schema))exit(1);
$config=chapi_config();$db=new PDO('mysql:host=127.0.0.1;dbname='.$schema.';charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);$db->exec("SET time_zone='+00:00'");
try { if($argv[2]==='spin') { $m=new ChapiPromocionModel($db,$config);$r=$m->spin((int)$argv[3],$argv[4]);echo json_encode(['codigo'=>$r['codigo']]); } else { $m=new ChapiPanelModel($db,$config);$m->redeem($m->user((int)$argv[3]),$argv[4]);echo json_encode(['redimido'=>true]); } } catch(ChapiError $e) {echo json_encode(['error'=>$e->errorCode]);}
