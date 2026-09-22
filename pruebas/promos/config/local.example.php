<?php
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
return [
    'dsn' => 'mysql:host=127.0.0.1;dbname=chapitour_promos;charset=utf8mb4',
    'db_user' => 'chapitour_app',
    'db_password' => 'CAMBIAR',
    'app_key' => 'GENERAR_CON_EL_INSTALADOR',
    'base_url' => 'http://localhost/ChapiTour',
    'secure_cookies' => false, // true en HTTPS / producción
];
