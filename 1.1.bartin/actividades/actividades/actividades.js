document.addEventListener("DOMContentLoaded", () => {
  const visibleElements = document.querySelectorAll(".visible");

  if (!("IntersectionObserver" in window)) {
    visibleElements.forEach((element) => {
      element.classList.add("is-visible");
    });
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1
  });

  visibleElements.forEach((element) => {
    observer.observe(element);
  });
});
// TARJETAS - JS
document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".card");

  cards.forEach((card) => {
    card.addEventListener("click", () => {
      console.log("Tarjeta seleccionada:", card.querySelector("h3").textContent);
    });
  });
});
