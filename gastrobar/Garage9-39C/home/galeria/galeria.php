<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/Garage9-39C/home/galeria/galeria.css';
$jsFile  = $base . '/gastrobar/Garage9-39C/home/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../gastrobar/Garage9-39C/home/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente de Capital Queer</h2>

  <p>
    Un espacio diverso, libre y seguro, pensado especialmente para mujeres,
    con música, luces, encuentros y momentos para compartir con respeto y buena energía.
  </p>

  <div class="gallery-grid">
    <img src="../../gastrobar/Garage9-39C/img/general11.jpg" alt="Ambiente de Capital Queer">
    <img src="../../gastrobar/Garage9-39C/img/general17.jpg" alt="Bar de Capital Queer">
    <img src="../../gastrobar/Garage9-39C/img/general15.jpg" alt="Espacio social de Capital Queer" class="gallery-img-mobile-hide">
  </div>

  <a href="../../gastrobar/Garage9-39C/galeria/index.php">
    <div class="button_container">
      <button class="btn btn30" type="button" name="button">Ver galería</button>
    </div>
  </a>
</section>

<script defer src="../../gastrobar/Garage9-39C/home/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
