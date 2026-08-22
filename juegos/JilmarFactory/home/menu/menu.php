<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/GarageDiscoBar/home/menu/menu.css';
$jsFile  = $base . '/gastrobar/GarageDiscoBar/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../gastrobar/GarageDiscoBar/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>


<section class="business-section visible">

  <span class="section-label">Experiencias</span>

  <h2>Disfruta en Garage Disco bar </h2>

  <p>
    Vive una experiencia completa con música, baile, comida, cócteles,
    licores y espacios para celebrar tus momentos especiales en el corazón
    de Chapinero.
  </p>
  <a href="../../gastrobar/GarageDiscoBar/img/Garage_Menu.pdf">
    <div class="button_container">
      <button class="btn btn30"type="button" name="button">Descargar menú</button>
    </div>
  </a>
  <div class="menu-grid">

    <!-- TARJETA DE BAILE -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../gastrobar/GarageDiscoBar/img/general7.jpg"
        alt="Personas bailando en Garage Disco bar Club"
        loading="lazy"
      >

      <div>
        <h3>Baile y música</h3>

        <p class="descripcion-card">
          Disfruta noches llenas de salsa, karaoke y diferentes ritmos
          para bailar, cantar y compartir con tus amigos en un ambiente
          alegre y lleno de energía.
        </p>
      </div>

    </article>

    <!-- TARJETA DE COMIDA -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../gastrobar/GarageDiscoBar/img/general14.jpg"
        alt="Comida para compartir en Garage Disco bar Club"
        loading="lazy"
      >

      <div>
        <h3>Comida para compartir</h3>

        <p class="descripcion-card">
          Acompaña tus bebidas con deliciosas opciones de comida,
          preparadas para compartir mientras disfrutas de la música,
          la rumba y el mejor ambiente.
        </p>
      </div>

    </article>

    <!-- TARJETA DE EVENTOS -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../gastrobar/GarageDiscoBar/img/general4.jpg"
        alt="Celebración de un evento en Garage Disco bar Club"
        loading="lazy"
      >

      <div>
        <h3>Eventos y celebraciones</h3>

        <p class="descripcion-card">
          Celebra cumpleaños, reuniones, encuentros empresariales
          y ocasiones especiales. Reserva el espacio y organiza
          una experiencia especial para tus invitados.
        </p>
      </div>

    </article>

    <!-- TARJETA DE CÓCTELES Y LICORES -->
    <article class="menu-card">

      <img
        class="menu-card-img"
        src="../../gastrobar/GarageDiscoBar/img/general5.jpg"
        alt="Cócteles y licores de Garage Disco bar Club"
        loading="lazy"
      >

      <div>
        <h3>Cócteles y licores</h3>

        <p class="descripcion-card">
          Encuentra cócteles, cervezas, micheladas y una variedad
          de licores para brindar, celebrar y acompañar cada momento
          de tu noche en Garage Disco bar Club.
        </p>
      </div>

    </article>

  </div>

  <a
    href="../../gastrobar/GarageDiscoBar/actividades/index.php"
    class="dowload"
  >
    <div class="button_container">

      <button class="btn btn30" type="button">
        Ver actividades y promociones
      </button>

    </div>
  </a>

</section>


<script
  defer
  src="../../gastrobar/GarageDiscoBar/home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
