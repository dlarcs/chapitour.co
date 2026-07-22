<?php
$cssTime = filemtime('home/slider/slider.css');
?>
<link rel="stylesheet" href="home/slider/slider.css?v=<?= $cssTime ?>">

<div class="hero-showcase">
  <section class="hero-chapitour">
    <div class="hero-card">
      <span class="hero-tag">Gastronomía · Bares · Experiencias</span>

      <h1>
        Descubre<br>
        sabores, planes<br>
        y rincones<br>
        <em>con estilo</em>
      </h1>

      <p>
        Tu guía para comer, brindar y vivir momentos inolvidables
        en los mejores lugares.
      </p>

      <div class="hero-actions">
        <a href="categorias/category/index.php" class="btn-primary">
          Explorar lugares <span>→</span>
        </a>

        <a href="categorias/category/index.php" class="btn-video">
          <span class="play">▶</span>
          Ver experiencias
        </a>
      </div>
    </div>
  </section>

  <section class="hero-gallery">
      <a href="#aliados" class="gallery-card img-1">
        <small>Sabores y Experiencias</small>
        <img src="home/img/restaurante.png"
        class="gallery-img"
        alt=""
        style="object-fit: cover; object-position: 60% 55%;">
      </a>

    <a href="#aliados" class="gallery-card img-2">
      <small>Orgullo y Planes</small>
      <img src="home/img/lgbti_lugar.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-3">
      <small>Rápidos y Sabrosos</small>
      <img src="home/img/hamburguesa.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-4">
      <small>Dulce Tradición</small>
      <img src="home/img/panaderia2.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-5">
      <small>Noches de chapinero</small>
      <img src="home/img/bar1.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-6">
      <small>Tertulias de Chapinero</small>
      <img src="home/img/bar_coctel.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-7">
      <small>Dulce Pecado</small>
      <img src="home/img/postre1.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-8">
      <small>Rincones de café</small>
      <img src="home/img/cafe1.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-9">
      <small>Tesoros Artesanales</small>
      <img
        src="home/img/artesanias_bolso.png"
        class="gallery-img"
        alt=""
        style="object-fit: cover; object-position: 60% 55%;">
    </a>

  </section>
</div>
