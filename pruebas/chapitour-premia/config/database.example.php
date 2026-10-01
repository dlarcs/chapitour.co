<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

// Copiar como database.local.php en el servidor y completar con los datos de hPanel.
// La copia privada no se incluye en los despliegues de Git.
return [
    'host' => 'localhost',
    'database' => '',
    'username' => '',
    'password' => '',
];
