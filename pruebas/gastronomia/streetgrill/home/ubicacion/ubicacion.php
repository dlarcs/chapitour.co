<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/GarageDiscoBar/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/gastrobar/GarageDiscoBar/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$googleMapsLink = 'https://maps.app.goo.gl/EnKkPuRb64VNMnQ77';
?>

<link rel="stylesheet" href="../../gastrobar/GarageDiscoBar/home/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section visible">
  <div class="business-location-social__inner">

    <div class="business-location" id="ubicacion">
      <span class="section-label">Ubicación</span>

      <h2>Encuéntranos en Chapinero</h2>

      <p>
        Visítanos en Chapinero y disfruta una experiencia de gastrobar enfocada
        en carnes al barril, preparaciones ahumadas, parrilla y sabores artesanales
        en un ambiente urbano y cercano.
      </p>

      <div class="location-info">
        <p>
          <strong>Dirección:</strong>
          Cra. 9 #57-85, Chapinero, Bogotá
        </p>

        <p>
          <strong>Horario:</strong>
          Consulta nuestros horarios de atención en nuestros canales oficiales.
        </p>

        <p>
          <strong>WhatsApp:</strong>
          <a href="https://wa.me/573143580355">3143580355</a>
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
        Descubre nuestras carnes al barril, hamburguesas artesanales,
        preparaciones ahumadas, bebidas y momentos de Street Grill
        a través de nuestras redes sociales.
      </p>

      <div class="social-links">
        <a href="https://www.instagram.com/streetgrillbbq/" class="social-card">
          <span>Instagram</span>
          <small>Street Grill</small>
        </a>

        <a href="https://www.facebook.com/street.grill.2025" class="social-card">
          <span>Facebook</span>
          <small>Street Grill</small>
        </a>

        <a href="https://wa.me/573143580355" class="social-card" target="_blank">
          <span>WhatsApp</span>
          <small>Información y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>

<script defer src="../../gastrobar/GarageDiscoBar/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
