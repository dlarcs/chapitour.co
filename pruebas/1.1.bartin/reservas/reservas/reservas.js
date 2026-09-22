// Animación al hacer scroll
const featuredPlaces = document.querySelectorAll(".visible");

if (featuredPlaces.length > 0) {
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
      threshold: 0.1,
    }
  );

  featuredPlaces.forEach((place) => {
    observer.observe(place);
  });
}

// Mostrar u ocultar la opción de separar comida
const separateFood = document.getElementById("separateFood");
const foodBox = document.getElementById("foodBox");

if (separateFood && foodBox) {
  separateFood.addEventListener("change", () => {
    if (separateFood.checked) {
      foodBox.classList.add("active");
    } else {
      foodBox.classList.remove("active");
    }
  });
}

// Simulación de reserva
const bookingForm = document.getElementById("bookingForm");
const bookingMessage = document.getElementById("bookingMessage");

if (bookingForm && bookingMessage) {
  bookingForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const date = document.getElementById("date").value;
    const time = document.getElementById("time").value;
    const people = document.getElementById("people").value;
    const wantsFood = separateFood.checked;

    let message = `Reserva creada para ${name}, el día ${date} a las ${time}, para ${people} persona(s).`;

    if (wantsFood) {
      const food = document.getElementById("food").value;
      const quantity = document.getElementById("quantity").value;

      if (food) {
        message += ` Comida separada: ${quantity} x ${food}.`;
      } else {
        message += ` No seleccionaste comida para separar.`;
      }
    }

    bookingMessage.textContent = message;

    bookingForm.reset();
    foodBox.classList.remove("active");
  });
}
