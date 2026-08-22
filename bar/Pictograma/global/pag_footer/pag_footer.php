<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$cssFile = $base . '/bar/Pictograma/global/pag_footer/pag_footer.css';
$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>
<link rel="stylesheet" href="../../../bar/Pictograma/global/pag_footer/pag_footer.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">
<footer class="site-footer">
  <div class="footer-inner">

    <div class="footer-brand">
        <a href="https://chapitour.co"><h1>Chapitour.co</h1></a>
      <p>Los mejores lugares, sabores y experiencias te esperan.</p>

      <div class="footer-social">
        <a href="https://www.instagram.com/chapitour.co/" aria-label="Instagram">◎</a>

      </div>
    </div>

    <div class="footer-col">
      <h5>Categorías</h5>
      <a href="categorias/1.tipo_bares/index.php">Bar</a>
      <a href="categorias/7.gastronomia/index.php">Gastro Bar</a>
      <a href="categorias/3.cafes/index.php">Café</a>
      <a href="categorias/2.planes_bares/index.php">Juegos</a>
      <a href="categorias/5.LGBTIQ+/index.php">LGBTIQ+</a>
    </div>

    <div class="footer-col">
      <h5>De interes</h5>
      <a href="categorias/category/index.php">Categorías</a>
      <a href="categorias/10.destacados/index.php">Destacados</a>
      <a href="https://Arbelaez.com.co">Arbelaez.com.co</a>
    </div>

  </div>

  <div class="footer-bottom">
    <small>© 2026 Chapitour.co</small>
    <small>Todos los derechos reservados.</small>
  </div>
</footer>
