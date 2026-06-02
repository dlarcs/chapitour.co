<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.tipo_bares/seccion/tipo_bares.css';
$jsFile  = $base . '/1.tipo_bares/seccion/tipo_bares.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../1.tipo_bares/seccion/tipo_bares.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="main-categories">
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
      <span>Lugares LGBTIQ+ en Chapinero</span>

      <h1>¿Qué espacio quieres descubrir hoy?</h1>

      <small>
        Encuentra bares, discotecas, gastrobares, cafés, shows y espacios
        diversos en Chapinero para compartir, bailar, celebrar y disfrutar
        en un ambiente seguro e incluyente.
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
        <img src="../home/img/esta_apartado.png" alt="Discotecas LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🪩</div>
          <h5>Discotecas</h5>
          <small>Música, baile, luces y ambientes llenos de energía para celebrar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/lgbtiq3.png" alt="Gastrobares LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍔</div>
          <h5>Gastrobares</h5>
          <small>Comida, cocteles, música y espacios cómodos para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>
      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/lgbtiq7.png" alt="Eventos LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎤</div>
          <h5>Eventos y noches temáticas</h5>
          <small>Karaoke, fiestas, presentaciones, encuentros y planes especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/lgbtiq8.png" alt="Espacios culturales LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎭</div>
          <h5>Espacios culturales</h5>
          <small>Arte, conversación, comunidad, talleres y propuestas alternativas.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/lgbtiq9.png" alt="Zonas para compartir en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">♡</div>
          <h5>Lugares para compartir</h5>
          <small>Ambientes seguros, diversos y amigables para pasar un buen momento.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
