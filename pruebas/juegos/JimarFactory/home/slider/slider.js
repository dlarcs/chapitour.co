document.addEventListener("DOMContentLoaded", () => {

  /* ==============================
     ANIMACIÓN DE ENTRADA
  ============================== */

  const sections = document.querySelectorAll(
    ".JimarFactory-visible"
  );

  if (sections.length > 0) {

    const observer = new IntersectionObserver(
      (entries) => {

        entries.forEach((entry) => {

          if (entry.isIntersecting) {

            entry.target.classList.add(
              "JimarFactory-is-visible"
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


  /* ==============================
     SLIDER JIMAR FACTORY
  ============================== */

  const sliders = document.querySelectorAll(
    ".JimarFactory-business-hero"
  );

  sliders.forEach((slider) => {

    const slides = slider.querySelectorAll(
      ".JimarFactory-business-hero__slide"
    );

    const dots = slider.querySelectorAll(
      ".JimarFactory-hero-dot"
    );

    if (slides.length === 0) {
      return;
    }


    /* ==============================
       VARIABLES
    ============================== */

    let currentSlide = 0;

    let autoSlider = null;

    const autoTime = 4000;


    /* ==============================
       MOSTRAR SLIDE
    ============================== */

    function showSlide(index) {

      if (index < 0) {
        index = slides.length - 1;
      }

      if (index >= slides.length) {
        index = 0;
      }

      currentSlide = index;


      slides.forEach((slide, slideIndex) => {

        const active =
          slideIndex === currentSlide;

        slide.classList.toggle(
          "JimarFactory-is-active",
          active
        );

        slide.setAttribute(
          "aria-hidden",
          active ? "false" : "true"
        );

      });


      dots.forEach((dot, dotIndex) => {

        const active =
          dotIndex === currentSlide;

        dot.classList.toggle(
          "JimarFactory-is-active",
          active
        );

        dot.setAttribute(
          "aria-current",
          active ? "true" : "false"
        );

      });

    }


    /* ==============================
       SIGUIENTE SLIDE
    ============================== */

    function nextSlide() {

      showSlide(currentSlide + 1);

    }


    /* ==============================
       SLIDE ANTERIOR
    ============================== */

    function previousSlide() {

      showSlide(currentSlide - 1);

    }


    /* ==============================
       AUTOMÁTICO
    ============================== */

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


    /* ==============================
       PUNTOS MANUALES
    ============================== */

    dots.forEach((dot, index) => {

      dot.addEventListener("click", () => {

        showSlide(index);

        restartAutoSlider();

      });

    });


    /* ==============================
       SWIPE MÓVIL
    ============================== */

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


      /* Izquierda = siguiente */

      if (distance > minimumSwipeDistance) {

        nextSlide();

        restartAutoSlider();

        return;
      }


      /* Derecha = anterior */

      if (distance < -minimumSwipeDistance) {

        previousSlide();

        restartAutoSlider();

      }

    }


    /* ==============================
       INICIO
    ============================== */

    showSlide(0);

    startAutoSlider();

  });

});
