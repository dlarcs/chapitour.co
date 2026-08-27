document.addEventListener("DOMContentLoaded", () => {

  /* =========================================
     ANIMACIÓN AL ENTRAR EN PANTALLA
  ========================================= */

  const sections = document.querySelectorAll(".visible");

  if (sections.length > 0) {

    const observer = new IntersectionObserver(
      (entries) => {

        entries.forEach((entry) => {

          if (entry.isIntersecting) {

            entry.target.classList.add("is-visible");

            observer.unobserve(entry.target);

          }

        });

      },
      {
        threshold: 0.3
      }
    );

    sections.forEach((section) => {

      observer.observe(section);

    });

  }


  /* =========================================
     SLIDER
  ========================================= */

  const slider = document.querySelector(".business-hero");

  if (!slider) return;


  const slides = slider.querySelectorAll(".business-hero__slide");

  const dots = slider.querySelectorAll(".hero-dot");

  const prevButton = slider.querySelector(
    ".business-hero__arrow--prev"
  );

  const nextButton = slider.querySelector(
    ".business-hero__arrow--next"
  );


  if (slides.length === 0) return;


  let currentSlide = 0;

  let autoplayInterval = null;


  /* Cada cuánto cambia automáticamente */
  const autoplayTime = 4000;


  /* =========================================
     MOSTRAR SLIDE
  ========================================= */

  function showSlide(index) {

    if (index >= slides.length) {

      index = 0;

    }

    if (index < 0) {

      index = slides.length - 1;

    }


    slides.forEach((slide) => {

      slide.classList.remove("active");

    });


    dots.forEach((dot) => {

      dot.classList.remove("active");

      dot.setAttribute(
        "aria-current",
        "false"
      );

    });


    slides[index].classList.add("active");


    if (dots[index]) {

      dots[index].classList.add("active");

      dots[index].setAttribute(
        "aria-current",
        "true"
      );

    }


    currentSlide = index;

  }


  /* =========================================
     SIGUIENTE SLIDE
  ========================================= */

  function nextSlide() {

    showSlide(
      currentSlide + 1
    );

  }


  /* =========================================
     SLIDE ANTERIOR
  ========================================= */

  function previousSlide() {

    showSlide(
      currentSlide - 1
    );

  }


  /* =========================================
     AUTOPLAY
  ========================================= */

  function startAutoplay() {

    stopAutoplay();

    autoplayInterval = setInterval(
      () => {

        nextSlide();

      },
      autoplayTime
    );

  }


  function stopAutoplay() {

    if (autoplayInterval) {

      clearInterval(
        autoplayInterval
      );

      autoplayInterval = null;

    }

  }


  function restartAutoplay() {

    stopAutoplay();

    startAutoplay();

  }


  /* =========================================
     FLECHA DERECHA
  ========================================= */

  if (nextButton) {

    nextButton.addEventListener(
      "click",
      () => {

        nextSlide();

        restartAutoplay();

      }
    );

  }


  /* =========================================
     FLECHA IZQUIERDA
  ========================================= */

  if (prevButton) {

    prevButton.addEventListener(
      "click",
      () => {

        previousSlide();

        restartAutoplay();

      }
    );

  }


  /* =========================================
     PUNTOS
  ========================================= */

  dots.forEach(
    (dot, index) => {

      dot.addEventListener(
        "click",
        () => {

          showSlide(index);

          restartAutoplay();

        }
      );

    }
  );


  /* =========================================
     SWIPE CON EL DEDO
  ========================================= */

  let touchStartX = 0;

  let touchStartY = 0;

  let touchEndX = 0;

  let touchEndY = 0;


  slider.addEventListener(
    "touchstart",
    (event) => {

      touchStartX =
        event.changedTouches[0].clientX;

      touchStartY =
        event.changedTouches[0].clientY;


      stopAutoplay();

    },
    {
      passive: true
    }
  );


  slider.addEventListener(
    "touchend",
    (event) => {

      touchEndX =
        event.changedTouches[0].clientX;

      touchEndY =
        event.changedTouches[0].clientY;


      handleSwipe();


      startAutoplay();

    },
    {
      passive: true
    }
  );


  function handleSwipe() {

    const distanceX =
      touchStartX - touchEndX;

    const distanceY =
      touchStartY - touchEndY;


    const minimumDistance = 50;


    /* Si el movimiento fue más vertical
       que horizontal, no cambiamos slide */

    if (
      Math.abs(distanceY) >
      Math.abs(distanceX)
    ) {

      return;

    }


    /* Movimiento demasiado pequeño */

    if (
      Math.abs(distanceX) <
      minimumDistance
    ) {

      return;

    }


    /* =====================================
       DESLIZA EL DEDO HACIA LA IZQUIERDA
       -> siguiente
    ===================================== */

    if (distanceX > 0) {

      nextSlide();

    }


    /* =====================================
       DESLIZA EL DEDO HACIA LA DERECHA
       -> anterior
    ===================================== */

    else {

      previousSlide();

    }

  }


  /* =========================================
     TECLADO
  ========================================= */

  document.addEventListener(
    "keydown",
    (event) => {

      if (
        event.key === "ArrowRight"
      ) {

        nextSlide();

        restartAutoplay();

      }


      if (
        event.key === "ArrowLeft"
      ) {

        previousSlide();

        restartAutoplay();

      }

    }
  );


  /* =========================================
     INICIAR
  ========================================= */

  showSlide(0);

  startAutoplay();

});
