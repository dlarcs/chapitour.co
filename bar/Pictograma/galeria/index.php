<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Galería | Pictogramas Cafe Bar</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/Pictograma/galeria/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Conoce la galería de Pictogramas Cafe Bar y descubre su ambiente, bebidas y espacios para compartir. Disfruta cócteles, granizados, licores, micheladas, bolivianas, cervezas, café y partidas de bolirana."
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
    href="https://www.chapitour.co/bar/Pictograma/galeria/index.php"
  >

  <meta name="theme-color" content="#000000">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Pictogramas Cafe Bar">

  <meta
    property="og:title"
    content="Galería | Pictogramas Cafe Bar"
  >

  <meta
    property="og:description"
    content="Conoce el ambiente de Pictogramas Cafe Bar, disfruta cócteles, granizados, micheladas, bolivianas, cervezas, café y diviértete jugando bolirana."
  >

  <meta
    property="og:url"
    content="https://www.chapitour.co/bar/Pictograma/galeria/index.php"
  >

  <meta
    property="og:image"
    content="https://www.chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://www.chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">

  <meta
    property="og:image:alt"
    content="Logo de Pictogramas Cafe Bar"
  >

  <meta property="og:locale" content="es_CO">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Galería | Pictogramas Cafe Bar"
  >

  <meta
    name="twitter:description"
    content="Descubre la galería, las bebidas, el ambiente y la bolirana de Pictogramas Cafe Bar."
  >

  <meta
    name="twitter:image"
    content="https://www.chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta
    name="twitter:image:alt"
    content="Logo de Pictogramas Cafe Bar"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    type="image/jpeg"
    href="/bar/Pictograma/img/logo.jpeg"
  >

  <link
    rel="apple-touch-icon"
    href="/bar/Pictograma/img/logo.jpeg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../../bar/Pictograma/galeria/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
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
        "inLanguage": "es-CO"
      },
      {
        "@type": "BarOrPub",
        "@id": "https://www.chapitour.co/bar/Pictograma/index.php#bar",
        "name": "Pictogramas Cafe Bar",
        "description": "Pictogramas Cafe Bar es un lugar para disfrutar cócteles, granizados, licores, micheladas, bolivianas, cervezas y café. También cuenta con bolirana para compartir y divertirse con amigos.",
        "url": "https://www.chapitour.co/bar/Pictograma/index.php",
        "image": "https://www.chapitour.co/bar/Pictograma/img/logo.jpeg",
        "servesCuisine": [
          "Cócteles",
          "Granizados",
          "Micheladas",
          "Bolivianas",
          "Cervezas",
          "Café"
        ],
        "amenityFeature": {
          "@type": "LocationFeatureSpecification",
          "name": "Bolirana",
          "value": true
        },
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      },
      {
        "@type": "CollectionPage",
        "@id": "https://www.chapitour.co/bar/Pictograma/galeria/index.php#webpage",
        "url": "https://www.chapitour.co/bar/Pictograma/galeria/index.php",
        "name": "Galería | Pictogramas Cafe Bar",
        "description": "Galería de Pictogramas Cafe Bar con imágenes de su ambiente, bebidas, espacios para compartir y zona de bolirana.",
        "inLanguage": "es-CO",
        "isPartOf": {
          "@id": "https://www.chapitour.co/#website"
        },
        "about": {
          "@id": "https://www.chapitour.co/bar/Pictograma/index.php#bar"
        }
      }
    ]
  }
  </script>
</head>

<body>

  <?php include "../../../bar/Pictograma/global/pag_nav/pag_nav.php"; ?>

  <div class="container_galeria">
    <?php include "../../../bar/Pictograma/galeria/galeria/galeria.php"; ?>
  </div>

  <?php include "../../../bar/Pictograma/global/boton/boton.php"; ?>

  <?php include "../../../bar/Pictograma/global/pag_footer/pag_footer.php"; ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573502835648?text=Hola%2C%20vengo%20desde%20la%20galer%C3%ADa%20de%20Pictogramas%20Cafe%20Bar%20y%20quiero%20recibir%20m%C3%A1s%20informaci%C3%B3n."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Contactar a Pictogramas Cafe Bar por WhatsApp"
  >
    <img
      src="../../../global/img/img_whatsApp.png"
      alt="Contactar a Pictogramas Cafe Bar por WhatsApp"
      decoding="async"
    >
  </a>

</body>
</html>
