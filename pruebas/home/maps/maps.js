"use strict";

class MapaChapitour {

  constructor() {

    this.centroChapinero = [4.6486, -74.0628];

    this.mapa = null;

    this.marcadores = [];

    /*
      Capital Queer y Gran&Chela Club pueden pertenecer
      a varias categorías al mismo tiempo.

      Los demás negocios son ejemplos que puedes
      cambiar por negocios reales.
    */

    this.negocios = [

      /* =====================================
         CAPITAL QUEER
      ===================================== */

      {
        id: 1,

        nombre: "Capital Queer",

        categorias: [
          "Bar",
          "EspacioDiverso"
        ],

        categoriaTexto: "Bar y espacio diverso",

        descripcion:
          "Un espacio libre, diverso y seguro en Chapinero, abierto a todas las personas.",

        latitud: 4.645436418244032,
        longitud: -74.06257274786871,

        icono: "🏳️‍🌈",
        clasePin: "pin-diverso",

        imagen:
          "/bar/CapitalQueer/img/logoCapitalQueer.jpg",

        url:
          "bar/CapitalQueer/index.php"
      },

      /* =====================================
         GRAN&CHELA CLUB
      ===================================== */

      {
        id: 2,

        nombre: "Gran&Chela Club",

        categorias: [
          "Bar",
          "Gastrobar",
          "Discoteca",
          "EspacioDiverso"
        ],

        categoriaTexto:
          "Bar, gastrobar y discoteca",

        descripcion:
          "Bar y discoteca diversa en Chapinero con música, promociones y eventos especiales.",

        /*
          Cambia estas coordenadas por las coordenadas
          exactas de Gran&Chela Club.
        */

        latitud: 4.6474,
        longitud: -74.0642,

        icono: "🪩",
        clasePin: "pin-discoteca",

        /*
          Cambia la imagen si la carpeta tiene otro nombre.
        */

        imagen:
          "/bar/Gran&Chela_Club/img/logo.jpg",

        url:
          "/bar/Gran&Chela_Club/index.php"
      },

      /* =====================================
         EJEMPLO DE CAFÉ
      ===================================== */

      {
        id: 3,

        nombre: "Café Chapinero",

        categorias: [
          "Cafe"
        ],

        categoriaTexto:
          "Café y bebidas",

        descripcion:
          "Ejemplo de una cafetería local con café colombiano, bebidas y espacios para compartir.",

        latitud: 4.6502,
        longitud: -74.0612,

        icono: "☕",
        clasePin: "pin-cafe",

        imagen:
          "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80",

        url:
          "#"
      },

      /* =====================================
         EJEMPLO DE GASTRONOMÍA
      ===================================== */

      {
        id: 4,

        nombre: "Sabores de Chapinero",

        categorias: [
          "Gastronomia"
        ],

        categoriaTexto:
          "Restaurante",

        descripcion:
          "Ejemplo de un restaurante con sabores locales, platos especiales y cocina colombiana.",

        latitud: 4.6491,
        longitud: -74.0660,

        icono: "🍽️",
        clasePin: "pin-gastronomia",

        imagen:
          "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80",

        url:
          "#"
      },

      /* =====================================
         EJEMPLO DE COMIDAS RÁPIDAS
      ===================================== */

      {
        id: 5,

        nombre: "La Hamburguesería Local",

        categorias: [
          "ComidasRapidas"
        ],

        categoriaTexto:
          "Comidas rápidas",

        descripcion:
          "Ejemplo de un negocio de hamburguesas, perros calientes, papas y comidas rápidas.",

        latitud: 4.6468,
        longitud: -74.0599,

        icono: "🍔",
        clasePin: "pin-comidas-rapidas",

        imagen:
          "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80",

        url:
          "#"
      },

      /* =====================================
         EJEMPLO DE POSTRES
      ===================================== */

      {
        id: 6,

        nombre: "Dulce Chapinero",

        categorias: [
          "Postres"
        ],

        categoriaTexto:
          "Postres y pastelería",

        descripcion:
          "Ejemplo de una pastelería con tortas, cupcakes, postres artesanales y bebidas.",

        latitud: 4.6521,
        longitud: -74.0638,

        icono: "🧁",
        clasePin: "pin-postres",

        imagen:
          "https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=80",

        url:
          "#"
      }
    ];

    this.iniciar();
  }

  iniciar() {

    const contenedorMapa =
      document.getElementById("map");

    if (!contenedorMapa) {
      console.error(
        "No se encontró el elemento con id='map'."
      );

      return;
    }

    if (typeof L === "undefined") {
      console.error(
        "Leaflet no pudo cargarse."
      );

      return;
    }

    this.crearMapa();
    this.agregarCapaBase();
    this.crearMarcadores();
    this.prepararFiltros();
    this.actualizarCantidades();
  }

  crearMapa() {

    this.mapa = L.map("map", {

      center: this.centroChapinero,

      zoom: 15,

      zoomControl: false,

      scrollWheelZoom: true,

      doubleClickZoom: true,

      touchZoom: true,

      dragging: true
    });

    L.control.zoom({

      position: "bottomright"

    }).addTo(this.mapa);
  }

  agregarCapaBase() {

    L.tileLayer(

      "https://tile.openstreetmap.org/{z}/{x}/{y}.png",

      {
        minZoom: 12,

        maxZoom: 19,

        attribution:
          '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a>'
      }

    ).addTo(this.mapa);
  }

