<?php
// Sirve solo la copia de pruebas contra una base temporal independiente.
if (PHP_SAPI!=='cli-server') { http_response_code(404); exit; }
$fixture=json_decode(file_get_contents('/private/tmp/chapi-customers-environment.json'),true);
if (!preg_match('/^chapitour_customer_test_[a-f0-9]{10}$/D',$fixture['database']??'')) { http_response_code(503); exit; }
putenv('CHAPI_DB_DSN='.$fixture['dsn']); putenv('CHAPI_DB_USER=root'); putenv('CHAPI_DB_PASSWORD=');
putenv('CHAPI_BASE_URL=http://127.0.0.1:8785/pruebas');
$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));
if ($path==='/') { header('Location: /pruebas/'); exit; }
if (!str_starts_with($path,'/pruebas/') || preg_match('~/(?:\.|tests(?:/|$)|entrega-hostinger(?:/|$)|promos/(?:config|src|storage|database|bin)(?:/|$))~',$path)) { http_response_code(404); exit; }
return false;
