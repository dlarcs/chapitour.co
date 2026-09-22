<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/home/slider/slider.css';
$jsFile  = $base . '/bar/Pictograma/home/slider/slider.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../bar/Pictograma/home/slider/slider.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section
  class="Pictogramas-business-hero Pictogramas-visible"
  aria-label="Presentación de Pictogramas Cafe Bar"
>

  <!-- Slide 1 -->
  <article
    class="Pictogramas-business-hero__slide Pictogramas-business-hero__slide--1 Pictogramas-is-active"
    style="background-image: url('../../bar/Pictograma/img/general3.png');"
    aria-hidden="false"
  >
    <div class="Pictogramas-business-hero__overlay"></div>

    <div class="Pictogramas-business-hero__content">
      <span>Café · Bar · Diversión</span>

      <h1>Pictogramas Cafe Bar</h1>

      <p>
        Un lugar para compartir con amigos, disfrutar bebidas deliciosas
        y pasar un momento diferente en un ambiente agradable y lleno de buena energía.
      </p>

      <div class="Pictogramas-business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a
        href="https://wa.me/573138846378?text=Hola%2C%20vengo%20desde%20Chapitour%20y%20quiero%20conocer%20m%C3%A1s%20sobre%20Pictogramas%20Cafe%20Bar."
      >
        Reservar o contactar
      </a>
    </div>
  </article>

  <!-- Slide 2 -->
  <article
    class="Pictogramas-business-hero__slide Pictogramas-business-hero__slide--2"
    style="background-image: url('../../bar/Pictograma/img/general.png');"
    aria-hidden="true"
  >
    <div class="Pictogramas-business-hero__overlay"></div>

    <div class="Pictogramas-business-hero__content">
      <span>Cócteles · Micheladas · Cervezas</span>

      <h1>Bebidas para disfrutar</h1>

      <p>
        Encuentra cócteles, granizados, licores, micheladas, bolivianas,
        cervezas y café para acompañar tus mejores momentos.
      </p>

      <div class="Pictogramas-business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="../../bar/Pictograma/actividades/index.php">
        Conocer bebidas y promociones
      </a>
    </div>
  </article>

  <!-- Slide 3 -->
  <article
    class="Pictogramas-business-hero__slide Pictogramas-business-hero__slide--3"
    style="background-image: url('../../bar/Pictograma/img/general15.png');"
    aria-hidden="true"
  >
    <div class="Pictogramas-business-hero__overlay"></div>

    <div class="Pictogramas-business-hero__content">
      <span>Bolirana · Amigos · Buena energía</span>

      <h1>Juega, comparte y diviértete</h1>

      <p>
        Reúne a tus amigos, arma tu equipo y disfruta una partida de bolirana
        mientras compartes tus bebidas favoritas en Pictogramas Cafe Bar.
      </p>

      <div class="Pictogramas-business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a
        href="https://wa.me/573138846378?text=Hola%2C%20vengo%20desde%20Chapitour%20y%20quiero%20informaci%C3%B3n%20sobre%20la%20bolirana%20de%20Pictogramas%20Cafe%20Bar."
      >
        Consultar por WhatsApp
      </a>
    </div>
  </article>

  <!-- Puntos inferiores -->
  <div
    class="Pictogramas-business-hero__controls"
    aria-label="Controles del carrusel"
  >
    <button
      class="Pictogramas-hero-dot Pictogramas-is-active"
      type="button"
      aria-label="Mostrar diapositiva 1"
      aria-current="true"
    ></button>

    <button
      class="Pictogramas-hero-dot"
      type="button"
      aria-label="Mostrar diapositiva 2"
      aria-current="false"
    ></button>

    <button
      class="Pictogramas-hero-dot"
      type="button"
      aria-label="Mostrar diapositiva 3"
      aria-current="false"
    ></button>
  </div>

</section>

<script
  defer
  src="../../bar/Pictograma/home/slider/slider.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
