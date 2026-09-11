<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastronomia/streetgrill/home/slider/slider.css';
$jsFile  = $base . '/gastronomia/streetgrill/home/slider/slider.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../gastronomia/streetgrill/home/slider/slider.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-hero visible" aria-label="Street Grill slider">


  <!-- Slide 1 -->
  <article
    class="business-hero__slide business-hero__slide--2"
    style="background-image: url('../../gastronomia/streetgrill/img/general3.jpeg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Carne al barril · Humo · Sabor artesanal</span>

      <h2>El sabor sale del barril</h2>

      <p>
        En Street Grill encuentras carnes al barril y preparaciones ahumadas
        con sabor intenso, ingredientes seleccionados y ese toque artesanal
        que hace diferente cada plato.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016?text=Hola%20quiero%20conocer%20Street%20Grill">Conocer más</a>
    </div>
  </article>

  <!-- Slide 2 -->
  <article
    class="business-hero__slide business-hero__slide--3"
    style="background-image: url('../../gastronomia/streetgrill/img/general6.jpeg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Bondiola · Cerdo · Res ahumada</span>

      <h2>Carne que se disfruta de verdad</h2>

      <p>
        Bondiola de cerdo, carne de res y preparaciones mixtas al barril
        para quienes disfrutan sabores ahumados, jugosos y llenos de carácter.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016?text=Hola%20quiero%20información%20sobre%20Street%20Grill">Contactar</a>
    </div>
  </article>

  <!-- Slide 3 -->
  <article
    class="business-hero__slide business-hero__slide--4"
    style="background-image: url('../../gastronomia/streetgrill/img/general5.jpeg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Hamburguesas · Chorizos · Gastrobar</span>

      <h2>Mucho más que carne al barril</h2>

      <p>
        Disfruta hamburguesas artesanales, chorizos, choripán, sándwiches,
        acompañamientos y bebidas en una experiencia gastronómica para compartir.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016?text=Hola%20quiero%20conocer%20el%20menú%20de%20Street%20Grill">Ver menú</a>
    </div>
  </article>


  <!-- Controls -->
  <div class="business-hero__controls" aria-label="Slider controls">
    <button class="hero-dot" type="button" aria-label="Slide 1"></button>
    <button class="hero-dot" type="button" aria-label="Slide 2"></button>
    <button class="hero-dot" type="button" aria-label="Slide 3"></button>

  </div>

</section>

<script defer src="../../gastronomia/streetgrill/home/slider/slider.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
