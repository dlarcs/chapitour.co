<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$cssFile = $base . '/global/footer/footer.css';
$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>
<link rel="stylesheet" href="../../global/footer/footer.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">
<footer class="site-footer">
  <div class="footer-inner">

    <div class="footer-brand">
      <h1>Chapitour.co</h1>
      <p>Los mejores lugares, sabores y experiencias te esperan.</p>

      <div class="footer-social">
        <a href="#" aria-label="Instagram">◎</a>
        <a href="#" aria-label="Facebook">f</a>
        <a href="#" aria-label="TikTok">♪</a>
      </div>
    </div>

    <div class="footer-col">
      <h5>Categorías</h5>
      <a href="../../categorias/1.tipo_bares/index.php">Bar</a>
      <a href="../../categorias/2.planes_bares/index.php">Gastro Bar</a>
      <a href="../../categorias/3.cafes/index.php">Café</a>
      <a href="../../categorias/4.postres/index.php">Postres</a>
      <a href="../../categorias/5.LGBTIQ+/index.php">LGBTIQ+</a>
      <a href="../../categorias/6.comida_rapida/index.php">Comida rápida</a>
      <a href="../../categorias/7.gastronomia/index.php">Gastronomía</a>
      <a href="../../categorias/8.artesanias/index.php">Artesanías</a>
      <a href="../../categorias/9.panaderia/index.php">Panaderías y pastelerías</a>
    </div>

    <div class="footer-col">
      <h5>De interes</h5>
      <a href="../../categorias/category/index.php">Categorías</a>
      <a href="../../categorias/10.destacados/index.php">Destacados</a>
      <a href="https://Arbelaez.com.co">Arbelaez.com.co</a>
    </div>

  </div>

  <div class="footer-bottom">
    <small>© 2026 Chapitour.co</small>
    <small>Todos los derechos reservados.</small>
  </div>
</footer>
