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
      <span>Comida rápida en Chapinero</span>

      <h1>Antojos rápidos, sabrosos y perfectos para cualquier plan</h1>

      <small>
        Encuentra lugares para disfrutar hamburguesas, pizzas, perros calientes,
        salchipapas, alitas, sándwiches y opciones rápidas para comer en el lugar,
        pedir, recoger o compartir con amigos.
      </small>
    </div>

      <!-- LISTA DE FILTROS -->
      <ul class="filter-list">
        <li data-filter="all" class="active">Todos</li>
        <li data-filter="hamburguesas">Hamburguesas</li>
        <li data-filter="pizzas">Pizzas</li>
        <li data-filter="perros-calientes">Perros calientes</li>
        <li data-filter="salchipapas">Salchipapas</li>
        <li data-filter="alitas">Alitas</li>
        <li data-filter="para-llevar">Para llevar</li>
      </ul>
    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card place-card" data-category="hamburguesas">
        <img src="../home/img/esta_apartado.png" alt="Hamburguesas en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.8</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="pizzas">
        <img src="../home/img/esta_apartado.png" alt="Pizzerías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍕</div>
          <h5>Pizzerías</h5>
          <small>Pizza por porción, familiar, artesanal, tradicional y opciones perfectas para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="perros-calientes">
        <img src="../home/img/general.png" alt="Perros calientes en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🌭</div>
          <h5>Perros calientes</h5>
          <small>Perros clásicos, especiales, gratinados, con toppings y muchas salsas.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="salchipapas">
        <img src="../home/img/general.png" alt="Salchipapas en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍟</div>
          <h5>Salchipapas</h5>
          <small>Papas, salchichas, carnes, quesos, salsas y combinaciones ideales para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="alitas">
        <img src="../home/img/rapida8.png" alt="Alitas en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍗</div>
          <h5>Alitas y snacks</h5>
          <small>Alitas BBQ, picantes, apanadas, acompañamientos y opciones para planes casuales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="para-llevar">
        <img src="../home/img/rapida9.png" alt="Comida rápida para llevar en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥡</div>
          <h5>Comida para llevar</h5>
          <small>Pedidos rápidos para recoger, llevar a casa, compartir o disfrutar al paso.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
