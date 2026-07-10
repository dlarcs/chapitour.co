<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Gran&Chela_Club/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/bar/Gran&Chela_Club/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$googleMapsLink = 'https://maps.app.goo.gl/ooTuqaX2yxjp4wMu6'; // Aquí cargas el link real de Google Maps
?>

<link rel="stylesheet" href="../../bar/Gran&Chela_Club/home/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section visible" >
  <div class="business-location-social__inner">

    <div class="business-location" id="ubicacion">
      <span class="section-label">Ubicación</span>

      <h2>Encuéntranos en Chapinero</h2>

      <p>
        Estamos ubicados en una zona estratégica de Chapinero, ideal para
        disfrutar, es seguro y divertido.
      </p>

      <div class="location-info">
        <p>
          <strong>Dirección:</strong>
          Calle 59 #10-24, Chapinero, Bogotá
        </p>

        <p>
          <strong>Horario:</strong>
         Jueves a Sabado · 6:30 p.m. - 4:00 a.m.
        </p>

        <p>
          <strong>WhatsApp:</strong>
          <a href="https://wa.me/573224680419"> 3224680419</a>
        </p>
      </div>

      <a class="location-button" href="<?= $googleMapsLink ?>" target="_blank" rel="noopener">
        Ver en Google Maps
      </a>
    </div>

    <div class="business-social" id="redes_sociales">
      <span class="section-label">Redes sociales</span>

      <h2>Síguenos</h2>

      <p>
        Conoce nuestros eventos, promociones, actividades y momentos
        especiales a través de nuestras redes sociales y página web.
      </p>

      <div class="social-links">
        <a href="https://www.instagram.com/granychela.club/" class="social-card" target="_blank">
          <span>Instagram</span>
          <small>@granychela.club</small>
        </a>

        <a href="https://www.facebook.com/profile.php?id=61591409664220" class="social-card" target="_blank">
          <span>Facebook</span>
          <small>Gran&chela Club.</small>
        </a>



        <a href="https://wa.me/573224680419" class="social-card" target="_blank">
          <span>WhatsApp</span>
          <small>Reservas y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>
<script defer src="../../bar/Gran&Chela_Club/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
