<?php
// Copiar a mail.local.php (privado y excluido de Git). No usar la contraseña de un cliente.
return [
    'enabled' => false,
    'host' => 'smtp.hostinger.com',
    'port' => 465,
    'encryption' => 'ssl', // ssl (TLS implícito, 465) o tls (STARTTLS, 587).
    'username' => 'admin@chapitour.co',
    'password' => '',
    'from_email' => 'admin@chapitour.co',
    'from_name' => 'Chapitour',
];
