(() => {

  "use strict";


  /* =========================================
     ANIMACIÓN AL HACER SCROLL
  ========================================= */

  const streetFeaturedPlaces =
    document.querySelectorAll(".street-visible");


  if (streetFeaturedPlaces.length > 0) {

    if ("IntersectionObserver" in window) {

      const streetObserver = new IntersectionObserver(
        (entries, observer) => {

          entries.forEach((entry) => {

            if (entry.isIntersecting) {

              entry.target.classList.add(
                "street-is-visible"
              );

              observer.unobserve(entry.target);

            }

          });

        },
        {
          threshold: 0.3
        }
      );


      streetFeaturedPlaces.forEach((streetPlace) => {

        streetObserver.observe(streetPlace);

      });

    } else {

      streetFeaturedPlaces.forEach((streetPlace) => {

        streetPlace.classList.add(
          "street-is-visible"
        );

      });

    }

  }



  /* =========================================
     DESCRIPCIONES CON "VER MÁS"
  ========================================= */

  const streetDescripciones =
    document.querySelectorAll(
      ".street-descripcion-card"
    );


  streetDescripciones.forEach(
    (streetDescripcion) => {

      const streetTextoCompleto =
        streetDescripcion.textContent.trim();

      const streetPalabras =
        streetTextoCompleto.split(" ");


      if (streetPalabras.length > 8) {

        const streetTextoCorto =
          streetPalabras
            .slice(0, 8)
            .join(" ");

        let streetExpandido = false;


        streetDescripcion.textContent =
          `${streetTextoCorto}...`;


        const streetBoton =
          document.createElement("span");


        streetBoton.textContent =
          " Ver más";

        streetBoton.classList.add(
          "street-ver-mas"
        );


        streetDescripcion.after(
          streetBoton
        );


        streetBoton.addEventListener(
          "click",
          () => {

            if (streetExpandido) {

              streetDescripcion.textContent =
                `${streetTextoCorto}...`;

              streetBoton.textContent =
                " Ver más";

              streetExpandido = false;

            } else {

              streetDescripcion.textContent =
                streetTextoCompleto;

              streetBoton.textContent =
                " Ver menos";

              streetExpandido = true;

            }

          }
        );

      }

    }
  );

})();
