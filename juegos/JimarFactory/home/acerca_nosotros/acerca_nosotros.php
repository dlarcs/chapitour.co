<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/home/acerca_nosotros/acerca_nosotros.css';
$jsFile  = $base . '/juegos/JimarFactory/home/acerca_nosotros/acerca_nosotros.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../juegos/JimarFactory/home/acerca_nosotros/acerca_nosotros.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-about visible" id="acerca_nosotros">

  <div class="about-text">
    <span class="section-label">Acerca de nosotros</span>

    <h2>Pasión por el billar, el entretenimiento y el buen servicio</h2>

    <p>
      Jimar Factory Chapinero es un espacio dedicado al billar, la diversión y los
      buenos momentos. Contamos con servicio de billar a tres bandas y pool, en un
      ambiente ideal para compartir con amigos y disfrutar una gran experiencia de juego.
    </p>

    <p>
      También ofrecemos variedad de bebidas, cócteles, café, productos con alcohol
      para mayores de 18 años y materiales especializados para el juego. Te atendemos
      de domingo a domingo, de 9:00 a. m. a 12:00 a. m., en la Calle 58 #13-93,
      Chapinero, Bogotá.
    </p>

    <p>
      Hacemos parte de Chapitour.co para conectar con más personas, dar a conocer
      uno de los mejores ambientes de billar de Bogotá y seguir fortaleciendo el
      entretenimiento y el comercio local de Chapinero.
    </p>
  </div>

  <img
    src="../../juegos/JimarFactory/img/general11.jpeg"
    alt="Mesas de billar en Jimar Factory Chapinero, Bogotá"
    loading="lazy"
    decoding="async"
  >

</section>

<a
  href="../../juegos/JimarFactory/reservas/index.php"
  aria-label="Realizar una reserva en Jimar Factory Chapinero"
>
  <div class="button_container">
    <button class="btn btn30" type="button">
      Reservas
    </button>
  </div>
</a>

<script defer src="../../juegos/JimarFactory/home/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
