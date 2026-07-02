<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.1.bartin/galeria/galeria/galeria.css';
$jsFile  = $base . '/1.1.bartin/galeria/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../1.1.bartin/galeria/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente</h2>

  <p>
    Un lugar con estilo moderno, luces cálidas, buena música y espacios pensados
    para disfrutar con amigos.
  </p>

  <div class="gallery-grid">
    <img src="../../home/img/bar.png" alt="Ambiente del gastrobar">
    <img src="../../home/img/bar.png" alt="Barra del gastrobar">
    <img src="../../home/img/bar.png" alt="Zona social del gastrobar">
    <img src="../../home/img/bar.png" alt="Decoración del gastrobar">
    <img src="../../home/img/bar.png" alt="Luces del gastrobar">
    <img src="../../home/img/bar.png" alt="Espacio para compartir">
    <img src="../../home/img/bar.png" alt="Interior moderno del gastrobar">
    <img src="../../home/img/bar.png" alt="Ambiente nocturno del gastrobar">
    <img src="../../home/img/bar.png" alt="Zona de bebidas del gastrobar">
    <img src="../../home/img/bar.png" alt="Mesa del gastrobar">
    <img src="../../home/img/bar.png" alt="Experiencia en el gastrobar">
  </div>

</section>

<script defer src="../../1.1.bartin/galeria/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
