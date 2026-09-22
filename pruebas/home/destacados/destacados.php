<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/home/destacados/destacados.css';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>

<link rel="stylesheet" href="home/destacados/destacados.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<div class="featured-places visible">
  <div class="interest-header">
    <h2>
      <span>☆</span>
      Actividades especiales
    </h2>

    <a href="#aliados" class="interest-more">
      Ver lugares
      <span>→</span>
    </a>
  </div>

  <div class="interest-body">
    <div class="featured-places__main">

      <article class="featured-card">
        <img id="featuredImage" src="home/img/cocktel.png" alt="Azul Restaurante">

        <div class="featured-card__tag">
          <span>★</span>
          <p>Destacado</p>
        </div>

        <div class="featured-card__content">
          <h4 id="featuredTitle">Actividades</h4>

          <div class="featured-card__meta">
            <p id="featuredLocation">📍 Chapinero central</p>
            <p id="featuredCategory">🍽️ Actividades</p>
            <p id="featuredRating">★ 4.8 (25)</p>
          </div>
        </div>
      </article>

      <div class="featured-dots" id="featuredDots">
        <button class="is-active" type="button" aria-label="Ver lugar 1"></button>
        <button type="button" aria-label="Ver lugar 2"></button>
        <button type="button" aria-label="Ver lugar 3"></button>
        <button type="button" aria-label="Ver lugar 4"></button>
        <button type="button" aria-label="Ver lugar 5"></button>
      </div>

    </div>

    <div class="featured-places__list">

      <a href="bar/Gran&Chela_Club/index.php">
        <article class="place-mini-card">
          <img src="bar/Gran&Chela_Club/img/general6.jpg" alt="El Jardín Secreto">
          <div>
            <h4>Gran&Chela Club</h4>
            <p>Granizado Monster 2x1</p>
          </div>
          <small>4.6 ★</small>
        </article>
      </a>

      <a href="bar/Gran&Chela_Club/index.php">
        <article class="place-mini-card">
          <img src="bar/Gran&Chela_Club/img/general8.jpg" alt="La Esquina del Sabor">
          <div>
            <h4>Gran&Chela Club§</h4>
            <p>Cerveza 2x1</p>
          </div>
          <small>4.5 ★</small>
        </article>
      </a>

      <a href="bar/Gran&Chela_Club/index.php">
        <article class="place-mini-card">
          <img src="bar/Gran&Chela_Club/img/general10.jpg" alt="Bar 80/20">
          <div>
            <h4>Gran&Chela Club</h4>
            <p>Eventos Especiales</p>
          </div>
          <small>4.7 ★</small>
        </article>
      </a>

      <a href="bar/Gran&Chela_Club/index.php">
        <article class="place-mini-card">
          <img src="bar/Gran&Chela_Club/img/general6.jpg" alt="Mercado del Chef">
          <div>
            <h4>Gran&Chela Club</h4>
            <p>Micheladas 2x1</p>
          </div>
          <small>4.6 ★</small>
        </article>
      </a>
      <a href="#aliados" class="featured-places__btn featured-places__btn--mobile">
        <small>Ver más lugares</small>
        <span>→</span>
      </a>

    </div>

  </div>
</div>
