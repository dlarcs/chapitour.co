<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/Garage9-39C/menu/menu/menu.css';
$jsFile  = $base . '/gastrobar/Garage9-39C/menu/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../gastrobar/Garage9-39C/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Menú destacado</span>

  <h2>Sabores para la rumba</h2>


  <p>
    Disfruta comida, bebidas, cocteles y opciones ideales para compartir antes,
    durante o después de una noche llena de música y buena energía en Chapinero.
  </p>
  <a href="../../gastrobar/Garage9-39C/menu/index.php" class="dowload">
    <div class="button_container">
      <button class="btn btn30" type="button">Ver menú</button>
    </div>
  </a>
  <div class="menu-grid">

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general.jpg" alt="Hamburguesas Garage 9-39C">

      <div>
        <h3>Hamburguesas</h3>
        <p class="descripcion-card">
          Hamburguesas con carne jugosa, queso fundido, vegetales frescos y salsas llenas de sabor.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general2.jpg" alt="Cocteles Garage 9-39C">

      <div>
        <h3>Cocteles</h3>
        <p class="descripcion-card">
          Bebidas especiales para brindar, compartir y acompañar la noche con buena vibra.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general3.jpg" alt="Picadas Garage 9-39C">

      <div>
        <h3>Picadas para compartir</h3>
        <p class="descripcion-card">
          Opciones ideales para disfrutar en grupo mientras compartes música, conversación y rumba.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general4.jpg" alt="Bebidas Garage 9-39C">

      <div>
        <h3>Bebidas</h3>
        <p class="descripcion-card">
          Licores, cervezas, mezclas y bebidas para acompañar cada momento de la noche.
        </p>
      </div>
    </article>


  </div>
  <a href="../../gastrobar/Garage9-39C/actividades/index.php" class="dowload">
    <div class="button_container">
      <button class="btn btn30" type="button">Ver actividades y promociones</button>
    </div>
  </a>
</section>

<script defer src="../../gastrobar/Garage9-39C/menu/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
