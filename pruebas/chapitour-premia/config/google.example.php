<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }
// Copiar como google.local.php. ID OAuth de tipo Aplicación web, no una API key.
// Origen JavaScript autorizado: https://chapitour.co
// Google Identity Services usa una ventana emergente y solo necesita el ID público.
return ['client_id' => ''];
