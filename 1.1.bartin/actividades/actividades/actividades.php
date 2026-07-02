<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.1.bartin/actividades/actividades/actividades.css';
$jsFile  = $base . '/1.1.bartin/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../1.1.bartin/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Actividades & Promociones</span>

  <h2>Lo mejor de este mes/año</h2>

  <p>
    Conoce nuestras promociones y actividades por mes, ven y disfruta de todo lo que te ofrecemos
  </p>

  <!-- TARJETAS - HTML -->
  <section class="cards-section">

    <div class="cards-grid">
      <article class="card">
        <div class="card-image">
          <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1200&auto=format&fit=crop" alt="Pizza">
          <span class="card-badge">2x1</span>
        </div>

        <div class="card-content">
          <h3>La Pizzería</h3>
          <p>2x1 en pizzas seleccionadas de lunes a jueves.</p>
          <span class="card-location">🗓️ 14 de Julio 2026</span>
        </div>
      </article>

      <article class="card">
        <div class="card-image">
          <img src="https://images.unsplash.com/photo-1470337458703-46ad1756a187?q=80&w=1200&auto=format&fit=crop" alt="Cóctel">
          <span class="card-badge">Happy Hour</span>
        </div>

        <div class="card-content">
          <h3>Salvo Patria</h3>
          <p>Happy Hour en cócteles clásicos de 5:00 p.m. a 8:00 p.m.</p>
          <span class="card-location">🗓️ 31 de Octubre 2026</span>
        </div>
      </article>

      <article class="card">
        <div class="card-image">
          <img src="https://images.unsplash.com/photo-1525351484163-7529414344d8?q=80&w=1200&auto=format&fit=crop" alt="Brunch">
          <span class="card-badge">10% off</span>
        </div>

        <div class="card-content">
          <h3>Azahar Café</h3>
          <p>10% de descuento en brunch presentando esta promoción.</p>
          <span class="card-location">🗓️ 26 de Agosto 2026</span>
        </div>
      </article>
      <article class="card">
        <div class="card-image">
          <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1200&auto=format&fit=crop" alt="Pizza">
          <span class="card-badge">2x1</span>
        </div>

        <div class="card-content">
          <h3>La Pizzería</h3>
          <p>2x1 en pizzas seleccionadas de lunes a jueves.</p>
          <span class="card-location">🗓️ 14 de Julio 2026</span>
        </div>
      </article>

      <article class="card">
        <div class="card-image">
          <img src="https://images.unsplash.com/photo-1470337458703-46ad1756a187?q=80&w=1200&auto=format&fit=crop" alt="Cóctel">
          <span class="card-badge">Happy Hour</span>
        </div>

        <div class="card-content">
          <h3>Salvo Patria</h3>
          <p>Happy Hour en cócteles clásicos de 5:00 p.m. a 8:00 p.m.</p>
          <span class="card-location">🗓️ 31 de Octubre 2026</span>
        </div>
      </article>

      <article class="card">
        <div class="card-image">
          <img src="https://images.unsplash.com/photo-1525351484163-7529414344d8?q=80&w=1200&auto=format&fit=crop" alt="Brunch">
          <span class="card-badge">10% off</span>
        </div>

        <div class="card-content">
          <h3>Azahar Café</h3>
          <p>10% de descuento en brunch presentando esta promoción.</p>
          <span class="card-location">🗓️ 26 de Agosto 2026</span>
        </div>
      </article>

    </div>
  </section>


</section>

<script defer src="../../1.1.bartin/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
