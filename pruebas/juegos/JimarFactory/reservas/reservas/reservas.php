<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/juegos/JimarFactory/reservas/reservas/reservas.css';
$jsFile  = $base . '/juegos/JimarFactory/reservas/reservas/reservas.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link
  rel="stylesheet"
  href="../../../juegos/JimarFactory/reservas/reservas/reservas.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section
  class="business-section visible"
  id="reservas"
  aria-labelledby="reservas-title"
>
  <span class="section-label">Reservas</span>

  <h2 id="reservas-title">Agenda tu mesa de billar</h2>

  <p>
    Reserva tu espacio en Jimar Factory Chapinero, elige entre billar tres bandas
    o pool y disfruta una experiencia de juego, bebidas y entretenimiento en la
    Calle 58 #13-93.
  </p>

  <div class="reservation-box">
    <h3>¿Cómo funciona?</h3>

    <div class="steps">

      <div class="step-card">
        <span>01</span>

        <p>
          Selecciona el día, la hora y el tipo de mesa que deseas reservar.
        </p>
      </div>

      <div class="step-card">
        <span>02</span>

        <p>
          Indica cuántas personas participarán en la visita.
        </p>
      </div>

      <div class="step-card">
        <span>03</span>

        <p>
          Envía tus datos y espera la confirmación de disponibilidad.
        </p>
      </div>

    </div>
  </div>

  <div class="booking-box">
    <h3>Datos de la reserva</h3>

    <p>
      Atendemos de domingo a domingo, de 9:00 a. m. a 12:00 a. m.
    </p>

    <form class="booking-form" id="bookingForm">

      <div class="form-group">
        <label for="name">Nombre completo</label>

        <input
          type="text"
          id="name"
          name="name"
          placeholder="Ej.: Kelly Romero"
          autocomplete="name"
          required
        >
      </div>

      <div class="form-group">
        <label for="phone">Número de celular</label>

        <input
          type="tel"
          id="phone"
          name="phone"
          placeholder="Ej.: 300 123 4567"
          autocomplete="tel"
          inputmode="tel"
          required
        >
      </div>

      <div class="form-group">
        <label for="gameType">Tipo de mesa</label>

        <select id="gameType" name="gameType" required>
          <option value="" selected disabled>
            Selecciona una opción
          </option>

          <option value="Billar tres bandas">
            Billar tres bandas
          </option>

          <option value="Pool">
            Pool
          </option>
        </select>
      </div>

      <div class="form-row">

        <div class="form-group">
          <label for="date">Fecha</label>

          <input
            type="date"
            id="date"
            name="date"
            required
          >
        </div>

        <div class="form-group">
          <label for="time">Hora</label>

          <input
            type="time"
            id="time"
            name="time"
            min="09:00"
            max="23:59"
            step="900"
            required
          >
        </div>

      </div>

      <div class="form-group">
        <label for="people">Cantidad de personas</label>

        <input
          type="number"
          id="people"
          name="people"
          min="1"
          max="20"
          value="2"
          required
        >
      </div>

      <div class="form-group">
        <label for="notes">Información adicional</label>

        <textarea
          id="notes"
          name="notes"
          rows="4"
          placeholder="Ej.: celebración, preferencia de mesa o solicitud especial"
        ></textarea>
      </div>

      <p>
        La reserva estará sujeta a disponibilidad y quedará confirmada cuando
        recibas respuesta de Jimar Factory Chapinero.
      </p>

      <button class="btn btn30" type="submit">
        Solicitar reserva
      </button>

      <p
        class="booking-message"
        id="bookingMessage"
        role="status"
        aria-live="polite"
      ></p>

    </form>
  </div>

</section>

<script
  defer
  src="../../../juegos/JimarFactory/reservas/reservas/reservas.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
