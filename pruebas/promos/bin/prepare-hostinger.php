<?php
// La entrega de producción se prepara y verifica desde el código principal.
// Así la demostración de pruebas no entra en el paquete de Hostinger.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__, 3) . '/promos/bin/prepare-hostinger.php';
