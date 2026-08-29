document.addEventListener("DOMContentLoaded", () => {
  const hero = document.querySelector(".business-hero");

  if (!hero) {
    return;
  }

  const slides = hero.querySelectorAll(".business-hero__slide");
  const dots = hero.querySelectorAll(".hero-dot");

  if (slides.length === 0) {
    return;
  }

  let currentSlide = 0;
  let autoplayInterval = null;

  const autoplayTime = 4000;
  const swipeMinimumDistance = 50;

  let touchStartX = 0;
  let touchStartY = 0;

  /* =========================
     MOSTRAR SLIDE
  ========================= */

  const showSlide = (index) => {
    if (index < 0) {
      index = slides.length - 1;
    }

    if (index >= slides.length) {
      index = 0;
    }

    currentSlide = index;

    slides.forEach((slide, slideIndex) => {
      slide.classList.toggle("active", slideIndex === currentSlide);
    });

    dots.forEach((dot, dotIndex) => {
      const isActive = dotIndex === currentSlide;

      dot.classList.toggle("active", isActive);
      dot.setAttribute(
        "aria-current",
        isActive ? "true" : "false"
      );
    });
  };

  /* =========================
     SIGUIENTE / ANTERIOR
  ========================= */

  const nextSlide = () => {
    showSlide(currentSlide + 1);
  };

  const previousSlide = () => {
    showSlide(currentSlide - 1);
  };

  /* =========================
     AUTOPLAY
  ========================= */

  const stopAutoplay = () => {
    if (autoplayInterval !== null) {
      clearInterval(autoplayInterval);
      autoplayInterval = null;
    }
  };

  const startAutoplay = () => {
    stopAutoplay();

    autoplayInterval = setInterval(() => {
      nextSlide();
    }, autoplayTime);
  };

  const restartAutoplay = () => {
    startAutoplay();
  };

  /* =========================
     PUNTOS
  ========================= */

  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
      showSlide(index);
      restartAutoplay();
    });
  });

  /* =========================
     SWIPE CELULAR
  ========================= */

  hero.addEventListener(
    "touchstart",
    (event) => {
      const touch = event.changedTouches[0];

      touchStartX = touch.clientX;
      touchStartY = touch.clientY;
    },
    { passive: true }
  );

  hero.addEventListener(
    "touchend",
    (event) => {
      const touch = event.changedTouches[0];

      const touchEndX = touch.clientX;
      const touchEndY = touch.clientY;

      const differenceX = touchEndX - touchStartX;
      const differenceY = touchEndY - touchStartY;

      const horizontalDistance = Math.abs(differenceX);
      const verticalDistance = Math.abs(differenceY);

      /*
       * Solo cambia de slide cuando el movimiento
       * es principalmente horizontal.
       *
       * Esto permite que el scroll vertical del
       * celular siga funcionando normalmente.
       */
      if (
        horizontalDistance < swipeMinimumDistance ||
        horizontalDistance <= verticalDistance
      ) {
        return;
      }

      if (differenceX < 0) {
        // Swipe izquierda
        nextSlide();
      } else {
        // Swipe derecha
        previousSlide();
      }

      restartAutoplay();
    },
    { passive: true }
  );

  /* =========================
     TECLADO
  ========================= */

  document.addEventListener("keydown", (event) => {
    if (event.key === "ArrowLeft") {
      previousSlide();
      restartAutoplay();
    }

    if (event.key === "ArrowRight") {
      nextSlide();
      restartAutoplay();
    }
  });

  /* =========================
     ANIMACIÓN DE ENTRADA
  ========================= */

  if (hero.classList.contains("visible")) {
    if ("IntersectionObserver" in window) {
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

      observer.observe(hero);
    } else {
      hero.classList.add("is-visible");
    }
  }

  /* =========================
     INICIO
  ========================= */

  showSlide(0);
  startAutoplay();
});
