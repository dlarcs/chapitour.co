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
    image: 'home/img/cocktel.png',
    title: 'Azul Restaurante',
    location: '📍 Zona T',
    category: '🍽️ Cocina contemporánea',
    rating: '★ 4.8 (245)',
    alt: 'Azul Restaurante'
  },
  {
    image: 'home/img/cafe1.png',
    title: 'El Jardín Secreto',
    location: '📍 Chapinero',
    category: '☕ Café & Bistro',
    rating: '★ 4.6 (180)',
    alt: 'El Jardín Secreto'
  },
  {
    image: 'home/img/hamburguesa1.png',
    title: 'La Esquina del Sabor',
    location: '📍 Zona G',
    category: '🍲 Comida local',
    rating: '★ 4.5 (132)',
    alt: 'La Esquina del Sabor'
  },
  {
    image: 'home/img/lgbtiq+.png',
    title: 'Bar 80/20',
    location: '📍 Chapinero Alto',
    category: '🍸 Cócteles & Música',
    rating: '★ 4.7 (210)',
    alt: 'Bar 80/20'
  },
  {
    image: 'home/img/artesanias_bolso.png',
    title: 'Mercado del Chef',
    location: '📍 Quinta Camacho',
    category: '🍴 Experiencia gastronómica',
    rating: '★ 4.6 (165)',
    alt: 'Mercado del Chef'
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
