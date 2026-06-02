document.addEventListener("DOMContentLoaded", () => {
  const sections = document.querySelectorAll(".visible_menu");

  if (sections.length > 0) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.3
    });

    sections.forEach((section) => {
      observer.observe(section);
    });
  }
});