  crearIcono(negocio) {

    return L.divIcon({

      className:
        "marcador-personalizado",

      html: `
        <div class="pin ${negocio.clasePin}">
          <span aria-hidden="true">
            ${negocio.icono}
          </span>
        </div>
      `,

      iconSize: [54, 54],

      iconAnchor: [27, 52],

      popupAnchor: [0, -55]
    });
  }

  crearPopup(negocio) {

    const categoriasTexto =
      negocio.categorias
        .map((categoria) => {
          return this.obtenerNombreCategoria(categoria);
        })
        .join(" · ");

    return `
      <article class="popup-negocio">

        <img
          class="popup-imagen"
          src="${negocio.imagen}"
          alt="Imagen de ${negocio.nombre}"
          loading="lazy"
          onerror="this.src='https://placehold.co/700x400?text=Chapitour'"
        >

        <div class="popup-contenido">

          <span class="popup-categoria">
            ${negocio.categoriaTexto}
          </span>

          <h2>
            ${negocio.nombre}
          </h2>

          <p>
            ${negocio.descripcion}
          </p>

          <small class="popup-etiquetas">
            ${categoriasTexto}
          </small>

          ${
            negocio.url !== "#"
              ? `
                <a
                  class="boton-negocio"
                  href="${negocio.url}"
                >
                  Ver negocio
                  <span aria-hidden="true">→</span>
                </a>
              `
              : `
                <span class="negocio-ejemplo">
                  Negocio de ejemplo
                </span>
              `
          }

        </div>

      </article>
    `;
  }

  obtenerNombreCategoria(categoria) {

    const nombres = {

      Bar:
        "Bar",

      Gastrobar:
        "Gastrobar",

      Cafe:
        "Café",

      Gastronomia:
        "Gastronomía",

      ComidasRapidas:
        "Comidas rápidas",

      EspacioDiverso:
        "Espacio diverso",

      Discoteca:
        "Discoteca",

      Postres:
        "Postres"
    };

    return nombres[categoria] || categoria;
  }

  crearMarcadores() {

    this.negocios.forEach((negocio) => {

      const marcador = L.marker(

        [
          negocio.latitud,
          negocio.longitud
        ],

        {
          icon:
            this.crearIcono(negocio),

          title:
            negocio.nombre
        }
      );

      marcador.bindPopup(

        this.crearPopup(negocio),

        {
          maxWidth: 320,

          minWidth: 250,

          closeButton: true
        }
      );

      marcador.addTo(this.mapa);

      this.marcadores.push({

        marcador: marcador,

        negocio: negocio
      });
    });
  }

  prepararFiltros() {

    const botones =
      document.querySelectorAll(
        "[data-categoria]"
      );

    botones.forEach((boton) => {

      boton.addEventListener(
        "click",
        () => {

          const categoria =
            boton.dataset.categoria;

          this.cambiarBotonActivo(
            boton
          );

          this.filtrarMarcadores(
            categoria
          );
        }
      );
    });
  }

  cambiarBotonActivo(
    botonSeleccionado
  ) {

    const botones =
      document.querySelectorAll(
        "[data-categoria]"
      );

    botones.forEach((boton) => {

      boton.classList.remove(
        "activa"
      );
    });

    botonSeleccionado.classList.add(
      "activa"
    );
  }

  filtrarMarcadores(
    categoriaSeleccionada
  ) {

    const marcadoresVisibles = [];

    this.mapa.closePopup();

    this.marcadores.forEach(
      (elemento) => {

        const perteneceCategoria =
          elemento.negocio.categorias.includes(
            categoriaSeleccionada
          );

        const debeMostrarse =
          categoriaSeleccionada === "todos" ||
          perteneceCategoria;

        if (debeMostrarse) {

          if (
            !this.mapa.hasLayer(
              elemento.marcador
            )
          ) {

            elemento.marcador.addTo(
              this.mapa
            );
          }

          marcadoresVisibles.push(
            elemento.marcador
          );

        } else {

          if (
            this.mapa.hasLayer(
              elemento.marcador
            )
          ) {

            this.mapa.removeLayer(
              elemento.marcador
            );
          }
        }
      }
    );

    this.ajustarVista(
      marcadoresVisibles
    );
  }

  ajustarVista(
    marcadoresVisibles
  ) {

    if (
      marcadoresVisibles.length === 0
    ) {

      this.mapa.setView(
        this.centroChapinero,
        15
      );

      return;
    }

    if (
      marcadoresVisibles.length === 1
    ) {

      const coordenadas =
        marcadoresVisibles[0]
          .getLatLng();

      this.mapa.setView(
        coordenadas,
        17
      );

      marcadoresVisibles[0]
        .openPopup();

      return;
    }

    const grupo =
      L.featureGroup(
        marcadoresVisibles
      );

    this.mapa.fitBounds(
      grupo.getBounds(),
      {
        padding: [50, 50],
        maxZoom: 16
      }
    );
  }

  actualizarCantidades() {

    const botones =
      document.querySelectorAll(
        "[data-categoria]"
      );

    botones.forEach((boton) => {

      const categoria =
        boton.dataset.categoria;

      const contador =
        boton.querySelector(
          ".categoria-cantidad"
        );

      if (!contador) {
        return;
      }

      let cantidad = 0;

      if (categoria === "todos") {

        cantidad =
          this.negocios.length;

      } else {

        cantidad =
          this.negocios.filter(
            (negocio) => {

              return negocio.categorias.includes(
                categoria
              );
            }
          ).length;
      }

      contador.textContent =
        cantidad;
    });
  }
}

document.addEventListener(
  "DOMContentLoaded",
  () => {

    new MapaChapitour();
  }
);
