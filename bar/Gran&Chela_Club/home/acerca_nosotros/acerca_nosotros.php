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

    <h2>Un club para bailar, tomar y celebrar</h2>

    <p>
      Gran&Chela Club es un bar y discoteca LGBTIQ+ en Chapinero, creado para
      disfrutar la noche con libertad, música, baile, buenas bebidas y un ambiente
      diverso, alegre y seguro. Es un espacio donde puedes compartir con amigos,
      celebrar fechas especiales y vivir una experiencia llena de energía.
    </p>

    <p>
      Además de ser un lugar para bailar y tomar, también prestamos el espacio
      para eventos privados, celebraciones, reuniones y planes especiales. Contamos
      con promociones según el día, para que cada visita tenga algo diferente por
      descubrir.
    </p>

    <p>
      Hacemos parte de Chapitour.co para conectar con más personas, visibilizar
      espacios diversos en Chapinero y seguir fortaleciendo el comercio local.
    </p>
  </div>

  <img src="../../bar/Gran&Chela_Club/img/general18.jpg" alt="Experiencia en Gran&Chela Club">

</section>

<a href="../../bar/Gran&Chela_Club/reservas/index.php">
  <div class="button_container">
    <button class="btn btn30" type="button" name="button">Reservas y eventos</button>
  </div>
</a>

<script defer src="../../bar/Gran&Chela_Club/home/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
