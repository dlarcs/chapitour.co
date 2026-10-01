<?php
declare(strict_types=1);
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('X-Robots-Tag: noindex, nofollow');
date_default_timezone_set('America/Bogota');
ini_set('session.use_strict_mode','1');
ini_set('session.use_only_cookies','1');
session_name('CHAPITOUR_PREMIA_DB');
session_set_cookie_params(['lifetime'=>0,'path'=>rtrim(dirname($_SERVER['SCRIPT_NAME']),'/').'/',
    'httponly'=>true,'samesite'=>'Strict','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off']);
session_start();
$_SESSION['csrf']=$_SESSION['csrf']??bin2hex(random_bytes(32));
require __DIR__.'/lib/Panel.php';
try {
    $method=$_SERVER['REQUEST_METHOD']; $input=[]; $action='state';
    if ($method==='POST') {
        if (!hash_equals($_SESSION['csrf'],$_SERVER['HTTP_X_CSRF_TOKEN']??'')) { throw new PanelError('Actualiza la página e intenta de nuevo.',403); }
        if ((int)($_SERVER['CONTENT_LENGTH']??0)>20000) { throw new PanelError('Solicitud demasiado grande.',413); }
        $raw=file_get_contents('php://input',false,null,0,20001);
        if (strlen($raw)>20000) { throw new PanelError('Solicitud demasiado grande.',413); }
        $input=json_decode($raw,true);
        if (!is_array($input) || !is_string($input['action']??null)) { throw new PanelError('Solicitud no válida.',422); }
        $action=$input['action'];
    } elseif ($method!=='GET' || ($_GET['action']??'state')!=='state') {
        throw new PanelError('Método no permitido.',405);
    }
    $pdo=require __DIR__.'/config/database.php';
    $panel=new ChapitourPanel($pdo);
    echo json_encode($panel->handle($action,$input),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
} catch (PanelError $e) {
    http_response_code($e->getCode()?:422);
    echo json_encode(['error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    // Do not expose connection credentials, SQL or server paths to the browser.
    $duplicate=($e->errorInfo[1]??null)===1062;
    http_response_code($duplicate?409:503);
    error_log('Chapitour database error: '.(string)$e->getCode());
    echo json_encode(['error'=>$duplicate?'Los datos ya existen. Actualiza la página e intenta de nuevo.':'No se pudo acceder a la base de datos. Revisa la configuración MySQL y los permisos del usuario en el servidor.'],JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500); error_log('Chapitour error: '.get_class($e));
    echo json_encode(['error'=>'No se pudo completar la solicitud. Intenta de nuevo.'],JSON_UNESCAPED_UNICODE);
}
