<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$cssFile = $base . '/gastrobar/Garage9-39C/global/boton/boton.css';
$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>
<link rel="stylesheet" href="../../../gastrobar/Garage9-39C/global/boton/boton.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">
<a href="../../../gastrobar/Garage9-39C/index.php" class="dowload">
  <div class="button_container">
    <button class="btn btn30" type="button">Volver</button>
  </div>
</a>
