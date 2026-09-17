<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
define('CHAPI_PROMOS',true); require dirname(__DIR__).'/src/bootstrap.php';
$db=chapi_db();
$rates=$db->exec('DELETE FROM cp_limites WHERE vence_at<UTC_TIMESTAMP()');
$daily=$db->exec('DELETE FROM cp_bienvenidas_ip WHERE dia<DATE_SUB(UTC_DATE(),INTERVAL 7 DAY)');
echo "Contadores temporales eliminados: $rates límites y $daily registros diarios de IP. Premios, referidos y auditoría conservados.\n";
