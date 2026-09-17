<?php
declare(strict_types=1);
define('CHAPI_PROMOS',true);
require dirname(__DIR__).'/src/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
$requestId=bin2hex(random_bytes(8));
try {
    $config=chapi_config();
    ChapiSecurity::start($config);
    if (($_SERVER['REQUEST_METHOD']??'')==='GET') {
        if (($_GET['action']??'')!=='csrf') throw new ChapiError('Ruta no encontrada.',404);
        $data=['csrf'=>$_SESSION['csrf']];
    } else {
        $input=ChapiSecurity::input($config);
        $controller=new ChapiController(chapi_db(),$config);
        $data=$controller->dispatch($input);
    }
    echo json_encode(['success'=>true,'data'=>$data,'request_id'=>$requestId],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
} catch (ChapiError $e) {
    http_response_code($e->status);
    if ($e->status===429) header('Retry-After: 60');
    echo json_encode(['success'=>false,'code'=>$e->errorCode,'message'=>$e->getMessage(),'request_id'=>$requestId],JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('ChapiPromos '.$requestId.' '.get_class($e).': '.$e->getMessage());
    http_response_code(503);
    echo json_encode(['success'=>false,'code'=>'SERVICE_UNAVAILABLE','message'=>'Promociones no está disponible en este momento. Intenta nuevamente.','request_id'=>$requestId]);
}
