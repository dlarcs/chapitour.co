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
      <span>Panaderías en Chapinero</span>

      <h1>¿Qué sabor de pan quieres descubrir hoy?</h1>

      <small>
        Explora panaderías en Chapinero: pan fresco, desayunos, onces,
        cafés, amasijos, pastelería, productos artesanales y lugares perfectos
        para disfrutar algo recién horneado.
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


      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general5.png" alt="Amasijos colombianos en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🫓</div>
          <h5>Amasijos</h5>
          <small>Almojábanas, pandebonos, buñuelos, arepas y sabores tradicionales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general6.png" alt="Pastelería de panadería en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥐</div>
          <h5>Pastelería</h5>
          <small>Croissants, hojaldres, panes dulces, milhojas y productos para antojarse.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general7.png" alt="Tortas y ponqués en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Tortas y ponqués</h5>
          <small>Opciones dulces para cumpleaños, reuniones, celebraciones o detalles especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general8.png" alt="Panadería saludable en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🌾</div>
          <h5>Opciones saludables</h5>
          <small>Panes integrales, semillas, opciones ligeras y productos con ingredientes naturales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general9.png" alt="Panadería para llevar en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🛍️</div>
          <h5>Para llevar</h5>
          <small>Pan, café, snacks, desayunos rápidos y productos listos para disfrutar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
