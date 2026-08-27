<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/CapitalQueer/home/slider/slider.css';
$jsFile  = $base . '/bar/CapitalQueer/home/slider/slider.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/CapitalQueer/home/slider/slider.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-hero visible" aria-label="Capital Queer slider">

  <!-- Slide 1 -->
  <article
    class="business-hero__slide business-hero__slide--1 active"
    style="background-image: url('../../bar/CapitalQueer/img/general3.png');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Bar · Diversidad · Ambiente seguro</span>

      <h1>Capital Queer</h1>

      <p>
        Creado especialmente para mujeres, donde también cualquier persona puede
        entrar, compartir y disfrutar de un ambiente libre, seguro y diverso.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016">Reservar o contactar</a>
    </div>
  </article>

  <!-- Slide 2 -->
  <article
    class="business-hero__slide business-hero__slide--2"
    style="background-image: url('../../bar/CapitalQueer/img/general.png');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Especialmente para mujeres</span>

      <h1>Siéntete libre</h1>

      <p>
        Capital Queer ofrece un ambiente pensado para que las mujeres puedan
        disfrutar la noche con tranquilidad, música, compañía y una experiencia
        auténtica en Chapinero.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016">Conocer más</a>
    </div>
  </article>

  <!-- Slide 3 -->
  <article
    class="business-hero__slide business-hero__slide--3"
    style="background-image: url('../../bar/CapitalQueer/img/general15.png');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Inclusión · Música · Encuentro</span>

      <h1>Diversidad que se vive</h1>

      <p>
        Aunque es un espacio enfocado especialmente en mujeres, Capital Queer
        recibe a todas las personas que quieran compartir con respeto, buena energía
        y libertad.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016">Conocer más</a>
    </div>
  </article>

  <!-- Flecha izquierda -->
  <button
    class="business-hero__arrow business-hero__arrow--prev"
    type="button"
    aria-label="Slide anterior"
  >
    &#10094;
  </button>

  <!-- Flecha derecha -->
  <button
    class="business-hero__arrow business-hero__arrow--next"
    type="button"
    aria-label="Siguiente slide"
  >
    &#10095;
  </button>

  <!-- Puntos -->
  <div class="business-hero__controls" aria-label="Controles del slider">
    <button
      class="hero-dot active"
      type="button"
      aria-label="Slide 1"
      aria-current="true"
    ></button>

    <button
      class="hero-dot"
      type="button"
      aria-label="Slide 2"
      aria-current="false"
    ></button>

    <button
      class="hero-dot"
      type="button"
      aria-label="Slide 3"
      aria-current="false"
    ></button>
  </div>

</section>

<script defer src="../../bar/CapitalQueer/home/slider/slider.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
