<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastronomia/streetgrill/menu/menu/menu.css';
$jsFile  = $base . '/gastronomia/streetgrill/menu/menu/menu.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastronomia/streetgrill/menu/menu/menu.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Menú destacado</span>

  <h2>Humo, barril y mucho sabor</h2>

  <p>
    Descubre una selección de carnes ahumadas, preparaciones al barril,
    hamburguesas artesanales, chorizos, acompañamientos y bebidas para disfrutar
    en Street Grill, Chapinero.
  </p>

  <div class="menu-grid">

      <article class="menu-card">
        <img src="../../../home/img/hamburguesa.png" alt="Hamburguesa Street Grill">

        <div>
          <h3>Hamburguesa Street Grill</h3>
          <p class="descripcion-card">
            Pan artesanal, carne 100 % de res, bondiola al barril, mozzarella,
            piña caramelizada, lechuga, tomate y salsas de la casa.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/cocktel.png" alt="Carne al barril de Street Grill">

        <div>
          <h3>Carne al barril</h3>
          <p class="descripcion-card">
            Preparaciones de cerdo y res ahumadas al barril, jugosas y llenas
            de ese sabor intenso que caracteriza a Street Grill.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/postre.png" alt="Bondiola de cerdo ahumada">

        <div>
          <h3>Bondiola ahumada</h3>
          <p class="descripcion-card">
            Bondiola de cerdo preparada al barril, tierna, ahumada y perfecta
            para disfrutar con nuestros acompañamientos.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/cafe1.png" alt="Chorizo artesanal con arepa boyacense">

        <div>
          <h3>Chorizo con arepita</h3>
          <p class="descripcion-card">
            Chorizo artesanal de cerdo acompañado de arepita boyacense,
            una combinación sencilla y llena de sabor.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/hamburguesa.png" alt="Hamburguesa Master">

        <div>
          <h3>Hamburguesa Master</h3>
          <p class="descripcion-card">
            Pan artesanal, carne de res al barril, lechuga, tomate, mozzarella,
            plátano maduro, chorizo y salsas de la casa.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/cocktel.png" alt="Barril mixto de cerdo y res">

        <div>
          <h3>Barril mixto</h3>
          <p class="descripcion-card">
            Bondiola de cerdo y carne de res ahumadas al barril,
            una opción ideal para quienes quieren probar un poco de todo.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/postre.png" alt="Choripán artesanal">

        <div>
          <h3>Choripán</h3>
          <p class="descripcion-card">
            Pan artesanal con chorizo, chimichurri y guacamole,
            preparado para quienes disfrutan sabores intensos y caseros.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/cafe1.png" alt="Sándwich Street Grill">

        <div>
          <h3>Sándwich Street Grill</h3>
          <p class="descripcion-card">
            Pan artesanal, bondiola al barril, mozzarella, chimichurri
            y guacamole en una combinación cargada de sabor.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/hamburguesa.png" alt="Hamburguesa clásica Street Grill">

        <div>
          <h3>Hamburguesa Clásica</h3>
          <p class="descripcion-card">
            Pan artesanal, carne 100 % de res al barril, lechuga, tomate,
            mozzarella y salsas de la casa.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/cocktel.png" alt="Maduro con queso">

        <div>
          <h3>Maduro con queso</h3>
          <p class="descripcion-card">
            Plátano maduro acompañado de queso, una opción perfecta
            para complementar las carnes y preparaciones al barril.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/postre.png" alt="Papa en casco">

        <div>
          <h3>Papa en casco</h3>
          <p class="descripcion-card">
            Papas en casco ideales para acompañar hamburguesas,
            carnes ahumadas, chorizos y preparaciones al barril.
          </p>
        </div>
      </article>

      <article class="menu-card">
        <img src="../../../home/img/cafe1.png" alt="Bebidas de Street Grill">

        <div>
          <h3>Bebidas</h3>
          <p class="descripcion-card">
            Té Hatsu, gaseosas, agua, cerveza y soda italiana
            para acompañar tu experiencia en Street Grill.
          </p>
        </div>
      </article>

  </div>
</section>

<script defer src="../../../gastronomia/streetgrill/menu/menu/menu.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
