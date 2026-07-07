<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/Garage9-39C/home/menu/menu.css';
$jsFile  = $base . '/gastrobar/Garage9-39C/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../gastrobar/Garage9-39C/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">

  <span class="section-label">Planes</span>

  <h2>Planes para vivir Garage 9-39C</h2>

  <p>
    Disfruta un disco bar y gastrobar LGBTIQ+ en Chapinero, con música, comida,
    bebidas, rumba y un ambiente diverso para compartir con libertad y buena energía.
  </p>

  <div class="menu-grid">

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general1.jpg" alt="Noche de rumba en Garage 9-39C">

      <div>
        <h3>Noche de rumba</h3>
        <p class="descripcion-card">
          Un plan perfecto para bailar, cantar, brindar y disfrutar la noche en un ambiente
          diverso, libre y lleno de energía.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general15.jpg" alt="Cócteles y música en Garage 9-39C">

      <div>
        <h3>Cócteles y música</h3>
        <p class="descripcion-card">
          Bebidas, música y buena vibra para desconectarte de la rutina y compartir
          con amigos en el corazón de Chapinero.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general3.jpg" alt="Noches temáticas Garage 9-39C">

      <div>
        <h3>Noches temáticas</h3>
        <p class="descripcion-card">
          Shows, fiestas especiales, celebraciones y actividades pensadas para vivir
          momentos diferentes dentro de Garage 9-39C.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../gastrobar/Garage9-39C/img/general6.jpg" alt="Gastrobar en Chapinero">

      <div>
        <h3>Gastrobar para compartir</h3>
        <p class="descripcion-card">
          Un espacio para disfrutar comida, bebidas, música y conversación antes,
          durante o después de la rumba.
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

<script defer src="../../gastrobar/Garage9-39C/home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
