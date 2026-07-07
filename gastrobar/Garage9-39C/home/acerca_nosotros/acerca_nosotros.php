<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/Garage9-39C/home/acerca_nosotros/acerca_nosotros.css';
$jsFile  = $base . '/gastrobar/Garage9-39C/home/acerca_nosotros/acerca_nosotros.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../gastrobar/Garage9-39C/home/acerca_nosotros/acerca_nosotros.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-about visible" id="acerca_nosotros">
  <div class="about-text">
    <span class="section-label">Acerca de nosotros</span>
    <h2>Un disco bar y gastrobar libre, diverso y seguro</h2>

    <p>
      Garage 9-39C es un espacio LGBTIQ+ en Chapinero creado para disfrutar la noche
      con música, rumba, comida, bebidas y un ambiente lleno de respeto, libertad y buena energía.
    </p>

    <p>
      Hacemos parte de Chapitour.co para conectar con más personas, visibilizar
      espacios diversos en Chapinero y seguir fortaleciendo el comercio local.
    </p>
  </div>

  <img src="../../gastrobar/Garage9-39C/img/general11.jpg" alt="Experiencia en Capital Queer">

</section>

<a href="../../gastrobar/Garage9-39C/reservas/index.php">
  <div class="button_container">
    <button class="btn btn30" type="button" name="button">Reservas</button>
  </div>
</a>

<script defer src="../../gastrobar/Garage9-39C/home/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
