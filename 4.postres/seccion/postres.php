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
      <span>Postres en Chapinero</span>

      <h1>¿Qué antojo dulce quieres descubrir hoy?</h1>

      <small>
        Descubre panaderías, reposterías, heladerías, cafés dulces,
        chocolaterías y lugares especializados en postres para compartir,
        regalar o darte un antojo especial.
      </small>
    </div>

    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="panaderia">Panaderías</li>
      <li data-filter="reposteria">Reposterías</li>
      <li data-filter="heladeria">Heladerías</li>
      <li data-filter="cafe-dulce">Cafés dulces</li>
      <li data-filter="chocolateria">Chocolaterías</li>
      <li data-filter="postres-para-llevar">Para llevar</li>
    </ul>

    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card place-card" data-category="reposteria">
        <img src="../home/img/esta_apartado.png" alt="Lugar especializado en postres en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="cafe-dulce">
        <img src="../home/img/postres5.png" alt="Cafés dulces en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Cafés dulces</h5>
          <small>Cafés, chocolate caliente, postres pequeños y espacios acogedores para conversar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="panaderia">
        <img src="../home/img/postres6.png" alt="Panaderías con postres en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥐</div>
          <h5>Panaderías y pastelerías</h5>
          <small>Pan dulce, tortas, hojaldres, ponqués, galletas y productos recién horneados.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="reposteria">
        <img src="../home/img/postres7.png" alt="Reposterías artesanales en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎂</div>
          <h5>Reposterías artesanales</h5>
          <small>Tortas personalizadas, cupcakes, postres caseros y preparaciones hechas con detalle.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="chocolateria">
        <img src="../home/img/postres8.png" alt="Chocolaterías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍫</div>
          <h5>Chocolaterías</h5>
          <small>Chocolate, bombones, bebidas calientes, detalles dulces y opciones para regalar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="heladeria">
        <img src="../home/img/postres9.png" alt="Heladerías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍦</div>
          <h5>Heladerías</h5>
          <small>Helados artesanales, malteadas, waffles, toppings y postres fríos para disfrutar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
