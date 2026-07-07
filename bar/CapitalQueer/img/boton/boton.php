<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$cssFile = $base . '/1.1.bartin/global/boton/boton.css';
$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>
<link rel="stylesheet" href="../../1.1.bartin/global/boton/boton.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">
<a href="../../1.1.bartin/index.php" class="dowload">
  <div class="button_container">
    <button class="btn btn30" type="button">Volver</button>
  </div>
</a>
