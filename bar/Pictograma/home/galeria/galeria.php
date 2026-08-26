<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/home/galeria/galeria.css';
$jsFile  = $base . '/bar/Pictograma/home/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/Pictograma/home/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente de Pictogramas Café Bar</h2>

  <p>
    Un espacio agradable para compartir con amigos, disfrutar cócteles, granizados,
    licores, micheladas, bolivianas, cervezas y café, mientras te diviertes jugando bolirana.
  </p>

  <div class="gallery-grid">
    <img src="../../bar/Pictograma/img/general21.png" alt="Ambiente de Pictogramas Cafe Bar">
    <img src="../../bar/Pictograma/img/general17.png" alt="Bar y zona de bebidas de Pictogramas Cafe Bar">
    <img src="../../bar/Pictograma/img/general22.png" alt="Espacio para compartir en Pictogramas Cafe Bar" class="gallery-img-mobile-hide">
  </div>

  <a href="../../bar/Pictograma/reservas/index.php">
    <div class="button_container">
      <button class="btn btn30" type="button" name="button">Ver galería</button>
    </div>
  </a>
</section>

<script defer src="../../bar/Pictograma/home/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
