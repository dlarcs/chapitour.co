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
      threshold: 0.3,
    }
  );

  featuredPlaces.forEach((place) => {
    observer.observe(place);
  });
}


// Descripciones con "Ver más"
const descripciones = document.querySelectorAll(".descripcion-card");

descripciones.forEach((descripcion) => {
  const textoCompleto = descripcion.textContent.trim();
  const palabras = textoCompleto.split(" ");

  if (palabras.length > 7) {
    const textoCorto = palabras.slice(0, 7).join(" ");
    let expandido = false;

    descripcion.textContent = `${textoCorto}...`;

    const boton = document.createElement("span");
    boton.textContent = " Ver más";
    boton.classList.add("ver-mas");

    descripcion.after(boton);

    boton.addEventListener("click", () => {
      if (expandido) {
        descripcion.textContent = `${textoCorto}...`;
        boton.textContent = " Ver más";
        expandido = false;
      } else {
        descripcion.textContent = textoCompleto;
        boton.textContent = " Ver menos";
        expandido = true;
      }
    });
  }
});
function toggleVideo(video) {
  if (video.paused) {
    video.play();
  } else {
    video.pause();
  }
}
