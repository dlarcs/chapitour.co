<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/global/pag_nav/pag_nav.css';
$jsFile  = $base . '/bar/Pictograma/global/pag_nav/pag_nav.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../bar/Pictograma/global/pag_nav/pag_nav.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<header class="chapitour-header">
  <a href="../../../bar/Pictograma/index.php" class="chapitour-logo" aria-label="Ir al inicio">
    <h1>Pictogramas </h1>
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
      <a href="../../../bar/Pictograma/menu/index.php">Menú</a>
      <a href="../../../bar/Pictograma/galeria/index.php">Galería</a>
      <a href="../../../bar/Pictograma/reservas/index.php">Reservas</a>
      <a href="../../../bar/Pictograma/index.php#acerca_nosotros">Nosotros</a>
      <a href="../../../bar/Pictograma/index.php#redes_sociales">Redes sociales</a>
      <a href="../../../bar/Pictograma/index.php#ubicacion">Ubicación</a>
      <a href="https://wa.me/573138846378?text=Hola%20vengo%20desde%20la%20web%20de%20Bar%20Restaurant">Contactanos</a>



    </div>
  </nav>
</header>
<script defer src="../../../bar/Pictograma/global/pag_nav/pag_nav.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
