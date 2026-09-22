<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/galeria/galeria/galeria.css';
$jsFile  = $base . '/juegos/JimarFactory/galeria/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../juegos/JimarFactory/galeria/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-gallery visible">
  <span class="section-label">Galería</span>

  <h2>Conoce el ambiente de Jimar Factory Chapinero</h2>

  <p>
    Disfruta del mejor ambiente de billar en Bogotá, con mesas de tres bandas y
    pool, bebidas, cócteles, café y entretenimiento para mayores de 18 años.
    Visítanos en la calle 58 #13-93, de domingo a domingo, de 9:00 a. m. a
    12:00 a. m.
  </p>

  <div class="gallery-grid">
    <img
      src="../../../juegos/JimarFactory/img/general.jpeg"
      alt="Ambiente de Jimar Factory Chapinero"
    >

    <img
      src="../../../juegos/JimarFactory/img/general1.jpeg"
      alt="Mesas de billar en Jimar Factory Chapinero"
    >

    <img
      src="../../../juegos/JimarFactory/img/general2.jpeg"
      alt="Partida de billar a tres bandas en Jimar Factory Chapinero"
    >

    <img
      src="../../../juegos/JimarFactory/img/general3.jpeg"
      alt="Mesas de pool en Jimar Factory Chapinero"
    >


    

    <img
      src="../../../juegos/JimarFactory/img/general26.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 26"
    >

    <img
      src="../../../juegos/JimarFactory/img/general27.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 27"
    >

    <img
      src="../../../juegos/JimarFactory/img/general28.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 28"
    >

    <img
      src="../../../juegos/JimarFactory/img/general29.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 29"
    >

    <img
      src="../../../juegos/JimarFactory/img/general30.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 30"
    >

    <img
      src="../../../juegos/JimarFactory/img/general31.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 31"
    >

    <img
      src="../../../juegos/JimarFactory/img/general32.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 32"
    >

    <img
      src="../../../juegos/JimarFactory/img/general33.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 33"
    >

    <img
      src="../../../juegos/JimarFactory/img/general34.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 34"
    >

    <img
      src="../../../juegos/JimarFactory/img/general35.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 35"
    >

    <img
      src="../../../juegos/JimarFactory/img/general36.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 36"
    >

    <img
      src="../../../juegos/JimarFactory/img/general37.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 37"
    >

    <img
      src="../../../juegos/JimarFactory/img/general38.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 38"
    >

    <img
      src="../../../juegos/JimarFactory/img/general39.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 39"
    >

    <img
      src="../../../juegos/JimarFactory/img/general40.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 40"
    >

    <img
      src="../../../juegos/JimarFactory/img/general41.jpeg"
      alt="Galería de Jimar Factory Chapinero imagen 41"
    >
  </div>
</section>

<script defer src="../../../juegos/JimarFactory/galeria/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
