<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/actividades/actividades/actividades.css';
$jsFile  = $base . '/bar/Pictograma/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../bar/Pictograma/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Actividades & Promociones</span>

  <h2>Planes para disfrutar en Pictograma</h2>

  <p>
    Disfruta cócteles, licores, café y granizados en un ambiente agradable, rodeado de banderas y acompañado de buena música, amigos y bolirrana.
  </p>

  <!-- TARJETAS - HTML -->
  <section class="cards-section">
    <div class="cards-grid">

      <article class="card promo-card" data-code="PICTOGRAMA-COCTELES-001">
        <div class="card-image">
          <img src="../../../bar/Pictograma/img/general1.png" alt="Noche de cócteles en Pictograma">
          <span class="card-badge">Cócteles</span>
        </div>

        <div class="card-content">
          <h3>Noche de Cócteles</h3>
          <p>Prueba diferentes combinaciones, sabores y colores mientras disfrutas de un ambiente tranquilo y agradable.</p>
          <span class="card-location"><span>&#127864;</span> Cócteles para compartir</span>
        </div>
      </article>

      <article class="card promo-card" data-code="PICTOGRAMA-BOLIRRANA-002">
        <div class="card-image">
          <img src="../../../bar/Pictograma/img/general2.png" alt="Juego de bolirrana">
          <span class="card-badge">Bolirrana</span>
        </div>

        <div class="card-content">
          <h3>Reto de Bolirrana</h3>
          <p>Arma tu grupo de amigos, demuestra tu puntería y disfruta una competencia amistosa acompañada de tu bebida favorita.</p>
          <span class="card-location"><span>&#127919;</span> Diversión entre amigos</span>
        </div>
      </article>

      <article class="card promo-card" data-code="PICTOGRAMA-BANDERAS-003">
        <div class="card-image">
          <img src="../../../bar/Pictograma/img/general3.png" alt="Decoración de banderas en el techo">
          <span class="card-badge">Experiencia</span>
        </div>

        <div class="card-content">
          <h3>Una Vuelta por el Mundo</h3>
          <p>Disfruta la decoración de banderas en el techo, conoce nuevos sabores y vive una experiencia diferente sin salir de la ciudad.</p>
          <span class="card-location"><span>&#127758;</span> Ambiente internacional</span>
        </div>
      </article>

      <article class="card promo-card" data-code="PICTOGRAMA-CAFE-004">
        <div class="card-image">
          <img src="../../../bar/Pictograma/img/general4.png" alt="Café para compartir">
          <span class="card-badge">Café</span>
        </div>

        <div class="card-content">
          <h3>Tardes de Café</h3>
          <p>Haz una pausa, conversa y disfruta una deliciosa taza de café en un espacio cómodo y acogedor.</p>
          <span class="card-location"><span>&#9749;</span> Un plan para conversar</span>
        </div>
      </article>

      <article class="card promo-card" data-code="PICTOGRAMA-GRANIZADOS-005">
        <div class="card-image">
          <img src="../../../bar/Pictograma/img/general5.png" alt="Granizados de diferentes sabores">
          <span class="card-badge">Granizados</span>
        </div>

        <div class="card-content">
          <h3>Granizados de Sabores</h3>
          <p>Refréscate con granizados llenos de sabor, perfectos para acompañar una tarde o comenzar una noche entre amigos.</p>
          <span class="card-location"><span>&#129482;</span> Refrescantes y deliciosos</span>
        </div>
      </article>

      <article class="card promo-card" data-code="PICTOGRAMA-AMIGOS-006">
        <div class="card-image">
          <img src="../../../bar/Pictograma/img/general6.png" alt="Encuentro entre amigos">
          <span class="card-badge">Buen ambiente</span>
        </div>

        <div class="card-content">
          <h3>Encuentro entre Amigos</h3>
          <p>Comparte licores, cócteles y buena música en un ambiente ideal para conversar, reír y pasar un momento especial.</p>
          <span class="card-location"><span>&#127881;</span> Música, bebidas y diversión</span>
        </div>
      </article>

    </div>
  </section>
</section>

<script defer src="../../../bar/Pictograma/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
