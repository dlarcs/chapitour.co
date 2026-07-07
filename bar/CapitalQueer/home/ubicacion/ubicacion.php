<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/CapitalQueer/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/bar/CapitalQueer/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$googleMapsLink = 'https://maps.app.goo.gl/NToBxLE8EheaadzY6'; // Aquí cargas el link real de Google Maps
?>

<link rel="stylesheet" href="../../bar/CapitalQueer/home/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section visible" >
  <div class="business-location-social__inner">

    <div class="business-location" id="ubicacion">
      <span class="section-label">Ubicación</span>

      <h2>Encuéntranos en Chapinero</h2>

      <p>
        Estamos ubicados en una zona estratégica de Chapinero, ideal para
        disfrutar un ambiente Queer, es seguro y divertido.
      </p>

      <div class="location-info">
        <p>
          <strong>Dirección:</strong>
          Carrera 9 #59-38, Chapinero, Bogotá
        </p>

        <p>
          <strong>Horario:</strong>
         Jueves a Sabado · 6:30 p.m. - 4:00 a.m.
        </p>

        <p>
          <strong>WhatsApp:</strong>
          <a href="https://wa.me/573007795016"> 3007795016</a>
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
        <a href="#" class="social-card">
          <span>Instagram</span>
          <small>@distritogastrobar</small>
        </a>

        <a href="#" class="social-card">
          <span>Facebook</span>
          <small>Distrito Gastrobar</small>
        </a>

        <a href="#" class="social-card">
          <span>TikTok</span>
          <small>@distritogastrobar</small>
        </a>

        <a href="https://wa.me/573007795016" class="social-card">
          <span>WhatsApp</span>
          <small>Reservas y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>
<script defer src="../../bar/CapitalQueer/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
