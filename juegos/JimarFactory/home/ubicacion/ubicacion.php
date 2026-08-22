<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/juegos/JimarFactory/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$googleMapsLink = 'https://maps.app.goo.gl/EnKkPuRb64VNMnQ77';
?>

<link rel="stylesheet" href="../../juegos/JimarFactory/home/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section visible">
  <div class="business-location-social__inner">

    <div class="business-location" id="ubicacion">
      <span class="section-label">Ubicación</span>

      <h2>Encuéntranos en Chapinero</h2>

      <p>
        Estamos ubicados en una zona estratégica de Chapinero, ideal para disfrutar
        un ambiente de gastrobar, seguro, diverso y divertido, con música, rumba,
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
        <a href="https://www.instagram.com/garagediscobar_?igsh=MXI2YTFuZ2JlMXpsMA%3D%3D" class="social-card">
          <span>Instagram</span>
          <small>@Luis Rios</small>
        </a>

        <a href="https://www.facebook.com/GARAGECHAPINERO9.34?mibextid=wwXIfr&rdid=nNeIixoVCmRKpngW&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F191aEZi498%2F%3Fmibextid%3DwwXIfr" class="social-card">
          <span>Facebook</span>
          <small>Luis Rios</small>
        </a>

        <a href="https://wa.me/573156175056" class="social-card" target="_blank">
          <span>WhatsApp</span>
          <small>Reservas y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>

<script defer src="../../juegos/JimarFactory/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
