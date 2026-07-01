<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.1.bartin/home/galeria/galeria.css';
$jsFile  = $base . '/1.1.bartin/home/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../1.1.bartin/home/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">
<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente</h2>

  <p>
    Un lugar con estilo moderno, luces cálidas, buena música y espacios pensados
    para disfrutar con amigos.
  </p>

  <div class="gallery-grid">
    <!-- <img src="../home/img/sofa.png" alt="Interior del gastrobar"> -->
    <img src="../home/img/bar.png" alt="Barra del gastrobar">
    <img src="../home/img/bar.png" alt="Barra del gastrobar">
    <img src="../home/img/bar.png" alt="Barra del gastrobar" style="@media (max-width: 820px) {display:none;">

  </div>
  <a href="../1.1.bartin/home/pag_galeria/index.php">
    <div class="button_container">
      <button class="btn btn30"type="button" name="button">Ver galería</button>
    </div>
  </a>
</section>
<script defer src="../1.1.bartin/home/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
