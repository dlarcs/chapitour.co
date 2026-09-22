<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/home/maps/maps.css';
$jsFile  = $base . '/home/maps/maps.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : time();
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : time();
?>

<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<link
  rel="stylesheet"
  href="home/maps/maps.css?v=<?= $cssVer ?>"
>
<main class="mapa-aplicacion">

  <!-- BARRA LATERAL -->
  <aside class="barra-lateral">

    <section class="encabezado-lateral">

      <div class="icono-mapa" aria-hidden="true">
        ⌖
      </div>

      <div>
        <h1>Explora Chapinero</h1>

        <p>
          Descubre lugares únicos y apoya los negocios locales.
        </p>
      </div>

    </section>

    <hr>

    <!-- CATEGORÍAS -->
    <section class="categorias" aria-label="Categorías de negocios">

      <button
        class="categoria activa"
        type="button"
        data-categoria="todos"
      >
        <span class="categoria-icono">⌖</span>
        <span class="categoria-nombre">Todos</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="Bar"
      >
        <span class="categoria-icono">🍸</span>
        <span class="categoria-nombre">Bares</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="Gastrobar"
      >
        <span class="categoria-icono">🍹</span>
        <span class="categoria-nombre">Gastrobares</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="Cafe"
      >
        <span class="categoria-icono">☕</span>
        <span class="categoria-nombre">Cafés y bebidas</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="Gastronomia"
      >
        <span class="categoria-icono">🍽️</span>
        <span class="categoria-nombre">Gastronomía</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="ComidasRapidas"
      >
        <span class="categoria-icono">🍔</span>
        <span class="categoria-nombre">Comidas rápidas</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="EspacioDiverso"
      >
        <span class="categoria-icono">🏳️‍🌈</span>
        <span class="categoria-nombre">Espacios diversos</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="Discoteca"
      >
        <span class="categoria-icono">🪩</span>
        <span class="categoria-nombre">Discotecas</span>
        <span class="categoria-cantidad">0</span>
      </button>

      <button
        class="categoria"
        type="button"
        data-categoria="Postres"
      >
        <span class="categoria-icono">🧁</span>
        <span class="categoria-nombre">Postres</span>
        <span class="categoria-cantidad">0</span>
      </button>

    </section>

    <!-- INFORMACIÓN -->
    <section class="informacion-barrio">

      <strong>📍 Chapinero, Bogotá</strong>

      <p>
        Un sector diverso, creativo y lleno de historias,
        sabores y experiencias.
      </p>

      <span>Apoya lo local ♡</span>

    </section>

  </aside>

  <!-- MAPA -->
  <section class="contenedor-mapa">
    <div id="map"></div>
  </section>

</main>

<!-- JavaScript de Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- JavaScript del mapa -->
<script
  defer
  src="home/maps/maps.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
