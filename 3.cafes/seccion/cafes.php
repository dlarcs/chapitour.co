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
      <span>Lugares para tomar café</span>

      <h1>Encuentra el café perfecto para tu momento</h1>

      <small>
        Descubre lugares en Chapinero para disfrutar un buen café,
        compartir una conversación, probar postres o pasar una tarde tranquila.
      </small>
    </div>
    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="cafe">De especialidad</li>
      <li data-filter="capuchino">Cultural</li>
      <li data-filter="moca">Brunch</li>
      <li data-filter="postres">Café y postres</li>
      <li data-filter="bebidas-frias">Pet friendly</li>
    </ul>
    <div class="categories-grid">

      <a href="../1.1.bartin/index.php" class="category-card place-card" data-category="cafe">
        <img src="../home/img/esta_apartado.png" alt="Café especial en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.8</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="capuchino">
        <img src="../home/img/esta_apartado.png" alt="Capuchino en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.3</div>
          <h5>Capuccino House</h5>
          <small>Capuchinos cremosos y ambiente tranquilo.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="moca">
        <img src="../home/img/general3.png" alt="Moca y bebidas dulces en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.5</div>
          <h5>Moca Café</h5>
          <small>Mocas, chocolate y bebidas especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="postres">
        <img src="../home/img/general7.png" alt="Café y postres en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.2</div>
          <h5>Dulce Aroma</h5>
          <small>Café, tortas y postres caseros.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="bebidas-frias">
        <img src="../home/img/general8.png" alt="Bebidas frías con café en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.4</div>
          <h5>Frappé Station</h5>
          <small>Frappés, cafés fríos y malteadas.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card place-card" data-category="cafe">
        <img src="../home/img/general9.png" alt="Lugar para tomar café en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">4.6</div>
          <h5>La Mesa Café</h5>
          <small>Café, conversación y buenos momentos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
