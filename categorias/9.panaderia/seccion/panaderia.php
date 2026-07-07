<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/categorias/1.tipo_bares/seccion/tipo_bares.css';
$jsFile  = $base . '/categorias/1.tipo_bares/seccion/tipo_bares.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../categorias/1.tipo_bares/seccion/tipo_bares.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="main-categories">

  <div class="main-categories__inner">

    <div class="main-categories__header">
      <span>Panadería y pastelería en Chapinero</span>

      <h1>Panes, tortas, amasijos y sabores recién horneados</h1>

      <small>
        Descubre panaderías, pastelerías, amasijos tradicionales, tortas,
        ponqués, cafés, desayunos y productos artesanales para disfrutar
        algo dulce, salado o recién salido del horno.
      </small>
    </div>

    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="pan-artesanal">Pan artesanal</li>
      <li data-filter="amasijos">Amasijos</li>
      <li data-filter="pasteleria">Pastelería</li>
      <li data-filter="tortas">Tortas</li>
      <li data-filter="desayunos">Desayunos</li>
      <li data-filter="para-llevar">Para llevar</li>
    </ul>

    <div class="categories-grid">

      <a href="../../1.1.bartin/index.php" class="category-card place-card" data-category="pan-artesanal">
        <img src="../../home/img/esta_apartado.png" alt="Pan artesanal en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥖</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="amasijos">
        <img src="../../home/img/general5.png" alt="Amasijos colombianos en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🫓</div>
          <h5>Amasijos tradicionales</h5>
          <small>Almojábanas, pandebonos, buñuelos, arepas, pan de yuca y sabores colombianos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="pasteleria">
        <img src="../../home/img/general6.png" alt="Pastelería en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥐</div>
          <h5>Pastelería</h5>
          <small>Croissants, hojaldres, milhojas, panes dulces, galletas y productos para antojarse.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="tortas">
        <img src="../../home/img/general7.png" alt="Tortas y ponqués en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Tortas y ponqués</h5>
          <small>Tortas, ponqués, cupcakes y opciones dulces para cumpleaños, reuniones y celebraciones.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="desayunos">
        <img src="../../home/img/general8.png" alt="Desayunos de panadería en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Desayunos y onces</h5>
          <small>Café, chocolate, pan fresco, huevos, jugos, tamales, snacks y opciones para empezar el día.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="para-llevar">
        <img src="../../home/img/general9.png" alt="Panadería para llevar en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🛍️</div>
          <h5>Productos para llevar</h5>
          <small>Pan, café, postres, desayunos rápidos, snacks y productos listos para disfrutar en casa.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../../categorias/1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
