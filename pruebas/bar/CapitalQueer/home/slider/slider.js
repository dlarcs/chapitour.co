document.addEventListener("DOMContentLoaded", () => {
  /* =========================================
     ANIMACIÓN DE ENTRADA
  ========================================= */

  const visibleElements = document.querySelectorAll(".visible");

  if (visibleElements.length) {
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

    visibleElements.forEach((element) => observer.observe(element));
  }


  /* =========================================
     SLIDER
  ========================================= */

  const slider = document.querySelector(".business-hero");

  if (!slider) return;

  const slides = slider.querySelectorAll(".business-hero__slide");
  const dots = slider.querySelectorAll(".hero-dot");

  if (!slides.length) return;

  let currentSlide = 0;
  let autoplayInterval;

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
      dot.setAttribute("aria-current", "false");
    });

    slides[index].classList.add("active");

    if (dots[index]) {
      dots[index].classList.add("active");
      dots[index].setAttribute("aria-current", "true");
    }

    currentSlide = index;
  }


  /* =========================================
     SIGUIENTE / ANTERIOR
  ========================================= */

  function nextSlide() {
    showSlide(currentSlide + 1);
  }

  function previousSlide() {
    showSlide(currentSlide - 1);
  }


  /* =========================================
     AUTOPLAY
  ========================================= */

  function startAutoplay() {
    clearInterval(autoplayInterval);

    autoplayInterval = setInterval(() => {
      nextSlide();
    }, autoplayTime);
  }

  function restartAutoplay() {
    startAutoplay();
  }


  /* =========================================
     PUNTOS
  ========================================= */

  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
      showSlide(index);
      restartAutoplay();
    });
  });


  /* =========================================
     SWIPE EN CELULAR
  ========================================= */

  let touchStartX = 0;
  let touchStartY = 0;

  slider.addEventListener(
    "touchstart",
    (event) => {
      touchStartX = event.changedTouches[0].clientX;
      touchStartY = event.changedTouches[0].clientY;
    },
    {
      passive: true
    }
  );

  slider.addEventListener(
    "touchend",
    (event) => {
      const touchEndX = event.changedTouches[0].clientX;
      const touchEndY = event.changedTouches[0].clientY;

      const distanceX = touchStartX - touchEndX;
      const distanceY = touchStartY - touchEndY;

      const minimumDistance = 50;

      // Si el movimiento es más vertical que horizontal,
      // dejamos que la página haga scroll normalmente.
      if (Math.abs(distanceY) > Math.abs(distanceX)) {
        return;
      }

      // Ignorar movimientos demasiado pequeños.
      if (Math.abs(distanceX) < minimumDistance) {
        return;
      }

      // Deslizar hacia la izquierda = siguiente.
      if (distanceX > 0) {
        nextSlide();
      } else {
        // Deslizar hacia la derecha = anterior.
        previousSlide();
      }

      restartAutoplay();
    },
    {
      passive: true
    }
  );


  /* =========================================
     TECLADO
  ========================================= */

  document.addEventListener("keydown", (event) => {
    if (event.key === "ArrowRight") {
      nextSlide();
      restartAutoplay();
    }

    if (event.key === "ArrowLeft") {
      previousSlide();
      restartAutoplay();
    }
  });


  /* =========================================
     INICIAR SLIDER
  ========================================= */

  showSlide(0);
  startAutoplay();
});
