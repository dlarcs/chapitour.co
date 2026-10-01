<?php
declare(strict_types=1);

// Uso desde PHP: $pdo = require __DIR__ . '/config/database.php';
// localhost corresponde a MySQL en el mismo servidor donde se ejecuta PHP.
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(404);
    exit;
}

$chapitourDbConfig = require __DIR__ . '/database.local.php';

// Server-side overrides allow an isolated QA database without changing Hostinger credentials.
foreach (['host'=>'HOST', 'database'=>'NAME', 'username'=>'USER', 'password'=>'PASSWORD'] as $key=>$suffix) {
    $value = getenv('CHAPITOUR_DB_'.$suffix);
    if ($value !== false) { $chapitourDbConfig[$key] = $value; }
}
$chapitourSocket = getenv('CHAPITOUR_DB_SOCKET');
$chapitourDsn = $chapitourSocket
    ? 'mysql:unix_socket='.$chapitourSocket
    : 'mysql:host='.$chapitourDbConfig['host'];

$chapitourPdo = new PDO(
    $chapitourDsn
        . ';dbname=' . $chapitourDbConfig['database']
        . ';charset=utf8mb4',
    $chapitourDbConfig['username'],
    $chapitourDbConfig['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ]
);

unset($chapitourDbConfig, $chapitourDsn, $chapitourSocket, $key, $suffix, $value);
$chapitourPdo->exec("SET time_zone = '+00:00'");

return $chapitourPdo;
