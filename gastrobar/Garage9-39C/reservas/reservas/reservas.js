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
// Enviar reserva a WhatsApp
const bookingForm = document.getElementById("bookingForm");
const bookingMessage = document.getElementById("bookingMessage");

// Cambia este número por el WhatsApp del negocio
// Formato: código país + número, sin espacios ni +
const whatsappNumber = "573156175056";

if (bookingForm && bookingMessage) {
  bookingForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const date = document.getElementById("date").value;
    const time = document.getElementById("time").value;
    const people = document.getElementById("people").value;

    const message = `
Hola, quiero hacer una reserva.

Nombre: ${name}
Celular: ${phone}
Fecha: ${date}
Hora: ${time}
Cantidad de personas: ${people}
`;

    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;

    window.open(whatsappUrl, "_blank");

    bookingMessage.textContent = "Tu reserva se abrirá en WhatsApp para confirmar.";
    bookingForm.reset();
  });
}
