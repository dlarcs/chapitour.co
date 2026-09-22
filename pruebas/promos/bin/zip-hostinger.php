<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$delivery = dirname(__DIR__, 2) . '/entrega-hostinger';
$publicRoot = $delivery . '/public_html';
if (!is_dir($publicRoot)) throw new RuntimeException('Primero prepara la entrega con prepare-hostinger.php.');
if (!class_exists('ZipArchive')) throw new RuntimeException('Se necesita la extensión PHP ZipArchive.');
$archivePath = $delivery . '/chapitour-promociones.zip';
$zip = new ZipArchive();
if ($zip->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) throw new RuntimeException('No fue posible crear el ZIP.');
$manifest = [];
$entries = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($publicRoot, FilesystemIterator::SKIP_DOTS));
foreach ($entries as $entry) {
    if ($entry->isLink()) throw new RuntimeException('No se empaquetan enlaces simbólicos.');
    if (!$entry->isFile()) continue;
    $relative = substr($entry->getPathname(), strlen($publicRoot) + 1);
    if (!in_array($relative, ['index.php'], true) && !preg_match('~^(home/promocion/|promos/(api|assets|panel|src|config|storage|bin)/)~', $relative)) throw new RuntimeException('Archivo fuera del módulo: ' . $relative);
    if (str_starts_with($relative, 'promos/storage/') && !in_array(basename($relative), ['.htaccess','.gitkeep'], true)) throw new RuntimeException('No se empaquetan accesos ni datos privados de storage.');
    $zip->addFile($entry->getPathname(), $relative);
    $manifest[$relative] = hash_file('sha256', $entry->getPathname());
}
if (!$zip->close()) throw new RuntimeException('No fue posible terminar el ZIP.');
chmod($archivePath, 0600);
ksort($manifest);
file_put_contents($delivery . '/MANIFIESTO.json', json_encode(['archivos'=>$manifest], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
$configContent = file_get_contents($publicRoot . '/promos/config/local.php');
echo 'ZIP actualizado: ' . count($manifest) . " archivos.\n";
if (str_contains($configContent, 'PENDIENTE_CONTRASENA_MYSQL')) echo "Pendiente: completar contraseña MySQL y regenerar el ZIP antes de desplegar.\n";
