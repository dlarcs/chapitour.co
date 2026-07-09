<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/home/menu/menu.css';
$jsFile  = $base . '/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<header class="chapitour-header">
  <a href="index.php" class="chapitour-logo" aria-label="Ir al inicio">
    <h1>Chapitour.co</h1>
  </a>

  <div class="chapitour-actions">


    <button
      class="icon-btn menu-btn"
      type="button"
      aria-label="Abrir menú"
      aria-expanded="false"
      aria-controls="chapitour-menu"
    >
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>

  <nav id="chapitour-menu" class="chapitour-menu" aria-label="Menú principal">
    <div class="menu-inner">
      <a href="categorias/1.tipo_bares/index.php">Bares</a>
      <a href="categorias/2.planes_bares/index.php">Gastrobares</a>
      <a href="categorias/3.cafes/index.php">Café</a>
      <a href="categorias/4.postres/index.php">Postres</a>
      <a href="categorias/5.LGBTIQ+/index.php">LGBTIQ+</a>
      <a href="categorias/6.comida_rapida/index.php">Comida rápida</a>
      <a href="categorias/7.gastronomia/index.php">Gastronomía</a>
      <a href="categorias/8.artesanias/index.php">Artesanías</a>
      <a href="categorias/9.panaderia/index.php">Panaderías</a>
      <a href="categorias/category/index.php">Categorías</a>
      <a href="categorias/destacados/index.php">Destacados</a>
    </div>
  </nav>
</header>
<script defer src="home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
