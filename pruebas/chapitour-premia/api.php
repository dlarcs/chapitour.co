<?php
declare(strict_types=1);

// Isolated, session-backed sandbox. No connection to the production database.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('X-Robots-Tag: noindex, nofollow');
date_default_timezone_set('America/Bogota');
$storage = sys_get_temp_dir() . '/chapitour-premia-sandbox';
if (!is_dir($storage)) { mkdir($storage, 0700, true); }
session_save_path($storage);
session_name('CHAPITOUR_PREMIA_TEST');
session_set_cookie_params(['lifetime' => 0, 'path' => rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/', 'httponly' => true, 'samesite' => 'Strict', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
ini_set('session.use_strict_mode', '1');
session_start(); // The session lock also serializes redemptions and spin requests.

function fail(string $message, int $status = 422): void {
    http_response_code($status);
    echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}
function field(array $input, string $key, int $max = 500, bool $required = true): string {
    $value = $input[$key] ?? '';
    if (!is_string($value)) { fail('El campo ' . $key . ' no es válido.'); }
    $value = trim($value);
    if (($required && $value === '') || mb_strlen($value) > $max) { fail('Revisa el campo ' . $key . '.'); }
    return $value;
}
function codeStatus(array $code): string {
    return $code['redeemed_at'] !== null ? 'Redimido' : ($code['expires_at'] <= time() ? 'Vencido' : 'Activo');
}
function seed(): array {
    $businesses = [
        ['id'=>'capital', 'name'=>'Capital Queer', 'category'=>'Diversidad · Vida nocturna', 'icon'=>'sparkles', 'color'=>'pink', 'path'=>'bar/CapitalQueer/', 'image'=>'bar/CapitalQueer/img/general18.jpg'],
        ['id'=>'gran', 'name'=>'Gran&Chela', 'category'=>'Amigos · Buena música', 'icon'=>'beer', 'color'=>'yellow', 'path'=>'bar/Gran&Chela_Club/', 'image'=>'bar/Gran&Chela_Club/img/general.jpg'],
        ['id'=>'garage', 'name'=>'Garage Gastrobar', 'category'=>'Gastronomía · Vida nocturna', 'icon'=>'music', 'color'=>'purple', 'path'=>'gastrobar/GarageDiscoBar/', 'image'=>'gastrobar/GarageDiscoBar/img/general15.jpg'],
        ['id'=>'pictogramas', 'name'=>'Pictogramas', 'category'=>'Café · Conversaciones', 'icon'=>'coffee', 'color'=>'cyan', 'path'=>'bar/Pictograma/', 'image'=>'bar/Pictograma/img/general3.png'],
        ['id'=>'street', 'name'=>'Street Grill', 'category'=>'Gastronomía · Buenos momentos', 'icon'=>'flame', 'color'=>'pink', 'path'=>'gastronomia/streetgrill/', 'image'=>'gastronomia/streetgrill/img/general.jpeg'],
        ['id'=>'jimar', 'name'=>'Jimar Factory', 'category'=>'Juegos · Encuentros', 'icon'=>'target', 'color'=>'cyan', 'path'=>'juegos/JimarFactory/', 'image'=>'juegos/JimarFactory/img/general17.jpeg'],
    ];
    $offers = ['capital'=>'Media de aguardiente Néctar y 6 cervezas — $88.000 COP.', 'gran'=>'Media más cubetazo y 7 cervezas — $80.000 COP.', 'garage'=>'Botella de Real más 4 cervezas — $148.000 COP.', 'street'=>'10 % de descuento.', 'pictogramas'=>'10 % de descuento al comprar una bebida de café y un postre.', 'jimar'=>'15 minutos adicionales de billar al pagar una hora.'];
    $state = ['businesses'=>[], 'promotions'=>[], 'codes'=>[], 'users'=>[], 'spin_tokens'=>[]];
    foreach ($businesses as $b) {
        $b['whatsapp'] = '';
        $state['businesses'][$b['id']] = $b;
        $state['users']['ally-'.$b['id']] = ['id'=>'ally-'.$b['id'], 'name'=>$b['name'], 'email'=>$b['id'].'@demo.invalid', 'role'=>'ally', 'business_id'=>$b['id'], 'demo'=>true, 'city'=>'Bogotá D.C.', 'created_at'=>time(), 'password'=>null];
        $state['promotions']['promo-'.$b['id']] = ['id'=>'promo-'.$b['id'], 'business_id'=>$b['id'], 'description'=>$offers[$b['id']], 'publication'=>in_array($b['id'], ['pictogramas', 'jimar'], true) ? 'draft' : 'reference', 'included'=>'', 'hours'=>'', 'restrictions'=>''];
    }
    $state['users']['demo-client'] = ['id'=>'demo-client', 'name'=>'Dani', 'email'=>'dani@demo.invalid', 'role'=>'client', 'business_id'=>null, 'demo'=>true, 'city'=>'Bogotá D.C.', 'created_at'=>time(), 'password'=>null];
    $state['users']['demo-admin'] = ['id'=>'demo-admin', 'name'=>'Aleina', 'email'=>'admin@demo.invalid', 'role'=>'admin', 'business_id'=>null, 'demo'=>true, 'city'=>'Bogotá D.C.', 'created_at'=>time(), 'password'=>null];
    foreach (['street', 'garage', 'capital', 'gran'] as $bi => $business) {
        foreach ([0, 1, 2] as $i) {
            $code = 'DEMO-CHAPI-' . strtoupper(bin2hex(random_bytes(4)));
            $created = time() - ($i === 2 ? 80 : ($i === 1 ? 26 : 8)) * 3600;
            $state['codes'][$code] = ['code'=>$code, 'business_id'=>$business, 'business_name'=>$state['businesses'][$business]['name'], 'promotion_id'=>'promo-'.$business, 'description'=>$offers[$business], 'user_id'=>$bi === 0 ? 'demo-client' : 'fixture-client', 'created_at'=>$created, 'expires_at'=>$created+72*3600, 'redeemed_at'=>$i === 1 ? time()-3600 : null, 'demo'=>true];
        }
    }
    return $state;
}
$_SESSION['data'] = $_SESSION['data'] ?? seed();
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(24));
$state =& $_SESSION['data'];
$action = $_GET['action'] ?? 'state';
$input = [];
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { fail('Método no permitido.', 405); }
    if (!hash_equals($_SESSION['csrf'], $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) { fail('Actualiza la página e intenta de nuevo.', 403); }
    if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 20000) { fail('Solicitud demasiado grande.', 413); }
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) { fail('Solicitud no válida.'); }
    $action = $input['action'] ?? '';
} elseif ($action !== 'state') { fail('Usa POST para realizar esta acción.', 405); }
$user = $state['users'][$_SESSION['user_id'] ?? ''] ?? null;
function requireRole(?array $user, array $roles): void {
    if (!$user || !in_array($user['role'], $roles, true)) { fail('No tienes permiso para realizar esta acción.', 403); }
}
function visibleCode(array $state, ?array $user, string $id): array {
    if (!$user || !isset($state['codes'][$id])) { fail('Código no encontrado.', 404); }
    $c = $state['codes'][$id];
    if (($user['role'] === 'client' && $c['user_id'] !== $user['id']) || ($user['role'] === 'ally' && $c['business_id'] !== $user['business_id'])) { fail('Código no encontrado.', 404); }
    return $c;
}
$result = [];
switch ($action) {
    case 'state': break;
    case 'demo_login':
        $role = field($input, 'role', 10);
        $id = $role === 'ally' ? 'ally-'.field($input, 'business_id', 50) : 'demo-'.$role;
        if (!isset($state['users'][$id]) || !$state['users'][$id]['demo']) { fail('Esta cuenta de demostración no está disponible.'); }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        break;
    case 'register':
        $email = strtolower(field($input, 'email', 150));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { fail('Escribe un correo válido.'); }
        foreach ($state['users'] as $u) { if ($u['email'] === $email) { fail('Ya existe una cuenta con este correo.'); } }
        $password = field($input, 'password', 200);
        if (strlen($password) < 8) { fail('La contraseña debe tener al menos 8 caracteres.'); }
        $id = 'client-'.bin2hex(random_bytes(8));
        $state['users'][$id] = ['id'=>$id, 'name'=>field($input, 'name', 80), 'email'=>$email, 'role'=>'client', 'business_id'=>null, 'demo'=>false, 'city'=>field($input, 'city', 80, false), 'created_at'=>time(), 'password'=>password_hash($password, PASSWORD_DEFAULT)];
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        break;
    case 'login':
        $email = strtolower(field($input, 'email', 150));
        $found = null;
        foreach ($state['users'] as $u) { if ($u['email'] === $email && $u['password'] && password_verify(field($input, 'password', 200), $u['password'])) { $found = $u['id']; break; } }
        if (!$found) { fail('Correo o contraseña incorrectos. Las cuentas de prueba pertenecen a este navegador.', 401); }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $found;
        break;
    case 'logout': unset($_SESSION['user_id']); session_regenerate_id(true); break;
    case 'profile':
        requireRole($user, ['client']);
        $email = strtolower(field($input, 'email', 150));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { fail('Escribe un correo válido.'); }
        foreach ($state['users'] as $u) { if ($u['id'] !== $user['id'] && $u['email'] === $email) { fail('Ya existe una cuenta con este correo.'); } }
        $state['users'][$user['id']]['name'] = field($input, 'name', 80);
        $state['users'][$user['id']]['email'] = $email;
        $state['users'][$user['id']]['city'] = field($input, 'city', 80, false);
        break;
    case 'delete_account':
        requireRole($user, ['client']);
        if (($input['confirm'] ?? '') !== $user['id']) { fail('Confirma la cuenta que quieres eliminar.'); }
        foreach ($state['codes'] as $id=>$c) { if ($c['user_id'] === $user['id']) { unset($state['codes'][$id]); } }
        unset($state['users'][$user['id']], $_SESSION['user_id']);
        session_regenerate_id(true);
        break;
    case 'prepare_spin':
        requireRole($user, ['client']);
        if (!$user['demo']) { fail('La entrega de premios está pendiente de configuración. Prueba la ruleta desde la cuenta de demostración.'); }
        // A single server token for this demonstration; refreshing never grants a reward.
        $token = bin2hex(random_bytes(20));
        $state['spin_tokens'][$user['id']] = ['token'=>$token, 'code'=>null, 'expires'=>time()+600];
        $result['spin_token'] = $token;
        break;
    case 'spin':
        requireRole($user, ['client']);
        $grant = $state['spin_tokens'][$user['id']] ?? null;
        if (!$user['demo'] || !$grant || !hash_equals($grant['token'], field($input, 'token', 100)) || $grant['expires'] < time()) { fail('Esta oportunidad de demostración ya no está disponible.', 409); }
        if ($grant['code']) { $result['prize'] = $state['codes'][$grant['code']]; break; }
        $eligible = array_values(array_filter($state['promotions'], function ($p) use ($state) { return $p['publication'] !== 'draft' && isset($state['businesses'][$p['business_id']]) && in_array($p['business_id'], ['capital','gran','garage','pictogramas','street','jimar'], true); }));
        if (!$eligible) { fail('No hay promociones disponibles para esta demostración.'); }
        $p = $eligible[random_int(0, count($eligible)-1)];
        $code = 'DEMO-CHAPI-'.strtoupper(bin2hex(random_bytes(6)));
        $state['codes'][$code] = ['code'=>$code, 'business_id'=>$p['business_id'], 'business_name'=>$state['businesses'][$p['business_id']]['name'], 'promotion_id'=>$p['id'], 'description'=>$p['description'], 'user_id'=>$user['id'], 'created_at'=>time(), 'expires_at'=>time()+72*3600, 'redeemed_at'=>null, 'demo'=>true];
        $state['spin_tokens'][$user['id']]['code'] = $code;
        $result['prize'] = $state['codes'][$code];
        break;
    case 'redeem':
        requireRole($user, ['ally', 'admin']);
        $id = field($input, 'code', 80);
        $c = visibleCode($state, $user, $id);
        if (codeStatus($c) !== 'Activo') { fail('Este código ya fue redimido o está vencido. No puede utilizarse nuevamente.', 409); }
        if (($input['confirm'] ?? '') !== $id) { fail('Confirma el código que deseas redimir.'); }
        $state['codes'][$id]['redeemed_at'] = time();
        $state['codes'][$id]['redeemed_by'] = $user['id'];
        break;
    case 'whatsapp':
        requireRole($user, ['client']);
        $c = visibleCode($state, $user, field($input, 'code', 80));
        if (codeStatus($c) !== 'Activo') { fail('Este código no está activo.', 409); }
        $phone = $state['businesses'][$c['business_id']]['whatsapp'] ?? '';
        if (!preg_match('/^[1-9][0-9]{7,14}$/', $phone)) { fail('WhatsApp pendiente de confirmar por el negocio.'); }
        $message = 'PRUEBA · SIN VALIDEZ COMERCIAL. Hola, '.$c['business_name'].'. Quiero redimir la promoción: '.$c['description'].' Mi código único es '.$c['code'].'.';
        $result['whatsapp_url'] = 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
        break;
    case 'save_business':
        requireRole($user, ['admin']);
        $name = field($input, 'name', 80);
        $email = strtolower(field($input, 'email', 150));
        $password = field($input, 'password', 200);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password)<8) { fail('Revisa el correo y la contraseña (mínimo 8 caracteres).'); }
        foreach ($state['users'] as $u) { if ($u['email'] === $email) { fail('Ese correo ya tiene una cuenta.'); } }
        $id = 'business-'.bin2hex(random_bytes(5));
        $state['businesses'][$id] = ['id'=>$id, 'name'=>$name, 'category'=>'Aliado de Chapitour', 'icon'=>'store', 'color'=>'purple', 'path'=>'', 'image'=>'', 'whatsapp'=>''];
        $state['users']['ally-'.$id] = ['id'=>'ally-'.$id, 'name'=>$name, 'email'=>$email, 'password'=>password_hash($password, PASSWORD_DEFAULT), 'role'=>'ally', 'business_id'=>$id, 'demo'=>false, 'city'=>'', 'created_at'=>time()];
        break;
    case 'delete_business':
        requireRole($user, ['admin']);
        $id = field($input, 'id', 80);
        if (!isset($state['businesses'][$id]) || ($input['confirm'] ?? '') !== $id) { fail('Confirma la cuenta del aliado.'); }
        // Keep promotions and issued code snapshots for review; disable future allocation.
        foreach ($state['promotions'] as &$p) { if ($p['business_id'] === $id) { $p['publication'] = 'draft'; } } unset($p);
        unset($state['users']['ally-'.$id], $state['businesses'][$id]);
        break;
    case 'save_promotion':
        requireRole($user, ['admin']);
        $id = field($input, 'id', 80, false);
        if ($id !== '' && !isset($state['promotions'][$id])) { fail('Promoción no encontrada.', 404); }
        $business = field($input, 'business_id', 80);
        if (!isset($state['businesses'][$business])) { fail('Selecciona un aliado disponible.'); }
        $publication = field($input, 'publication', 20);
        if (!in_array($publication, ['draft','reference','approved'], true)) { fail('Estado de publicación no válido.'); }
        if ($publication === 'reference' && (!$id || $state['promotions'][$id]['publication'] !== 'reference')) { fail('Una oferta nueva o provisional debe guardarse como borrador o confirmarse.'); }
        $phone = preg_replace('/[\s+()-]/', '', field($input, 'whatsapp', 25, false));
        if ($phone !== '' && !preg_match('/^[1-9][0-9]{7,14}$/', $phone)) { fail('El WhatsApp debe incluir el indicativo del país y entre 8 y 15 dígitos.'); }
        $p = ['id'=>$id ?: 'promo-'.bin2hex(random_bytes(6)), 'business_id'=>$business, 'description'=>field($input, 'description', 500), 'publication'=>$publication, 'included'=>field($input, 'included', 400, false), 'hours'=>field($input, 'hours', 300, false), 'restrictions'=>field($input, 'restrictions', 500, false)];
        if ($publication === 'approved' && (!$phone || !$p['included'] || !$p['hours'] || !$p['restrictions'] || ($input['confirmed'] ?? false) !== true)) { fail('Confirma con el negocio el beneficio, los productos o servicios, los horarios, las restricciones y el WhatsApp.'); }
        $state['promotions'][$p['id']] = $p;
        $state['businesses'][$business]['whatsapp'] = $phone;
        break;
    case 'delete_promotion':
        requireRole($user, ['admin']);
        $id = field($input, 'id', 80);
        if (!isset($state['promotions'][$id]) || ($input['confirm'] ?? '') !== $id) { fail('Confirma la promoción que quieres eliminar.'); }
        unset($state['promotions'][$id]); // Issued codes retain their original benefit and validity.
        break;
    default: fail('Acción no disponible.', 404);
}
$user = $state['users'][$_SESSION['user_id'] ?? ''] ?? null;
$safeUser = $user;
if ($safeUser) { unset($safeUser['password']); }
$businesses = array_values($state['businesses']);
$promotions = array_values($state['promotions']);
$codes = [];
if ($user) {
    if ($user['role'] === 'ally') {
        $businesses = array_values(array_filter($businesses, function ($b) use ($user) { return $b['id'] === $user['business_id']; }));
        $promotions = array_values(array_filter($promotions, function ($p) use ($user) { return $p['business_id'] === $user['business_id']; }));
    }
    foreach ($state['codes'] as $c) {
        if (($user['role'] === 'client' && $c['user_id'] !== $user['id']) || ($user['role'] === 'ally' && $c['business_id'] !== $user['business_id'])) { continue; }
        $c['status'] = codeStatus($c);
        unset($c['user_id'], $c['redeemed_by']);
        $codes[] = $c;
    }
}
if (!$user || $user['role'] === 'client') { $promotions = array_values(array_filter($promotions, function ($p) { return $p['publication'] !== 'draft'; })); }
if ($user && $user['role'] === 'admin') {
    foreach ($businesses as &$b) { $b['email'] = $state['users']['ally-'.$b['id']]['email'] ?? ''; } unset($b);
}
if (isset($result['prize'])) { $result['prize']['status'] = codeStatus($result['prize']); unset($result['prize']['user_id']); }
echo json_encode(array_merge(['csrf'=>$_SESSION['csrf'], 'user'=>$safeUser, 'businesses'=>$businesses, 'promotions'=>$promotions, 'codes'=>$codes, 'server_time'=>time(), 'campaign'=>['enabled'=>false, 'visits_per_reward'=>null, 'new_visit_after'=>null, 'replaces_previous_rule'=>null, 'monthly_visit_reset'=>null]], $result), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
