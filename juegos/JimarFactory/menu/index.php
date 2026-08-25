<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Menú y servicios | Jimar Factory Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/juegos/JimarFactory/menu/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Conoce el menú y los servicios de Jimar Factory Chapinero: bebidas, cócteles, café, billar tres bandas, pool y materiales de juego para mayores de 18 años."
  >

  <meta
    name="robots"
    content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1"
  >

  <meta
    name="googlebot"
    content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1"
  >

  <link
    rel="canonical"
    href="https://www.chapitour.co/juegos/JimarFactory/menu/"
  >

  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:locale" content="es_CO">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">

  <meta
    property="og:title"
    content="Menú y servicios | Jimar Factory Chapinero"
  >

  <meta
    property="og:description"
    content="Bebidas, cócteles, café, billar tres bandas, pool y entretenimiento todos los días en Jimar Factory Chapinero."
  >

  <meta
    property="og:url"
    content="https://www.chapitour.co/juegos/JimarFactory/menu/"
  >

  <meta
    property="og:image"
    content="https://www.chapitour.co/juegos/JimarFactory/img/general11.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://www.chapitour.co/juegos/JimarFactory/img/general11.jpeg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <meta
    property="og:image:alt"
    content="Menú y servicios de Jimar Factory Chapinero"
  >

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Menú y servicios | Jimar Factory Chapinero"
  >

  <meta
    name="twitter:description"
    content="Conoce nuestras bebidas, cócteles, café, servicios de billar tres bandas, pool y materiales de juego."
  >

  <meta
    name="twitter:image"
    content="https://www.chapitour.co/juegos/JimarFactory/img/general11.jpeg"
  >

  <meta
    name="twitter:image:alt"
    content="Jimar Factory Chapinero, billar y entretenimiento en Bogotá"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    type="image/jpeg"
    href="/juegos/JimarFactory/img/general11.jpeg"
  >

  <link
    rel="icon"
    type="image/jpeg"
    sizes="32x32"
    href="/juegos/JimarFactory/img/general11.jpeg"
  >

  <link
    rel="icon"
    type="image/jpeg"
    sizes="16x16"
    href="/juegos/JimarFactory/img/general11.jpeg"
  >

  <link
    rel="apple-touch-icon"
    sizes="180x180"
    href="/juegos/JimarFactory/img/general11.jpeg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../../juegos/JimarFactory/menu/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
  >

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebSite",
        "@id": "https://www.chapitour.co/#website",
        "name": "Chapitour",
        "url": "https://www.chapitour.co/",
        "inLanguage": "es-CO",
        "description": "Guía comercial, turística y de entretenimiento de Chapinero, Bogotá."
      },
      {
        "@type": "WebPage",
        "@id": "https://www.chapitour.co/juegos/JimarFactory/menu/#webpage",
        "url": "https://www.chapitour.co/juegos/JimarFactory/menu/",
        "name": "Menú y servicios | Jimar Factory Chapinero",
        "description": "Menú de bebidas, cócteles, café y servicios de billar de Jimar Factory Chapinero.",
        "inLanguage": "es-CO",
        "isPartOf": {
          "@id": "https://www.chapitour.co/#website"
        },
        "about": {
          "@id": "https://www.chapitour.co/juegos/JimarFactory/#business"
        },
        "mainEntity": {
          "@id": "https://www.chapitour.co/juegos/JimarFactory/menu/#catalog"
        },
        "primaryImageOfPage": {
          "@type": "ImageObject",
          "url": "https://www.chapitour.co/juegos/JimarFactory/img/general11.jpeg",
          "width": 1200,
          "height": 630
        }
      },
      {
        "@type": [
          "SportsActivityLocation",
          "EntertainmentBusiness"
        ],
        "@id": "https://www.chapitour.co/juegos/JimarFactory/#business",
        "name": "Jimar Factory Chapinero",
        "alternateName": "Jimar Factory",
        "slogan": "El mejor ambiente de billar en Bogotá",
        "description": "Jimar Factory Chapinero ofrece billar tres bandas, pool, bebidas, cócteles, café, entretenimiento y venta de materiales de juego para mayores de 18 años.",
        "url": "https://www.chapitour.co/juegos/JimarFactory/",
        "telephone": "+57 315 617 5056",
        "image": "https://www.chapitour.co/juegos/JimarFactory/img/general11.jpeg",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Calle 58 #13-93",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        },
        "areaServed": {
          "@type": "City",
          "name": "Bogotá"
        },
        "openingHoursSpecification": [
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
              "https://schema.org/Monday",
              "https://schema.org/Tuesday",
              "https://schema.org/Wednesday",
              "https://schema.org/Thursday",
              "https://schema.org/Friday",
              "https://schema.org/Saturday",
              "https://schema.org/Sunday"
            ],
            "opens": "09:00",
            "closes": "00:00"
          }
        ],
        "hasOfferCatalog": {
          "@id": "https://www.chapitour.co/juegos/JimarFactory/menu/#catalog"
        }
      },
      {
        "@type": "OfferCatalog",
        "@id": "https://www.chapitour.co/juegos/JimarFactory/menu/#catalog",
        "name": "Menú y servicios de Jimar Factory Chapinero",
        "url": "https://www.chapitour.co/juegos/JimarFactory/menu/",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Billar tres bandas",
              "description": "Servicio de mesas de billar tres bandas para disfrutar partidas en Chapinero."
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Pool",
              "description": "Servicio de mesas de pool para compartir partidas con amigos."
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Product",
              "name": "Bebidas",
              "description": "Variedad de bebidas para acompañar las partidas y momentos de entretenimiento."
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Product",
              "name": "Cócteles",
              "description": "Cócteles y bebidas con alcohol disponibles únicamente para mayores de 18 años.",
              "audience": {
                "@type": "PeopleAudience",
                "suggestedMinAge": 18
              }
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Product",
              "name": "Café",
              "description": "Servicio de café para acompañar cada visita."
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Product",
              "name": "Materiales y accesorios de juego",
              "description": "Venta de materiales y accesorios especializados para billar y pool, dirigida a mayores de 18 años.",
              "audience": {
                "@type": "PeopleAudience",
                "suggestedMinAge": 18
              }
            }
          }
        ]
      }
    ]
  }
  </script>

</head>

<body>

  <?php include "../../../juegos/JimarFactory/global/pag_nav/pag_nav.php"; ?>

  <main class="container_menu">
    <?php include "../../../juegos/JimarFactory/menu/menu/menu.php"; ?>
  </main>

  <?php include "../../../juegos/JimarFactory/global/boton/boton.php"; ?>

  <?php include "../../../juegos/JimarFactory/global/pag_footer/pag_footer.php"; ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573165180649?text=Hola%2C%20vengo%20desde%20la%20web%20de%20Jimar%20Factory%20Chapinero%20y%20quisiera%20conocer%20el%20men%C3%BA%2C%20las%20bebidas%20y%20los%20servicios."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Consultar el menú de Jimar Factory Chapinero por WhatsApp"
  >
    <img
      src="/juegos/JimarFactory/global/img/img_whatsApp.png"
      alt="Contactar a Jimar Factory Chapinero por WhatsApp"
      loading="lazy"
      decoding="async"
    >
  </a>

</body>
</html>
