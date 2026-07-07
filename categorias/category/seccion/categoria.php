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
      <span>Categorías principales</span>

      <h1>¿Qué plan quieres descubrir hoy en Chapinero?</h1>

      <small>
        Elige una categoría y encuentra lugares para comer, tomar café,
        salir de noche, descubrir postres, comprar artesanías, compartir
        con amigos o vivir nuevas experiencias en Chapinero.
      </small>
    </div>

    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="tipo-bares">Bar</li>
      <li data-filter="planes-bares">Gastro Bar</li>
      <li data-filter="cafes">Cafés</li>
      <li data-filter="postres">Postres</li>
      <li data-filter="lgbtiq">LGBTIQ+</li>
      <li data-filter="comidas-rapidas">Comidas rápidas</li>
      <li data-filter="gastronomia">Gastronomía</li>
      <li data-filter="artesanias">Artesanías</li>
      <li data-filter="panaderias">Panaderías</li>
    </ul>

    <div class="categories-grid">

      <a href="../../categorias/1.tipo_bares/index.php" class="category-card place-card" data-category="tipo-bares">
        <img src="../../home/img/bar.png" alt="Tipos de bares en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍺</div>
          <h5>Tipos de bares</h5>
          <small>Bares clásicos, gastrobares, bares temáticos, coctelerías y espacios para disfrutar la noche.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/2.planes_bares/index.php" class="category-card place-card" data-category="planes-bares">
        <img src="../../home/img/cocktel.png" alt="Planes en bares en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍸</div>
          <h5>Planes en bares</h5>
          <small>Noches de música, cocteles, comida, amigos, celebraciones y ambientes para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/3.cafes/index.php" class="category-card place-card" data-category="cafes">
        <img src="../../home/img/cafe1.png" alt="Cafés en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">☕</div>
          <h5>Cafés</h5>
          <small>Café, capuchino, bebidas especiales, postres pequeños y espacios tranquilos para conversar.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/4.postres/index.php" class="category-card place-card" data-category="postres">
        <img src="../../home/img/postre1.png" alt="Postres en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍰</div>
          <h5>Postres</h5>
          <small>Tortas, helados, waffles, cafés dulces, chocolaterías y antojos para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/5.LGBTIQ+/index.php" class="category-card place-card" data-category="lgbtiq">
        <img src="../../home/img/lgbtiq+.png" style="object-fit: cover; object-position: 50% 20%;" alt="Lugares LGBTIQ+ en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">♡</div>
          <h5>LGBTIQ+</h5>
          <small>Lugares seguros, eventos, shows, actividades, cultura, rumba y espacios diversos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/6.comida_rapida/index.php" class="category-card place-card" data-category="comidas-rapidas">
        <img src="../../home/img/hamburguesa1.png" alt="Comidas rápidas en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍔</div>
          <h5>Comidas rápidas</h5>
          <small>Hamburguesas, pizzas, perros calientes, salchipapas, alitas, empanadas y antojos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/7.gastronomia/index.php" class="category-card place-card" data-category="gastronomia">
        <img src="../../home/img/restaurante.png" alt="Gastronomía en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍴</div>
          <h5>Gastronomía</h5>
          <small>Restaurantes mexicanos, caseros, gourmet, internacionales, saludables y para compartir.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/8.artesanias/index.php" class="category-card place-card" data-category="artesanias">
        <img src="../../home/img/artesanias_bolso.png" alt="Artesanías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🎨</div>
          <h5>Artesanías</h5>
          <small>Accesorios, decoración, arte local, regalos especiales, moda artesanal y productos únicos.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="../../categorias/9.panaderia/index.php" class="category-card place-card" data-category="panaderias">
        <img src="../../home/img/panaderia.png" alt="Panaderías y pastelerías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🥐</div>
          <h5>Panaderías</h5>
          <small>Pan fresco, pastelería, amasijos, tortas, desayunos, onces y productos recién horneados.</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

    </div>
  </div>
</section>

<script defer src="../../categorias/1.tipo_bares/seccion/tipo_bares.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
