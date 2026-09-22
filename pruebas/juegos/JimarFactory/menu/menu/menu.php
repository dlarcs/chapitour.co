<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/menu/menu/menu.css';
$jsFile  = $base . '/juegos/JimarFactory/menu/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../../juegos/JimarFactory/menu/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section
  class="business-section visible"
  aria-labelledby="titulo-servicios-jimar"
>
  <span class="section-label">Servicios destacados</span>

  <h2 id="titulo-servicios-jimar">
    Billar, bebidas y entretenimiento en Chapinero
  </h2>

  <p>
    Visita Jimar Factory Chapinero y disfruta billar de tres bandas, pool,
    cócteles, café, licores y diferentes opciones de entretenimiento.
    Estamos ubicados en la Calle 58 #13-93 y atendemos de domingo a domingo,
    de 9:00 a. m. a 12:00 a. m.
  </p>

  <div class="menu-grid">

    <!-- BILLAR DE TRES BANDAS -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general7.jpeg"
        alt="Billar de tres bandas en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Billar de tres bandas</h3>

        <p class="descripcion-card">
          Disfruta partidas de tres bandas en un espacio pensado para
          aficionados y jugadores apasionados por el billar.
        </p>
      </div>
    </article>

    <!-- POOL -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general14.jpeg"
        alt="Mesa de pool en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Pool</h3>

        <p class="descripcion-card">
          Comparte con tus amigos, reta a otros jugadores y disfruta
          entretenidas partidas de pool en Chapinero.
        </p>
      </div>
    </article>

    <!-- EXPERIENCIA DE BILLAR -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general11.jpeg"
        alt="Experiencia de billar en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Pasión por el billar</h3>

        <p class="descripcion-card">
          Vive una experiencia de juego completa en Jimar Factory,
          reconocido por sus clientes como uno de los mejores espacios
          de billar de Bogotá.
        </p>
      </div>
    </article>

    <!-- ENTRETENIMIENTO -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general4.jpeg"
        alt="Entretenimiento para mayores de edad en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Entretenimiento</h3>

        <p class="descripcion-card">
          Disfruta un ambiente ideal para jugar, compartir, conversar
          y pasar un buen momento con amigos.
        </p>
      </div>
    </article>

    <!-- CÓCTELES -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general5.jpeg"
        alt="Cócteles disponibles en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Cócteles</h3>

        <p class="descripcion-card">
          Acompaña tus partidas con diferentes cócteles preparados
          para disfrutar durante una tarde o noche en Chapinero.
        </p>
      </div>
    </article>

    <!-- CERVEZAS Y LICORES -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general14.jpeg"
        alt="Cervezas y licores en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Cervezas y licores</h3>

        <p class="descripcion-card">
          Encuentra una variedad de cervezas, licores y bebidas con
          alcohol para acompañar cada partida y celebración.
        </p>
      </div>
    </article>

    <!-- BEBIDAS SIN ALCOHOL -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general4.jpeg"
        alt="Bebidas sin alcohol en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Bebidas sin alcohol</h3>

        <p class="descripcion-card">
          También contamos con bebidas refrescantes sin alcohol para
          que encuentres una opción adecuada para cada momento.
        </p>
      </div>
    </article>

    <!-- CAFÉ -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general5.jpeg"
        alt="Servicio de café en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Café</h3>

        <p class="descripcion-card">
          Disfruta una buena taza de café mientras conversas, esperas
          tu turno o acompañas una entretenida partida de billar.
        </p>
      </div>
    </article>

    <!-- MATERIALES DE JUEGO -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general7.jpeg"
        alt="Materiales de juego disponibles en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Materiales de juego</h3>

        <p class="descripcion-card">
          Encuentra materiales y artículos relacionados con el billar
          para complementar tu experiencia de juego.
        </p>
      </div>
    </article>

    <!-- ACCESORIOS DE BILLAR -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general11.jpeg"
        alt="Accesorios para billar en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Accesorios para billar</h3>

        <p class="descripcion-card">
          Consulta la disponibilidad de accesorios y elementos de juego
          para jugadores mayores de 18 años.
        </p>
      </div>
    </article>

    <!-- ATENCIÓN DIARIA -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general14.jpeg"
        alt="Atención todos los días en Jimar Factory Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>Atención todos los días</h3>

        <p class="descripcion-card">
          Estamos abiertos de domingo a domingo, desde las 9:00 a. m.
          hasta las 12:00 a. m., para que juegues cuando prefieras.
        </p>
      </div>
    </article>

    <!-- UBICACIÓN -->
    <article class="menu-card">
      <img
        src="../../../juegos/JimarFactory/img/general4.jpeg"
        alt="Jimar Factory ubicado en la Calle 58 número 13-93 en Chapinero"
        loading="lazy"
        decoding="async"
      >

      <div>
        <h3>En el corazón de Chapinero</h3>

        <p class="descripcion-card">
          Encuéntranos en la Calle 58 #13-93, en una ubicación central
          y de fácil acceso en Chapinero, Bogotá.
        </p>
      </div>
    </article>

  </div>

  <p class="descripcion-card">
    Venta y consumo de bebidas alcohólicas únicamente para mayores de
    18 años. El exceso de alcohol es perjudicial para la salud.
  </p>
</section>

<script
  defer
  src="../../../juegos/JimarFactory/menu/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
