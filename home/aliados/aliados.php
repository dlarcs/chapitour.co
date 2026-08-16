<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/home/aliados/aliados.css';
$jsFile  = $base . '/home/aliados/aliados.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="home/aliados/aliados.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="main-categories" id="aliados">

  <div class="main-categories__inner">

    <div class="main-categories__header">
      <span>Aliados</span>

      <h3>¿Dónde quieres divertirte?</h3>

      <small>
        Elige un lugar y encuentra lugares para disfrutar, bailar,
        salir de noche y hacer amigos.
      </small>
    </div>

    <!-- LISTA DE FILTROS -->
    <ul class="filter-list">
      <li data-filter="all" class="active">Todos</li>
      <li data-filter="bares">Bar</li>
      <li data-filter="gastrobar">gastrobar</li>
      <li data-filter="discoteca">Discoteca</li>
      <li data-filter="Crossover">Crossover</li>
      <li data-filter="cover">No cover</li>
      <li data-filter="lgbtiq">LGBTIQ+</li>


    </ul>

    <div class="categories-grid">

      <a href="bar/CapitalQueer/index.php" class="category-card place-card" data-category="lgbtiq bares cover">
        <img src="bar/CapitalQueer/img/logoCapitalQueer.jpg" alt="Tipos de bares en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍺</div>
          <h5>Capital Queer</h5>
          <small>Bar solo para mujeres, diversidad y emoción</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>


      <a href="https://granychelaclub.com" class="category-card place-card" data-category="lgbtiq bares discoteca Crossover cover">
        <img src="bar/Gran&Chela_Club/img/logo.jpg" alt="Panaderías y pastelerías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍺</div>
          <h5>Gran&Chela Club</h5>
          <small>Bar, discoteca y eventos, todo en uno</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>

      <a href="gastrobar/GarageDiscoBar/index.php" class="category-card place-card" data-category="lgbtiq bares discoteca Crossover gastrobar cover">
        <img src="gastrobar/GarageDiscoBar/img/general11.jpg" alt="Panaderías y pastelerías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍸</div>
          <h5>Garage Disco Bar</h5>
          <small>Cocteles, crossover, gastrobar</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>
      <a href="bar/Pictograma/index.php" class="category-card place-card" data-category="lgbtiq bares discoteca Crossover gastrobar cover">
        <img src="bar/Pictograma/img/logo.jpeg" alt="Panaderías y pastelerías en Chapinero">
        <div class="category-card__overlay"></div>

        <div class="category-card__content">
          <div class="category-card__icon">🍸</div>
          <h5>Píctogramas Café bar</h5>
          <small>Cocteles, café, bar, bolirana</small>
        </div>

        <span class="category-card__arrow">→</span>
      </a>
    </div>
  </div>
</section>

<script defer src="home/aliados/aliados.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
