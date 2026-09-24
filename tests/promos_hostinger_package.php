<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$root = dirname(__DIR__);
$delivery = $root . '/entrega-hostinger';
$private = $delivery . '/privado';
$checks = 0;
function verifyPackage(bool $ok, string $message): void {
    global $checks;
    if (!$ok) throw new RuntimeException('FAIL: ' . $message);
    $checks++;
    echo 'OK ' . $message . "\n";
}
$credentials = require $private . '/cuentas.php';
$sql = file_get_contents($private . '/004_cuentas_panel.sql');
verifyPackage(count($credentials) === 7 && count(array_unique(array_column($credentials, 'password_temporal'))) === 7, 'siete claves temporales diferentes');
verifyPackage(!preg_match('/\b(DROP|UPDATE|DELETE|REPLACE|TRUNCATE)\b/i', preg_replace('/^--.*$/m', '', $sql)), 'SQL solo inserta cuentas nuevas');
verifyPackage((bool)preg_match('/INSERT INTO cp_usuarios\b[\s\S]+?;/i', $sql, $insert), 'una sola inserción para las siete cuentas');

// Verifica el INSERT generado y sus restricciones sin conectar al hosting ni a MySQL local.
$db = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$db->exec('PRAGMA foreign_keys = ON');
$db->exec('CREATE TABLE cp_negocios (id INTEGER PRIMARY KEY)');
for ($i=1; $i<=6; $i++) $db->exec('INSERT INTO cp_negocios VALUES (' . $i . ')');
$db->exec("CREATE TABLE cp_usuarios (id INTEGER PRIMARY KEY AUTOINCREMENT, negocio_id INTEGER NULL REFERENCES cp_negocios(id), usuario TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL, rol TEXT NOT NULL CHECK(rol IN ('admin','aliado')), activo INTEGER NOT NULL, cambiar_password INTEGER NOT NULL, version_sesion INTEGER NOT NULL)");
$db->exec($insert[0]);
$rows = $db->query('SELECT * FROM cp_usuarios ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
verifyPackage(count($rows) === 7, 'SQL inserta las siete cuentas');
foreach ($credentials as $i=>$credential) {
    $row = $rows[$i];
    $sameBusiness = $row['negocio_id'] === null ? $credential['negocio_id'] === null : (int)$row['negocio_id'] === $credential['negocio_id'];
    verifyPackage($row['usuario'] === $credential['usuario'] && $row['rol'] === $credential['rol'] && $sameBusiness && password_verify($credential['password_temporal'], $row['password_hash']) && (int)$row['cambiar_password'] === 1 && (int)$row['activo'] === 1, 'acceso, negocio y cambio obligatorio: ' . $credential['usuario']);
}
$duplicateFailed = false;
try { $db->exec($insert[0]); } catch (PDOException $e) { $duplicateFailed = true; }
verifyPackage($duplicateFailed && $db->query('SELECT * FROM cp_usuarios ORDER BY id')->fetchAll(PDO::FETCH_ASSOC) === $rows, 'segunda importación no reemplaza accesos existentes');

define('CHAPI_PROMOS', true);
$config = require $delivery . '/public_html/promos/config/local.php';
$localConfig = require $root . '/promos/config/local.php';
verifyPackage($config['db_user'] === 'u348170507_chapi_app' && $config['dsn'] === 'mysql:host=localhost;dbname=u348170507_chapi_promos;charset=utf8mb4' && $config['base_url'] === 'https://chapitour.co' && $config['secure_cookies'] === true, 'configuración de producción y HTTPS');
verifyPackage(strlen($config['app_key']) === 64 && $config['app_key'] !== $localConfig['app_key'], 'clave privada independiente de XAMPP');
verifyPackage(hash_file('sha256',$private.'/005_dashboard_promociones.sql') === hash_file('sha256',$root.'/promos/database/005_dashboard_promociones.sql'), 'migración del dashboard preparada para la base existente');
$manifest = json_decode(file_get_contents($delivery . '/MANIFIESTO.json'), true, 512, JSON_THROW_ON_ERROR)['archivos'];
$zip = new ZipArchive();
verifyPackage($zip->open($delivery . '/chapitour-promociones.zip') === true, 'ZIP válido');
verifyPackage($zip->numFiles === count($manifest), 'ZIP coincide con su manifiesto');
for ($i=0; $i<$zip->numFiles; $i++) {
    $name = $zip->getNameIndex($i);
    $contents = $zip->getFromIndex($i);
    if (!isset($manifest[$name]) || hash('sha256', $contents) !== $manifest[$name] || hash_file('sha256', $delivery . '/public_html/' . $name) !== $manifest[$name]) throw new RuntimeException('Archivo desactualizado: ' . $name);
    if ($name!=='promos/config/local.php' && is_file($root.'/'.$name) && hash_file('sha256',$root.'/'.$name)!==$manifest[$name]) throw new RuntimeException('El paquete no contiene el código actual: '.$name);
    if (str_contains($name,'promocion.demo') || str_contains($contents,'chapi-promo-demo-launcher')) throw new RuntimeException('La demo debe permanecer solo en pruebas.');
    if (preg_match('~(^|/)(privado|tests|database)/|accesos-iniciales|cuentas\.php|\.sql$~', $name)) throw new RuntimeException('Archivo privado en ZIP: ' . $name);
    foreach ($credentials as $credential) {
        if (str_contains($contents, $credential['password_temporal'])) throw new RuntimeException('Clave de panel en ZIP.');
    }
    if (str_contains($contents, $localConfig['db_password'])) throw new RuntimeException('Credencial MySQL local en ZIP.');
}
$zip->close();
verifyPackage(true, 'ZIP actualizado, sin claves de panel ni credenciales locales');
foreach (['config', 'src', 'storage', 'bin'] as $folder) {
    verifyPackage(str_contains(file_get_contents($delivery . '/public_html/promos/' . $folder . '/.htaccess'), 'Require all denied'), 'carpeta protegida: ' . $folder);
}
verifyPackage((fileperms($private . '/ACCESOS-PANELES.md') & 0777) === 0600 && (fileperms($delivery) & 0777) === 0700, 'permisos privados para la entrega y las claves');
echo "RESULTADO: $checks comprobaciones del paquete correctas.\n";
