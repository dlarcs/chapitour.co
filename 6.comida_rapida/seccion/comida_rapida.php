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
      <span>Comida rápida en Chapinero</span>

      <h1>¿Qué antojo quieres disfrutar hoy?</h1>

      <small>
        Encuentra lugares de comida rápida en Chapinero para disfrutar
        hamburguesas, pizzas, perros calientes, alitas, empanadas,
        salchipapas y opciones perfectas para comer algo delicioso.
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
        <img src="../home/img/esta_apartado.png" alt="Pizzerías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍕</div>
          <h5>Pizzerías</h5>
          <small>Pizza por porción, familiar, artesanal y opciones para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/general" alt="Perros calientes en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🌭</div>
          <h5>Perros calientes</h5>
          <small>Perros clásicos, especiales, gratinados y llenos de salsas.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/general.png" alt="Salchipapas en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍟</div>
          <h5>Salchipapas</h5>
          <small>Papas, salchichas, carnes, quesos y combinaciones para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/rapida8.png" alt="Sandwiches en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥪</div>
          <h5>Sándwiches y wraps</h5>
          <small>Opciones rápidas, frescas y fáciles para comer en cualquier momento.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/rapida9.png" alt="Comida para llevar en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥡</div>
          <h5>Comida para llevar</h5>
          <small>Pedidos rápidos para recoger, compartir en casa o disfrutar al paso.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
