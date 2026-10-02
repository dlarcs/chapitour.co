<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }
function chapitourSession(): void {
    date_default_timezone_set('America/Bogota');
    ini_set('session.use_strict_mode','1');
    ini_set('session.use_only_cookies','1');
    session_name('CHAPITOUR_PREMIA_DB');
    session_set_cookie_params(['lifetime'=>0,'path'=>rtrim(dirname($_SERVER['SCRIPT_NAME']),'/').'/',
        'httponly'=>true,'samesite'=>'Strict','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off']);
    session_start();
    $_SESSION['csrf']=$_SESSION['csrf']??bin2hex(random_bytes(32));
}
