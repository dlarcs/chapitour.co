<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/global/pag_nav/pag_nav.css';
$jsFile  = $base . '/juegos/JimarFactory/global/pag_nav/pag_nav.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../juegos/JimarFactory/global/pag_nav/pag_nav.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<header class="chapitour-header">
  <a href="../../../juegos/JimarFactory/index.php" class="chapitour-logo" aria-label="Ir al inicio">
    <h2>Jimar Factory Chapinero</h2>
  </a>

  <div class="chapitour-actions">
    <!-- <button class="icon-btn" type="button" aria-label="Buscar">
      <span class="search-icon"></span>
    </button> -->

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
      <a href="../../../juegos/JimarFactory/menu/index.php">Menú</a>
      <a href="../../../juegos/JimarFactory/galeria/index.php">Galería</a>
      <a href="../../../juegos/JimarFactory/reservas/index.php">Reservas</a>
      <a href="../../../juegos/JimarFactory/index.php#acerca_nosotros">Nosotros</a>
      <a href="../../../juegos/JimarFactory/index.php#redes_sociales">Redes sociales</a>
      <a href="../../../juegos/JimarFactory/index.php#ubicacion">Ubicación</a>
      <a href="https://wa.me/573165180649?text=Hola%2C%20vengo%20desde%20Chapitour%20y%20quiero%20informaci%C3%B3n%20sobre%20Jimar%20Factory.">Contáctanos</a>



    </div>
  </nav>
</header>
<script defer src="../../../juegos/JimarFactory/global/pag_nav/pag_nav.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
