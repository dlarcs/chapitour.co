<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/Garage9-39C/home/slider/slider.css';
$jsFile  = $base . '/gastrobar/Garage9-39C/home/slider/slider.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../gastrobar/Garage9-39C/home/slider/slider.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-hero visible" aria-label="Garage 9-39C slider">


  <!-- Slide 1 -->
  <article
    class="business-hero__slide business-hero__slide--2"
    style="background-image: url('../../gastrobar/Garage9-39C/img/general10.jpg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Rumba · Música · Buena energía</span>

      <h1>Vive la noche</h1>

      <p>
        Garage 9-39C combina música, luces, baile y un ambiente perfecto para
        compartir con amigos y disfrutar la noche.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016?text=Hola%20quiero%20conocer%20Garage%209-39C">Conocer más</a>
    </div>
  </article>

  <!-- Slide 2 -->
  <article
    class="business-hero__slide business-hero__slide--3"
    style="background-image: url('../../gastrobar/Garage9-39C/img/general5.jpg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Inclusión · Diversidad · Encuentro</span>

      <h1>Diversidad que se celebra</h1>

      <p>
        Un lugar LGBTIQ+ abierto para compartir con respeto, libertad,
        autenticidad y buena vibra.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016?text=Hola%20quiero%20información%20sobre%20Garage%209-39C">Contactar</a>
    </div>
  </article>

  <!-- Slide 3 -->
  <article
    class="business-hero__slide business-hero__slide--4"
    style="background-image: url('../../gastrobar/Garage9-39C/img/general15.jpg');"
  >
    <div class="business-hero__overlay"></div>

    <div class="business-hero__content">
      <span>Comida · Bebidas · Gastrobar</span>

      <h1>Sabor de la noche</h1>

      <p>
        Disfruta una experiencia de gastrobar con comida, bebidas y cocteles
        ideales para comenzar o cerrar la rumba.
      </p>

      <div class="business-stars">
        <strong>★★★★★</strong>
        <small>4.8 / 5</small>
      </div>

      <a href="https://wa.me/573007795016?text=Hola%20quiero%20reservar%20en%20Garage%209-39C">Reservar</a>
    </div>
  </article>


  <!-- Controls -->
  <div class="business-hero__controls" aria-label="Slider controls">
    <button class="hero-dot" type="button" aria-label="Slide 1"></button>
    <button class="hero-dot" type="button" aria-label="Slide 2"></button>
    <button class="hero-dot" type="button" aria-label="Slide 3"></button>

  </div>

</section>

<script defer src="../../gastrobar/Garage9-39C/home/slider/slider.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
