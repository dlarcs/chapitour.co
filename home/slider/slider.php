<?php
$cssTime = filemtime('home/slider/slider.css');
?>
<link rel="stylesheet" href="home/slider/slider.css?v=<?= $cssTime ?>">

<div class="hero-showcase">
  <section class="hero-chapitour">
    <div class="hero-card">
      <span class="hero-tag">Gastronomía · Bares · Billares · Tatto </span>

      <h1>
        Descubre<br>
        lugares,<br>
        eventos<br>
        <em>con estilo</em>
      </h1>

      <p>
        Tu guía para comer, brindar y vivir momentos inolvidables
        en los mejores lugares.
      </p>

      <div class="hero-actions">
        <a href="#aliados" class="btn-primary">
          Explorar lugares <span>→</span>
        </a>

        <a href="#siguenos" class="btn-video">
          <span class="play">▶</span>
          Siguenos
        </a>
      </div>
    </div>
  </section>

  <section class="hero-gallery">
      <a href="#aliados" class="gallery-card img-1">
        <small>Sabores y Experiencias</small>
        <img src="juegos/JimarFactory/img/general17.jpeg"
        class="gallery-img"
        alt=""
        style="object-fit: cover; object-position: 60% 55%;">
      </a>

    <a href="#aliados" class="gallery-card img-2">
      <small>Orgullo y Planes</small>
      <img src="bar/CapitalQueer/img/general18.jpg"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-3">
      <small>Rápidos y Sabrosos</small>
      <img src="gastronomia/streetgrill/img/general3.jpeg"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-4">
      <small>Dulce Tradición</small>
      <img src="bar/Gran&Chela_Club/img/general7.jpg"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-5">
      <small>Noches de chapinero</small>
      <img src="juegos/JimarFactory/img/general9.jpeg"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-6">
      <small>Tertulias de Chapinero</small>
      <img src="bar/Pictograma/img/general3.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-7">
      <small>Dulce Pecado</small>
      <img src="bar/Pictograma/img/general13.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-8">
      <small>Rincones de café</small>
      <img src="bar/Pictograma/img/general7.png"
      class="gallery-img"
      alt=""
      style="object-fit: cover; object-position: 60% 55%;">
    </a>

    <a href="#aliados" class="gallery-card img-9">
      <small>Tesoros Artesanales</small>
      <img
        src="gastrobar/GarageDiscoBar/img/general15.jpg"
        class="gallery-img"
        alt=""
        style="object-fit: cover; object-position: 60% 55%;">
    </a>

  </section>
