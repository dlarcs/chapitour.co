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
      <span>Lugares recomendados</span>

      <h1>¿Quieres visitar lo mejor de Chapinero?</h1>

      <small>
        Elige un lugar destacado y encuentra opciones recomendadas para comer,
        compartir, bailar, tomar café o pasar un buen rato.
      </small>
    </div>
    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="gastro-bar">Gastro Bar</li>
      <li data-filter="bar-metal">Bar Metal</li>
      <li data-filter="cafes">Cafés</li>
      <li data-filter="panaderias">Panaderías</li>
      <li data-filter="pastelerias">Pastelerías</li>
      <li data-filter="comida-rapida">Comida rápida</li>
    </ul>
    <div class="categories-grid">

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="gastro-bar">
        <img src="../home/img/esta_apartado.png" alt="Gastrobares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍸</div>
          <h5>Gastrobares</h5>
          <small>Comida, cocteles y ambientes para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="bar-metal">
        <img src="../home/img/general4.png" alt="Bares metaleros">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🤘</div>
          <h5>Bares metaleros</h5>
          <small>Rock, metal, cerveza y ambientes alternativos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="cafes">
        <img src="../home/img/general6.png" alt="Cafés">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Cafés</h5>
          <small>Espacios tranquilos para conversar, trabajar o descansar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="panaderias">
        <img src="../home/img/general7.png" alt="Panaderías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🧁</div>
          <h5>Panaderías</h5>
          <small>Pan fresco, desayunos, onces y sabores tradicionales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="pastelerias">
        <img src="../home/img/general8.png" alt="Pastelerías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Pastelerías</h5>
          <small>Tortas, postres, detalles dulces y momentos especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="comida-rapida">
        <img src="../home/img/general9.png" alt="Comida rápida">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍔</div>
          <h5>Comida rápida</h5>
          <small>Hamburguesas, pizzas, perros, empanadas y antojos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
