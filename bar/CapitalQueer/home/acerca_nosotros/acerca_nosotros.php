<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/CapitalQueer/home/acerca_nosotros/acerca_nosotros.css';
$jsFile  = $base . '/bar/CapitalQueer/home/acerca_nosotros/acerca_nosotros.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/CapitalQueer/home/acerca_nosotros/acerca_nosotros.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-about visible" id="acerca_nosotros">
  <div class="about-text">
    <span class="section-label">Acerca de nosotros</span>

    <h2>Un bar libre, diverso y seguro</h2>

    <p>
      Capital Queer es un espacio en Chapinero creado especialmente para mujeres,
      donde también cualquier persona puede entrar, compartir y disfrutar con respeto.
      Nuestra propuesta combina música, bebidas, encuentros y un ambiente pensado
      para vivir la noche con libertad y buena energía.
    </p>

    <p>
      Hacemos parte de Chapitour.co para conectar con más personas, visibilizar
      espacios diversos en Chapinero y seguir fortaleciendo el comercio local.
    </p>
  </div>

  <img src="../../bar/CapitalQueer/img/general18.jpg" alt="Experiencia en Capital Queer">

</section>

<a href="../../bar/CapitalQueer/reservas/index.php">
  <div class="button_container">
    <button class="btn btn30" type="button" name="button">Reservas</button>
  </div>
</a>

<script defer src="../../bar/CapitalQueer/home/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
