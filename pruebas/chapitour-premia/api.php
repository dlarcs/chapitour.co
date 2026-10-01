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
} catch (ChapitourDatabaseConfigurationError $e) {
    http_response_code(503);
    error_log('Chapitour configuration error: '.$e->getMessage());
    echo json_encode(['error'=>'La conexión de Chapitour aún no está configurada en el servidor. Contacta al administrador.', 'code'=>'DATABASE_CONFIGURATION'],JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    // Do not expose connection credentials, SQL or server paths to the browser.
    $driverCode=(int)($e->errorInfo[1]??0);
    $duplicate=$driverCode===1062;
    http_response_code($duplicate?409:503);
    error_log('Chapitour database error: '.(string)$e->getCode().' / '.$driverCode);
    $databaseErrors=[
        1044=>['DATABASE_ACCESS_DENIED','MySQL rechazó el acceso a la base de datos. Revisa los permisos del usuario en Hostinger.'],
        1045=>['DATABASE_LOGIN_FAILED','MySQL rechazó las credenciales de conexión. Revisa el usuario y la contraseña MySQL en Hostinger.'],
        1049=>['DATABASE_NOT_FOUND','La base de datos configurada no existe en este servidor. Revisa su nombre en Hostinger.'],
        1146=>['DATABASE_SCHEMA_MISSING','Faltan tablas necesarias para Chapitour. Revisa la instalación de la base de datos.'],
        1054=>['DATABASE_SCHEMA_MISMATCH','La estructura de la base de datos no coincide con esta versión de Chapitour.'],
        2002=>['DATABASE_UNREACHABLE','No se pudo contactar al servidor MySQL. Revisa el servidor de la conexión.'],
        2003=>['DATABASE_UNREACHABLE','No se pudo contactar al servidor MySQL. Revisa el servidor de la conexión.'],
    ];
    [$errorCode,$message]=$databaseErrors[$driverCode]??['DATABASE_ERROR',$duplicate?'Los datos ya existen. Actualiza la página e intenta de nuevo.':'No se pudo acceder a la base de datos. Revisa la configuración MySQL y los permisos del usuario en el servidor.'];
    echo json_encode(['error'=>$message,'code'=>$errorCode],JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    // Record location for the hosting error log, without request data or credentials.
    error_log('Chapitour error: '.get_class($e).' in '.basename($e->getFile()).':'.$e->getLine());
    echo json_encode(['error'=>'No se pudo completar la solicitud. Intenta de nuevo.'],JSON_UNESCAPED_UNICODE);
}
