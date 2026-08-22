<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$cssFile = $base . '/juegos/JimarFactory/global/boton/boton.css';
$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>
<link rel="stylesheet" href="../../../juegos/JimarFactory/global/boton/boton.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">
<a href="../../../juegos/JimarFactory/index.php" class="dowload">
  <div class="button_container">
    <button class="btn btn30" type="button">Volver</button>
  </div>
</a>
