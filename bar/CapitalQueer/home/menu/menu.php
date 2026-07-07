<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/CapitalQueer/home/menu/menu.css';
$jsFile  = $base . '/bar/CapitalQueer/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/CapitalQueer/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">

  <span class="section-label">Actividades</span>

  <h2>Planes para vivir Capital Queer</h2>

  <p>
    Disfruta un espacio pensado especialmente para mujeres, con actividades para
    compartir, bailar, conversar y vivir la noche con libertad, respeto y buena energía.
  </p>

  <div class="menu-grid">

    <article class="menu-card">
      <img src="../../bar/CapitalQueer/img/general1.png" alt="Noche de amigas">

      <div>
        <h3>Noche de amigas</h3>
        <p class="descripcion-card">
          Un plan perfecto para reunirse, conversar, brindar y disfrutar un ambiente seguro,
          cómodo y lleno de buena energía.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../bar/CapitalQueer/img/general15.png" alt="Cócteles y música">

      <div>
        <h3>Cócteles y música</h3>
        <p class="descripcion-card">
          Bebidas, música y un ambiente ideal para desconectarte de la rutina y compartir
          con personas que vibran con la misma energía.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../bar/CapitalQueer/img/general20.png" alt="Noches temáticas">

      <div>
        <h3>Noches temáticas</h3>
        <p class="descripcion-card">
          Actividades especiales, celebraciones, dinámicas y encuentros pensados para crear
          momentos diferentes dentro de Capital Queer.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../bar/CapitalQueer/img/general6.png" alt="Espacio para compartir">

      <div>
        <h3>Espacio para compartir</h3>
        <p class="descripcion-card">
          Un lugar abierto a todas las personas, especialmente pensado para mujeres que buscan
          un ambiente libre, diverso y respetuoso.
        </p>
      </div>
    </article>

  </div>

  <a href="../../bar/CapitalQueer/actividades/index.php" class="dowload">
    <div class="button_container">
      <button class="btn btn30" type="button">Ver actividades y promociones</button>
    </div>
  </a>

</section>

<script defer src="../../bar/CapitalQueer/home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
