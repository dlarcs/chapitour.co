<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.1.bartin/ubicacion/ubicacion.css';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';

$googleMapsLink = 'https://www.google.com/maps'; // Aquí cargas el link real de Google Maps
?>

<link rel="stylesheet" href="../1.1.bartin/ubicacion/ubicacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-location-social business-section">
  <div class="business-location-social__inner">

    <div class="business-location">
      <span class="section-label">Ubicación</span>

      <h2>Encuéntranos en Chapinero</h2>

      <p>
        Estamos ubicados en una zona estratégica de Chapinero, ideal para
        disfrutar comida rápida, cocteles, música y un buen ambiente con amigos.
      </p>

      <div class="location-info">
        <p>
          <strong>Dirección:</strong>
          Calle 00 #00-00, Chapinero, Bogotá
        </p>

        <p>
          <strong>Horario:</strong>
          Miércoles a domingo · 4:00 p.m. - 1:00 a.m.
        </p>

        <p>
          <strong>WhatsApp:</strong>
          <a href="https://wa.me/573138846378"> 313 334 6378</a>
        </p>
      </div>

      <a class="location-button" href="<?= $googleMapsLink ?>" target="_blank" rel="noopener">
        Ver en Google Maps
      </a>
    </div>

    <div class="business-social">
      <span class="section-label">Redes sociales</span>

      <h2>Síguenos</h2>

      <p>
        Conoce nuestros eventos, promociones, platos destacados y momentos
        especiales a través de nuestras redes sociales.
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

        <a href="https://wa.me/573138846378" class="social-card">
          <span>WhatsApp</span>
          <small>Reservas y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>
