<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }
$mailConfig = require __DIR__.'/mail.example.php';
if (is_file(__DIR__.'/mail.local.php')) {
    $local = require __DIR__.'/mail.local.php';
    if (!is_array($local)) { throw new RuntimeException('MAIL_CONFIGURATION'); }
    $mailConfig = array_replace($mailConfig, $local);
}
foreach (['enabled','host','port','encryption','username','password','from_email','from_name'] as $key) {
    $value = getenv('CHAPITOUR_MAIL_'.strtoupper($key));
    if ($value !== false) { $mailConfig[$key] = $value; }
}
$enabled = filter_var($mailConfig['enabled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
if ($enabled === null) { throw new RuntimeException('MAIL_CONFIGURATION'); }
$mailConfig['enabled'] = $enabled;
return $mailConfig;
