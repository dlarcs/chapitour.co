<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }

return [
    // 'primera_vez', 'cada_visitas' o 'cada_dias'. Cambia estos valores, no el modelo.
    'frecuencia' => 'cada_visitas',
    'cada_visitas' => 5,
    'cada_dias' => 7,
    // Recargas dentro de este plazo no cuentan como otra visita.
    'min_segundos_entre_visitas' => 0,
    'vigencia_horas' => 72,
    'amigos_requeridos' => 8,
    'premiar_redencion' => true,
    // 'anonimo': cookie aleatoria. 'ip': agrupa a TODOS los equipos de la misma IP.
    'identificacion' => 'anonimo',
    'max_bienvenidas_por_ip_dia' => 3,
    // Verifica visitas confirmadas al enlace; NO verifica mensajes enviados en WhatsApp.
    'referidos_habilitados' => true,
    'min_segundos_referido' => 10,
    'referidos_ip_distinta' => true,
    'cookie_dias' => 365,
    'sesion_panel_minutos' => 60,
    'sesion_cliente_minutos' => 43200,
    // La demostración completa está habilitada exclusivamente en esta copia.
    'modo_pruebas' => true,
    'niveles' => [
        ['nombre'=>'Explorador','redenciones'=>0],
        ['nombre'=>'Gold','redenciones'=>5],
        ['nombre'=>'Platino','redenciones'=>15],
    ],
];
