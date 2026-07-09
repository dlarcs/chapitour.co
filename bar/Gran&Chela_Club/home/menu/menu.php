<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Gran&Chela_Club/home/menu/menu.css';
$jsFile  = $base . '/bar/Gran&Chela_Club/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/Gran&Chela_Club/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">

  <span class="section-label">Actividades</span>

  <h2>Planes para vivir Gran&Chela Club</h2>

  <p>
    Disfruta un bar discoteca con karaoke, salsa, promociones en bebidas,
    eventos especiales y un ambiente ideal para bailar, cantar, brindar
    y compartir con amigos.
  </p>

  <div class="menu-grid">

    <article class="menu-card">
      <video class="menu-card-video" muted loop playsinline preload="metadata">
        <source src="../../bar/Gran&Chela_Club/img/video.mp4" type="video/mp4">
      </video>

      <div>
        <h3>Miércoles de voces y copas</h3>
        <p class="descripcion-card">
          Una noche de karaoke para cantar tus canciones favoritas, brindar
          con amigos y disfrutar un plan diferente entre semana.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <video class="menu-card-video" muted loop playsinline preload="metadata">
        <source src="../../bar/Gran&Chela_Club/img/video1.mp4" type="video/mp4">
      </video>

      <div>
        <h3>Jueves salsero: azúcar y sabor</h3>
        <p class="descripcion-card">
          Solo salsa para bailar toda la noche, disfrutar buena música
          y vivir un ambiente lleno de ritmo, sabor y energía.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <video class="menu-card-video" muted loop playsinline preload="metadata">
        <source src="../../bar/Gran&Chela_Club/img/video2.mp4" type="video/mp4">
      </video>

      <div>
        <h3>Viernes de michelada doble</h3>
        <p class="descripcion-card">
          Disfruta la promoción 2x1 en micheladas de cualquier cerveza,
          perfecta para empezar el fin de semana con buena rumba.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <video class="menu-card-video" muted loop playsinline preload="metadata">
        <source src="../../bar/Gran&Chela_Club/img/video3.mp4" type="video/mp4">
      </video>

      <div>
        <h3>Eventos especiales con reserva</h3>
        <p class="descripcion-card">
          Prestamos el espacio para cumpleaños, reuniones, celebraciones
          privadas y eventos especiales con reserva previa.
        </p>
      </div>
    </article>

  </div>

  <a href="../../bar/Gran&Chela_Club/actividades/index.php" class="dowload">
    <div class="button_container">
      <button class="btn btn30" type="button">Ver actividades y promociones</button>
    </div>
  </a>

</section>

<script defer src="../../bar/Gran&Chela_Club/home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
