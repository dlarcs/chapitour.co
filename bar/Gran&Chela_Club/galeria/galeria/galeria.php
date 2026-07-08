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
    <img src="../../../bar/Gran&Chela_Club/img/general.png" alt="Ambiente de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general1.png" alt="Barra de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general2.png" alt="Zona social de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general3.png" alt="Decoración de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general4.png" alt="Luces de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general5.png" alt="Espacio para compartir en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general6.png" alt="Interior moderno de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general7.png" alt="Ambiente nocturno de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general8.png" alt="Zona de bebidas de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general9.png" alt="Mesa de Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general10.png" alt="Experiencia en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general11.png" alt="Noche especial en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general12.png" alt="Música en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general13.png" alt="Celebración en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general14.png" alt="Cocteles en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general15.png" alt="Encuentro entre amigas en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general16.png" alt="Ambiente queer en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general17.jpg" alt="Espacio seguro en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general18.jpg" alt="Fiesta en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general19.png" alt="Evento especial en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general20.png" alt="Noche de baile en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general21.png" alt="Compartir en Capital Queer">
    <img src="../../../bar/Gran&Chela_Club/img/general22.png" alt="Experiencia nocturna en Capital Queer">
  </div>

</section>

<script defer src="../../../bar/Gran&Chela_Club/galeria/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
