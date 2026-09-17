<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

// Prepara archivos locales. No conecta a MySQL ni publica en Hostinger.
$root = dirname(__DIR__, 2);
$output = $root . '/entrega-hostinger';
if (file_exists($output)) {
    fwrite(STDERR, "La entrega ya existe. Se conserva para no reemplazar contraseñas ni configuración.\n");
    exit(1);
}
if (!class_exists('ZipArchive')) throw new RuntimeException('Se necesita la extensión PHP ZipArchive.');
umask(0077);
mkdir($output, 0700);
file_put_contents($output . '/.htaccess', "Require all denied\n");
$public = $output . '/public_html';
$private = $output . '/privado';
mkdir($public, 0755);
mkdir($private, 0700);
file_put_contents($private . '/.htaccess', "Require all denied\n");

function copyRuntime(string $source, string $destination): void
{
    if (is_link($source)) throw new RuntimeException('No se empaquetan enlaces simbólicos: ' . $source);
    if (is_dir($source)) {
        if (!is_dir($destination)) { mkdir($destination, 0755, true); chmod($destination, 0755); }
        foreach (new DirectoryIterator($source) as $entry) {
            if (!$entry->isDot()) copyRuntime($entry->getPathname(), $destination . '/' . $entry->getFilename());
        }
        return;
    }
    if (!is_dir(dirname($destination))) { mkdir(dirname($destination), 0755, true); chmod(dirname($destination), 0755); }
    if (!copy($source, $destination)) throw new RuntimeException('No fue posible copiar ' . $source);
    chmod($destination, 0644);
}

// Lista explícita: excluye los accesos locales, la configuración XAMPP y los tests.
copyRuntime($root . '/index.php', $public . '/index.php');
copyRuntime($root . '/home/promocion', $public . '/home/promocion');
foreach (['api', 'assets', 'panel', 'src'] as $directory) {
    copyRuntime($root . '/promos/' . $directory, $public . '/promos/' . $directory);
}
foreach (['config/.htaccess', 'config/reglas.php', 'storage/.htaccess', 'storage/.gitkeep', 'bin/.htaccess', 'bin/accounts.php', 'bin/maintenance.php'] as $file) {
    copyRuntime($root . '/promos/' . $file, $public . '/promos/' . $file);
}

$config = [
    'dsn' => 'mysql:host=localhost;dbname=u348170507_chapi_promos;charset=utf8mb4',
    'db_user' => 'u348170507_chapi_app',
    'db_password' => 'PENDIENTE_CONTRASENA_MYSQL',
    'app_key' => bin2hex(random_bytes(32)),
    'base_url' => 'https://chapitour.co',
    'secure_cookies' => true,
];
$configuration = "<?php\n// Completar db_password con la contraseña MySQL definida en Hostinger.\n// Las contraseñas de los paneles son diferentes de la contraseña MySQL.\nif (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }\nreturn " . var_export($config, true) . ";\n";
file_put_contents($public . '/promos/config/local.php', $configuration);
chmod($public . '/promos/config/local.php', 0644);

$accounts = [
    [null, 'admin', 'admin', 'Administración'],
    [1, 'street-grill', 'aliado', 'Street Grill'],
    [2, 'capital-queer', 'aliado', 'Capital Queer'],
    [3, 'jimar-factory', 'aliado', 'Jimar Factory'],
    [4, 'garage-disco-bar', 'aliado', 'Garage Disco Bar'],
    [5, 'pictogramas', 'aliado', 'Pictogramas Café Bar'],
    [6, 'gran-chela', 'aliado', 'Gran&Chela Club'],
];
$credentials = [];
$values = [];
$quote = static fn(string $value): string => "'" . str_replace("'", "''", $value) . "'";
$accessDocument = "# Accesos de los paneles — Hostinger\n\nDocumento privado. No subir a public_html ni compartir completo con los establecimientos.\nEntrega a cada aliado únicamente su propia cuenta.\n\nPanel: https://chapitour.co/promos/panel/\n\nEstas cuentas se activan al importar 004_cuentas_panel.sql y desplegar el módulo.\nCada contraseña es temporal y debe cambiarse en el primer ingreso.\n\n| Negocio | Usuario | Contraseña temporal |\n| --- | --- | --- |\n";
foreach ($accounts as [$business, $username, $role, $label]) {
    $password = 'Chapi-' . bin2hex(random_bytes(12)) . '!';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $credentials[] = ['negocio_id'=>$business, 'negocio'=>$label, 'usuario'=>$username, 'rol'=>$role, 'password_temporal'=>$password];
    $values[] = '(' . ($business === null ? 'NULL' : $business) . ',' . $quote($username) . ',' . $quote($hash) . ',' . $quote($role) . ',1,1,1)';
    $accessDocument .= '| ' . $label . ' | ' . $username . ' | `' . $password . "` |\n";
}
$sql = "-- Importar UNA VEZ en u348170507_chapi_promos, después del esquema inicial.\n-- Solo crea las siete cuentas del panel, con hashes y cambio inicial obligatorio.\n-- No modifica usuarios existentes. Si un nombre ya existe, el INSERT completo falla.\n-- No contiene contraseñas en texto plano. No subir este archivo a public_html.\nSET NAMES utf8mb4;\nSTART TRANSACTION;\nINSERT INTO cp_usuarios (negocio_id,usuario,password_hash,rol,activo,cambiar_password,version_sesion) VALUES\n" . implode(",\n", $values) . ";\nCOMMIT;\n";
file_put_contents($private . '/004_cuentas_panel.sql', $sql);
file_put_contents($private . '/ACCESOS-PANELES.md', $accessDocument);
file_put_contents($private . '/cuentas.php', "<?php\nif (PHP_SAPI !== 'cli') { http_response_code(404); exit; }\nreturn " . var_export($credentials, true) . ";\n");
copyRuntime($root . '/promos/database/hostinger_inicial.sql', $private . '/hostinger_inicial.sql');
chmod($private . '/hostinger_inicial.sql', 0600);

