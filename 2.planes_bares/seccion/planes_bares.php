<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.tipo_bares/seccion/tipo_bares.css';
$jsFile  = $base . '/1.tipo_bares/seccion/tipo_bares.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../1.tipo_bares/seccion/tipo_bares.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="main-categories visible">


  <div class="main-categories__inner">

    <div class="main-categories__header">
      <span>Planes de bares</span>

      <h1>Encuentra el plan perfecto para salir en Chapinero</h1>

      <small>
        Descubre planes en bares para tomar algo, compartir,
        bailar, escuchar música en vivo o disfrutar una noche con estilo.
      </small>
    </div>
    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="after-office">After office</li>
      <li data-filter="rumba">Rumba</li>
      <li data-filter="musica-en-vivo">Música en vivo</li>
      <li data-filter="cocteles">Cócteles</li>
      <li data-filter="deportes">Deportes</li>
    </ul>

    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card place-card" data-category="after-office">
        <img src="../home/img/esta_apartado.png" alt="After office en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.1</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="rumba">
        <img src="../home/img/esta_apartado.png" alt="Rumba en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>Noche de rumba</h5>
          <small>Música, baile y ambiente para disfrutar la noche.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="musica-en-vivo">
        <img src="../home/img/esta_apartado.png" alt="Música en vivo en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>Música en vivo</h5>
          <small>Bandas, artistas locales y noches con sonido directo.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="cocteles">
        <img src="../home/img/esta_apartado.png" alt="Cócteles en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>Plan de cócteles</h5>
          <small>Sabores, mezclas y espacios perfectos para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="deportes">
        <img src="../home/img/general8.png" alt="Ver deportes en bares de Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>Ver partidos</h5>
          <small>Pantallas, cerveza fría y ambiente deportivo.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="after-office">
        <img src="../home/img/general9.png" alt="Plan casual en bar de Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>Plan casual</h5>
          <small>Una salida tranquila para conversar y tomar algo.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
