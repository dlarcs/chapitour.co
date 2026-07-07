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
      <span>Artesanías en Chapinero</span>

      <h1>Creaciones locales, piezas únicas y diseño hecho a mano</h1>

      <small>
        Descubre accesorios, decoración, arte local, regalos especiales,
        moda artesanal, tejidos, cerámica y productos hechos por artistas,
        emprendedores y marcas independientes de Chapinero.
      </small>
    </div>

    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="accesorios">Accesorios</li>
      <li data-filter="decoracion">Decoración</li>
      <li data-filter="arte-local">Arte local</li>
      <li data-filter="regalos">Regalos</li>
      <li data-filter="moda-artesanal">Moda artesanal</li>
      <li data-filter="talleres">Talleres</li>
    </ul>

    <div class="categories-grid">

      <a href="../../1.1.bartin/index.php" class="category-card place-card" data-category="accesorios">
        <img src="../../home/img/esta_apartado.png" alt="Accesorios artesanales en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">📿</div>
          <h5>Da click</h5>
          <small>Página esencial para todos </small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="decoracion">
        <img src="../../home/img/general2.png" alt="Decoración artesanal en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🏺</div>
          <h5>Decoración artesanal</h5>
          <small>Cerámica, tejidos, objetos decorativos, velas, cuadros y detalles para transformar espacios.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="arte-local">
        <img src="../../home/img/general3.png" alt="Arte local en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎨</div>
          <h5>Arte local</h5>
          <small>Ilustraciones, pinturas, piezas creativas, murales pequeños y propuestas de artistas independientes.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="regalos">
        <img src="../../home/img/general4.png" alt="Regalos artesanales en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎁</div>
          <h5>Regalos especiales</h5>
          <small>Detalles únicos para cumpleaños, fechas especiales, recuerdos, sorpresas y regalos con significado.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="moda-artesanal">
        <img src="../../home/img/general5.png" alt="Moda artesanal en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">👜</div>
          <h5>Moda artesanal</h5>
          <small>Bolsos, prendas, textiles, tejidos, bordados y diseños hechos por marcas locales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../0.invitación/index.php" class="category-card place-card" data-category="talleres">
        <img src="../../home/img/general6.png" alt="Talleres artesanales en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🧶</div>
          <h5>Talleres y experiencias</h5>
          <small>Espacios para aprender, crear, pintar, tejer, decorar y vivir experiencias manuales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../../categorias/1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
