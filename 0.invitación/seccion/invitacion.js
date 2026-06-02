document.addEventListener('DOMContentLoaded', function () {
  const items = document.querySelectorAll('.sr-item');

  if (!items.length) return;

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (prefersReducedMotion) {
    items.forEach(function (item) {
      item.classList.add('is-revealed');
    });

    return;
  }

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-revealed');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.25
  });

  items.forEach(function (item) {
    observer.observe(item);
  });
});
