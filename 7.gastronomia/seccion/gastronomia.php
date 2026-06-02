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
      <span>Gastronomía en Chapinero</span>

      <h1>¿Qué sabor quieres descubrir hoy?</h1>

      <small>
        Explora lugares gastronómicos en Chapinero: restaurantes, comida local,
        cocina internacional, brunch, cafés, postres, comida rápida y espacios
        perfectos para disfrutar, compartir o probar algo nuevo.
      </small>
    </div>

    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card">
        <img src="../home/img/general1.png" alt="Gastrobares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.1</div>
          <h5>Da click para mirar la página</h5>
          <small>Cocteles de autor, gastronomía y mas, da click aquí</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>
      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general5.png" alt="Brunch en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥐</div>
          <h5>Brunch y desayunos</h5>
          <small>Opciones para empezar el día con café, pan, huevos, frutas y platos especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general6.png" alt="Cafés gastronómicos en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Cafés gastronómicos</h5>
          <small>Café, bebidas especiales, repostería y espacios tranquilos para conversar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general7.png" alt="Comida rápida en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍔</div>
          <h5>Comida rápida</h5>
          <small>Hamburguesas, pizzas, perros calientes, alitas, empanadas y antojos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general8.png" alt="Postres en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Postres</h5>
          <small>Tortas, helados, waffles, cafés dulces y detalles para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general9.png" alt="Comida saludable en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥗</div>
          <h5>Comida saludable</h5>
          <small>Bowls, ensaladas, jugos naturales, opciones vegetarianas y platos frescos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
