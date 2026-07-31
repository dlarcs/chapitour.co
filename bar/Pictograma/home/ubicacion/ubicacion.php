<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/bar/Pictograma/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$address = 'Calle 59 #13-20, Chapinero, Bogotá';
$googleMapsLink = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);

$whatsappNumber = '573138846378';
$whatsappText = urlencode('Hola, vengo desde Chapitour y quiero recibir más información sobre Pictogramas Cafe Bar.');
$whatsappLink = "https://wa.me/{$whatsappNumber}?text={$whatsappText}";
?>

<link rel="stylesheet" href="../../bar/Pictograma/home/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section visible">
  <div class="business-location-social__inner">

    <div class="business-location" id="ubicacion">
      <span class="section-label">Ubicación</span>

      <h2>Encuéntranos en Chapinero</h2>

      <p>
        Visita Pictogramas Cafe Bar y disfruta un ambiente agradable para compartir,
        tomar cócteles, micheladas, cervezas, café y divertirte jugando bolirana.
      </p>

      <div class="location-info">
        <p>
          <strong>Dirección:</strong>
          <?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
          <strong>Horario:</strong>
          Lunes a Domingo 11:00am a 3:00pm
        </p>

        <p>
          <strong>WhatsApp:</strong>
          <a href="<?= $whatsappLink ?>" target="_blank" rel="noopener noreferrer">
            313 884 6378
          </a>
        </p>
      </div>

      <a class="location-button" href="<?= $googleMapsLink ?>" target="_blank" rel="noopener noreferrer">
        Ver en Google Maps
      </a>
    </div>

    <div class="business-social" id="redes_sociales">
      <span class="section-label">Redes sociales</span>

      <h2>Síguenos</h2>

      <p>
        Conoce nuestras bebidas, promociones, actividades y momentos especiales
        a través de las redes sociales de Pictogramas Cafe Bar.
      </p>

      <div class="social-links">
        <a href="#" class="social-card social-card--disabled" aria-disabled="true" onclick="return false;">
          <span>Instagram</span>
          <small>Usuario próximamente</small>
        </a>

        <a href="#" class="social-card social-card--disabled" aria-disabled="true" onclick="return false;">
          <span>TikTok</span>
          <small>Usuario próximamente</small>
        </a>

        <a href="<?= $whatsappLink ?>" class="social-card" target="_blank" rel="noopener noreferrer">
          <span>WhatsApp</span>
          <small>Información y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>

<script defer src="../../bar/Pictograma/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
