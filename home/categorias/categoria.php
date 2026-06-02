<?php
$cssTime = filemtime('home/categorias/categoria.css'); // ejemplo: '../Home/5.Video/video.css'
$jsTime = filemtime('home/categorias/categoria.js');   // ejemplo: '../Home/5.Video/video.js'
?>
<link rel="stylesheet" href="home/categorias/categoria.css?v=<?= $cssTime ?>">


<section id="categoria"class="seccion-categorias">
  <div class="interest-header">
    <h2>
      <span>☆</span>
      Categorías
    </h2>

    <a href="categorias/index.php" class="interest-more">
      Ver lugares
      <span>→</span>
    </a>
  </div>
  <div class="container-carousel">
    <div class="seccion-slider">
      <div class="carousel" id="categoryCarousel">
        <div class="card-category">
          <a href="1.tipo_bares/index.php" aria-label="Ver Bares"></a>
          <div class="card-content">
            <small>Tipos de bares</small>
            <h2>Tertulias de Chapinero</h2>
            <!-- <p>Espacios para conversar, brindar y disfrutar la noche.</p> -->
          </div>
        </div>

        <div class="card-category">
          <a href="2.planes_bares/index.php" aria-label="Ver Gastrobares"></a>
          <div class="card-content">
            <small>Planes en bares</small>
            <h2>Noches de chapinero</h2>
            <!-- <p>Platos, música y ambientes perfectos para compartir.</p> -->
          </div>
        </div>
        <div class="card-category">
          <a href="3.cafes/index.php" aria-label="Ver Comida colombiana"></a>
          <div class="card-content">
            <small>Café</small>
            <h2>Rincones de café</h2>
            <!-- <p>Tradición, sazón y platos con identidad local.</p> -->
          </div>
        </div>

        <div class="card-category">
          <a href="6.comida_rapida/index.php" aria-label="Ver Comida rápida"></a>
          <div class="card-content">
            <small>Comida rápida</small>
            <h2>Rápidos y Sabrosos</h2>
            <!-- <p>Antojitos prácticos, ricos y llenos de sabor.</p> -->
          </div>
        </div>

        <div class="card-category">
          <a href="7.gastronomia/index.php" aria-label="Ver Almuerzos"></a>
          <div class="card-content">
            <small>Gastronomía</small>
            <h2>Sabores y experiencias</h2>
            <!-- <p>Opciones frescas para disfrutar a la hora del almuerzo.</p> -->
          </div>
        </div>

        <div class="card-category">
          <a href="8.artesanias/index.php" aria-label="Ver Artesanías"></a>
          <div class="card-content">
            <small>Artesanías</small>
            <h2>Tesoros Artesanales</h2>
            <!-- <p>Detalles únicos hechos con creatividad y tradición.</p> -->
          </div>
        </div>

        <div class="card-category">
          <a href="9.panaderia/index.php" aria-label="Ver Panaderías y pastelerías"></a>
          <div class="card-content">
            <small>Panaderías y pastelerías</small>
            <h2>Dulce Tradición</h2>
            <!-- <p>Panes, postres y sabores para endulzar el día.</p> -->
          </div>
        </div>

        <div class="card-category">
          <a href="5.LGBTIQ+/index.php" aria-label="Ver espacios LGBTIQ+"></a>
          <div class="card-content">
            <small>LGBTIQ+</small>
            <h2>Orgullo y planes</h2>
            <!-- <p>Ambientes diversos, seguros y llenos de buena vibra.</p> -->
          </div>
        </div>
        <div class="card-category">
          <a href="4.postres/index.php" aria-label="Ver espacios LGBTIQ+"></a>
          <div class="card-content">
            <small>Postres</small>
            <h2>Dulce pecado</h2>
            <!-- <p>Ambientes diversos, seguros y llenos de buena vibra.</p> -->
          </div>
        </div>

      </div>
    </div>
    <div class="carousel-controls">
     <button type="button" id="carouselPrev">‹</button>
     <button type="button" id="carouselNext">›</button>
    </div>
  </div>

</section>
<script src="home/categorias/categoria.js?v=<?= $jsTime ?>" type="text/javascript"></script>
