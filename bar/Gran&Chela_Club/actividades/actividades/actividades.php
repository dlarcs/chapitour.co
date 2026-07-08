<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Gran&Chela_Club/actividades/actividades/actividades.css';
$jsFile  = $base . '/bar/Gran&Chela_Club/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../bar/Gran&Chela_Club/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Actividades & Promociones</span>

  <h2>Rumba, sabor y buenos momentos</h2>

  <p>
    Conoce las actividades, promociones y eventos especiales de Gran&Chela Club.
    Ven a cantar, bailar, brindar y disfrutar la noche con buena música y excelente ambiente.
  </p>

  <section class="cards-section">
    <div class="cards-grid">

      <article class="card promo-card" data-code="GRAN-CHELA-KARAOKE-001">
        <div class="card-image">
          <img src="../../../bar/Gran&Chela_Club/img/general7.jpg" alt="Miércoles de karaoke en Gran&Chela Club">
          <span class="card-badge">Karaoke</span>
        </div>

        <div class="card-content">
          <h3>Miércoles de Voces y Copas</h3>
          <p>
            Una noche para cantar tus canciones favoritas, brindar con amigos
            y disfrutar un plan diferente entre semana.
          </p>
          <span class="card-location"><span>&#128467;&#65039;</span> Todos los miércoles</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GRAN-CHELA-SALSA-002">
        <div class="card-image">
          <img src="../../../bar/Gran&Chela_Club/img/general3.jpg" alt="Jueves de salsa en Gran&Chela Club">
          <span class="card-badge">Salsa</span>
        </div>

        <div class="card-content">
          <h3>Jueves Salsero: Azúcar y Sabor</h3>
          <p>
            Solo salsa para bailar toda la noche, disfrutar buena música
            y vivir un ambiente lleno de ritmo y energía.
          </p>
          <span class="card-location"><span>&#128467;&#65039;</span> Todos los jueves</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GRAN-CHELA-MICHELADAS-003">
        <div class="card-image">
          <img src="../../../bar/Gran&Chela_Club/img/general8.jpg" alt="Promoción de micheladas en Gran&Chela Club">
          <span class="card-badge">2x1</span>
        </div>

        <div class="card-content">
          <h3>Viernes de Michelada Doble</h3>
          <p>
            Disfruta la promoción 2x1 en micheladas de cualquier cerveza,
            perfecta para empezar el fin de semana con buena rumba.
          </p>
          <span class="card-location"><span>&#128467;&#65039;</span> Todos los viernes</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GRAN-CHELA-SABADO-004">
        <div class="card-image">
          <img src="../../../bar/Gran&Chela_Club/img/general3.jpg" alt="Sábado de salsa en Gran&Chela Club">
          <span class="card-badge">Sábado</span>
        </div>

        <div class="card-content">
          <h3>Sábado Salsa Club</h3>
          <p>
            Una noche de salsa, rumba y mucho sabor para bailar,
            compartir y disfrutar con amigos hasta el cierre.
          </p>
          <span class="card-location"><span>&#128467;&#65039;</span> Todos los sábados</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GRAN-CHELA-EVENTOS-005">
        <div class="card-image">
          <img src="../../../bar/Gran&Chela_Club/img/general12.jpg" alt="Eventos especiales en Gran&Chela Club">
          <span class="card-badge">Eventos</span>
        </div>

        <div class="card-content">
          <h3>Eventos Especiales con Reserva</h3>
          <p>
            Prestamos el espacio para cumpleaños, reuniones, celebraciones
            privadas y eventos especiales con reserva previa.
          </p>
          <span class="card-location"><span>&#128467;&#65039;</span> Reserva previa</span>
        </div>
      </article>

      <article class="card promo-card" data-code="GRAN-CHELA-RUMBA-006">
        <div class="card-image">
          <img src="../../../bar/Gran&Chela_Club/img/general10.jpg" alt="Rumba en Gran&Chela Club">
          <span class="card-badge">Rumba</span>
        </div>

        <div class="card-content">
          <h3>Noches de Rumba y Amigos</h3>
          <p>
            Licores, cervezas, micheladas, música y un ambiente ideal
            para bailar, brindar y pasar una noche llena de buenos momentos.
          </p>
          <span class="card-location"><span>&#128467;&#65039;</span> Viernes y sábados</span>
        </div>
      </article>

    </div>
  </section>
</section>

<script defer src="../../../bar/Gran&Chela_Club/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
