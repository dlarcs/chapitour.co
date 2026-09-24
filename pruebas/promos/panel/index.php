<?php
declare(strict_types=1);
header('Cache-Control: no-store, private');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Panel de promociones | Chapitour</title><link rel="stylesheet" href="../assets/panel.css?v=<?= filemtime(__DIR__.'/../assets/panel.css') ?>"><link rel="stylesheet" href="../assets/prize-card.css?v=<?= filemtime(__DIR__.'/../assets/prize-card.css') ?>"><script type="module" src="../assets/panel.js?v=<?= filemtime(__DIR__.'/../assets/panel.js') ?>"></script></head>
<body>
<header class="topbar"><a class="brand" href="../../">✳ <span>chapitour.co<small>CHAPINERO TE PREMIA</small></span></a><div id="session-actions" hidden><span id="session-name"></span><button type="button" id="password-open" class="subtle">Contraseña</button><button type="button" id="logout" class="subtle">Salir</button></div></header>
<main>
<p id="notice" class="notice" role="status" hidden></p>
<section class="login-card" id="login-section"><span class="eyebrow">ESPACIO PARA ALIADOS</span><h1>Tu negocio.<br><em>Más buenos planes.</em></h1><p>Crea promociones, confirma visitas y conoce los resultados de tu establecimiento.</p><form id="login-form"><label>Usuario<input name="usuario" autocomplete="username" maxlength="100" required></label><label>Contraseña<input name="password" type="password" autocomplete="current-password" maxlength="72" required></label><button class="primary" type="submit">Entrar a mi panel <span>↗</span></button></form><small>Cada establecimiento tiene un acceso privado.</small></section>
<section class="password-card" id="password-section" hidden><span class="eyebrow">SEGURIDAD DE TU CUENTA</span><h1>Actualiza tu contraseña.</h1><p id="password-help">Usa entre 12 y 72 caracteres. La contraseña temporal debe cambiarse antes de ingresar.</p><form id="password-form"><label>Contraseña actual<input name="actual" type="password" autocomplete="current-password" maxlength="72" required></label><label>Nueva contraseña<input name="nueva" type="password" autocomplete="new-password" minlength="12" maxlength="72" required></label><label>Repite la nueva contraseña<input name="repetir" type="password" autocomplete="new-password" minlength="12" maxlength="72" required></label><button class="primary" type="submit">Guardar contraseña</button><button class="subtle" id="password-cancel" type="button">Volver al panel</button></form></section>
<section id="dashboard" hidden>
  <div class="heading-row"><div><span class="eyebrow" id="dashboard-role">TU ACTIVIDAD EN CHAPITOUR</span><h1 id="dashboard-title">Panel de promociones</h1><p>Del primer giro a una visita real.</p></div><button type="button" id="refresh" class="outline">Actualizar ↻</button></div>
  <nav class="tabs" aria-label="Secciones del panel"><button type="button" data-tab="overview" aria-current="page">Resumen y códigos</button><button type="button" data-tab="promotions">Promociones</button><button type="button" data-tab="businesses" id="businesses-tab" hidden>Negocios</button><button type="button" data-tab="admin" id="admin-tab" hidden>Dueños y administración</button></nav>
  <div data-panel="overview">
    <form class="filters" id="filters"><label>Desde<input type="date" name="desde" required></label><label>Hasta<input type="date" name="hasta" required></label><label>Estado<select name="estado"><option value="">Todos</option><option value="activo">Activos</option><option value="redimido">Redimidos</option><option value="vencido">Vencidos</option></select></label><label>Código<input name="buscar" maxlength="40" placeholder="CHAPI-..."></label><button class="outline" type="submit">Filtrar</button></form>
    <div id="metrics" class="metrics"></div>
    <div class="content-grid"><section class="card validation"><span class="eyebrow">EL CLIENTE YA ESTÁ AQUÍ</span><h2>Valida su código</h2><p>Comprueba el premio y confirma la visita cuando se utilice la promoción.</p><form id="lookup-form" class="code-form"><label class="sr-only" for="lookup-code">Código del cliente</label><input id="lookup-code" name="codigo" placeholder="CHAPI-01-…" maxlength="40" required autocomplete="off"><button class="primary" type="submit">Consultar</button></form><div id="lookup-result" hidden></div><p id="owner-redemption-note" class="muted" hidden>La redención la confirma el dueño desde el panel de su negocio.</p><form id="redeem-form" hidden><label class="checkbox"><input type="checkbox" name="confirmado" required>El cliente está presente y utilizó esta promoción.</label><button class="primary" type="submit">✓ Confirmar visita y redimir código</button><small>Este código se usará una sola vez. El visitante recibirá otro giro si la regla está habilitada.</small></form></section><section class="card"><span class="eyebrow">DE LOS GIROS A LAS VISITAS</span><h2>Resultados por negocio</h2><div id="distribution"></div><p class="muted">El periodo corresponde a la fecha de emisión del premio. Las aperturas de WhatsApp no equivalen a mensajes enviados.</p></section></div>
    <section class="card"><div class="heading-row"><h2>Historial de promociones</h2><span id="prize-total" class="muted"></span></div><div class="table-wrap"><table><thead><tr><th>Código</th><th>Negocio / beneficio</th><th>Estado</th><th>Emisión</th><th>Vencimiento</th><th>Redención</th></tr></thead><tbody id="prize-rows"></tbody></table></div><div class="pagination"><button id="previous" type="button" class="subtle">← Anterior</button><span id="page-label"></span><button id="next" type="button" class="subtle">Siguiente →</button></div></section>
  </div>
  <div data-panel="promotions" hidden>
    <div class="heading-row section-heading"><div><h2>Tus promociones</h2><p>Crea ofertas y activa las que quieras incluir en la ruleta. Los códigos entregados conservan su beneficio y vigencia.</p></div><button class="primary" id="promotion-new" type="button">+ Nueva promoción</button></div>
    <div id="promotion-cards" class="business-grid"></div>
  </div>
  <div data-panel="businesses" hidden>
    <div class="heading-row section-heading"><div><h2>Negocios aliados</h2><p>Agrega negocios y configura sus datos de contacto.</p></div><button class="primary" id="business-new" type="button">+ Nuevo negocio</button></div>
    <div id="business-cards" class="business-grid"></div>
  </div>
  <div data-panel="admin" hidden><section class="card"><div class="heading-row"><h2>Dueños de negocios</h2><button type="button" class="primary" id="owner-new">+ Agregar dueño</button></div><p class="muted">Asigna cada dueño a un negocio. Su contraseña temporal se muestra una sola vez y debe cambiarla al entrar. Desactivar un acceso cierra sus sesiones.</p><div id="users-list"></div></section><section class="card"><h2>Reglas de la campaña</h2><p>Así se entregan las promociones a los visitantes.</p><div id="rules-view"></div></section><div class="content-grid"><section class="card"><h2>Actividad registrada</h2><div id="events-list"></div></section><section class="card"><h2>Últimas acciones del panel</h2><div id="audit-list"></div></section></div></div>
