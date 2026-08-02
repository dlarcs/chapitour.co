<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/home/acerca_nosotros/acerca_nosotros.css';
$jsFile  = $base . '/bar/Pictograma/home/acerca_nosotros/acerca_nosotros.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/Pictograma/home/acerca_nosotros/acerca_nosotros.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-about visible" id="acerca_nosotros">
  <div class="about-text">
    <span class="section-label">Acerca de nosotros</span>

    <h2>Bebidas, amigos y diversión</h2>

    <p>
      Pictogramas Cafe Bar es un lugar pensado para compartir, disfrutar y pasar
      un buen momento con amigos. Nuestra propuesta reúne cócteles, granizados,
      licores, micheladas, bolivianas, cervezas y café en un ambiente agradable
      y lleno de buena energía.
    </p>

    <p>
      Además, contamos con bolirana para que puedas divertirte mientras disfrutas
      tus bebidas favoritas. Hacemos parte de Chapitour.co para conectar con más
      personas, promover los negocios de Chapinero y fortalecer el comercio local.
    </p>
  </div>

  <img
    src="../../bar/Pictograma/img/logo.jpeg"
    alt="Ambiente y experiencia en Pictogramas Cafe Bar"
  >
</section>

<a href="../../bar/Pictograma/reservas/index.php">
  <div class="button_container">
    <button class="btn btn30" type="button" name="button">Reservas</button>
  </div>
</a>

<script defer src="../../bar/Pictograma/home/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
