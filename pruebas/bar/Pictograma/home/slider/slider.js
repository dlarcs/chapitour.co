document.addEventListener("DOMContentLoaded", () => {

  /* =========================================
     ANIMACIÓN DE ENTRADA DE LA SECCIÓN
  ========================================= */

  const sections = document.querySelectorAll(
    ".Pictogramas-visible"
  );

  if (sections.length > 0) {

    const observer = new IntersectionObserver(
      (entries) => {

        entries.forEach((entry) => {

          if (entry.isIntersecting) {

            entry.target.classList.add(
              "Pictogramas-is-visible"
            );

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

  const sliders = document.querySelectorAll(
    ".Pictogramas-business-hero"
  );

  sliders.forEach((slider) => {

    const slides = slider.querySelectorAll(
      ".Pictogramas-business-hero__slide"
    );

    const dots = slider.querySelectorAll(
      ".Pictogramas-hero-dot"
    );

    if (slides.length === 0) {
      return;
    }

    let currentSlide = 0;

    let autoSlider = null;

    const autoTime = 4000;


    /* =========================================
       MOSTRAR SLIDE
    ========================================= */

    function showSlide(index) {

      if (index < 0) {
        index = slides.length - 1;
      }

      if (index >= slides.length) {
        index = 0;
      }

      currentSlide = index;


      slides.forEach((slide, slideIndex) => {

        const active = slideIndex === currentSlide;

        slide.classList.toggle(
          "Pictogramas-is-active",
          active
        );

        slide.setAttribute(
          "aria-hidden",
          active ? "false" : "true"
        );

      });


      dots.forEach((dot, dotIndex) => {

        const active = dotIndex === currentSlide;

        dot.classList.toggle(
          "Pictogramas-is-active",
          active
        );

        dot.setAttribute(
          "aria-current",
          active ? "true" : "false"
        );

      });

    }


    /* =========================================
       SIGUIENTE SLIDE
    ========================================= */

    function nextSlide() {

      showSlide(currentSlide + 1);

    }


    /* =========================================
       SLIDE ANTERIOR
    ========================================= */

    function previousSlide() {

      showSlide(currentSlide - 1);

    }


    /* =========================================
       AUTOMÁTICO
    ========================================= */

    function startAutoSlider() {

      stopAutoSlider();

      autoSlider = setInterval(() => {

        nextSlide();

      }, autoTime);

    }


    function stopAutoSlider() {

      if (autoSlider) {

        clearInterval(autoSlider);

        autoSlider = null;

      }

    }


    function restartAutoSlider() {

      stopAutoSlider();

      startAutoSlider();

    }


    /* =========================================
       PUNTOS MANUALES
    ========================================= */

    dots.forEach((dot, index) => {

      dot.addEventListener("click", () => {

        showSlide(index);

        restartAutoSlider();

      });

    });


    /* =========================================
       SWIPE / DESLIZAR CON EL DEDO
    ========================================= */

    let touchStartX = 0;

    let touchEndX = 0;

    const minimumSwipeDistance = 50;


    slider.addEventListener(
      "touchstart",
      (event) => {

        touchStartX =
          event.changedTouches[0].clientX;

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

        handleSwipe();

      },
      {
        passive: true
      }
    );


    function handleSwipe() {

      const distance =
        touchStartX - touchEndX;


      /*
        Dedo hacia la izquierda
        = siguiente slide
      */

      if (distance > minimumSwipeDistance) {

        nextSlide();

        restartAutoSlider();

        return;
      }


      /*
        Dedo hacia la derecha
        = slide anterior
      */

      if (distance < -minimumSwipeDistance) {

        previousSlide();

        restartAutoSlider();

      }

    }


    /* =========================================
       INICIALIZACIÓN
    ========================================= */

    showSlide(0);

    startAutoSlider();

  });

});
