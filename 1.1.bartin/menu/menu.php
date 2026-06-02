<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.1.bartin/menu/menu.css';
$jsFile  = $base . '/1.1.bartin/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../1.1.bartin/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Menú destacado</span>

  <h2>Sabores para compartir</h2>

  <p>
    Disfruta una selección de comida rápida, bebidas y opciones ideales para
    una tarde o noche en Chapinero. Haz click en cualquier imagen para ver el menú:
  </p>
  <div class="menu-grid">

    <a href="../pdf/menu-hamburguesa.pdf" download class="menu-card-link">
      <article class="menu-card">
        <img src="../home/img/hamburguesa.png" alt="Hamburguesa artesanal">

        <div>
          <h3>Hamburguesa artesanal</h3>
          <p>Carne jugosa, queso fundido, vegetales frescos y salsa de la casa.</p>
        </div>
      </article>
    </a>

    <a href="../pdf/menu-coctel.pdf" download class="menu-card-link">
      <article class="menu-card">
        <img src="../home/img/cocktel.png" alt="Cóctel de la casa">

        <div>
          <h3>Cóctel de la casa</h3>
          <p>Bebida especial con sabores frutales, presentación elegante y toque urbano.</p>
        </div>
      </article>
    </a>

    <a href="../pdf/menu-postres.pdf" download class="menu-card-link">
      <article class="menu-card">
        <img src="../home/img/postre.png" alt="Papas especiales">

        <div>
          <h3>Postres</h3>
          <p>Postres tres leches, con chocolate derretido, fresas, frambuesas y más.</p>
        </div>
      </article>
    </a>

  </div>
</section>
<script defer src="../1.1.bartin/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
