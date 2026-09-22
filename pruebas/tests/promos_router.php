<?php
// Local-only HTTP test server. Never deploy this file.
if (PHP_SAPI!=='cli-server') { http_response_code(404); exit; }
$fixture=json_decode(file_get_contents('/private/tmp/chapi-test-environment.json'),true);
if (!preg_match('/^chapitour_test_[a-f0-9]{10}$/D',$fixture['database']??'')) { http_response_code(503); exit; }
putenv('CHAPI_DB_DSN='.$fixture['dsn']); putenv('CHAPI_DB_USER=root'); putenv('CHAPI_DB_PASSWORD='); putenv('CHAPI_BASE_URL=http://127.0.0.1:8774');
$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));
if (preg_match('~/(?:\.|tests(?:/|$)|promos/(?:config|src|storage|database|bin)(?:/|$))~',$path)) { http_response_code(404); exit; }
return false;
