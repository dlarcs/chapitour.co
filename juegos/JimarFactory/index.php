<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Jimar Factory Chapinero | Billar de tres bandas y pool en Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/juegos/JimarFactory/style.css';
  $jsFile  = $base . '/juegos/JimarFactory/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Visita Jimar Factory Chapinero, billar de tres bandas y pool en la Calle 58 #13-93. Disfruta cócteles, café, bebidas y entretenimiento todos los días."
  >

  <meta
    name="keywords"
    content="Jimar Factory Chapinero, billar Chapinero, billar Bogotá, billar tres bandas, pool Chapinero, cócteles Chapinero, café, entretenimiento, materiales de billar"
  >

  <meta name="author" content="Jimar Factory Chapinero">

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
    href="https://chapitour.co/juegos/JimarFactory/"
  >

  <meta name="theme-color" content="#005548">

  <!-- Ubicación -->
  <meta name="geo.region" content="CO-DC">
  <meta name="geo.placename" content="Chapinero, Bogotá">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:locale" content="es_CO">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">

  <meta
    property="og:title"
    content="Jimar Factory Chapinero | Billar de tres bandas y pool"
  >

  <meta
    property="og:description"
    content="Disfruta billar de tres bandas, pool, cócteles, café, licores, bebidas y entretenimiento todos los días en Jimar Factory Chapinero."
  >

  <meta
    property="og:url"
    content="https://chapitour.co/juegos/JimarFactory/"
  >

  <meta
    property="og:image"
    content="https://chapitour.co/juegos/JimarFactory/img/logo.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://chapitour.co/juegos/JimarFactory/img/logo.jpeg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <meta
    property="og:image:alt"
    content="Jimar Factory Chapinero, billar de tres bandas y pool en Bogotá"
  >

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Jimar Factory Chapinero | Billar en Bogotá"
  >

  <meta
    name="twitter:description"
    content="Billar de tres bandas y pool, cócteles, café, bebidas y entretenimiento en la Calle 58 #13-93, Chapinero."
  >

  <meta
    name="twitter:image"
    content="https://chapitour.co/juegos/JimarFactory/img/logo.jpeg"
  >

  <meta
    name="twitter:image:alt"
    content="Jimar Factory Chapinero, billar de tres bandas y pool"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    href="/juegos/JimarFactory/img/logo.jpeg"
  >

  <link
    rel="icon"
    type="image/jpeg"
    sizes="32x32"
    href="/juegos/JimarFactory/img/logo.jpeg"
  >

  <link
    rel="icon"
    type="image/jpeg"
    sizes="16x16"
    href="/juegos/JimarFactory/img/logo.jpeg"
  >

  <link
    rel="apple-touch-icon"
    sizes="180x180"
    href="/juegos/JimarFactory/img/logo.jpeg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../juegos/JimarFactory/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
  >

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebSite",
        "@id": "https://chapitour.co/#website",
        "name": "Chapitour",
        "url": "https://chapitour.co/",
        "inLanguage": "es-CO",
        "description": "Guía turística y comercial de negocios, lugares y experiencias en Chapinero, Bogotá."
      },
      {
        "@type": "WebPage",
        "@id": "https://chapitour.co/juegos/JimarFactory/#webpage",
        "url": "https://chapitour.co/juegos/JimarFactory/",
        "name": "Jimar Factory Chapinero | Billar de tres bandas y pool",
        "description": "Página de Jimar Factory Chapinero, un espacio de billar de tres bandas, pool, bebidas y entretenimiento en Bogotá.",
        "inLanguage": "es-CO",
        "isPartOf": {
          "@id": "https://chapitour.co/#website"
        },
        "about": {
          "@id": "https://chapitour.co/juegos/JimarFactory/#business"
        },
        "primaryImageOfPage": {
          "@type": "ImageObject",
          "url": "https://chapitour.co/juegos/JimarFactory/img/logo.jpeg",
          "width": 1200,
          "height": 630
        }
      },
      {
        "@type": [
          "SportsActivityLocation",
          "BarOrPub",
          "LocalBusiness"
        ],
        "@id": "https://chapitour.co/juegos/JimarFactory/#business",
        "name": "Jimar Factory Chapinero",
        "alternateName": "Jimar Factory",
        "slogan": "El mejor billar de Bogotá",
        "description": "Jimar Factory Chapinero es un espacio de entretenimiento con servicio de billar de tres bandas y pool, cócteles, café, licores, variedad de bebidas y venta de materiales de juego para mayores de 18 años. Atiende los siete días de la semana.",
        "url": "https://chapitour.co/juegos/JimarFactory/",
        "image": [
          "https://chapitour.co/juegos/JimarFactory/img/logo.jpeg"
        ],
        "logo": {
          "@type": "ImageObject",
          "url": "https://chapitour.co/juegos/JimarFactory/img/logo.jpeg"
        },
        "telephone": "+573165180649",
        "servesCuisine": [
          "Cócteles",
          "Café",
          "Bebidas"
        ],
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
        "audience": {
          "@type": "PeopleAudience",
          "suggestedMinAge": 18
        },
        "openingHoursSpecification": {
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
          "closes": "23:59"
        },
        "hasOfferCatalog": {
          "@type": "OfferCatalog",
          "name": "Servicios de Jimar Factory Chapinero",
          "itemListElement": [
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Billar de tres bandas",
                "description": "Servicio de mesas para disfrutar partidas de billar de tres bandas."
              }
            },
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Pool",
                "description": "Servicio de mesas de pool para jugar y compartir con amigos."
              }
            },
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Cócteles y bebidas",
                "description": "Variedad de cócteles, bebidas con alcohol y bebidas sin alcohol."
              }
            },
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Servicio de café",
                "description": "Café disponible durante el horario de atención."
              }
            },
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Product",
                "name": "Materiales de juego",
                "description": "Venta de materiales y accesorios relacionados con el billar para mayores de 18 años."
              }
            }
          ]
        }
      }
    ]
  }
  </script>

  <!-- JS -->
  <script
    defer
    src="../../juegos/JimarFactory/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"
  ></script>
</head>

<body>

  <?php include "../../juegos/JimarFactory/home/nav/nav.php" ?>
  <?php include "../../juegos/JimarFactory/home/slider/slider.php" ?>

  <div class="container_JimarFactory">
    <?php include "../../juegos/JimarFactory/home/menu/menu.php" ?>
    <?php include "../../juegos/JimarFactory/home/galeria/galeria.php" ?>
    <?php include "../../juegos/JimarFactory/home/acerca_nosotros/acerca_nosotros.php" ?>
    <?php include "../../juegos/JimarFactory/home/ubicacion/ubicacion.php" ?>
  </div>

  <?php include "../../juegos/JimarFactory/home/footer/footer.php" ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573165180649?text=Hola%2C%20vengo%20desde%20la%20web%20de%20Jimar%20Factory%20Chapinero%20y%20quiero%20recibir%20m%C3%A1s%20informaci%C3%B3n."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Chatear con Jimar Factory Chapinero por WhatsApp"
  >
    <img
      src="../../global/img/img_whatsApp.png"
      alt="Contactar a Jimar Factory Chapinero por WhatsApp"
      decoding="async"
    >
  </a>

</body>
</html>
