<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/GarageDiscoBar/global/pag_nav/pag_nav.css';
$jsFile  = $base . '/gastrobar/GarageDiscoBar/global/pag_nav/pag_nav.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastrobar/GarageDiscoBar/global/pag_nav/pag_nav.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<header class="chapitour-header">
  <a href="../../../gastrobar/GarageDiscoBar/index.php" class="chapitour-logo" aria-label="Ir al inicio">
    <h1>Garage Disco Bar</h1>
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
      <a href="../../../gastrobar/GarageDiscoBar/menu/index.php">Menú</a>
      <a href="../../../gastrobar/GarageDiscoBar/galeria/index.php">Galería</a>
      <a href="../../../gastrobar/GarageDiscoBar/reservas/index.php">Reservas</a>
      <a href="../../../gastrobar/GarageDiscoBar/index.php#acerca_nosotros">Nosotros</a>
      <a href="../../../gastrobar/GarageDiscoBar/index.php#redes_sociales">Redes sociales</a>
      <a href="../../../gastrobar/GarageDiscoBar/index.php#ubicacion">Ubicación</a>
      <a href="https://wa.me/573138846378?text=Hola%20vengo%20desde%20la%20web%20de%20Bar%20Restaurant">Contactanos</a>



    </div>
  </nav>
</header>
<script defer src="../../../gastrobar/GarageDiscoBar/global/pag_nav/pag_nav.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
