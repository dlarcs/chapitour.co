<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/Garage9-39C/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/gastrobar/Garage9-39C/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$googleMapsLink = 'https://maps.app.goo.gl/NToBxLE8EheaadzY6';
?>

<link rel="stylesheet" href="../../gastrobar/Garage9-39C/home/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section visible">
  <div class="business-location-social__inner">

    <div class="business-location" id="ubicacion">
      <span class="section-label">Ubicación</span>

      <h2>Encuéntranos en Chapinero</h2>

      <p>
        Estamos ubicados en una zona estratégica de Chapinero, ideal para disfrutar
        un ambiente LGBTIQ+, seguro, diverso y divertido, con música, rumba,
        comida, bebidas y buena energía.
      </p>

      <div class="location-info">
        <p>
          <strong>Dirección:</strong>
          Calle 59 #9-39, Chapinero, Bogotá
        </p>

        <p>
          <strong>Horario:</strong>
            5:00 p.m. - 5:00 a.m.
        </p>

        <p>
          <strong>WhatsApp:</strong>
          <a href="https://wa.me/573156175056">3156175056</a>
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
        Conoce nuestros eventos, promociones, actividades, noches especiales
        y momentos destacados a través de nuestras redes sociales y página web.
      </p>

      <div class="social-links">
        <a href="#" class="social-card">
          <span>Instagram</span>
          <small>@garage939c</small>
        </a>

        <a href="#" class="social-card">
          <span>Facebook</span>
          <small>Garage 9-39C</small>
        </a>

        <a href="#" class="social-card">
          <span>TikTok</span>
          <small>@garage939c</small>
        </a>

        <a href="https://wa.me/573156175056" class="social-card" target="_blank">
          <span>WhatsApp</span>
          <small>Reservas y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>

<script defer src="../../gastrobar/Garage9-39C/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
