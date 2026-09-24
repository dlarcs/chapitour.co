<?php
declare(strict_types=1);
header('Cache-Control: no-store, private');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Mis promociones | Chapitour</title>
<link rel="stylesheet" href="../assets/panel.css?v=<?= filemtime(__DIR__.'/../assets/panel.css') ?>">
<link rel="stylesheet" href="../assets/prize-card.css?v=<?= filemtime(__DIR__.'/../assets/prize-card.css') ?>">
<link rel="stylesheet" href="../assets/customer.css?v=<?= filemtime(__DIR__.'/../assets/customer.css') ?>">
<script type="module" src="../assets/customer.js?v=<?= filemtime(__DIR__.'/../assets/customer.js') ?>"></script></head>
<body>
<header class="topbar"><a class="brand" href="../../">✳ <span>chapitour.co<small>TUS PLANES. TUS PREMIOS.</small></span></a><a class="subtle" href="../../">Ir a la ruleta ↗</a></header>
<main>
  <p class="preview-note">PRUEBAS · Las promociones de esta vista no son canjeables en los negocios reales.</p>
  <p id="customer-notice" class="notice" role="status" hidden></p>
  <section class="customer-hero"><div><span class="eyebrow" id="customer-name">TU ESPACIO EN CHAPITOUR</span><h1>Los buenos planes<br><em>se quedan contigo.</em></h1><p>Tus códigos, su vigencia y cada visita que confirma un negocio. Todo en un solo lugar.</p></div><button class="outline" id="customer-refresh" type="button">Actualizar ↻</button></section>
  <section class="account-strip"><div><h2 id="account-title">Explora sin registro</h2><p id="account-description">Guardamos tus promociones en este navegador. Crea una cuenta para conservarlas y consultarlas desde otros dispositivos.</p></div><div class="account-actions"><button class="primary" id="register-open" type="button">Guardar mi historial</button><button class="subtle" id="login-open" type="button">Ya tengo cuenta</button><button class="subtle" id="customer-logout" type="button" hidden>Cerrar sesión</button></div></section>
  <section class="journey-card"><div class="heading-row"><div><span class="eyebrow">TU CAMINO POR CHAPINERO</span><h2 id="customer-level">Explorador</h2></div><span class="journey-tag">RUTA EN PRUEBA</span></div><p id="level-description"></p><div class="level-track" id="level-track"></div><p class="muted">Metas de demostración. Los nombres de los niveles y sus beneficios todavía están por definir.</p></section>
  <div class="customer-challenges">
    <section class="card"><span class="eyebrow">VUELVE A DESCUBRIR</span><h2>Un giro cada 5 visitas</h2><p id="visit-progress"></p><p id="available-spins" class="challenge-count"></p><a class="outline" href="../../">Ver la ruleta →</a></section>
    <section class="card"><span class="eyebrow">ARMA TU PARCHE</span><h2 id="referral-title">Invita a 8 personas</h2><p id="referral-progress"></p><p class="muted">Cada persona abre tu enlace, espera 10 segundos y confirma su visita. No necesitas compartir tus códigos.</p><div class="account-actions"><a class="primary" id="customer-invite" target="_blank" rel="noopener noreferrer">Invitar por WhatsApp ↗</a><button class="subtle" id="customer-copy-invite" type="button">Copiar enlace</button></div></section>
  </div>
  <section aria-labelledby="history-title"><div class="heading-row"><h2 id="history-title">Mis promociones</h2><span id="customer-total" class="muted"></span></div><nav class="customer-filters" aria-label="Filtrar mis códigos"><button type="button" data-status="" aria-pressed="true">Todas <strong id="count-total">0</strong></button><button type="button" data-status="activo" aria-pressed="false">Activos <strong id="count-activos">0</strong></button><button type="button" data-status="redimido" aria-pressed="false">Redimidos <strong id="count-redimidos">0</strong></button><button type="button" data-status="vencido" aria-pressed="false">Vencidos <strong id="count-vencidos">0</strong></button></nav><div id="customer-prizes" class="customer-prizes" aria-live="polite"><p class="empty-state">Cargando tus promociones…</p></div><div class="pagination"><button class="subtle" id="customer-previous" type="button">← Anterior</button><span id="customer-page"></span><button class="subtle" id="customer-next" type="button">Siguiente →</button></div></section>
</main>
<footer>Chapitour · Los códigos vencen 72 horas después del giro · Solo el negocio confirma la redención</footer>
<dialog id="customer-auth" aria-labelledby="auth-title"><div class="heading-row"><h2 id="auth-title">Guarda tus promociones</h2><button class="subtle" id="auth-close" type="button" aria-label="Cerrar acceso">×</button></div><p id="auth-description">Al crear tu cuenta vincularemos las promociones de este navegador. No perderás tus códigos.</p><form id="customer-auth-form"><label id="auth-name-label">Tu nombre<input name="nombre" autocomplete="name" maxlength="120" required></label><label>Correo electrónico<input name="email" type="email" autocomplete="email" maxlength="190" required></label><label>Contraseña<input name="password" type="password" autocomplete="new-password" minlength="12" maxlength="72" required></label><label id="auth-repeat-label">Repite la contraseña<input name="repetir" type="password" autocomplete="new-password" minlength="12" maxlength="72" required></label><p id="auth-error" class="form-error" role="alert"></p><button class="primary" id="auth-submit" type="submit">Crear cuenta y guardar historial</button></form></dialog>
</body></html>
