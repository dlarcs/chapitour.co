<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/CapitalQueer/home/galeria/galeria.css';
$jsFile  = $base . '/bar/CapitalQueer/home/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../bar/CapitalQueer/home/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section class="CapitalQueer-business-section CapitalQueer-business-gallery CapitalQueer-visible">

  <span class="CapitalQueer-section-label">
    Galería
  </span>

  <h2>
    Conoce el ambiente de Capital Queer
  </h2>

  <p>
    Un espacio diverso, libre y seguro, pensado especialmente para mujeres,
    con música, luces, encuentros y momentos para compartir con respeto y buena energía.
  </p>

  <div class="CapitalQueer-gallery-grid">

    <img
      class="CapitalQueer-gallery-image"
      src="../../bar/CapitalQueer/img/general21.png"
      alt="Ambiente de Capital Queer"
      role="button"
      tabindex="0"
      aria-label="Ampliar imagen: Ambiente de Capital Queer"
    >

    <img
      class="CapitalQueer-gallery-image"
      src="../../bar/CapitalQueer/img/general17.jpg"
      alt="Bar de Capital Queer"
      role="button"
      tabindex="0"
      aria-label="Ampliar imagen: Bar de Capital Queer"
    >

    <img
      class="CapitalQueer-gallery-image CapitalQueer-gallery-img-mobile-hide"
      src="../../bar/CapitalQueer/img/general22.png"
      alt="Espacio social de Capital Queer"
      role="button"
      tabindex="0"
      aria-label="Ampliar imagen: Espacio social de Capital Queer"
    >

  </div>

  <a
    class="CapitalQueer-gallery-link"
    href="../../bar/CapitalQueer/galeria/index.php"
  >
    <div class="button_container CapitalQueer-button-container">
      <button
        class="btn btn30 CapitalQueer-gallery-button"
        type="button"
        name="button"
      >
        Ver galería
      </button>
    </div>
  </a>

</section>


<!-- LIGHTBOX CAPITAL QUEER -->
<div
  id="CapitalQueer-lightbox"
  class="CapitalQueer-lightbox"
  role="dialog"
  aria-modal="true"
  aria-hidden="true"
  aria-label="Imagen ampliada de Capital Queer"
>

  <button
    type="button"
    class="CapitalQueer-lightbox-close"
    aria-label="Cerrar imagen ampliada"
  >
    &times;
  </button>

  <img
    class="CapitalQueer-lightbox-image"
    src=""
    alt=""
  >

</div>


<script
  defer
  src="../../bar/CapitalQueer/home/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
