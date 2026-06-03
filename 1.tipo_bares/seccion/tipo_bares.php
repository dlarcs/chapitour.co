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
    <span>Tipos de bares</span>

    <h1>Encuentra el bar perfecto para tu plan</h1>

    <small>
      Descubre bares en Chapinero para tomar algo, compartir,
      bailar, escuchar música o vivir una noche con estilo.
    </small>
  </div>
  <!-- LISTA DE FILTROS -->
  <ul class="filter-list">
    <li data-filter="all" class="active">Todos</li>
    <li data-filter="bar">Metal</li>
    <li data-filter="gastro-bar">Rock</li>
    <li data-filter="bar-metal">Salsa</li>
    <li data-filter="bar-gay">Bar LGBTIQ+</li>
    <li data-filter="cocteleria">Deportes</li>
  </ul>
  <div class="categories-grid">

    <a href="../1.1.bartin/index.php" class="category-card place-card" data-category="bar">
      <img src="../home/img/esta_apartado.png" alt="Bar metal en Chapinero">
      <div class="category-card__overlay"></div>

      <div class="category-card__content">
        <div class="category-card__icon">4.1</div>
        <h5>Da click</h5>
        <small>Página esencial para todos </small>
      </div>

      <span class="category-card__arrow">→</span>
    </a>

    <a href="../0.invitación/index.php" class="category-card place-card" data-category="gastro-bar">
      <img src="../home/img/esta_apartado.png" alt="Bar rock en Chapinero">
      <div class="category-card__overlay"></div>

      <div class="category-card__content">
        <div class="category-card__icon">#</div>
        <h5>Distrito Rock Bar</h5>
        <small>Música en vivo, cerveza y noches con actitud.</small>
      </div>

      <span class="category-card__arrow">→</span>
    </a>

    <a href="../0.invitación/index.php" class="category-card place-card" data-category="bar-metal">
      <img src="../home/img/esta_apartado.png" alt="Bar de salsa en Chapinero">
      <div class="category-card__overlay"></div>

      <div class="category-card__content">
        <div class="category-card__icon">#</div>
        <h5>Salsa Urbana</h5>
        <small>Baile, sabor latino y ambiente para compartir.</small>
      </div>

      <span class="category-card__arrow">→</span>
    </a>

    <a href="../0.invitación/index.php" class="category-card place-card" data-category="bar-gay">
      <img src="../home/img/general7.png" alt="Bar LGBTIQ+ en Chapinero">
      <div class="category-card__overlay"></div>

      <div class="category-card__content">
        <div class="category-card__icon">#</div>
        <h5>Rainbow Club</h5>
        <small>Diversidad, música, baile y una noche libre.</small>
      </div>

      <span class="category-card__arrow">→</span>
    </a>

    <a href="../0.invitación/index.php" class="category-card place-card" data-category="cocteleria">
      <img src="../home/img/general8.png" alt="Bar deportivo en Chapinero">
      <div class="category-card__overlay"></div>

      <div class="category-card__content">
        <div class="category-card__icon">#</div>
        <h5>Sport Beer House</h5>
        <small>Pantallas, cerveza fría y partidos en vivo.</small>
      </div>

      <span class="category-card__arrow">→</span>
    </a>

    <a href="../0.invitación/index.php" class="category-card place-card" data-category="gastro-bar">
      <img src="../home/img/general9.png" alt="Bar rock alternativo en Chapinero">
      <div class="category-card__overlay"></div>

      <div class="category-card__content">
        <div class="category-card__icon">#</div>
        <h5>Garage Rock</h5>
        <small>Sonidos clásicos, ambiente casual y buena vibra.</small>
      </div>

      <span class="category-card__arrow">→</span>
    </a>

  </div>

  </div>
</section>
<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
