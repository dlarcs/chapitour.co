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
      <span>Categorías principales</span>
      <h1>¿Qué plan quieres descubrir hoy?</h1>
      <small>
        Elige una categoría y encuentra lugares recomendados para comer,
        compartir, bailar, tomar café o pasar un buen rato.
      </small>
    </div>

    <div class="categories-grid">



      <a href="../2.planes_bares/index.php" class="category-card">
        <img src="../home/img/cocktel.png" alt="Gastrobares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍸</div>
          <h5>Noches de chapinero</h5>
          <small>Comida, cocteles y ambientes perfectos para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../7.gastronomia/index.php" class="category-card">
        <img src="../home/img/restaurante.png" alt="Restaurantes">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍴</div>
          <h5>Sabores y experiencias</h5>
          <small>Sabores locales, cocina moderna y lugares recomendados.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../1.tipo_bares/index.php" class="category-card">
        <img src="../home/img/bar.png" alt="Bares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍺</div>
          <h5>Tertulias de Chapinero</h5>
          <small>Espacios para brindar, escuchar música y disfrutar la noche.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../8.artesanias/index.php" class="category-card">
        <img src="../home/img/artesanias_bolso.png" alt="Bares metaleros">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🤘</div>
          <h5>Tesoros Artesanales</h5>
          <small>Espacios tranquilos disfrutar y dar detalles.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../5.LGBTIQ+/index.php" class="category-card">
        <img src="../home/img/lgbtiq+.png"style="object-fit: cover; object-position: 50% 20%;"  alt="Bares LGBTIQ+">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">♡</div>
          <h5>Orgullo y planes</h5>
          <small>Lugares seguros, diversos y llenos de energía.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../3.cafes/index.php" class="category-card">
        <img src="../home/img/cafe1.png" alt="Cafés">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Rincones del Café</h5>
          <small>Café, capuchino, frapuchino y ambientes alternativos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../9.panaderia/index.php" class="category-card">
        <img src="../home/img/panaderia.png" alt="Panaderías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🧁</div>
          <h5>  Dulce Tradición</h5>
          <small>Pan fresco, desayunos, onces y sabores tradicionales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../4.postres/index.php" class="category-card">
        <img src="../home/img/postre1.png" alt="Pastelerías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Dulce pecado</h5>
          <small>Tortas, postres, detalles dulces y momentos especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../6.comida_rapida/index.php" class="category-card">
        <img src="../home/img/hamburguesa1.png" alt="Comida rápida">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍔</div>
          <h5>Rápidos y Sabrosos
            </h5>
          <small>Hamburguesas, pizzas, perros, empanadas y antojos.</small>
        </div>


        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>
<script defer src="../1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
