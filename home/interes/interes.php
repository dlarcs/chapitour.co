<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$cssFile = $base . '/home/interes/interes.css';
$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>

<link rel="stylesheet" href="home/interes/interes.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="interest-section">
  <div class="interest-header">
    <h2>
      <span>☆</span>
      También te puede interesar
    </h2>

    <a href="https://arbelaez.com.co" class="interest-more">
      Ver arbelaez.com.co
      <span>→</span>
    </a>
  </div>

  <div class="interest-scroll">

  <a href="https://arbelaez.com.co" blank>
    <article class="interest-card">
      <img src="home/img/arbelaez.jpg" alt="Prisma Lounge">

      <div class="interest-card__content">
        <h3>Arbelaez.com.co</h3>
        <p>Alojamiento · Gastronomía · Artesanías</p>

        <div class="interest-card__rating">
          <span>★</span>
          <p>4.8 (230)</p>
        </div>
      </div>
    </article>
  </a>
    <!-- <button class="interest-next" type="button" aria-label="Ver más">
      ›
    </button> -->

  </div>
</section>
