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
      <span>Postres en Chapinero</span>

      <h1>¿Qué antojo dulce quieres descubrir hoy?</h1>

      <small>
        Explora lugares de Chapinero para disfrutar tortas, helados,
        waffles, cafés dulces, postres artesanales y sabores perfectos
        para compartir.
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
        <img src="../home/img/postres5.png" alt="Cafés dulces en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Cafés dulces</h5>
          <small>Café, chocolate caliente, postres pequeños y espacios acogedores.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/postres6.png" alt="Tortas personalizadas en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎂</div>
          <h5>Tortas personalizadas</h5>
          <small>Diseños especiales para cumpleaños, eventos y celebraciones.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/postres7.png" alt="Postres artesanales en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍮</div>
          <h5>Postres artesanales</h5>
          <small>Recetas caseras, sabores únicos y preparaciones hechas con detalle.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/postres8.png" alt="Chocolaterías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍫</div>
          <h5>Chocolaterías</h5>
          <small>Chocolate, bombones, bebidas calientes y detalles para regalar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../0.invitación/index.php" class="category-card">
        <img src="../home/img/postres9.png" alt="Postres para llevar en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥡</div>
          <h5>Postres para llevar</h5>
          <small>Opciones rápidas para pedir, recoger, regalar o disfrutar en casa.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