</div>
<script>
(() => {
  'use strict';

  function initGallery() {
    const gallery = document.querySelector('.hero-gallery');

    // Evita errores y una inicialización duplicada.
    if (
      !gallery ||
      !gallery.getAnimations ||
      gallery.dataset.dragReady
    ) {
      return;
    }

    const getAnimations = () => gallery
      .getAnimations({ subtree: true })
      .filter(animation => animation.animationName === 'floatUp');

    if (!getAnimations().length) return;

    gallery.dataset.dragReady = 'true';
    gallery.classList.add('is-interactive');

    // Permite enfocar la galería con Tab y usar las flechas.
    gallery.tabIndex = 0;
    gallery.setAttribute(
      'aria-label',
      'Galería de lugares. Usa las flechas para recorrerla.'
    );

    // 1 = desplazamiento normal.
    // 1.4 = mayor sensibilidad.
    // 0.7 = menor sensibilidad.
    const SENSITIVITY = 1;

    let drag = null;
    let blockClickUntil = 0;

    /* =========================================
       MOVER LA ANIMACIÓN EXISTENTE

       Positivo: las imágenes suben.
       Negativo: las imágenes bajan.
    ========================================= */

    function moveGallery(pixels) {
      const height = gallery.clientHeight;

      if (!height || !Number.isFinite(pixels)) {
        return false;
      }

      // Convierte los valores de top de tus keyframes a píxeles.
      const toPixels = value => {
        const text = String(value);
        const number = parseFloat(text);

        return text.endsWith('%')
          ? number * height / 100
          : number;
      };

      let moved = false;

      getAnimations().forEach(animation => {
        const effect = animation.effect;
        const timing = effect.getTiming();
        const duration = Number(timing.duration);
        const frames = effect.getKeyframes();

        if (
          frames.length < 2 ||
          !Number.isFinite(duration) ||
          duration <= 0
        ) {
          return;
        }

        // Lee el recorrido real de floatUp.
        // Así respeta los keyframes de cada tamaño de pantalla.
        const distance =
          toPixels(frames[0].top) -
          toPixels(frames[frames.length - 1].top);

        if (!Number.isFinite(distance) || distance === 0) {
          return;
        }

        const shift =
          pixels * SENSITIVITY * duration / distance;

        const activeTime =
          (animation.currentTime ?? 0) - timing.delay;

        // Mantiene el movimiento dentro del ciclo infinito.
        const phase =
          ((activeTime + shift) % duration + duration) % duration;

        // Conserva el desfase de cada imagen.
        // No pausa ni reinicia la animación.
        animation.currentTime = timing.delay + phase + duration;

        moved = true;
      });

      return moved;
    }

    /* =========================================
       RUEDA DEL RATÓN Y TRACKPAD
    ========================================= */

    gallery.addEventListener('wheel', event => {
      // No interceptar los gestos de zoom ni los modificadores.
      if (
        event.ctrlKey ||
        event.metaKey ||
        event.shiftKey ||
        !event.cancelable
      ) {
        return;
      }

      // No interceptar un desplazamiento principalmente horizontal.
      if (
        !event.deltaY ||
        Math.abs(event.deltaX) > Math.abs(event.deltaY)
      ) {
        return;
      }

      // La rueda puede informar píxeles, líneas o páginas.
      const unit =
        event.deltaMode === 1
          ? 16
          : event.deltaMode === 2
            ? gallery.clientHeight
            : 1;

      if (moveGallery(event.deltaY * unit)) {
        event.preventDefault();
      }
    }, { passive: false });

    /* =========================================
       ARRASTRAR CON RATÓN, DEDO O LÁPIZ
    ========================================= */

    gallery.addEventListener('pointerdown', event => {
      if (!event.isPrimary || event.button !== 0 || drag) {
        return;
      }

      blockClickUntil = 0;

      const target =
        event.target.closest('.gallery-card') || gallery;

      drag = {
        id: event.pointerId,
        startY: event.clientY,
        lastY: event.clientY,
        moved: false,
        target
      };

      // Capturar en el enlace mantiene los clics normales.
      target.setPointerCapture(event.pointerId);
    });

    gallery.addEventListener('pointermove', event => {
      if (!drag || event.pointerId !== drag.id) {
        return;
      }

      // Un pequeño movimiento no debe confundirse con un arrastre.
      if (
        !drag.moved &&
        Math.abs(event.clientY - drag.startY) < 6
      ) {
        return;
      }

      drag.moved = true;
      gallery.classList.add('is-dragging');

      if (event.cancelable) {
        event.preventDefault();
      }

      // Las imágenes siguen la dirección del dedo o ratón.
      moveGallery(-(event.clientY - drag.lastY));

      drag.lastY = event.clientY;
    }, { passive: false });

    function finishDrag(event) {
      if (!drag) return;

      if (
        event?.pointerId !== undefined &&
        event.pointerId !== drag.id
      ) {
        return;
      }

      const previous = drag;
      drag = null;

      gallery.classList.remove('is-dragging');

      // Evita abrir el enlace al terminar de arrastrar.
      if (previous.moved) {
        blockClickUntil = performance.now() + 400;
      }

      if (previous.target.hasPointerCapture(previous.id)) {
        previous.target.releasePointerCapture(previous.id);
      }
    }

    gallery.addEventListener('pointerup', finishDrag);
    gallery.addEventListener('pointercancel', finishDrag);
    gallery.addEventListener('lostpointercapture', finishDrag);

    window.addEventListener('blur', finishDrag);

    /* =========================================
       EVITAR CLICS ACCIDENTALES
    ========================================= */

    gallery.addEventListener('dragstart', event => {
      event.preventDefault();
    });

    gallery.addEventListener('click', event => {
      if (
        event.detail !== 0 &&
        performance.now() < blockClickUntil
      ) {
        event.preventDefault();
        event.stopImmediatePropagation();
      }
    }, true);

    /* =========================================
       NAVEGACIÓN CON TECLADO
    ========================================= */

    gallery.addEventListener('keydown', event => {
      if (
        event.target !== gallery ||
        event.ctrlKey ||
        event.metaKey ||
        event.altKey
      ) {
        return;
      }

      const offsets = {
        ArrowUp: -70,
        ArrowDown: 70,
        PageUp: -gallery.clientHeight * 0.75,
        PageDown: gallery.clientHeight * 0.75
      };

      if (
        event.key in offsets &&
        moveGallery(offsets[event.key])
      ) {
        event.preventDefault();
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener(
      'DOMContentLoaded',
      initGallery,
      { once: true }
    );
  } else {
    initGallery();
  }
})();
</script>
