<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/home/menu/menu.css';
$jsFile  = $base . '/juegos/JimarFactory/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../juegos/JimarFactory/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section

  class="business-section visible"
  aria-labelledby="titulo-experiencias-jimar"
>


  <span class="section-label">Servicios y experiencias</span>

  <h2 id="titulo-experiencias-jimar">
    Vive el mejor billar en Jimar Factory Chapinero
  </h2>

  <p>
    Disfruta billar de tres bandas, pool, cócteles, café y una amplia
    variedad de bebidas en la Calle 58 #13-93, Chapinero. Domingo a domingo, de 9:00 a. m. a 12:00 a. m.
  </p>



  <div class="menu-grid">

    <!-- TARJETA DE BILLAR DE TRES BANDAS -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../juegos/JimarFactory/img/general41.jpeg"
        alt="Mesa de billar de tres bandas en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Billar de tres bandas</h3>

        <p class="descripcion-card">
          Disfruta una experiencia de juego de alto nivel en nuestras
          mesas de billar de tres bandas. Practica, compite y comparte
          con otros apasionados de este deporte en Chapinero.
        </p>
      </div>

    </article>

    <!-- TARJETA DE POOL -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../juegos/JimarFactory/img/general20.jpeg"
        alt="Mesa de pool en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Pool y entretenimiento</h3>

        <p class="descripcion-card">
          Juega pool con tus amigos y disfruta un espacio pensado para
          competir, divertirte y pasar un buen momento. Tenemos atención
          los siete días de la semana.
        </p>
      </div>

    </article>

    <!-- TARJETA DE BEBIDAS -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../juegos/JimarFactory/img/general25.jpeg"
        alt="Cócteles y bebidas en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Cócteles y bebidas</h3>

        <p class="descripcion-card">
          Acompaña cada partida con cócteles, cervezas, licores,
          bebidas sin alcohol y diferentes opciones para compartir
          mientras disfrutas del mejor ambiente.
        </p>
      </div>

    </article>

    <!-- TARJETA DE CAFÉ Y MATERIALES -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../juegos/JimarFactory/img/general23.jpeg"
        alt="Café y materiales de juego disponibles en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Café y materiales de juego</h3>

        <p class="descripcion-card">
          Disfruta nuestro servicio de café y encuentra materiales
          y accesorios para tus partidas. Jimar Factory Chapinero es
          un espacio de entretenimiento para mayores de 18 años.
        </p>
      </div>

    </article>

  </div>



</section>
<a
  href="../../juegos/JimarFactory/actividades/index.php"
  class="dowload"
  aria-label="Ver actividades y promociones de Jimar Factory Chapinero"
>
  <div class="button_container">
    <span class="btn btn30">
      Ver actividades y promociones
    </span>
  </div>
</a>
<a
  href="../../juegos/JimarFactory/img/Jimar_Menu.pdf"
  class="dowload"
  target="_blank"
  rel="noopener"
  aria-label="Ver la carta de Jimar Factory Chapinero en formato PDF"
>
  <div class="button_container">
    <span class="btn30">Ver carta de bebidas</span>
  </div>
</a>

<script
  defer
  src="../../juegos/JimarFactory/home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
