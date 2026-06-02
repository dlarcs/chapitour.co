<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/1.1.bartin/acerca_nosotros/acerca_nosotros.css';
$jsFile  = $base . '/1.1.bartin/acerca_nosotros/acerca_nosotros.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';


?>

<link rel="stylesheet" href="../1.1.bartin/acerca_nosotros/acerca_nosotros.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-about visible_acerca_nosotros">
  <div class="about-text">
    <span class="section-label">Acerca de nosotros</span>

    <h2>Un gastrobar con sabor urbano</h2>

    <p>
      Distrito Gastrobar es un espacio creado para quienes buscan buena comida,
      bebidas, música y un ambiente relajado en Chapinero. Nuestra propuesta
      combina comida rápida, licores y una experiencia social pensada para
      compartir.
    </p>

    <p>
      Hacemos parte de Chapitour.co para conectar con más personas, mostrar
      nuestra propuesta y seguir fortaleciendo el comercio local.
    </p>
  </div>

  <img src="../home/img/cocktel.png" alt="Experiencia en gastrobar">
</section>
<script defer src="../1.1.bartin/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
