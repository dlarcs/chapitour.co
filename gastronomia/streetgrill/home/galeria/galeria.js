(() => {

  'use strict';


  /* =========================================
     ANIMACIÓN DE ENTRADA
  ========================================= */

  const StreetgrillFeaturedPlaces =
    document.querySelectorAll('.street-visible');


  if (StreetgrillFeaturedPlaces.length > 0) {

    if ('IntersectionObserver' in window) {

      const StreetgrillObserver = new IntersectionObserver(
        (entries, observer) => {

          entries.forEach((entry) => {

            if (entry.isIntersecting) {

              entry.target.classList.add(
                'street-is-visible'
              );

              observer.unobserve(entry.target);
            }

          });

        },
        {
          threshold: 0.3
        }
      );


      StreetgrillFeaturedPlaces.forEach((StreetgrillPlace) => {

        StreetgrillObserver.observe(StreetgrillPlace);

      });

    } else {

      StreetgrillFeaturedPlaces.forEach((StreetgrillPlace) => {

        StreetgrillPlace.classList.add(
          'street-is-visible'
        );

      });

    }

  }


  /* =========================================
     LIGHTBOX CAPITAL QUEER
  ========================================= */

  const StreetgrillGalleryImages =
    document.querySelectorAll('.street-gallery-image');

  const StreetgrillLightbox =
    document.getElementById('Streetgrill-lightbox');

  const StreetgrillLightboxImage =
    StreetgrillLightbox?.querySelector(
      '.street-lightbox-image'
    );

  const StreetgrillLightboxClose =
    StreetgrillLightbox?.querySelector(
      '.street-lightbox-close'
    );


  if (
    StreetgrillGalleryImages.length === 0 ||
    !StreetgrillLightbox ||
    !StreetgrillLightboxImage ||
    !StreetgrillLightboxClose
  ) {
    return;
  }


  let StreetgrillLastFocusedElement = null;


  /* =========================================
     ABRIR LIGHTBOX
  ========================================= */

  function StreetgrillOpenLightbox(StreetgrillImage) {

    if (!StreetgrillImage) {
      return;
    }


    StreetgrillLastFocusedElement =
      document.activeElement;


    StreetgrillLightboxImage.src =
      StreetgrillImage.currentSrc ||
      StreetgrillImage.src;


    StreetgrillLightboxImage.alt =
      StreetgrillImage.alt ||
      'Imagen ampliada de Street Grill';


    StreetgrillLightbox.classList.add(
      'street-lightbox-visible'
    );


    StreetgrillLightbox.setAttribute(
      'aria-hidden',
      'false'
    );


    document.documentElement.classList.add(
      'street-lightbox-open'
    );


    document.body.classList.add(
      'street-lightbox-open'
    );


    StreetgrillLightboxClose.focus();

  }


  /* =========================================
     CERRAR LIGHTBOX
  ========================================= */

  function StreetgrillCloseLightbox() {

    if (
      !StreetgrillLightbox.classList.contains(
        'street-lightbox-visible'
      )
    ) {
      return;
    }


    StreetgrillLightbox.classList.remove(
      'street-lightbox-visible'
    );


    StreetgrillLightbox.setAttribute(
      'aria-hidden',
      'true'
    );


    document.documentElement.classList.remove(
      'street-lightbox-open'
    );


    document.body.classList.remove(
      'street-lightbox-open'
    );


    StreetgrillLightboxImage.src = '';
    StreetgrillLightboxImage.alt = '';


    if (
      StreetgrillLastFocusedElement &&
      typeof StreetgrillLastFocusedElement.focus === 'function'
    ) {

      StreetgrillLastFocusedElement.focus();

    }


    StreetgrillLastFocusedElement = null;

  }


  /* =========================================
     EVENTOS DE LAS IMÁGENES
  ========================================= */

  StreetgrillGalleryImages.forEach(
    (StreetgrillImage) => {

      StreetgrillImage.addEventListener(
        'click',
        () => {

          StreetgrillOpenLightbox(
            StreetgrillImage
          );

        }
      );


      StreetgrillImage.addEventListener(
        'keydown',
        (event) => {

          if (
            event.key === 'Enter' ||
            event.key === ' '
          ) {

            event.preventDefault();

            StreetgrillOpenLightbox(
              StreetgrillImage
            );

          }

        }
      );

    }
  );


  /* =========================================
     BOTÓN X
  ========================================= */

  StreetgrillLightboxClose.addEventListener(
    'click',
    StreetgrillCloseLightbox
  );


  /* =========================================
     CERRAR AL TOCAR EL FONDO
  ========================================= */

  StreetgrillLightbox.addEventListener(
    'click',
    (event) => {

      if (
        event.target === StreetgrillLightbox
      ) {

        StreetgrillCloseLightbox();

      }

    }
  );


  /* =========================================
     TECLADO
  ========================================= */

  document.addEventListener(
    'keydown',
    (event) => {

      const StreetgrillIsOpen =
        StreetgrillLightbox.classList.contains(
          'street-lightbox-visible'
        );


      if (!StreetgrillIsOpen) {
        return;
      }


      /* ESCAPE */

      if (event.key === 'Escape') {

        event.preventDefault();

        StreetgrillCloseLightbox();

        return;

      }


      if (event.key === 'Tab') {

        event.preventDefault();

        StreetgrillLightboxClose.focus();

      }

    }
  );

})();
