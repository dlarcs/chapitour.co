<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/CapitalQueer/home/ubicacion/ubicacion.css';
$jsFile  = $base . '/bar/CapitalQueer/home/ubicacion/ubicacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';

$googleMapsLink = 'https://maps.app.goo.gl/7kCxVj9r8EvT7BT27'; // Aquí cargas el link real de Google Maps
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
        <a href="https://www.instagram.com/capital_queer_/" class="social-card" target="_blank">
          <span>Instagram</span>
          <small>@capital_queer_</small>
        </a>



        <a href="https://www.tiktok.com/@capital.queer?_d=secCgYIASAHKAESPgo8Iuy3wUZbl%2FeVIGA1O%2By5QbEqi4jzSw3Vs3OjNkvTKFqAqEq3W953e38no%2BpcrkKNzA9ovt2pJmM5uLPoGgA%3D&_r=1&_svg=1&checksum=8abc37b340f1266ee4e24e77435b6570270be6926e609f2606a884816c1ab6df&lang=es&reflow_sign_scene=7&rgssign=8.1.3BCSPW6Bh9Po_Bm9NIOmwA&sec_uid=MS4wLjABAAAAVOk7FAKCVsUFU6Zo6BMB18vdI6Y-gsT-_Hjp2XynCfgYAsJ7tsoXGmqx460a_8po&sec_user_id=MS4wLjABAAAAVOk7FAKCVsUFU6Zo6BMB18vdI6Y-gsT-_Hjp2XynCfgYAsJ7tsoXGmqx460a_8po&share_app_id=1340&share_author_id=7618361413047911445&share_link_id=17d31ed6-860a-418e-8401-47f7319b66f4&share_region=CO&share_scene=1&sharer_language=es&source=h5_m&timestamp=1783641712&u_code=f2efea0ak5lcad&ugbiz_name=Account&user_id=7618361413047911445&utm_campaign=client_share&utm_source=whatsapp" class="social-card">
          <span>TikTok</span>
          <small>@Capital Queer</small>
        </a>

        <a href="https://wa.me/573007795016" class="social-card" target="_blank">
          <span>WhatsApp</span>
          <small>Reservas y contacto</small>
        </a>
      </div>
    </div>

  </div>
</section>
<script defer src="../../bar/CapitalQueer/home/ubicacion/ubicacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
