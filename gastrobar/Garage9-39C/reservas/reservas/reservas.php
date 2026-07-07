<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastrobar/Garage9-39C/reservas/reservas/reservas.css';
$jsFile  = $base . '/gastrobar/Garage9-39C/reservas/reservas/reservas.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../gastrobar/Garage9-39C/reservas/reservas/reservas.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">
  <span class="section-label">Reservas</span>

  <h2>Agenda tu mesa</h2>

  <p>
    Reserva tu espacio, elige la fecha, la hora y separa tu comida para disfrutar
    una experiencia cómoda en Chapinero.
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
        <p>Indica cuántas personas asistirán.</p>
      </div>

      <div class="step-card">
        <span>03</span>
        <p>Confirma tu reserva y disfruta tu plan.</p>
      </div>
    </div>
  </div>

  <div class="booking-box">
    <h3>Datos de la reserva</h3>

    <form class="booking-form" id="bookingForm">
      <div class="form-group">
        <label for="name">Nombre completo</label>
        <input type="text" id="name" name="name" placeholder="Ej: Laura Rozo" required>
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
        <option value="Hamburguesa artesanal">Hamburguesa artesanal</option>
        <option value="Cóctel de la casa">Cóctel de la casa</option>
        <option value="Postre especial">Postre especial</option>
        <option value="Café de especialidad">Café de especialidad</option>
        <option value="Menú para compartir">Menú para compartir</option>
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
        placeholder="Ej: Sin cebolla, mesa en terraza, cumpleaños..."></textarea>
    </div>

    <div class="form-group">
      <label>Selecciona el porcentaje del anticipo</label>

      <div class="payment-options">
        <button class="btn" type="button">Pagar 50%</button>
        <button class="btn" type="button">Pagar 80%</button>
        <button class="btn" type="button">Pagar 90%</button>
      </div>
    </div>

    <a  href="../../../gastrobar/Garage9-39C/index.php" class="btn btn30">
      Pagar anticipo
    </a> -->
    <button class="btn btn30" type="submit">Reservar</button>
</div>


      <p class="booking-message" id="bookingMessage"></p>
    </form>
  </div>

</section>

<script defer src="../../../gastrobar/Garage9-39C/reservas/reservas/reservas.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
