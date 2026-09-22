<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/CapitalQueer/actividades/actividades/actividades.css';
$jsFile  = $base . '/bar/CapitalQueer/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../../bar/CapitalQueer/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section class="CapitalQueer-business-section CapitalQueer-visible">
  <span class="CapitalQueer-section-label">
    Actividades & Promociones
  </span>

  <h2>Lo mejor de este año</h2>

  <p>
    Conoce nuestras promociones y actividades por mes, ven y disfruta de todo lo que te ofrecemos
  </p>

  <!-- TARJETAS - HTML -->
  <section class="CapitalQueer-cards-section">
    <div class="CapitalQueer-cards-grid">

      <article
        class="CapitalQueer-card CapitalQueer-promo-card"
        data-code="CAPITAL-AMOR-AMISTAD-001"
      >
        <div class="CapitalQueer-card-image">
          <img
            src="../../../bar/CapitalQueer/img/general17.jpg"
            alt="Amor y amistad"
          >

          <span class="CapitalQueer-card-badge">
            Noche especial
          </span>
        </div>

        <div class="CapitalQueer-card-content">
          <h3>Amor y Amistad Queer</h3>

          <p>
            Una noche para celebrar el amor, la amistad, la música y las conexiones bonitas.
          </p>

          <span class="CapitalQueer-card-location">
            <span>&#128467;&#65039;</span>
            19 de Septiembre 2026
          </span>
        </div>
      </article>

      <article
        class="CapitalQueer-card CapitalQueer-promo-card"
        data-code="CAPITAL-HALLOWEEN-002"
      >
        <div class="CapitalQueer-card-image">
          <img
            src="../../../bar/CapitalQueer/img/general13.png"
            alt="Halloween"
          >

          <span class="CapitalQueer-card-badge">
            Halloween
          </span>
        </div>

        <div class="CapitalQueer-card-content">
          <h3>Halloween Party</h3>

          <p>
            Ven con tu mejor disfraz y disfruta una noche llena de música, shows y sorpresas.
          </p>

          <span class="CapitalQueer-card-location">
            <span>&#128467;&#65039;</span>
            31 de Octubre 2026
          </span>
        </div>
      </article>

      <article
        class="CapitalQueer-card CapitalQueer-promo-card"
        data-code="CAPITAL-VELITAS-003"
      >
        <div class="CapitalQueer-card-image">
          <img
            src="../../../bar/CapitalQueer/img/general18.jpg"
            alt="Día de velitas"
          >

          <span class="CapitalQueer-card-badge">
            Velitas
          </span>
        </div>

        <div class="CapitalQueer-card-content">
          <h3>Noche de Velitas</h3>

          <p>
            Una noche mágica para brindar, compartir y empezar diciembre con buena energía.
          </p>

          <span class="CapitalQueer-card-location">
            <span>&#128467;&#65039;</span>
            7 de Diciembre 2026
          </span>
        </div>
      </article>

      <article
        class="CapitalQueer-card CapitalQueer-promo-card"
        data-code="CAPITAL-NAVIDAD-004"
      >
        <div class="CapitalQueer-card-image">
          <img
            src="../../../bar/CapitalQueer/img/general3.png"
            alt="Navidad"
          >

          <span class="CapitalQueer-card-badge">
            Navidad
          </span>
        </div>

        <div class="CapitalQueer-card-content">
          <h3>Pre Navidad Capital</h3>

          <p>
            Una celebración navideña para disfrutar entre amigas, cocteles, música y mucho ambiente.
          </p>

          <span class="CapitalQueer-card-location">
            <span>&#128467;&#65039;</span>
            24 de Diciembre 2026
          </span>
        </div>
      </article>

      <article
        class="CapitalQueer-card CapitalQueer-promo-card"
        data-code="CAPITAL-FIN-DE-ANO-005"
      >
        <div class="CapitalQueer-card-image">
          <img
            src="../../../bar/CapitalQueer/img/general21.png"
            alt="Fin de año"
          >

          <span class="CapitalQueer-card-badge">
            Fin de año
          </span>
        </div>

        <div class="CapitalQueer-card-content">
          <h3>Despedida de Año</h3>

          <p>
            Despidamos el año con fiesta, buena música, brindis y una noche inolvidable.
          </p>

          <span class="CapitalQueer-card-location">
            <span>&#128467;&#65039;</span>
            31 de Diciembre 2026
          </span>
        </div>
      </article>

      <article
        class="CapitalQueer-card CapitalQueer-promo-card"
        data-code="CAPITAL-VIERNES-006"
      >
        <div class="CapitalQueer-card-image">
          <img
            src="../../../bar/CapitalQueer/img/general10.png"
            alt="Viernes de amigas"
          >

          <span class="CapitalQueer-card-badge">
            Viernes
          </span>
        </div>

        <div class="CapitalQueer-card-content">
          <h3>Viernes de Amigas</h3>

          <p>
            Todos los viernes tenemos ambiente especial para venir, bailar y compartir entre mujeres.
          </p>

          <span class="CapitalQueer-card-location">
            <span>&#128467;&#65039;</span>
            Todos los viernes desde Julio 2026
          </span>
        </div>
      </article>

    </div>
  </section>
</section>

<script
  defer
  src="../../../bar/CapitalQueer/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
