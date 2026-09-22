const whatsappNumber = "573138846378";

const promoCards = document.querySelectorAll(".promo-card");

function generarCodigoPromo() {
  const fecha = new Date();

  const horas = String(fecha.getHours()).padStart(2, "0");
  const minutos = String(fecha.getMinutes()).padStart(2, "0");
  const dia = String(fecha.getDate()).padStart(2, "0");
  const mes = String(fecha.getMonth() + 1).padStart(2, "0");
  const año = fecha.getFullYear();

  const codigo = `${horas}${minutos}${dia}${mes}${año}`;

  return codigo;
}

promoCards.forEach((card) => {
  card.addEventListener("click", () => {
    const businessName = card.querySelector("h3").textContent.trim();
    const description = card.querySelector("p").textContent.trim();
    const date = card.querySelector(".card-location").textContent.trim();
    const discount = card.querySelector(".card-badge").textContent.trim();

    const code = generarCodigoPromo();

    const message = `
Hey, que tal?  vengo desde Chapitour.co.

Quiero adquirir esta promoción:

Negocio: ${businessName}
Promoción: ${discount}
Detalle: ${description}
Fecha: ${date}
Código de promoción: ${code}

Con este código, puedo reclamar mi promoción en las proximas 42 horas.
    `.trim();

    const encodedMessage = encodeURIComponent(message);

    window.open(
      `https://wa.me/${whatsappNumber}?text=${encodedMessage}`,
      "_blank"
    );
  });
});
