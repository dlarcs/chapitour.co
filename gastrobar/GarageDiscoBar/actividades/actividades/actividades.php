<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/GarageDiscoBar/actividades/actividades/actividades.css';
$jsFile  = $base . '/gastrobar/GarageDiscoBar/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastrobar/GarageDiscoBar/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Actividades & Eventos</span>

  <h2>Planes para disfrutar en Garage Disco Bar</h2>

  <p>
    Disfruta noches de karaoke, baile, música crossover, shows especiales y una propuesta de gastrobar para compartir con amigos.
  </p>

  <!-- TARJETAS - HTML -->
  <section class="cards-section">
    <div class="cards-grid">

      <article class="card promo-card" data-code="GARAGE-KARAOKE-001">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general15.jpg" alt="Noche de karaoke">
          <span class="card-badge">Karaoke</span>
        </div>

        <div class="card-content">
          <h3>Noches de Karaoke</h3>
          <p>Sube al escenario, canta tus canciones favoritas y comparte una noche diferente con tus amigos.</p>
          <span class="card-location"><span>&#127908;</span> Karaoke para cantar y disfrutar</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-CROSSOVER-002">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general13.jpg" alt="Fiesta crossover">
          <span class="card-badge">Crossover</span>
        </div>

        <div class="card-content">
          <h3>Fiesta Crossover</h3>
          <p>Baila diferentes géneros musicales en una noche llena de ritmo, energía y el mejor ambiente.</p>
          <span class="card-location"><span>&#127925;</span> Música para todos los gustos</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-SHOWS-003">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general17.jpg" alt="Shows especiales">
          <span class="card-badge">Shows</span>
        </div>

        <div class="card-content">
          <h3>Shows Cada 15 Días</h3>
          <p>Disfruta presentaciones especiales, artistas invitados y experiencias preparadas para sorprenderte.</p>
          <span class="card-location"><span>&#127917;</span> Shows especiales cada quince días</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-GASTROBAR-004">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general6.jpg" alt="Comida y bebidas">
          <span class="card-badge">Gastrobar</span>
        </div>

        <div class="card-content">
          <h3>Sabores de Gastrobar</h3>
          <p>Acompaña tu noche con comida, bebidas y opciones ideales para compartir antes de salir a bailar.</p>
          <span class="card-location"><span>&#127828;</span> Comida, bebidas y buena compañía</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-DISCOBAR-005">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general1.jpg" alt="Garage Disco Bar">
          <span class="card-badge">Disco Bar</span>
        </div>

        <div class="card-content">
          <h3>Ambiente de Disco Bar</h3>
          <p>Vive una noche completa con luces, música, bebidas y un espacio preparado para bailar y celebrar.</p>
          <span class="card-location"><span>&#127926;</span> Fiesta, música y diversión</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-BAILE-006">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general10.jpg" alt="Noche de baile">
          <span class="card-badge">Baile</span>
        </div>

        <div class="card-content">
          <h3>Noches para Bailar</h3>
          <p>Reúne a tus amigos y disfruta una pista llena de música crossover, alegría y mucho movimiento.</p>
          <span class="card-location"><span>&#128131;</span> Baila y celebra con nosotros</span>
        </div>
      </article>

    </div>
  </section>
</section>

<script defer src="../../../gastrobar/GarageDiscoBar/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
