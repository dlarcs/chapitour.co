
<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Pictogramas Café Bar | Bebidas y bolirana en Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/Pictograma/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Pictogramas Cafe Bar es un lugar en Chapinero para disfrutar cócteles, granizados, licores, cervezas, café y divertidas partidas de bolirana."
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
    href="https://chapitour.co/bar/Pictograma/index.php"
  >

  <meta name="theme-color" content="#000000">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">

  <meta
    property="og:title"
    content="Pictogramas Cafe Bar | Bebidas y bolirana en Chapinero"
  >

  <meta
    property="og:description"
    content="Disfruta cócteles, granizados, micheladas, bolivianas, cervezas, café y bolirana en Pictogramas Cafe Bar, ubicado en Chapinero, Bogotá."
  >

  <meta
    property="og:url"
    content="https://chapitour.co/bar/Pictograma/index.php"
  >

  <meta
    property="og:image"
    content="https://chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">

  <meta
    property="og:image:alt"
    content="Logo de Pictogramas Cafe Bar en Chapinero"
  >

  <meta property="og:locale" content="es_CO">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Pictogramas Cafe Bar | Bebidas y bolirana en Chapinero"
  >

  <meta
    name="twitter:description"
    content="Un lugar para disfrutar cócteles, micheladas, cervezas, café y divertidas partidas de bolirana en Chapinero."
  >

  <meta
    name="twitter:image"
    content="https://chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta
    name="twitter:image:alt"
    content="Logo de Pictogramas Cafe Bar"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    type="image/jpeg"
    href="/bar/Pictograma/img/logo.jpeg?v=2"
  >

  <link
    rel="icon"
    type="image/jpeg"
    sizes="32x32"
    href="/bar/Pictograma/img/logo.jpeg"
  >

  <link
    rel="icon"
    type="image/jpeg"
    sizes="16x16"
    href="/bar/Pictograma/img/logo.jpeg"
  >

  <link
    rel="apple-touch-icon"
    href="/bar/Pictograma/img/logo.jpeg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../bar/Pictograma/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
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
        "description": "Guía turística y comercial de lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "BarOrPub",
        "@id": "https://chapitour.co/bar/Pictograma/index.php#bar",
        "name": "Pictogramas Cafe Bar",
        "description": "Pictogramas Cafe Bar es un lugar en Chapinero para disfrutar cócteles, granizados, licores, micheladas, bolivianas, cervezas, café y partidas de bolirana en un ambiente agradable para compartir con amigos.",
        "url": "https://chapitour.co/bar/Pictograma/index.php",
        "image": "https://chapitour.co/bar/Pictograma/img/logo.jpeg",
        "telephone": "+573502835648",
        "servesCuisine": [
          "Cócteles",
          "Granizados",
          "Licores",
          "Micheladas",
          "Bolivianas",
          "Cervezas",
          "Café"
        ],
        "priceRange": "$$",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Calle 59 #13-20",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        },
        "areaServed": {
          "@type": "Place",
          "name": "Chapinero, Bogotá"
        },
        "amenityFeature": [
          {
            "@type": "LocationFeatureSpecification",
            "name": "Bolirana",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Cócteles y bebidas",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Café",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Espacio para compartir con amigos",
            "value": true
          }
        ]
      },
      {
        "@type": "WebPage",
        "@id": "https://chapitour.co/bar/Pictograma/index.php#webpage",
        "url": "https://chapitour.co/bar/Pictograma/index.php",
        "name": "Pictogramas Cafe Bar | Bebidas y bolirana en Chapinero",
        "description": "Página principal de Pictogramas Cafe Bar, un lugar en Chapinero con cócteles, cervezas, café, micheladas y bolirana.",
        "inLanguage": "es-CO",
        "isPartOf": {
          "@id": "https://chapitour.co/#website"
        },
        "about": {
          "@id": "https://chapitour.co/bar/Pictograma/index.php#bar"
        }
      }
    ]
  }
  </script>
</head>

<body>

  <?php include "../../bar/Pictograma/home/nav/nav.php"; ?>
  <?php include "../../bar/Pictograma/home/slider/slider.php"; ?>

  <div class="container_pictograma">
    <?php include "../../bar/Pictograma/home/menu/menu.php"; ?>
    <?php include "../../bar/Pictograma/home/galeria/galeria.php"; ?>
    <?php include "../../bar/Pictograma/home/acerca_nosotros/acerca_nosotros.php"; ?>
    <?php include "../../bar/Pictograma/home/ubicacion/ubicacion.php"; ?>
  </div>

  <?php include "../../bar/Pictograma/home/footer/footer.php"; ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573502835648?text=Hola%2C%20vengo%20desde%20la%20p%C3%A1gina%20de%20Pictogramas%20Cafe%20Bar%20en%20Chapitour%20y%20quiero%20recibir%20m%C3%A1s%20informaci%C3%B3n."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Contactar a Pictogramas Cafe Bar por WhatsApp"
  >
    <img
      src="../../global/img/img_whatsApp.png"
      alt="Contactar a Pictogramas Cafe Bar por WhatsApp"
      decoding="async"
    >
  </a>

</body>
</html>
