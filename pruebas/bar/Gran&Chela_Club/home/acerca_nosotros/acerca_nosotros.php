<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Gran&Chela_Club/home/acerca_nosotros/acerca_nosotros.css';
$jsFile  = $base . '/bar/Gran&Chela_Club/home/acerca_nosotros/acerca_nosotros.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/Gran&Chela_Club/home/acerca_nosotros/acerca_nosotros.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-about visible" id="acerca_nosotros">
  <div class="about-text">
    <span class="section-label">Acerca de nosotros</span>

    <h2>Bar, discoteca y eventos en un solo lugar</h2>

    <p>
      Gran&Chela Club es un bar y discoteca en Chapinero, pensado para disfrutar
      la noche con buena música, baile, licores, cervezas, micheladas y un ambiente
      alegre para compartir con amigos.
    </p>

    <p>
      Aquí puedes vivir noches de karaoke, salsa, rumba y promociones especiales
      según el día. Es un espacio creado para cantar, bailar, brindar y pasar
      momentos inolvidables con buena energía.
    </p>

    <p>
      Además, Gran&Chela Club presta su espacio para eventos privados,
      cumpleaños, celebraciones, reuniones, fiestas y planes especiales con
      reserva previa.
    </p>

    <p>
      Hacemos parte de Chapitour.co para conectar con más personas, dar a conocer
      los mejores planes de Chapinero y seguir fortaleciendo el comercio local.
    </p>
  </div>

  <img src="../../bar/Gran&Chela_Club/img/logo_lema.jpg" alt="Ambiente de bar y discoteca en Gran&Chela Club">

</section>

<a href="../../bar/Gran&Chela_Club/reservas/index.php">
  <div class="button_container">
    <button class="btn btn30" type="button" name="button">Reservas y eventos</button>
  </div>
</a>

<script defer src="../../bar/Gran&Chela_Club/home/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
