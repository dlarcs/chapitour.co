<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.1.bartin/slider/slider.css';
$jsFile  = $base . '/1.1.bartin/slider/slider.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../1.1.bartin/slider/slider.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-hero visible_slider" aria-label="Distrito Gastrobar slider">

  <!-- Slide 1 -->
  <article
    class="business-hero__slide business-hero__slide--1"
    style="background-image: url('../home/img/cocktel.png');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Gastrobar · Comida rápida · Licores</span>

      <h1>Distrito Gastrobar</h1>

      <p>
        Un espacio urbano en Chapinero para disfrutar hamburguesas, cocteles,
        música y buenos momentos con amigos.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573138846378">Reservar o contactar</a>
    </div>
  </article>

  <!-- Slide 2 -->
  <article
    class="business-hero__slide business-hero__slide--2"
    style="background-image: url('../home/img/cafe1.png');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Ambiente nocturno</span>

      <h1>Comida, licor y experiencias</h1>

      <p>
        Vive una experiencia relajada con platos rápidos, bebidas seleccionadas
        y un ambiente perfecto para compartir.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573138846378">Conocer más</a>
    </div>
  </article>

  <!-- Slide 3 -->
  <article
    class="business-hero__slide business-hero__slide--3"
    style="background-image: url('../home/img/cafe.png');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Ambiente nocturno</span>

      <h1>Comida, licor y experiencias</h1>

      <p>
        Vive una experiencia relajada con platos rápidos, bebidas seleccionadas
        y un ambiente perfecto para compartir.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573138846378">Conocer más</a>
    </div>
  </article>

  <!-- Controls -->
  <div class="business-hero__controls" aria-label="Slider controls">
    <button class="hero-dot" type="button" aria-label="Slide 1"></button>
    <button class="hero-dot" type="button" aria-label="Slide 2"></button>
    <button class="hero-dot" type="button" aria-label="Slide 3"></button>
  </div>

</section>
<script defer src="../1.1.bartin/slider/slider.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
