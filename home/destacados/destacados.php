<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/home/destacados/destacados.css';
$jsFile  = $base . '/home/destacados/destacados.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="home/destacados/destacados.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<div class="featured-places">
  <div class="interest-header">
    <h2>
      <span>☆</span>
      Lugares recomendados
    </h2>

    <a href="destacados/index.php" class="interest-more">
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
          <h4 id="featuredTitle">Azul Restaurante</h4>

          <div class="featured-card__meta">
            <p id="featuredLocation">📍 Zona T</p>
            <p id="featuredCategory">🍽️ Cocina contemporánea</p>
            <p id="featuredRating">★ 4.8 (245)</p>
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

      <a href="0.invitación/index.php">
        <article class="place-mini-card">
          <img src="home/img/esta_apartado.png" alt="El Jardín Secreto">
          <div>
            <h4>El Jardín Secreto</h4>
            <p>Café & Bistro</p>
          </div>
          <small>4.6 ★</small>
        </article>
      </a>

      <a href="0.invitación/index.php">
        <article class="place-mini-card">
          <img src="home/img/esta_apartado.png" alt="La Esquina del Sabor">
          <div>
            <h4>La Esquina del Sabor</h4>
            <p>Comida local</p>
          </div>
          <small>4.5 ★</small>
        </article>
      </a>

      <a href="0.invitación/index.php">
        <article class="place-mini-card">
          <img src="home/img/esta_libre.png" alt="Bar 80/20">
          <div>
            <h4>Bar 80/20</h4>
            <p>Cócteles & Música</p>
          </div>
          <small>4.7 ★</small>
        </article>
      </a>

      <a href="0.invitación/index.php">
        <article class="place-mini-card">
          <img src="home/img/esta_libre.png" alt="Mercado del Chef">
          <div>
            <h4>Mercado del Chef</h4>
            <p>Experiencia gastronómica</p>
          </div>
          <small>4.6 ★</small>
        </article>
      </a>
      <a href="0.invitación/index.php" class="featured-places__btn featured-places__btn--mobile">
        <small>Ver más lugares</small>
        <span>→</span>
      </a>

    </div>

  </div>
</div>

<script defer src="home/destacados/destacados.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
