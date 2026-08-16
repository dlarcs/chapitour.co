const featuredPlaces = document.querySelector('.visible');

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
const places = [
  {
    image: 'bar/CapitalQueer/img/general.png',
    title: 'Capital Queer',
    location: '📍 Chapinero central',
    category: '🍺 Capital Queer',
    rating: '★ 4.8 (245)',
    alt: 'Bar de mujeres'
  },
  {
    image: 'bar/CapitalQueer/img/general1.png',
    title: 'Viernes de rumba',
    location: '📍 Chapinero central',
    category: '🍺 Capital Queer',
    rating: '★ 4.8 (245)',
    alt: 'Bar de mujeres'
  },
  {
    image: 'bar/Gran&Chela_Club/img/general5.jpg',
    title: 'Micheladas 2x1',
    location: '📍 Chapinero central',
    category: '🍺 Gran&Chela Club',
    rating: '★ 4.8 (245)',
    alt: 'Bar de mujeres'
  },
  {
    image: 'bar/Gran&Chela_Club/img/general9.jpg',
    title: 'Miércoles de Voces y Copas',
    location: '📍 Chapinero central',
    category: '🍺 Gran&Chela Club',
    rating: '★ 4.7 (210)',
    alt: 'Gran&Chela Club'
  },
  {
    image: 'bar/Gran&Chela_Club/img/general4.jpg',
    title: 'Micheladas 2x1',
    location: '📍 Chapinero central',
    category: '🍺 Gran&Chela Club',
    rating: '★ 4.6 (165)',
    alt: 'Gran&Chela Club'
  }
];

const featuredCard = document.querySelector('.featured-card');
const featuredImage = document.getElementById('featuredImage');
const featuredTitle = document.getElementById('featuredTitle');
const featuredLocation = document.getElementById('featuredLocation');
const featuredCategory = document.getElementById('featuredCategory');
const featuredRating = document.getElementById('featuredRating');
const featuredDots = document.querySelectorAll('#featuredDots button');

let currentPlace = 0;

function showPlace(index) {
  const place = places[index];

  featuredCard.classList.add('is-changing');

  setTimeout(() => {
    featuredImage.src = place.image;
    featuredImage.alt = place.alt;
    featuredTitle.textContent = place.title;
    featuredLocation.textContent = place.location;
    featuredCategory.textContent = place.category;
    featuredRating.textContent = place.rating;

    featuredDots.forEach((dot) => {
      dot.classList.remove('is-active');
    });

    featuredDots[index].classList.add('is-active');

    featuredCard.classList.remove('is-changing');
    currentPlace = index;
  }, 250);
}

featuredDots.forEach((dot, index) => {
  dot.addEventListener('click', () => {
    showPlace(index);
  });
});
