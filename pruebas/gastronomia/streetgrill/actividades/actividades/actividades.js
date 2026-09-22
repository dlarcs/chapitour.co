(() => {
  "use strict";

  const section = document.getElementById("StreetGrill-actividades");

  if (!section) {
    return;
  }

  // Número del código original.
  // Verifica que corresponda a Street Grill antes de publicar.
  const whatsappNumber = "573143580355";

  const businessName = "Street Grill";
  const promoCards = section.querySelectorAll(".promo-card");

  function generarCodigoConsulta() {
    const fecha = new Date();

    const horas = String(fecha.getHours()).padStart(2, "0");
    const minutos = String(fecha.getMinutes()).padStart(2, "0");
    const segundos = String(fecha.getSeconds()).padStart(2, "0");
    const dia = String(fecha.getDate()).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0");
    const anio = fecha.getFullYear();

    return `SG-${anio}${mes}${dia}-${horas}${minutos}${segundos}`;
  }

  function abrirConsultaWhatsApp(card) {
    const title = card.querySelector("h3")?.textContent.trim() || "";

    const description =
      card.querySelector(".card-content p")?.textContent.trim() || "";

    const product =
      card.querySelector(".card-badge")?.textContent.trim() || "";

    const reference = card.dataset.code || "STREETGRILL";
    const code = generarCodigoConsulta();

    const message = [
      "¡Hola! Vengo desde Chapitour.co.",
      "",
      `Negocio: ${businessName}`,
      `Me interesa: ${product}`,
      `Opción: ${title}`,
      `Detalle: ${description.replace(/\s+/g, " ")}`,
      `Referencia: ${reference}`,
      `Código de consulta: ${code}`,
      "",
      "¿Me comparten los precios y las opciones disponibles para hacer mi pedido?"
    ].join("\n");

    const encodedMessage = encodeURIComponent(message);

    window.open(
      `https://wa.me/${whatsappNumber}?text=${encodedMessage}`,
      "_blank",
      "noopener,noreferrer"
    );
  }

  promoCards.forEach((card) => {
    card.addEventListener("click", () => {
      abrirConsultaWhatsApp(card);
    });

    // Permite abrir la consulta usando el teclado.
    card.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        event.preventDefault();
        abrirConsultaWhatsApp(card);
      }
    });
  });
})();
