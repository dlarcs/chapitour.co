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
<section class="main-categories">
  <div class="main-categories__inner">

    <div class="main-categories__header">
      <span>Artesanías en Chapinero</span>

      <h1>¿Qué creación local quieres descubrir hoy?</h1>

      <small>
        Explora lugares de artesanías en Chapinero: productos hechos a mano,
        accesorios, decoración, arte local, regalos especiales, diseño independiente
        y piezas únicas creadas por emprendedores y artistas.
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
        <img src="../home/img/general2.png" alt="Accesorios artesanales en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">📿</div>
          <h5>Accesorios artesanales</h5>
          <small>Aretes, collares, pulseras, tejidos y complementos con identidad propia.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general3.png" alt="Decoración artesanal en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🏺</div>
          <h5>Decoración</h5>
          <small>Objetos decorativos, cerámica, tejidos y detalles para transformar espacios.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general4.png" alt="Arte local en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎨</div>
          <h5>Arte local</h5>
          <small>Ilustraciones, pinturas, piezas creativas y propuestas de artistas independientes.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general5.png" alt="Regalos artesanales en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎁</div>
          <h5>Regalos especiales</h5>
          <small>Detalles únicos para cumpleaños, fechas especiales o recuerdos con significado.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="0.invitación/index.php" class="category-card">
        <img src="../home/img/general6.png" alt="Moda artesanal en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">👜</div>
          <h5>Moda artesanal</h5>
          <small>Bolsos, prendas, textiles, tejidos y diseños hechos por marcas locales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>
    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
