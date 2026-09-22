document.addEventListener("DOMContentLoaded", () => {
  const visibleElements = document.querySelectorAll(
    ".CapitalQueer-visible"
  );

  if (!("IntersectionObserver" in window)) {
    visibleElements.forEach((element) => {
      element.classList.add("CapitalQueer-is-visible");
    });

    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("CapitalQueer-is-visible");
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1
    }
  );

  visibleElements.forEach((element) => {
    observer.observe(element);
  });
});
