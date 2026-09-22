<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
date_default_timezone_set('UTC');
spl_autoload_register(static function (string $class): void {
    if (preg_match('/^Chapi([A-Za-z]+)$/', $class)) {
        $file = __DIR__ . '/' . $class . '.php';
        if (is_file($file)) require_once $file;
    }
});

function chapi_config(): array
{
    static $config;
    if ($config !== null) return $config;
    $file = dirname(__DIR__) . '/config/local.php';
    if (!is_file($file)) throw new RuntimeException('Instalar la configuración privada de promociones.');
    $config = require $file;
    $config['reglas'] = require dirname(__DIR__) . '/config/reglas.php';
    if (!in_array($config['reglas']['frecuencia'], ['primera_vez','cada_visitas','cada_dias'], true)
        || !in_array($config['reglas']['identificacion'], ['anonimo','ip'], true)) throw new RuntimeException('Regla de promoción desconocida.');
    foreach (['cada_visitas','cada_dias','vigencia_horas','amigos_requeridos','max_bienvenidas_por_ip_dia','cookie_dias','sesion_panel_minutos'] as $rule) {
        if (!is_int($config['reglas'][$rule]) || $config['reglas'][$rule]<1) throw new RuntimeException('La regla '.$rule.' debe ser un entero positivo.');
    }
    if ($config['reglas']['amigos_requeridos']>20) throw new RuntimeException('La meta visual admite hasta 20 invitados.');
    $config['campana_id'] = 1;
    // Solo el entorno del proceso del servidor puede sobrescribir estos valores.
    foreach (['dsn'=>'CHAPI_DB_DSN','db_user'=>'CHAPI_DB_USER','db_password'=>'CHAPI_DB_PASSWORD','base_url'=>'CHAPI_BASE_URL'] as $key=>$env) {
        if (getenv($env) !== false) $config[$key] = getenv($env);
    }
    if (strlen($config['app_key'] ?? '') < 32) throw new RuntimeException('Clave privada no configurada.');
    $config['base_url'] = rtrim($config['base_url'], '/');
    return $config;
}

function chapi_db(): PDO
{
    static $pdo;
    if ($pdo) return $pdo;
    $c = chapi_config();
    $pdo = new PDO($c['dsn'], $c['db_user'], $c['db_password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $pdo->exec("SET time_zone = '+00:00'");
    return $pdo;
}
