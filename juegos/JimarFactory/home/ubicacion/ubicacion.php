<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/juegos/JimarFactory/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$googleMapsLink = 'https://www.google.com/maps/search/?api=1&query=Jimar+Factory+Chapinero+Calle+58+%2313-93+Bogota';
?>

<link rel="stylesheet" href="../../juegos/JimarFactory/home/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section visible">
  <div class="business-location-social__inner">

    <div class="business-location" id="ubicacion">
      <span class="section-label">Ubicación</span>

      <h2>Encuentra Jimar Factory en Chapinero</h2>

      <p>
        Visítanos en Chapinero y disfruta de uno de los mejores ambientes de billar
        en Bogotá. Contamos con servicio de billar a tres bandas y pool, bebidas,
        cócteles, café y entretenimiento para mayores de 18 años.
      </p>

      <div class="location-info">
        <p>
          <strong>Dirección:</strong>
          Calle 58 #13-93, Chapinero, Bogotá
        </p>

        <p>
          <strong>Horario:</strong>
          Domingo a domingo, de 9:00 a. m. a 12:00 a. m.
        </p>

        <p>
          <strong>Servicios:</strong>
          Billar a tres bandas, pool, bebidas, cócteles, café y venta de materiales de juego.
        </p>
      </div>

      <a
        class="location-button"
        href="<?= htmlspecialchars($googleMapsLink, ENT_QUOTES, 'UTF-8') ?>"
        target="_blank"
        rel="noopener noreferrer"
      >
        Ver en Google Maps
      </a>
    </div>

    <div class="business-social" id="redes_sociales">
      <span class="section-label">Jimar Factory</span>

      <h2>Billar y entretenimiento en Chapinero</h2>

      <p>
        Disfruta de mesas de tres bandas y pool, variedad de bebidas, cócteles,
        café y una excelente atención los siete días de la semana. También
        encontrarás materiales y accesorios para tus partidas de billar.
      </p>

      <div class="social-links">
        <a href="https://www.instagram.com/jimar_factory_bogota/" class="social-card">
          <span>Instagram</span>
          <small>@jimar_factory_bogot</small>
        </a>

        <a href="https://www.facebook.com/profile.php?id=61577261077969" class="social-card">
          <span>Facebook</span>
          <small>Jimar Factory Bogota</small>
        </a>

        <a href="https://wa.me/573165180649" class="social-card" target="_blank">
          <span>WhatsApp</span>
          <small>Reservas y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>

<script defer src="../../juegos/JimarFactory/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
