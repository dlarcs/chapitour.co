<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/home/galeria/galeria.css';
$jsFile  = $base . '/juegos/JimarFactory/home/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../juegos/JimarFactory/home/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce Jimar Factory Chapinero</h2>

  <p>
    Disfruta del mejor ambiente de billar en Bogotá, con mesas de tres bandas y
    pool, variedad de bebidas, cócteles, café y entretenimiento para mayores de
    18 años. Estamos ubicados en la calle 58 #13-93 y atendemos todos los días,
    de 9:00 a. m. a 12:00 a. m.
  </p>

  <div class="gallery-grid">
    <img
      src="../../juegos/JimarFactory/img/general6.jpeg"
      alt="Mesas de billar en Jimar Factory Chapinero"
    >

    <img
      src="../../juegos/JimarFactory/img/general26.jpeg"
      alt="Ambiente de Jimar Factory Chapinero"
    >

    <img
      src="../../juegos/JimarFactory/img/general38.jpeg"
      alt="Zona social y de bebidas de Jimar Factory Chapinero"
      class="gallery-img-mobile-hide"
    >
  </div>

  <a href="../../juegos/JimarFactory/galeria/index.php">
    <div class="button_container">
      <button class="btn btn30" type="button" name="button">
        Ver galería
      </button>
    </div>
  </a>
</section>

<script defer src="../../juegos/JimarFactory/home/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
