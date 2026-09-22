<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
final class ChapiSecurity
{
    public static function start(array $config): void
    {
        header('Cache-Control: no-store, private');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: same-origin');
        header('X-Frame-Options: DENY');
        if (session_status() !== PHP_SESSION_ACTIVE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            session_name('chapi_promos_session');
            session_set_cookie_params(['lifetime'=>0, 'path'=>self::cookiePath($config), 'httponly'=>true, 'secure'=>(bool)$config['secure_cookies'], 'samesite'=>'Lax']);
            session_start();
        }
        if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    public static function cookiePath(array $config): string
    {
        return rtrim((string)parse_url($config['base_url'], PHP_URL_PATH), '/') . '/';
    }

    public static function ipHash(array $config): string
    {
        // Never trust client-supplied X-Forwarded-For. Configure trusted proxies explicitly if needed.
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $packed = @inet_pton($ip);
        return hash_hmac('sha256', 'ip:' . ($packed !== false ? bin2hex($packed) : 'unknown'), $config['app_key']);
    }

    public static function identity(array $config): string
    {
        $token = $_COOKIE['chapi_visitante'] ?? '';
        if (!is_string($token) || !preg_match('/^[a-f0-9]{64}$/D', $token)) {
            $token = bin2hex(random_bytes(32));
            setcookie('chapi_visitante', $token, ['expires'=>time() + (int)$config['reglas']['cookie_dias'] * 86400, 'path'=>self::cookiePath($config), 'httponly'=>true, 'secure'=>(bool)$config['secure_cookies'], 'samesite'=>'Lax']);
            $_COOKIE['chapi_visitante'] = $token;
        }
        return $config['reglas']['identificacion'] === 'ip'
            ? hash_hmac('sha256', 'identity:' . self::ipHash($config), $config['app_key'])
            : hash_hmac('sha256', 'visitor:' . $token, $config['app_key']);
    }

    public static function input(array $config): array
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') throw new ChapiError('Usa una solicitud POST.', 405);
        $type = strtolower(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]);
        if ($type !== 'application/json') throw new ChapiError('Formato de solicitud incorrecto.', 415);
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $url = parse_url($config['base_url']);
        $expected = $url['scheme'] . '://' . $url['host'] . (isset($url['port']) ? ':' . $url['port'] : '');
        if ($origin !== '' && $origin !== $expected) throw new ChapiError('Origen no permitido.', 403);
        if (($_SERVER['HTTP_SEC_FETCH_SITE'] ?? '') === 'cross-site') throw new ChapiError('Origen no permitido.', 403);
        $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!is_string($csrf) || !hash_equals($_SESSION['csrf'] ?? '', $csrf) || $csrf === '') throw new ChapiError('Recarga la página para renovar la sesión.', 403, 'CSRF');
        $raw = file_get_contents('php://input', false, null, 0, 16385);
        if (strlen($raw) > 16384) throw new ChapiError('Solicitud demasiado grande.', 413);
        $data = json_decode($raw, true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) throw new ChapiError('JSON inválido.');
        return $data;
    }

    public static function text(array $data, string $key, int $max = 160, bool $required = true): string
    {
        $value = $data[$key] ?? '';
        if (!is_string($value)) throw new ChapiError('Campo inválido: ' . $key);
        $value = trim($value);
        if (($required && $value === '') || mb_strlen($value) > $max) throw new ChapiError('Revisa el campo ' . $key . '.');
        return $value;
    }

    public static function integer(array $data, string $key): int
    {
        $value = filter_var($data[$key] ?? null, FILTER_VALIDATE_INT);
        if ($value === false || $value === null || $value < 1) throw new ChapiError('Identificador inválido.');
        return $value;
    }

    public static function limit(PDO $db, array $config, string $scope, int $max, int $seconds): void
    {
        $bucket = (int)floor(time() / $seconds);
        $key = hash_hmac('sha256', $scope . ':' . $bucket, $config['app_key']);
        $stmt = $db->prepare('INSERT INTO cp_limites (clave,cantidad,vence_at) VALUES (?,1,?) ON DUPLICATE KEY UPDATE cantidad=cantidad+1');
        $stmt->execute([$key, gmdate('Y-m-d H:i:s', ($bucket + 1) * $seconds)]);
        $stmt = $db->prepare('SELECT cantidad FROM cp_limites WHERE clave=?');
        $stmt->execute([$key]);
        if ((int)$stmt->fetchColumn() > $max) throw new ChapiError('Demasiados intentos. Intenta de nuevo más tarde.', 429, 'RATE_LIMIT');
    }
}
