<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/GarageDiscoBar/galeria/galeria/galeria.css';
$jsFile  = $base . '/gastrobar/GarageDiscoBar/galeria/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastrobar/GarageDiscoBar/galeria/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente</h2>

  <p>
    Un lugar con estilo moderno, luces cálidas, buena música y espacios pensados
    para disfrutar con amigos.
  </p>

  <div class="gallery-grid">
    <img src="../../../gastrobar/GarageDiscoBar/img/general.jpg" alt="Ambiente de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general1.jpg" alt="Barra de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general2.jpg" alt="Zona social de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general3.jpg" alt="Decoración de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general4.jpg" alt="Luces de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general5.jpg" alt="Espacio para compartir en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general6.jpg" alt="Interior moderno de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general7.jpg" alt="Ambiente nocturno de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general8.jpg" alt="Zona de bebidas de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general9.jpg" alt="Mesa de Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general10.jpg" alt="Experiencia en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general11.jpg" alt="Noche especial en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general12.jpg" alt="Música en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general13.jpg" alt="Celebración en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general14.jpg" alt="Cocteles en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general15.jpg" alt="Encuentro entre amigas en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general16.jpg" alt="Ambiente queer en Capital Queer">
    <img src="../../../gastrobar/GarageDiscoBar/img/general17.jpg" alt="Espacio seguro en Capital Queer">

  </div>

</section>

<script defer src="../../../gastrobar/GarageDiscoBar/galeria/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
