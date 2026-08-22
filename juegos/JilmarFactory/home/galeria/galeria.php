<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JilmarFactory/home/galeria/galeria.css';
$jsFile  = $base . '/juegos/JilmarFactory/home/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../juegos/JilmarFactory/home/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce Garage Disco Bar</h2>

  <p>
    Un disco bar y gastrobar LGBTIQ+ en Chapinero, con música, luces, rumba,
    comida, bebidas y un ambiente diverso para compartir con libertad, respeto y buena energía.
  </p>

  <div class="gallery-grid">
    <img src="../../juegos/JilmarFactory/img/general3.jpg" alt="Ambiente de Capital Queer">
    <img src="../../juegos/JilmarFactory/img/general17.jpg" alt="Bar de Capital Queer">
    <img src="../../juegos/JilmarFactory/img/general15.jpg" alt="Espacio social de Capital Queer" class="gallery-img-mobile-hide">
  </div>

  <a href="../../juegos/JilmarFactory/galeria/index.php">
    <div class="button_container">
      <button class="btn btn30" type="button" name="button">Ver galería</button>
    </div>
  </a>
</section>

<script defer src="../../juegos/JilmarFactory/home/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
