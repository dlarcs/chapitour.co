<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$path = '/private/tmp/chapi-test-environment.json';
if (!is_file($path)) { echo "No hay un entorno temporal que limpiar.\n"; exit; }
$fixture = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
if (!preg_match('/^chapitour_test_[a-f0-9]{10}$/D', $fixture['database'] ?? '')) {
    throw new RuntimeException('Solo se pueden eliminar bases temporales de estas pruebas.');
}
$db = new PDO('mysql:host=127.0.0.1;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$db->exec('DROP DATABASE IF EXISTS `' . $fixture['database'] . '`');
unlink($path);
echo "Base temporal y configuración de pruebas eliminadas.\n";
