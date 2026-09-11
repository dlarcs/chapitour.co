<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

// Ruta original de los archivos.
// Cámbiala por la carpeta real de Street Grill cuando corresponda.
$assetsPath = '/gastronomia/streetgrill';

$cssPath = $assetsPath . '/gastronomia/streetgrill/actividades/actividades/actividades.css';
$jsPath  = $assetsPath . '/gastronomia/streetgrill/actividades/actividades/actividades.js';

$cssFile = $base . $cssPath;
$jsFile  = $base . $jsPath;

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="<?= $cssPath ?><?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section
  id="StreetGrill-actividades"
  class="business-section visible"
>
  <span class="section-label">Sabor & Especialidades</span>

  <h2>Carne al barril y antojos que se disfrutan de verdad</h2>

  <p>
    En Street Grill, el protagonista es el sabor. Disfruta nuestra
    especialidad en carne al barril, hamburguesas, sándwiches,
    arepas boyacenses, chorizos y choripapa. Acompaña tu elección
    con una michelada o una soda italiana y arma tu próximo plan.
  </p>

  <!-- TARJETAS -->
  <section
    class="cards-section"
    aria-label="Especialidades de Street Grill"
  >
    <div class="cards-grid">

      <!-- CARNE AL BARRIL -->
      <article
        class="card promo-card"
        data-code="STREETGRILL-CARNE-001"
        tabindex="0"
        role="link"
        aria-label="Consultar por WhatsApp sobre la carne al barril"
      >
        <div class="card-image">
          <!-- Reemplaza esta fotografía por una de la carne al barril. -->
          <img
            src="<?= $assetsPath ?>/img/general15.jpeg"
            alt="Carne al barril de Street Grill"
            loading="lazy"
            decoding="async"
          >

          <span class="card-badge">Carne al barril</span>
        </div>

        <div class="card-content">
          <h3>La especialidad que tienes que probar</h3>

          <p>
            La carne al barril es la protagonista de Street Grill.
            Ven a disfrutar nuestras carnes y convierte tu próxima
            comida en un plan lleno de sabor.
          </p>

          <span class="card-location">
            <span aria-hidden="true">&#128293;</span>
            Nuestra especialidad, tu próximo antojo
          </span>
        </div>
      </article>

      <!-- HAMBURGUESAS Y SÁNDWICHES -->
      <article
        class="card promo-card"
        data-code="STREETGRILL-HAMBURGUESAS-SANDWICHES-002"
        tabindex="0"
        role="link"
        aria-label="Consultar por WhatsApp sobre hamburguesas y sándwiches"
      >
        <div class="card-image">
          <!-- Reemplaza esta fotografía por una de hamburguesas o sándwiches. -->
          <img
            src="<?= $assetsPath ?>/img/general13.jpeg"
            alt="Hamburguesas y sándwiches de Street Grill"
            loading="lazy"
            decoding="async"
          >

          <span class="card-badge">Hamburguesas & Sándwiches</span>
        </div>

        <div class="card-content">
          <h3>Dale un buen mordisco al día</h3>

          <p>
            ¿Team hamburguesa o team sándwich? En Street Grill
            tienes opciones para darle gusto a ese antojo.
            Elige tu favorito y disfruta cada mordisco.
          </p>

          <span class="card-location">
            <span aria-hidden="true">&#127828;</span>
            Dos formas de disfrutar un buen antojo
          </span>
        </div>
      </article>

      <!-- AREPAS BOYACENSES -->
      <article
        class="card promo-card"
        data-code="STREETGRILL-AREPAS-003"
        tabindex="0"
        role="link"
        aria-label="Consultar por WhatsApp sobre las arepas boyacenses"
      >
        <div class="card-image">
          <!-- Reemplaza esta fotografía por una de las arepas boyacenses. -->
          <img
            src="<?= $assetsPath ?>/img/general17.jpeg"
            alt="Arepas boyacenses de Street Grill"
            loading="lazy"
            decoding="async"
          >

          <span class="card-badge">Arepas boyacenses</span>
        </div>

        <div class="card-content">
          <h3>Un antojo con sabor a tradición</h3>

          <p>
            Dale un lugar a nuestras arepas boyacenses en tu próxima
            visita. Una opción para acompañar tu comida o disfrutar
            cuando se te antoja algo diferente.
          </p>

          <span class="card-location">
            <span aria-hidden="true">&#129747;</span>
            Un sabor que siempre provoca
          </span>
        </div>
      </article>

      <!-- CHORIZOS -->
      <article
        class="card promo-card"
        data-code="STREETGRILL-CHORIZOS-004"
        tabindex="0"
        role="link"
        aria-label="Consultar por WhatsApp sobre los chorizos"
      >
        <div class="card-image">
          <!-- Reemplaza esta fotografía por una de los chorizos. -->
          <img
            src="<?= $assetsPath ?>/img/general6.jpeg"
            alt="Chorizos de Street Grill"
            loading="lazy"
            decoding="async"
          >

          <span class="card-badge">Chorizos</span>
        </div>

        <div class="card-content">
          <h3>Un clásico que no se queda por fuera</h3>

          <p>
            Hay antojos que no necesitan presentación. Disfruta
            nuestros chorizos y súmale más sabor a tu visita.
            Pídelos para ti o inclúyelos en el plan con tu parche.
          </p>

          <span class="card-location">
            <span aria-hidden="true">&#127860;</span>
            Para esos antojos que no dan espera
          </span>
        </div>
      </article>

      <!-- CHORIPAPA -->
      <article
        class="card promo-card"
        data-code="STREETGRILL-CHORIPAPA-005"
        tabindex="0"
        role="link"
        aria-label="Consultar por WhatsApp sobre la choripapa"
      >
        <div class="card-image">
          <!-- Reemplaza esta fotografía por una de la choripapa. -->
          <img
            src="<?= $assetsPath ?>/img/general1.jpeg"
            alt="Choripapa de Street Grill"
            loading="lazy"
            decoding="async"
          >

          <span class="card-badge">Choripapa</span>
        </div>

        <div class="card-content">
          <h3>El antojo ya tiene nombre</h3>

          <p>
            Cuando el plan pide choripapa, Street Grill es el punto
            de encuentro. Ven con hambre, reúne a tu parche
            y disfruta una pausa con mucho sabor.
          </p>

          <span class="card-location">
            <span aria-hidden="true">&#127839;</span>
            Dale gusto a ese antojo
          </span>
        </div>
      </article>

      <!-- MICHELADAS Y SODAS ITALIANAS -->
      <article
        class="card promo-card"
        data-code="STREETGRILL-BEBIDAS-006"
        tabindex="0"
        role="link"
        aria-label="Consultar por WhatsApp sobre micheladas y sodas italianas"
      >
        <div class="card-image">
          <!-- Reemplaza esta fotografía por una de las bebidas. -->
          <img
            src="<?= $assetsPath ?>/img/general10.jpeg"
            alt="Micheladas y sodas italianas de Street Grill"
            loading="lazy"
            decoding="async"
          >

          <span class="card-badge">Micheladas & Sodas italianas</span>
        </div>

        <div class="card-content">
          <h3>El toque refrescante de tu plan</h3>

          <p>
            Acompaña tu comida con nuestras micheladas y sodas
            italianas. Pregunta por las opciones disponibles
            y encuentra la bebida para completar tu antojo.
          </p>

          <span class="card-location">
            <span aria-hidden="true">&#129380;</span>
            Refresca tu visita a Street Grill
          </span>
        </div>
      </article>

    </div>
  </section>
</section>

<script
  defer
  src="<?= $jsPath ?><?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
