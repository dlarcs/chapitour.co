<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/actividades/actividades/actividades.css';
$jsFile  = $base . '/juegos/JimarFactory/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../juegos/JimarFactory/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Billar, bebidas & entretenimiento</span>

  <h2>Vive la experiencia de Jimar Factory Chapinero</h2>

  <p>
    Disfruta el mejor ambiente de billar en Bogotá con mesas de tres bandas y pool,
    variedad de bebidas, cócteles, café y atención todos los días. Visítanos en la
    Calle 58 #13-93, de domingo a domingo, de 9:00 a. m. a 12:00 a. m.
  </p>

  <!-- TARJETAS - HTML -->
  <section class="cards-section">
    <div class="cards-grid">

      <article class="card promo-card" data-code="JIMAR-TRES-BANDAS-001">
        <div class="card-image">
          <img
            src="../../../juegos/JimarFactory/img/general15.jpeg"
            alt="Mesa de billar tres bandas en Jimar Factory Chapinero"
          >
          <span class="card-badge">Tres bandas</span>
        </div>

        <div class="card-content">
          <h3>Demuestra tu precisión</h3>

          <p>
            Disfruta partidas de billar a tres bandas en un espacio diseñado para
            jugadores que buscan estrategia, concentración y una gran experiencia.
          </p>

          <span class="card-location">
            <span>&#127921;</span> Billar de alto nivel en Chapinero
          </span>
        </div>
      </article>

      <article class="card promo-card" data-code="JIMAR-POOL-002">
        <div class="card-image">
          <img
            src="../../../juegos/JimarFactory/img/general13.jpeg"
            alt="Servicio de pool en Jimar Factory Chapinero"
          >
          <span class="card-badge">Pool</span>
        </div>

        <div class="card-content">
          <h3>Arma tu partida de pool</h3>

          <p>
            Reúne a tus amigos, prepara tu mejor jugada y disfruta una partida de
            pool en un ambiente cómodo, entretenido y lleno de buena energía.
          </p>

          <span class="card-location">
            <span>&#127922;</span> Diversión para compartir con tu parche
          </span>
        </div>
      </article>

      <article class="card promo-card" data-code="JIMAR-BEBIDAS-003">
        <div class="card-image">
          <img
            src="../../../juegos/JimarFactory/img/general17.jpeg"
            alt="Bebidas y cócteles en Jimar Factory Chapinero"
          >
          <span class="card-badge">Bebidas y cócteles</span>
        </div>

        <div class="card-content">
          <h3>La bebida perfecta para cada partida</h3>

          <p>
            Encuentra una amplia variedad de bebidas, licores y cócteles para
            acompañar tus partidas y disfrutar cada momento en Jimar Factory.
          </p>

          <span class="card-location">
            <span>&#127864;</span> Variedad, sabor y buena atención
          </span>
        </div>
      </article>

      <article class="card promo-card" data-code="JIMAR-CAFE-004">
        <div class="card-image">
          <img
            src="../../../juegos/JimarFactory/img/general6.jpeg"
            alt="Servicio de café en Jimar Factory Chapinero"
          >
          <span class="card-badge">Café</span>
        </div>

        <div class="card-content">
          <h3>Haz una pausa y disfruta</h3>

          <p>
            Acompaña tu visita con una buena taza de café mientras compartes,
            conversas o esperas tu próxima partida.
          </p>

          <span class="card-location">
            <span>&#9749;</span> Café y servicio durante toda la jornada
          </span>
        </div>
      </article>

      <article class="card promo-card" data-code="JIMAR-MATERIALES-005">
        <div class="card-image">
          <img
            src="../../../juegos/JimarFactory/img/general1.jpeg"
            alt="Venta de materiales y accesorios de billar en Jimar Factory Chapinero"
          >
          <span class="card-badge">Materiales de juego</span>
        </div>

        <div class="card-content">
          <h3>Todo lo que necesitas para jugar</h3>

          <p>
            Encuentra materiales y accesorios para tus partidas de billar y pool.
            Venta dirigida exclusivamente a personas mayores de 18 años.
          </p>

          <span class="card-location">
            <span>&#10133;18</span> Productos y accesorios especializados
          </span>
        </div>
      </article>

      <article class="card promo-card" data-code="JIMAR-HORARIO-006">
        <div class="card-image">
          <img
            src="../../../juegos/JimarFactory/img/general10.jpeg"
            alt="Entretenimiento todos los días en Jimar Factory Chapinero"
          >
          <span class="card-badge">Todos los días</span>
        </div>

        <div class="card-content">
          <h3>Tu plan de domingo a domingo</h3>

          <p>
            Visítanos todos los días, desde las 9:00 a. m. hasta las 12:00 a. m.,
            y disfruta billar, pool, bebidas y entretenimiento en Chapinero.
          </p>

          <span class="card-location">
            <span>&#128205;</span> Calle 58 #13-93, Chapinero
          </span>
        </div>
      </article>

    </div>
  </section>
</section>

<script defer src="../../../juegos/JimarFactory/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
