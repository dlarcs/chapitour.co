<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.tipo_bares/seccion/tipo_bares.css';
$jsFile  = $base . '/1.tipo_bares/seccion/tipo_bares.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../1.tipo_bares/seccion/tipo_bares.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="main-categories">



  <div class="main-categories__inner">

    <div class="main-categories__header">
      <span>Espacios LGBTIQ+ en Chapinero</span>

      <h1>Lugares, eventos y experiencias diversas para descubrir</h1>

      <small>
        Explora bares, discotecas, shows, eventos temáticos, actividades culturales
        y espacios seguros pensados para compartir, celebrar, conectar y disfrutar
        la diversidad en Chapinero.
      </small>
    </div>
    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="bares-lgbtiq">Bares LGBTIQ+</li>
      <li data-filter="discotecas">Discotecas</li>
      <li data-filter="eventos">Eventos</li>
      <li data-filter="shows">Shows</li>
      <li data-filter="cultura">Cultura</li>
      <li data-filter="comunidad">Comunidad</li>
    </ul>
    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card place-card" data-category="bares-lgbtiq">
        <img src="../home/img/esta_apartado.png" alt="Bares LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🏳️‍🌈</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="discotecas">
        <img src="../home/img/esta_apartado.png" alt="Discotecas LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🪩</div>
          <h5>Discotecas y rumba</h5>
          <small>Música, baile, luces, DJs y noches llenas de energía para celebrar la diversidad.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="shows">
        <img src="../home/img/lgbtiq3.png" alt="Shows LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎤</div>
          <h5>Shows y presentaciones</h5>
          <small>Drag shows, karaoke, música en vivo, comedia, performances y noches especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="eventos">
        <img src="../home/img/lgbtiq7.png" alt="Eventos LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎉</div>
          <h5>Eventos y noches temáticas</h5>
          <small>Fiestas, encuentros, celebraciones, actividades especiales y planes para vivir Chapinero.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="cultura">
        <img src="../home/img/lgbtiq8.png" alt="Espacios culturales LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎭</div>
          <h5>Arte y cultura diversa</h5>
          <small>Espacios con talleres, conversaciones, exposiciones, teatro, cine, arte y propuestas alternativas.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="comunidad">
        <img src="../home/img/lgbtiq9.png" alt="Espacios comunitarios LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">♡</div>
          <h5>Espacios de comunidad</h5>
          <small>Lugares seguros para conectar, compartir experiencias, crear redes y sentirse en casa.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
