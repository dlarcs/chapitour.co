<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Gran&Chela_Club/galeria/galeria/galeria.css';
$jsFile  = $base . '/bar/Gran&Chela_Club/galeria/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../bar/Gran&Chela_Club/galeria/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente</h2>

  <p>
    Un lugar con estilo moderno, luces cálidas, buena música y espacios pensados
    para disfrutar con amigos.
  </p>

  <div class="gallery-grid">
    <img src="../../../bar/Gran&Chela_Club/img/general.jpg" alt="Ambiente de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general1.jpg" alt="Barra de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general2.jpg" alt="Zona social de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general3.jpg" alt="Decoración de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general4.jpg" alt="Luces de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general5.jpg" alt="Espacio para compartir en Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general6.jpg" alt="Interior moderno de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general7.jpg" alt="Ambiente nocturno de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general8.jpg" alt="Zona de bebidas de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general9.jpg" alt="Mesa de Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general10.jpg" alt="Experiencia en Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general11.jpg" alt="Noche especial en Gran&Chelas Club">
    <img src="../../../bar/Gran&Chela_Club/img/general12.jpg" alt="Música en Gran&Chelas Club">

  </div>

</section>

<script defer src="../../../bar/Gran&Chela_Club/galeria/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
