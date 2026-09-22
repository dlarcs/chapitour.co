const featuredPlaces = document.querySelector('.featured-places');

if (featuredPlaces) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        featuredPlaces.classList.add('is-visible');
        observer.unobserve(featuredPlaces);
      }
    });
  }, {
    threshold: 0.3
  });

  observer.observe(featuredPlaces);
}
