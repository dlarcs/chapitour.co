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
      <span>Gastronomía en Chapinero</span>

      <h1>Restaurantes y sabores para descubrir en Chapinero</h1>

      <small>
        Explora restaurantes mexicanos, comida casera, propuestas gourmet,
        cocina internacional, platos saludables y espacios gastronómicos
        perfectos para almorzar, cenar, compartir o probar algo nuevo.
      </small>
    </div>
    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="mexicanos">Mexicanos</li>
      <li data-filter="caseros">Caseros</li>
      <li data-filter="gourmet">Gourmet</li>
      <li data-filter="internacional">Internacional</li>
      <li data-filter="saludable">Saludable</li>
      <li data-filter="para-compartir">Para compartir</li>
    </ul>
    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card place-card" data-category="mexicanos">
        <img src="../home/img/general1.png" alt="Restaurantes mexicanos en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🌮</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="caseros">
        <img src="../home/img/general5.png" alt="Restaurantes de comida casera en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍲</div>
          <h5>Comida casera</h5>
          <small>Almuerzos, sopas, platos tradicionales y sabores hechos como en casa.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="gourmet">
        <img src="../home/img/general6.png" alt="Restaurantes gourmet en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍽️</div>
          <h5>Restaurantes gourmet</h5>
          <small>Propuestas de autor, platos especiales, presentaciones cuidadas y experiencias diferentes.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="internacional">
        <img src="../home/img/general7.png" alt="Cocina internacional en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🌍</div>
          <h5>Cocina internacional</h5>
          <small>Sabores del mundo, comida italiana, asiática, peruana, árabe y otras propuestas.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="saludable">
        <img src="../home/img/general8.png" alt="Restaurantes de comida saludable en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥗</div>
          <h5>Comida saludable</h5>
          <small>Bowls, ensaladas, jugos naturales, opciones vegetarianas y platos frescos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="para-compartir">
        <img src="../home/img/general9.png" alt="Restaurantes para compartir en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍛</div>
          <h5>Restaurantes para compartir</h5>
          <small>Platos fuertes, entradas, picadas, cenas casuales y espacios para ir con amigos o familia.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
