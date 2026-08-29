document.addEventListener("DOMContentLoaded", function () {

  const slider = document.querySelector("#Chapitour-eventos-slider");

  if (!slider) {
    return;
  }


  const track = slider.querySelector(".Chapitour-eventos-track");

  const slides = slider.querySelectorAll(".Chapitour-eventos-slide");

  const botonAnterior = slider.querySelector(
    ".Chapitour-eventos-anterior"
  );

  const botonSiguiente = slider.querySelector(
    ".Chapitour-eventos-siguiente"
  );

  const puntos = slider.querySelectorAll(
    ".Chapitour-eventos-punto"
  );


  let indiceActual = 0;

  let intervalo = null;

  const tiempoAutomatico = 6000;


  /* =====================================
     MOSTRAR SLIDE
  ===================================== */

  function mostrarSlide(indice) {

    if (indice >= slides.length) {
      indiceActual = 0;

    } else if (indice < 0) {
      indiceActual = slides.length - 1;

    } else {
      indiceActual = indice;
    }


    track.style.transform =
      `translateX(-${indiceActual * 100}%)`;


    puntos.forEach((punto, index) => {

      punto.classList.toggle(
        "Chapitour-eventos-punto-activo",
        index === indiceActual
      );

    });


    slides.forEach((slide, index) => {

      slide.setAttribute(
        "aria-hidden",
        index === indiceActual ? "false" : "true"
      );

    });

  }


  /* =====================================
     SIGUIENTE
  ===================================== */

  function siguienteSlide() {

    mostrarSlide(indiceActual + 1);

  }


  /* =====================================
     ANTERIOR
  ===================================== */

  function anteriorSlide() {

    mostrarSlide(indiceActual - 1);

  }


  /* =====================================
     AUTOMÁTICO
  ===================================== */

  function iniciarAutomatico() {

    detenerAutomatico();

    intervalo = setInterval(
      siguienteSlide,
      tiempoAutomatico
    );

  }


  function detenerAutomatico() {

    if (intervalo) {

      clearInterval(intervalo);

      intervalo = null;

    }

  }


  function reiniciarAutomatico() {

    detenerAutomatico();

    iniciarAutomatico();

  }


  /* =====================================
     BOTONES
  ===================================== */

  botonSiguiente.addEventListener(
    "click",
    function () {

      siguienteSlide();

      reiniciarAutomatico();

    }
  );


  botonAnterior.addEventListener(
    "click",
    function () {

      anteriorSlide();

      reiniciarAutomatico();

    }
  );


  /* =====================================
     PUNTOS
  ===================================== */

  puntos.forEach((punto, index) => {

    punto.addEventListener(
      "click",
      function () {

        mostrarSlide(index);

        reiniciarAutomatico();

      }
    );

  });


  /* =====================================
     PAUSAR CON EL MOUSE
  ===================================== */

  slider.addEventListener(
    "mouseenter",
    detenerAutomatico
  );


  slider.addEventListener(
    "mouseleave",
    iniciarAutomatico
  );


  /* =====================================
     SWIPE EN CELULAR
  ===================================== */

  let inicioX = 0;

  let finalX = 0;

  const distanciaMinima = 45;


  slider.addEventListener(
    "touchstart",
    function (event) {

      inicioX =
        event.touches[0].clientX;

      detenerAutomatico();

    },
    {
      passive: true
    }
  );


  slider.addEventListener(
    "touchmove",
    function (event) {

      finalX =
        event.touches[0].clientX;

    },
    {
      passive: true
    }
  );


  slider.addEventListener(
    "touchend",
    function () {

      const distancia =
        inicioX - finalX;


      if (
        Math.abs(distancia) >
        distanciaMinima
      ) {

        if (distancia > 0) {

          siguienteSlide();

        } else {

          anteriorSlide();

        }

      }


      inicioX = 0;
      finalX = 0;

      iniciarAutomatico();

    }
  );


  /* =====================================
     TECLADO
  ===================================== */

  slider.setAttribute("tabindex", "0");


  slider.addEventListener(
    "keydown",
    function (event) {

      if (event.key === "ArrowRight") {

        siguienteSlide();

        reiniciarAutomatico();

      }


      if (event.key === "ArrowLeft") {

        anteriorSlide();

        reiniciarAutomatico();

      }

    }
  );


  /* =====================================
     INICIO
  ===================================== */

  mostrarSlide(0);

  iniciarAutomatico();

});