$instructions = <<<'MD'
# Entrega de promociones para Hostinger

Archivos preparados localmente. No se han subido ni se han creado las cuentas del panel en Hostinger todavía.

## Contenido

- `public_html/`: actualización de la portada y del módulo de promociones. Copiar su contenido dentro del `public_html` existente de **chapitour.co**.
- `privado/004_cuentas_panel.sql`: siete cuentas listas para importar en **u348170507_chapi_promos**; contiene hashes de las contraseñas.
- `privado/ACCESOS-PANELES.md`: usuarios y claves temporales para entregar individualmente.
- `privado/hostinger_inicial.sql`: copia del esquema inicial, **ya importado**. No volver a ejecutarlo en esta base.
- `chapitour-promociones.zip`: contiene solamente los archivos de `public_html/`, sin la carpeta privada ni las claves de los paneles.

El ZIP es una actualización del sitio PHP existente: incluye `index.php`, `home/promocion/` y `promos/`. Las otras secciones, imágenes, CSS y JS del sitio deben conservarse en el hosting. No es una copia completa del sitio.

## Único dato de conexión pendiente

Editar `public_html/promos/config/local.php` y reemplazar `PENDIENTE_CONTRASENA_MYSQL` por la contraseña que se definió al crear el usuario MySQL en Hostinger. No es la contraseña de `admin` ni de un establecimiento.

Ya están configurados:

- Base: `u348170507_chapi_promos`.
- Usuario MySQL: `u348170507_chapi_app`.
- Servidor: `localhost`, para el PHP ejecutándose en Hostinger.
- URL: `https://chapitour.co`.
- Cookies seguras y clave privada nueva para esta instalación. Conservar esa clave entre actualizaciones.

Referencia del servidor: [documentación oficial de Hostinger](https://www.hostinger.com/support/1583226-which-database-management-system-is-used-at-hostinger/).

## Cuando se suba a Hostinger

1. Completar la contraseña MySQL en `public_html/promos/config/local.php`.
2. Si se utilizará el ZIP, regenerarlo después de editar ese archivo: `php promos/bin/zip-hostinger.php`, desde la raíz del proyecto local.
3. Conservar una copia de la portada y los archivos que ya existan en el hosting antes de reemplazarlos.
4. En phpMyAdmin, seleccionar **u348170507_chapi_promos** e importar **una sola vez** `privado/004_cuentas_panel.sql`. Este INSERT crea siete cuentas juntas; no restablece contraseñas existentes.
5. Subir únicamente el contenido de `public_html/`, o extraer el ZIP directamente dentro del `public_html` del sitio. No subir la carpeta `entrega-hostinger` completa ni `privado/`.
6. Entrar en `https://chapitour.co/promos/panel/` con la cuenta `admin` y cambiar la clave temporal.
7. Configurar los beneficios y condiciones reales y activar las promociones.

Las seis cuentas de los aliados también exigen cambio de contraseña al entrar por primera vez. La configuración local de XAMPP permanece separada de estos archivos.

No ejecutar de nuevo `prepare-hostinger.php` sobre esta entrega: conserva las cuentas y sus claves. El comando se detiene si la carpeta ya existe.
MD;
file_put_contents($output . '/LEEME.md', $instructions . "\n");
// La carpeta exterior es privada; las carpetas destinadas al hosting usan 0755.
chmod($public, 0755);
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($public, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $entry) {
    if ($entry->isDir()) chmod($entry->getPathname(), 0755);
}
require __DIR__ . '/zip-hostinger.php';
echo "Entrega preparada: $output\nSiete cuentas con contraseñas temporales privadas. Falta completar la contraseña MySQL antes de subir.\n";
