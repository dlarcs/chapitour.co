<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/pag_categorias/seccion/pag_categorias.css';
$jsFile  = $base . '/pag_categorias/seccion/pag_categorias.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../pag_categorias/seccion/pag_categorias.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="main-categories">
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

      <a href="#" class="category-card">
        <img src="../home/img/general1.png" alt="Gastrobares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍸</div>
          <h5>Gastrobares</h5>
          <small>Comida, cocteles y ambientes perfectos para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general2.png" alt="Restaurantes">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍴</div>
          <h5>Restaurantes</h5>
          <small>Sabores locales, cocina moderna y lugares recomendados.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general3.png" alt="Bares">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍺</div>
          <h5>Bares</h5>
          <small>Espacios para brindar, escuchar música y disfrutar la noche.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general4.png" alt="Bares metaleros">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🤘</div>
          <h5>Bares metaleros</h5>
          <small>Rock, metal, cerveza y ambientes alternativos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general5.png" alt="Bares LGBTIQ+">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">♡</div>
          <h5>Bares LGBTIQ+</h5>
          <small>Lugares seguros, diversos y llenos de energía.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general6.png" alt="Cafés">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Cafés</h5>
          <small>Espacios tranquilos para conversar, trabajar o descansar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general7.png" alt="Panaderías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🧁</div>
          <h5>Panaderías</h5>
          <small>Pan fresco, desayunos, onces y sabores tradicionales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general8.png" alt="Pastelerías">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Pastelerías</h5>
          <small>Tortas, postres, detalles dulces y momentos especiales.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="#" class="category-card">
        <img src="../home/img/general9.png" alt="Comida rápida">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍔</div>
          <h5>Comida rápida</h5>
          <small>Hamburguesas, pizzas, perros, empanadas y antojos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>
<script defer src="../pag_categorias/seccion/pag_categorias.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
