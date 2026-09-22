<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastronomia/streetgrill/home/menu/menu.css';
$jsFile  = $base . '/gastronomia/streetgrill/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../gastronomia/streetgrill/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section class="street-business-section street-visible">
  <br><br>

  <span class="street-section-label">Nuestro menú</span>

  <h2>El sabor ahumado de Street Grill</h2>

  <p>
    Disfruta carnes ahumadas, chorizos artesanales y hamburguesas preparadas
    con mucho sabor. En Street Grill el humo, la parrilla y la buena comida
    son los protagonistas.
  </p>

  <div class="street-menu-grid">

    <!-- CARNE AHUMADA -->
    <article class="street-menu-card">

      <img
        src="../../gastronomia/streetgrill/img/general1.jpeg"
        alt="Carne ahumada Street Grill"
      >

      <div>

        <h3>Carne ahumada</h3>

        <p class="street-descripcion-card">
          Carnes preparadas lentamente para lograr un sabor intenso,
          jugoso y ahumado que representa la esencia de Street Grill.
        </p>

      </div>

    </article>


    <!-- HAMBURGUESAS -->
    <article class="street-menu-card">

      <img
        src="../../gastronomia/streetgrill/img/general5.jpeg"
        alt="Hamburguesas Street Grill"
      >

      <div>

        <h3>Hamburguesas</h3>

        <p class="street-descripcion-card">
          Hamburguesas llenas de sabor, preparadas con carnes,
          ingredientes frescos y el toque especial de Street Grill.
        </p>

      </div>

    </article>


    <!-- CHORIZOS -->
    <article class="street-menu-card">

      <img
        src="../../gastronomia/streetgrill/img/general3.jpeg"
        alt="Chorizos artesanales Street Grill"
      >

      <div>

        <h3>Chorizos artesanales</h3>

        <p class="street-descripcion-card">
          Chorizos llenos de sabor, perfectos para acompañar una buena
          comida y disfrutar el auténtico estilo de parrilla.
        </p>

      </div>

    </article>


    <!-- PARRILLA STREET GRILL -->
    <article class="street-menu-card">

      <img
        src="../../gastronomia/streetgrill/img/general4.jpeg"
        alt="Parrilla Street Grill"
      >

      <div>

        <h3>Sabores de la parrilla</h3>

        <p class="street-descripcion-card">
          Una combinación de carnes, preparaciones ahumadas y sabores
          ideales para compartir y disfrutar una experiencia Street Grill.
        </p>

      </div>

    </article>

  </div>


  <a
    href="../../gastronomia/streetgrill/img/menu.jpeg"
    class="street-dowload"
  >

    <div class="street-button-container">

      <button
        class="street-btn street-btn30"
        type="button"
      >
        Descargar Menú
      </button>

    </div>

  </a>

</section>

<script
  defer
  src="../../gastronomia/streetgrill/home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
