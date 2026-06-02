<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.tipo_bares/seccion/tipo_bares.css';
$jsFile  = $base . '/1.tipo_bares/seccion/tipo_bares.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../1.tipo_bares/seccion/tipo_bares.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="main-categories visible">
  <!-- LISTA DE FILTROS -->
  <ul class="filter-list">
    <li data-filter="all" class="active">Todos</li>
    <li data-filter="bar">Bar</li>
    <li data-filter="gastro-bar">Gastro Bar</li>
    <li data-filter="bar-metal">Bar Metal</li>
    <li data-filter="bar-gay">Bar Gay</li>
    <li data-filter="cocteleria">Coctelería</li>
  </ul>

  <div class="main-categories__inner">

    <div class="main-categories__header">
    <span>Tipos de bares</span>

    <h1>Encuentra el bar perfecto para tu plan</h1>

    <small>
      Descubre bares en Chapinero para tomar algo, compartir,
      bailar, escuchar música o vivir una noche con estilo.
    </small>
  </div>

    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card">
        <img src="../home/img/esta_apartado.png" alt="Gastrobares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.1</div>
          <h5>Da click para mirar la página</h5>
          <small>Cocteles de autor, gastronomía y mas, da click aquí</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/esta_apartado.png" alt="Restaurantes">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>NOMBRE DEL BAR</h5>
          <small>DESCRIPCIÓN 5 palabras</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/esta_apartado.png" alt="Bares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>NOMBRE DEL BAR</h5>
          <small>DESCRIPCIÓN</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/general7.png" alt="Panaderías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>NOMBRE DEL BAR</h5>
          <small>DESCRIPCIÓN</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/general8.png" alt="Pastelerías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>NOMBRE DEL BAR</h5>
          <small>DESCRIPCIÓN</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/general9.png" alt="Comida rápida">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">#</div>
          <h5>NOMBRE DEL BAR</h5>
          <small>DESCRIPCIÓN</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>
<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
