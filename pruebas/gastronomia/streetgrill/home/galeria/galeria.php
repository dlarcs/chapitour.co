<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastronomia/streetgrill/home/galeria/galeria.css';
$jsFile  = $base . '/gastronomia/streetgrill/home/galeria/galeria.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../gastronomia/streetgrill/home/galeria/galeria.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section class="street-business-section street-business-gallery street-visible">

  <span class="street-section-label">
    Galería
  </span>

  <h2>
    Conoce el sabor de Street Grill
  </h2>

  <p>
    Un espacio para disfrutar carne ahumada, carne al barril, hamburguesas,
    chorizos y diferentes preparaciones llenas de sabor.
  </p>

  <div class="street-gallery-grid">

    <img
      class="street-gallery-image"
      src="../../gastronomia/streetgrill/img/general11.jpeg"
      alt="Ambiente de Street Grill"
      role="button"
      tabindex="0"
      aria-label="Ampliar imagen: Ambiente de Street Grill"
    >

    <img
      class="street-gallery-image"
      src="../../gastronomia/streetgrill/img/general17.jpeg"
      alt="Preparaciones de Street Grill"
      role="button"
      tabindex="0"
      aria-label="Ampliar imagen: Preparaciones de Street Grill"
    >

    <img
      class="street-gallery-image street-gallery-img-mobile-hide"
      src="../../gastronomia/streetgrill/img/general4.jpeg"
      alt="Carne al barril de Street Grill"
      role="button"
      tabindex="0"
      aria-label="Ampliar imagen: Carne al barril de Street Grill"
    >

  </div>

  <a
    class="street-gallery-link"
    href="../../gastronomia/streetgrill/galeria/index.php"
  >
    <div class="button_container street-button-container">
      <button
        class="street-btn street-btn30"
        type="button"
        name="button"
      >
        Ver galería
      </button>
    </div>
  </a>

</section>


<!-- LIGHTBOX STREET GRILL -->
<div
  id="street-lightbox"
  class="street-lightbox"
  role="dialog"
  aria-modal="true"
  aria-hidden="true"
  aria-label="Imagen ampliada de Street Grill"
>

  <button
    type="button"
    class="street-lightbox-close"
    aria-label="Cerrar imagen ampliada"
  >
    &times;
  </button>

  <img
    class="street-lightbox-image"
    src=""
    alt=""
  >

</div>


<script
  defer
  src="../../gastronomia/streetgrill/home/galeria/galeria.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
