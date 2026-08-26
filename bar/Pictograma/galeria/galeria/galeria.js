document.addEventListener("DOMContentLoaded", () => {
  const pictogramasVisibleElements = document.querySelectorAll(
    ".pictogramas-visible"
  );

  if (!("IntersectionObserver" in window)) {
    pictogramasVisibleElements.forEach((element) => {
      element.classList.add("pictogramas-is-visible");
    });

    return;
  }

  const pictogramasObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add("pictogramas-is-visible");
        pictogramasObserver.unobserve(entry.target);
      });
    },
    {
      threshold: 0.1
    }
  );

  pictogramasVisibleElements.forEach((element) => {
    pictogramasObserver.observe(element);
  });
});
