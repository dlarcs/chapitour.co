<?php
// Completar db_password con la contraseña MySQL definida en Hostinger.
// Las contraseñas de los paneles son diferentes de la contraseña MySQL.
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
return array (
  'dsn' => 'mysql:host=localhost;dbname=u348170507_chapi_promos;charset=utf8mb4',
  'db_user' => 'u348170507_chapi_app',
  'db_password' => 'PENDIENTE_CONTRASENA_MYSQL',
  'app_key' => 'fe6060c34640089dcf2f008364def953d73026ea622c23b9bf120e7066709a00',
  'base_url' => 'https://chapitour.co',
  'secure_cookies' => true,
);