</section>
</main>
<footer>Chapitour · Promociones y visitas verificadas por los aliados</footer>
<dialog id="edit-dialog" aria-labelledby="promotion-dialog-title">
  <form id="promotion-form">
    <div class="heading-row"><h2 id="promotion-dialog-title">Nueva promoción</h2><button class="subtle" type="button" data-close="edit-dialog" aria-label="Cerrar promoción">×</button></div>
    <input type="hidden" name="id">
    <label>Negocio<select name="negocio_id" required></select></label>
    <label>Nombre del beneficio<input name="titulo" maxlength="160" placeholder="Ej. 15 % en tu próxima visita" required></label>
    <label>Descripción<textarea name="descripcion" maxlength="500"></textarea></label>
    <label>Condiciones de uso<textarea name="condiciones" maxlength="1000" placeholder="Días, horarios, productos incluidos y restricciones" required></textarea></label>
    <div class="content-grid"><label>Descuento % (opcional)<input name="porcentaje" type="number" min="0.01" max="100" step="0.01"></label><label>Cupo total (opcional)<input name="cupo_total" type="number" min="1" step="1"></label></div>
    <label class="checkbox"><input name="activa" type="checkbox">Activar promoción para la ruleta</label>
    <p class="muted">Sin activar, la oferta queda como borrador o pausada. Pausarla no invalida los códigos ya emitidos.</p>
    <p id="edit-error" class="form-error" role="alert"></p><button class="primary" type="submit">Guardar promoción</button>
  </form>
</dialog>
<dialog id="business-dialog" aria-labelledby="business-dialog-title">
  <form id="business-form">
    <div class="heading-row"><h2 id="business-dialog-title">Nuevo negocio</h2><button class="subtle" type="button" data-close="business-dialog" aria-label="Cerrar negocio">×</button></div>
    <input type="hidden" name="id">
    <label>Nombre del negocio<input name="nombre" maxlength="120" required></label>
    <label>Categoría<input name="categoria" maxlength="80" placeholder="Restaurante, café, bar…" required></label>
    <label>WhatsApp con indicativo de país<input name="whatsapp" maxlength="20" placeholder="573001234567" pattern="[1-9][0-9]{7,14}"></label>
    <label>Dirección<input name="direccion" maxlength="200"></label>
    <label class="checkbox"><input name="activo" type="checkbox" checked>Negocio habilitado</label>
    <p id="business-error" class="form-error" role="alert"></p><button class="primary" type="submit">Guardar negocio</button>
  </form>
</dialog>
<dialog id="owner-dialog" aria-labelledby="owner-dialog-title">
  <div class="heading-row"><h2 id="owner-dialog-title">Agregar dueño</h2><button class="subtle" type="button" data-close="owner-dialog" aria-label="Cerrar dueño">×</button></div>
  <form id="owner-form">
    <label>Nombre de usuario<input name="usuario" autocomplete="off" minlength="3" maxlength="100" pattern="[a-zA-Z0-9._\-]{3,100}" placeholder="Ej. dueno.streetgrill" required></label>
    <label>Negocio asignado<select name="negocio_id" required></select></label>
    <p class="muted">Podrá crear, editar y pausar las promociones de este negocio, además de validar sus códigos.</p>
    <p id="owner-error" class="form-error" role="alert"></p><button class="primary" type="submit">Crear acceso de dueño</button>
  </form>
  <div id="owner-created" hidden role="status"><p>Acceso creado. Guarda estos datos para entregarlos al dueño. La contraseña se muestra una sola vez.</p><p id="owner-created-name"></p><code id="owner-created-password" class="temporary-password"></code><p class="muted">El dueño deberá cambiarla en su primer ingreso.</p></div>
</dialog>
<dialog id="reset-dialog"><h2>Restablecer contraseña</h2><p id="reset-info"></p><p id="reset-error" role="alert"></p><code id="temporary-password" hidden></code><div class="dialog-actions"><button class="subtle" type="button" id="reset-close">Cerrar</button><button class="primary" type="button" id="reset-confirm">Generar contraseña temporal</button></div></dialog>
</body></html>
