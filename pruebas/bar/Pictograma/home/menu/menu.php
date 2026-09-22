<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Pictograma/home/menu/menu.css';
$jsFile  = $base . '/bar/Pictograma/home/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../bar/Pictograma/home/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">

  <span class="section-label">Actividades</span>

  <h2>Planes para disfrutar Pictogramas Cafe Bar</h2>

  <p>
    Comparte con amigos, disfruta tus bebidas favoritas y vive momentos divertidos
    en un ambiente agradable, acompañado de música, buena energía y bolirana.
  </p>

  <div class="menu-grid">

    <article class="menu-card">
      <img src="../../bar/Pictograma/img/general1.png" alt="Partida de bolirana en Pictogramas Cafe Bar">

      <div>
        <h3>Juega bolirana</h3>
        <p class="descripcion-card">
          Reúne a tus amigos, arma tu equipo y disfruta una divertida partida de bolirana
          mientras compartes tus bebidas favoritas.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../bar/Pictograma/img/general15.png" alt="Cócteles y bebidas de Pictogramas Cafe Bar">

      <div>
        <h3>Cócteles y bebidas</h3>
        <p class="descripcion-card">
          Disfruta cócteles, granizados, licores, micheladas, bolivianas y cervezas
          preparadas para acompañar tus mejores momentos.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../bar/Pictograma/img/general3.png" alt="Café y encuentros en Pictogramas Cafe Bar">

      <div>
        <h3>Café y conversación</h3>
        <p class="descripcion-card">
          Tómate un café, conversa con tus amigos y disfruta un espacio cómodo
          para desconectarte de la rutina y pasar un buen momento.
        </p>
      </div>
    </article>

    <article class="menu-card">
      <img src="../../bar/Pictograma/img/general6.png" alt="Ambiente para compartir en Pictogramas Cafe Bar">

      <div>
        <h3>Un espacio para compartir</h3>
        <p class="descripcion-card">
          Música, bebidas, amigos y diversión en un ambiente agradable, perfecto
          para celebrar, conversar y crear buenos recuerdos.
        </p>
      </div>
    </article>

  </div>

  <a href="../../bar/Pictograma/actividades/index.php" class="dowload">
    <div class="button_container">
      <button class="btn btn30" type="button">Ver actividades y promociones</button>
    </div>
  </a>

</section>

<script defer src="../../bar/Pictograma/home/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
