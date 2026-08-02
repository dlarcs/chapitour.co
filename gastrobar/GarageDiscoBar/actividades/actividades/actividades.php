<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/GarageDiscoBar/actividades/actividades/actividades.css';
$jsFile  = $base . '/gastrobar/GarageDiscoBar/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastrobar/GarageDiscoBar/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Experiencias & Eventos</span>

  <h2>Aquí cada noche se convierte en una historia</h2>

  <p>
    Canta, baila, celebra y disfruta experiencias llenas de música, sabor y diversión. En Garage Disco Bar siempre hay un plan esperando por ti.
  </p>

  <!-- TARJETAS - HTML -->
  <section class="cards-section">
    <div class="cards-grid">

      <article class="card promo-card" data-code="GARAGE-KARAOKE-001">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general15.jpg" alt="Noche de karaoke en Garage Disco Bar">
          <span class="card-badge">Karaoke</span>
        </div>

        <div class="card-content">
          <h3>¡El escenario es tuyo!</h3>
          <p>Toma el micrófono, canta ese éxito que nunca falla y vive una noche llena de música, risas y momentos inolvidables.</p>
          <span class="card-location"><span>&#127908;</span> Canta, disfruta y sorprende a todos</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-CROSSOVER-002">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general13.jpg" alt="Fiesta crossover en Garage Disco Bar">
          <span class="card-badge">Crossover</span>
        </div>

        <div class="card-content">
          <h3>Una noche, todos los ritmos</h3>
          <p>Del reguetón a la salsa y de los clásicos a los éxitos del momento. Ven preparado para bailar sin parar.</p>
          <span class="card-location"><span>&#127925;</span> Música para todos los gustos</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-SHOWS-003">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general17.jpg" alt="Shows especiales en Garage Disco Bar">
          <span class="card-badge">Shows en vivo</span>
        </div>

        <div class="card-content">
          <h3>Cada 15 días, algo nuevo</h3>
          <p>Déjate sorprender por artistas invitados, presentaciones especiales y espectáculos que harán de tu noche una experiencia diferente.</p>
          <span class="card-location"><span>&#127917;</span> Presentaciones para vivir y compartir</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-GASTROBAR-004">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general6.jpg" alt="Comida y bebidas en Garage Disco Bar">
          <span class="card-badge">Gastrobar</span>
        </div>

        <div class="card-content">
          <h3>El sabor también es parte de la fiesta</h3>
          <p>Disfruta comida, cocteles y bebidas ideales para compartir mientras te preparas para una gran noche.</p>
          <span class="card-location"><span>&#127828;</span> Sabores para acompañar cada momento</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-DISCOBAR-005">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general1.jpg" alt="Ambiente de fiesta en Garage Disco Bar">
          <span class="card-badge">Disco Bar</span>
        </div>

        <div class="card-content">
          <h3>Enciende la noche</h3>
          <p>Luces, música, bebidas y toda la energía que necesitas para celebrar cumpleaños, encuentros y noches especiales.</p>
          <span class="card-location"><span>&#127926;</span> El punto de encuentro para celebrar</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GARAGE-BAILE-006">
        <div class="card-image">
          <img src="../../../gastrobar/GarageDiscoBar/img/general10.jpg" alt="Noche de baile en Garage Disco Bar">
          <span class="card-badge">Baile</span>
        </div>

        <div class="card-content">
          <h3>Que nadie se quede sentado</h3>
          <p>Reúne a tu parche, entra a la pista y déjate llevar por una noche cargada de ritmo, alegría y buena energía.</p>
          <span class="card-location"><span>&#128131;</span> Ven a bailar y celebra con nosotros</span>
        </div>
      </article>

    </div>
  </section>
</section>

<script defer src="../../../gastrobar/GarageDiscoBar/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
