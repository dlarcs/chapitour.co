<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/home/slider/slider.css';
$jsFile  = $base . '/juegos/JimarFactory/home/slider/slider.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../juegos/JimarFactory/home/slider/slider.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section
  class="JimarFactory-business-hero JimarFactory-visible"
  aria-label="Carrusel principal de Jimar Factory Chapinero"
>

  <!-- Slide 1: Billar -->
  <article
    class="JimarFactory-business-hero__slide JimarFactory-business-hero__slide--1 JimarFactory-is-active"
    style="background-image: url('../../juegos/JimarFactory/img/general39.jpeg');"
    aria-hidden="false"
  >
    <div class="JimarFactory-business-hero__overlay"></div>

    <div class="JimarFactory-business-hero__content">
      <span>Billar · Tres bandas · Pool</span>

      <h2>Vive el mejor ambiente de billar</h2>

      <p>
        Disfruta partidas de tres bandas y pool en Jimar Factory Chapinero,
        un espacio de entretenimiento, precisión y buena energía para compartir
        con amigos.
      </p>

      <div class="JimarFactory-business-stars">
        <strong>Domingo a domingo</strong>
        <small>9:00 a. m. a 12:00 a. m.</small>
      </div>

      <a
        href="https://wa.me/573165180649?text=Hola%2C%20vengo%20desde%20Chapitour%20y%20quiero%20conocer%20Jimar%20Factory%20Chapinero."
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Contactar a Jimar Factory Chapinero por WhatsApp"
      >
        Conocer más
      </a>
    </div>
  </article>

  <!-- Slide 2: Bebidas -->
  <article
    class="JimarFactory-business-hero__slide JimarFactory-business-hero__slide--2"
    style="background-image: url('../../juegos/JimarFactory/img/general16.jpeg');"
    aria-hidden="true"
  >
    <div class="JimarFactory-business-hero__overlay"></div>

    <div class="JimarFactory-business-hero__content">
      <span>Bebidas · Cócteles · Café</span>

      <h2>Acompaña cada partida</h2>

      <p>
        Encuentra variedad de bebidas, cócteles, café y productos con alcohol
        para mayores de 18 años, acompañados de una atención cercana los siete
        días de la semana.
      </p>

      <div class="JimarFactory-business-stars">
        <strong>Variedad y servicio</strong>
        <small>Todo en un mismo lugar</small>
      </div>

      <a
        href="https://wa.me/573165180649?text=Hola%2C%20quiero%20recibir%20informaci%C3%B3n%20sobre%20las%20bebidas%20y%20servicios%20de%20Jimar%20Factory%20Chapinero."
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Solicitar información sobre Jimar Factory Chapinero"
      >
        Contactar
      </a>
    </div>
  </article>

  <!-- Slide 3: Materiales y ubicación -->
  <article
    class="JimarFactory-business-hero__slide JimarFactory-business-hero__slide--3"
    style="background-image: url('../../juegos/JimarFactory/img/general23.jpeg');"
    aria-hidden="true"
  >
    <div class="JimarFactory-business-hero__overlay"></div>

    <div class="JimarFactory-business-hero__content">
      <span>Materiales de juego · Servicio · Entretenimiento</span>

      <h2>Todo para disfrutar el billar</h2>

      <p>
        Además de nuestras mesas de tres bandas y pool, ofrecemos materiales
        y accesorios de juego para mayores de 18 años. Visítanos en el corazón
        de Chapinero.
      </p>

      <div class="JimarFactory-business-stars">
        <strong>Calle 58 #13-93</strong>
        <small>Chapinero, Bogotá</small>
      </div>

      <a
        href="https://wa.me/573165180649?text=Hola%2C%20quiero%20visitar%20Jimar%20Factory%20Chapinero%20en%20la%20Calle%2058%20%2313-93."
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Consultar cómo llegar a Jimar Factory Chapinero"
      >
        Cómo llegar
      </a>
    </div>
  </article>

  <!-- Controles -->
  <div
    class="JimarFactory-business-hero__controls"
    aria-label="Controles del carrusel"
  >
    <button
      class="JimarFactory-hero-dot JimarFactory-is-active"
      type="button"
      aria-label="Mostrar información sobre billar tres bandas y pool"
      aria-current="true"
    ></button>

    <button
      class="JimarFactory-hero-dot"
      type="button"
      aria-label="Mostrar información sobre bebidas, cócteles y café"
      aria-current="false"
    ></button>

    <button
      class="JimarFactory-hero-dot"
      type="button"
      aria-label="Mostrar información sobre materiales de juego y ubicación"
      aria-current="false"
    ></button>
  </div>

</section>

<script
  defer
  src="../../juegos/JimarFactory/home/slider/slider.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
