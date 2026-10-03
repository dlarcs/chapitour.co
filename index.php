<?php
declare(strict_types=1);
header('Cache-Control: no-store');
header('Cross-Origin-Opener-Policy: same-origin-allow-popups');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://accounts.google.com/gsi/client; style-src 'self' 'unsafe-inline' https://accounts.google.com/gsi/style; img-src 'self' data: blob:; connect-src 'self' https://accounts.google.com/gsi/; frame-src https://accounts.google.com/gsi/; base-uri 'self'; form-action 'self'; frame-ancestors 'self'");
?>
<!doctype html>
<html lang="es-CO">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#080a12">
  <meta name="description" content="Descubre Chapinero, Bogotá: bares, gastronomía y experiencias. Participa en los retos de Chapitour y gira la ruleta para obtener promociones de nuestros aliados.">
  <meta name="robots" content="index,follow,max-image-preview:large">
  <link rel="canonical" href="https://chapitour.co/">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Chapitour te premia · Descubre Chapinero">
  <meta property="og:description" content="Explora lugares, participa en retos y descubre promociones de los negocios de Chapinero.">
  <meta property="og:url" content="https://chapitour.co/">
  <meta property="og:image" content="https://chapitour.co/home/img/letrero_bogota.png">
  <meta name="twitter:card" content="summary_large_image">
  <title>Chapitour te premia · Lugares y experiencias en Chapinero, Bogotá</title>
  <link rel="icon" href="/premia/assets/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="/premia/assets/app.css?v=20261003-accesos-1">
  <script defer src="/premia/assets/app.js?v=20261003-accesos-1"></script>
</head>
<body>
  <a class="skip-link" href="#main">Saltar al contenido</a>
  <div id="app"><main id="main" class="loading"><span class="loader"></span><h1>Chapitour te premia</h1><p>Descubre lugares y experiencias en Chapinero. Preparando tu próximo plan…</p></main></div>
  <dialog id="modal" aria-labelledby="modal-title"></dialog>
  <div id="toast" role="status" aria-live="polite"></div>
  <noscript>Activa JavaScript para consultar tus retos, promociones y la comunidad de Chapitour.</noscript>
</body>
</html>
