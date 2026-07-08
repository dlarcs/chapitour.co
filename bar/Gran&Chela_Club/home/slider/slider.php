<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Gran&Chela_Club/home/slider/slider.css';
$jsFile  = $base . '/bar/Gran&Chela_Club/home/slider/slider.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/Gran&Chela_Club/home/slider/slider.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-hero visible" aria-label="Gran&Chela Club slider">

  <!-- Slide 1 -->
  <article
    class="business-hero__slide business-hero__slide--1"
    style="background-image: url('../../bar/Gran&Chela_Club/img/general2.jpg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Bar · Discoteca · Buena rumba</span>

      <h1>Gran&Chela Club</h1>

      <p>
        Un lugar para disfrutar la noche con buena música, licores, cervezas,
        micheladas y un ambiente lleno de energía para compartir con amigos.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573224680419">Reservar o contactar</a>
    </div>
  </article>

  <!-- Slide 2 -->
  <article
    class="business-hero__slide business-hero__slide--2"
    style="background-image: url('../../bar/Gran&Chela_Club/img/general12.jpg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Karaoke · Salsa · Promociones</span>

      <h1>Rumba, sabor y buenos momentos</h1>

      <p>
        Vive noches especiales con karaoke, salsa, micheladas, promociones
        y música para bailar, cantar, brindar y pasarla bien.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573224680419">Conocer más</a>
    </div>
  </article>

  <!-- Slide 3 -->
  <article
    class="business-hero__slide business-hero__slide--3"
    style="background-image: url('../../bar/Gran&Chela_Club/img/general11.jpg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Eventos · Reservas · Celebraciones</span>

      <h1>Alquila el espacio para tu evento</h1>

      <p>
        En Gran&Chela Club también prestamos el espacio para cumpleaños,
        reuniones, celebraciones privadas y eventos especiales con reserva previa.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573224680419">Reservar evento</a>
    </div>
  </article>

  <!-- Controls -->
  <div class="business-hero__controls" aria-label="Slider controls">
    <button class="hero-dot" type="button" aria-label="Slide 1"></button>
    <button class="hero-dot" type="button" aria-label="Slide 2"></button>
    <button class="hero-dot" type="button" aria-label="Slide 3"></button>
  </div>

</section>

<script defer src="../../bar/Gran&Chela_Club/home/slider/slider.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
