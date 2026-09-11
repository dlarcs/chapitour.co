<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastronomia/streetgrill/galeria/galeria/galeria.css';
$jsFile  = $base . '/gastronomia/streetgrill/galeria/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastronomia/streetgrill/galeria/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Descubre Street Grill</h2>

  <p>
    Conoce un espacio donde el humo, la parrilla y el sabor al barril se encuentran
    en una experiencia gastronómica ideal para compartir entre amigos.
  </p>

  <div class="gallery-grid">
    <img src="../../../gastronomia/streetgrill/img/general.jpeg" alt="Ambiente de Street Grill en Chapinero">
    <img src="../../../gastronomia/streetgrill/img/general1.jpeg" alt="Gastrobar Street Grill en Bogotá">
    <img src="../../../gastronomia/streetgrill/img/general2.jpeg" alt="Espacio para compartir en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general3.jpeg" alt="Ambiente urbano de Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general4.jpeg" alt="Experiencia gastronómica en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general5.jpeg" alt="Espacio para disfrutar carnes al barril en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general6.jpeg" alt="Interior de Street Grill en Chapinero">
    <img src="../../../gastronomia/streetgrill/img/general7.jpeg" alt="Ambiente de gastrobar en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general8.jpeg" alt="Bebidas y gastronomía en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general9.jpeg" alt="Mesa para compartir en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general10.jpeg" alt="Experiencia de carne al barril en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general11.jpeg" alt="Carne ahumada y sabor artesanal en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general12.jpeg" alt="Parrilla y carnes ahumadas en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general13.jpeg" alt="Momento para compartir en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general14.jpeg" alt="Bebidas para acompañar la comida en Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general15.jpeg" alt="Amigos disfrutando la experiencia Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general16.jpeg" alt="Gastrobar de carnes al barril Street Grill">
    <img src="../../../gastronomia/streetgrill/img/general17.jpeg" alt="Street Grill gastrobar de carnes en Chapinero">
  </div>

</section>

<script defer src="../../../gastronomia/streetgrill/galeria/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
