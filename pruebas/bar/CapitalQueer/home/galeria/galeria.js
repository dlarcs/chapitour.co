(() => {

  'use strict';


  /* =========================================
     ANIMACIÓN DE ENTRADA
  ========================================= */

  const CapitalQueerFeaturedPlaces =
    document.querySelectorAll('.CapitalQueer-visible');


  if (CapitalQueerFeaturedPlaces.length > 0) {

    if ('IntersectionObserver' in window) {

      const CapitalQueerObserver = new IntersectionObserver(
        (entries, observer) => {

          entries.forEach((entry) => {

            if (entry.isIntersecting) {

              entry.target.classList.add(
                'CapitalQueer-is-visible'
              );

              observer.unobserve(entry.target);
            }

          });

        },
        {
          threshold: 0.3
        }
      );


      CapitalQueerFeaturedPlaces.forEach((CapitalQueerPlace) => {

        CapitalQueerObserver.observe(CapitalQueerPlace);

      });

    } else {

      CapitalQueerFeaturedPlaces.forEach((CapitalQueerPlace) => {

        CapitalQueerPlace.classList.add(
          'CapitalQueer-is-visible'
        );

      });

    }

  }


  /* =========================================
     LIGHTBOX CAPITAL QUEER
  ========================================= */

  const CapitalQueerGalleryImages =
    document.querySelectorAll('.CapitalQueer-gallery-image');

  const CapitalQueerLightbox =
    document.getElementById('CapitalQueer-lightbox');

  const CapitalQueerLightboxImage =
    CapitalQueerLightbox?.querySelector(
      '.CapitalQueer-lightbox-image'
    );

  const CapitalQueerLightboxClose =
    CapitalQueerLightbox?.querySelector(
      '.CapitalQueer-lightbox-close'
    );


  if (
    CapitalQueerGalleryImages.length === 0 ||
    !CapitalQueerLightbox ||
    !CapitalQueerLightboxImage ||
    !CapitalQueerLightboxClose
  ) {
    return;
  }


  let CapitalQueerLastFocusedElement = null;


  /* =========================================
     ABRIR LIGHTBOX
  ========================================= */

  function CapitalQueerOpenLightbox(CapitalQueerImage) {

    if (!CapitalQueerImage) {
      return;
    }


    CapitalQueerLastFocusedElement =
      document.activeElement;


    CapitalQueerLightboxImage.src =
      CapitalQueerImage.currentSrc ||
      CapitalQueerImage.src;


    CapitalQueerLightboxImage.alt =
      CapitalQueerImage.alt ||
      'Imagen ampliada de Capital Queer';


    CapitalQueerLightbox.classList.add(
      'CapitalQueer-lightbox-visible'
    );


    CapitalQueerLightbox.setAttribute(
      'aria-hidden',
      'false'
    );


    document.documentElement.classList.add(
      'CapitalQueer-lightbox-open'
    );


    document.body.classList.add(
      'CapitalQueer-lightbox-open'
    );


    CapitalQueerLightboxClose.focus();

  }


  /* =========================================
     CERRAR LIGHTBOX
  ========================================= */

  function CapitalQueerCloseLightbox() {

    if (
      !CapitalQueerLightbox.classList.contains(
        'CapitalQueer-lightbox-visible'
      )
    ) {
      return;
    }


    CapitalQueerLightbox.classList.remove(
      'CapitalQueer-lightbox-visible'
    );


    CapitalQueerLightbox.setAttribute(
      'aria-hidden',
      'true'
    );


    document.documentElement.classList.remove(
      'CapitalQueer-lightbox-open'
    );


    document.body.classList.remove(
      'CapitalQueer-lightbox-open'
    );


    CapitalQueerLightboxImage.src = '';
    CapitalQueerLightboxImage.alt = '';


    if (
      CapitalQueerLastFocusedElement &&
      typeof CapitalQueerLastFocusedElement.focus === 'function'
    ) {

      CapitalQueerLastFocusedElement.focus();

    }


    CapitalQueerLastFocusedElement = null;

  }


  /* =========================================
     EVENTOS DE LAS IMÁGENES
  ========================================= */

  CapitalQueerGalleryImages.forEach(
    (CapitalQueerImage) => {

      CapitalQueerImage.addEventListener(
        'click',
        () => {

          CapitalQueerOpenLightbox(
            CapitalQueerImage
          );

        }
      );


      CapitalQueerImage.addEventListener(
        'keydown',
        (event) => {

          if (
            event.key === 'Enter' ||
            event.key === ' '
          ) {

            event.preventDefault();

            CapitalQueerOpenLightbox(
              CapitalQueerImage
            );

          }

        }
      );

    }
  );


  /* =========================================
     BOTÓN X
  ========================================= */

  CapitalQueerLightboxClose.addEventListener(
    'click',
    CapitalQueerCloseLightbox
  );


  /* =========================================
     CERRAR AL TOCAR EL FONDO
  ========================================= */

  CapitalQueerLightbox.addEventListener(
    'click',
    (event) => {

      if (
        event.target === CapitalQueerLightbox
      ) {

        CapitalQueerCloseLightbox();

      }

    }
  );


  /* =========================================
     TECLADO
  ========================================= */

  document.addEventListener(
    'keydown',
    (event) => {

      const CapitalQueerIsOpen =
        CapitalQueerLightbox.classList.contains(
          'CapitalQueer-lightbox-visible'
        );


      if (!CapitalQueerIsOpen) {
        return;
      }


      /* ESCAPE */

      if (event.key === 'Escape') {

        event.preventDefault();

        CapitalQueerCloseLightbox();

        return;

      }


      /*
       * El único control interactivo dentro
       * del lightbox es el botón cerrar.
       * Esto evita que TAB vaya al contenido
       * que se encuentra detrás del visor.
       */

      if (event.key === 'Tab') {

        event.preventDefault();

        CapitalQueerLightboxClose.focus();

      }

    }
  );

})();
