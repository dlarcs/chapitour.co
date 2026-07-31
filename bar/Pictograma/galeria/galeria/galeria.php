<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/galeria/galeria/galeria.css';
$jsFile  = $base . '/bar/Pictograma/galeria/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../bar/Pictograma/galeria/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente de Pictogramas</h2>

  <p>
    Disfruta un ambiente agradable para compartir con amigos, tomar cócteles,
    granizados, micheladas, cervezas o café y divertirte jugando bolirana.
  </p>

  <div class="gallery-grid">
    <img src="../../../bar/Pictograma/img/general.png" alt="Ambiente de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general1.png" alt="Barra de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general2.png" alt="Zona social de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general3.png" alt="Decoración de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general4.png" alt="Ambiente iluminado de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general5.png" alt="Espacio para compartir en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general6.png" alt="Interior de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general7.png" alt="Ambiente nocturno de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general8.png" alt="Zona de bebidas de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general9.png" alt="Mesas de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general10.png" alt="Experiencia en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general11.png" alt="Noche especial en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general12.png" alt="Música y diversión en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general13.png" alt="Celebración en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general14.png" alt="Cócteles de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general15.png" alt="Encuentro entre amigos en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general16.png" alt="Ambiente para compartir en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general17.jpg" alt="Espacio agradable de Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general18.jpg" alt="Diversión en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general19.png" alt="Evento especial en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general20.png" alt="Plan para disfrutar en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general21.png" alt="Compartir bebidas en Pictogramas Cafe Bar">
    <img src="../../../bar/Pictograma/img/general22.png" alt="Experiencia en Pictogramas Cafe Bar">
  </div>
</section>

<script defer src="../../../bar/Pictograma/galeria/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
