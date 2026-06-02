
document.addEventListener("DOMContentLoaded", () => {
  const carousel = document.getElementById("categoryCarousel");
  const prevBtn = document.getElementById("carouselPrev");
  const nextBtn = document.getElementById("carouselNext");

  if (carousel && prevBtn && nextBtn) {
    let angle = 0;
    const totalCards = carousel.querySelectorAll(".card-category").length;
    const step = totalCards ? 360 / totalCards : 0;

    function rotateCarouselManual(direction) {
      carousel.style.animation = "none";
      angle += direction * step;
      carousel.style.transform = `rotateY(${angle}deg)`;
    }

    prevBtn.addEventListener("click", () => {
      rotateCarouselManual(1);
    });

    nextBtn.addEventListener("click", () => {
      rotateCarouselManual(-1);
    });
  }

  new TextParallax();
});

class TextParallax {
  constructor(selector = ".text-parallax") {
    this.items = document.querySelectorAll(selector);
    if (!this.items.length) return;

    this.init();
  }

  init() {
    window.addEventListener("scroll", () => this.animateText());
    window.addEventListener("resize", () => this.animateText());

    this.animateText();
  }

  animateText() {
    this.items.forEach((item) => {
      const rect = item.getBoundingClientRect();
      const windowHeight = window.innerHeight;

      if (rect.top < windowHeight && rect.bottom > 0) {
        item.classList.add("is-visible");

        const progress = 1 - rect.top / windowHeight;
        const movement = Math.max(0, Math.min(progress, 1)) * 120;

        if (item.classList.contains("from-left")) {
          item.style.transform = `translateX(${-120 + movement}px)`;
        }

        if (item.classList.contains("from-right")) {
          item.style.transform = `translateX(${120 - movement}px)`;
        }
      }
    });
  }
}
