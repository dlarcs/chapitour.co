<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastronomia/streetgrill/reservas/reservas/reservas.css';
$jsFile  = $base . '/gastronomia/streetgrill/reservas/reservas/reservas.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastronomia/streetgrill/reservas/reservas/reservas.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Reservas</span>

  <h2>Agenda tu mesa en Street Grill</h2>

  <p>
    Reserva tu espacio y disfruta una experiencia gastronómica en Chapinero
    con carnes al barril, preparaciones ahumadas, hamburguesas artesanales
    y sabores hechos para compartir.
  </p>

  <div class="reservation-box">
    <h3>¿Cómo funciona?</h3>

    <div class="steps">
      <div class="step-card">
        <span>01</span>
        <p>Elige el día y la hora de tu visita.</p>
      </div>

      <div class="step-card">
        <span>02</span>
        <p>Indica cuántas personas disfrutarán la experiencia.</p>
      </div>

      <div class="step-card">
        <span>03</span>
        <p>Confirma tu reserva y prepárate para disfrutar Street Grill.</p>
      </div>
    </div>
  </div>

  <div class="booking-box">
    <h3>Datos de la reserva</h3>

    <form class="booking-form" id="bookingForm">
      <div class="form-group">
        <label for="name">Nombre completo</label>
        <input type="text" id="name" name="name" placeholder="Ej: Kelly Romero" required>
      </div>

      <div class="form-group">
        <label for="phone">Celular</label>
        <input type="tel" id="phone" name="phone" placeholder="Ej: 300 123 4567" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="date">Fecha</label>
          <input type="date" id="date" name="date" required>
        </div>

        <div class="form-group">
          <label for="time">Hora</label>
          <input type="time" id="time" name="time" required>
        </div>
      </div>

      <div class="form-group">
        <label for="people">Cantidad de personas</label>
        <input type="number" id="people" name="people" min="1" max="20" value="2" required>
      </div>

<!--
      <div class="food-option">
        <label class="checkbox-label" for="separateFood">
          <input type="checkbox" id="separateFood" name="separateFood">
          <span>Quiero separar comida antes de llegar</span>
        </label>
      </div> -->

  <!-- <div class="food-box" id="foodBox">
    <div class="form-group">
      <label for="food">Comida</label>
      <select id="food" name="food">
        <option value="">Selecciona una opción</option>
        <option value="Carne al barril">Carne al barril</option>
        <option value="Bondiola de cerdo ahumada">Bondiola de cerdo ahumada</option>
        <option value="Hamburguesa artesanal">Hamburguesa artesanal</option>
        <option value="Choripán">Choripán</option>
        <option value="Barril mixto de cerdo y res">Barril mixto de cerdo y res</option>
      </select>
    </div>

    <div class="form-group">
      <label for="quantity">Cantidad</label>
      <input type="number" id="quantity" name="quantity" min="1" value="1">
    </div>

    <div class="form-group">
      <label for="notes">Notas adicionales</label>
      <textarea
        id="notes"
        name="notes"
        placeholder="Ej: Sin cebolla, preferencia de acompañamiento, observaciones sobre el pedido..."></textarea>
    </div>

    <div class="form-group">
      <label>Selecciona el porcentaje del anticipo</label>

      <div class="payment-options">
        <button class="btn" type="button">Pagar 50%</button>
        <button class="btn" type="button">Pagar 80%</button>
        <button class="btn" type="button">Pagar 90%</button>
      </div>
    </div>

    <a href="../../../gastronomia/streetgrill/index.php" class="btn btn30">
      Pagar anticipo
    </a> -->

    <button class="btn btn30" type="submit">Reservar</button>
</div>


      <p class="booking-message" id="bookingMessage"></p>
    </form>
  </div>

</section>

<script defer src="../../../gastronomia/streetgrill/reservas/reservas/reservas.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
