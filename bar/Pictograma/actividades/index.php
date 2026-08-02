<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Actividades y promociones | Pictogramas Cafe Bar</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/Pictograma/actividades/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Descubre las actividades y promociones de Pictogramas Cafe Bar. Disfruta cócteles, granizados, licores, micheladas, bolivianas, cervezas, café y partidas de bolirana en un ambiente ideal para compartir."
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
    href="https://www.chapitour.co/bar/Pictograma/actividades/index.php"
  >

  <meta name="theme-color" content="#000000">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Pictogramas Cafe Bar">

  <meta
    property="og:title"
    content="Actividades y promociones | Pictogramas Cafe Bar"
  >

  <meta
    property="og:description"
    content="Conoce las actividades y promociones de Pictogramas Cafe Bar. Disfruta cócteles, granizados, micheladas, bolivianas, cervezas, café, licores y divertidas partidas de bolirana."
  >

  <meta
    property="og:url"
    content="https://www.chapitour.co/bar/Pictograma/actividades/index.php"
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
    content="Actividades y promociones | Pictogramas Cafe Bar"
  >

  <meta
    name="twitter:description"
    content="Descubre cócteles, granizados, licores, micheladas, bolivianas, cervezas, café y bolirana en Pictogramas Cafe Bar."
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
    href="../../../bar/Pictograma/actividades/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
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
        "description": "Pictogramas Cafe Bar es un espacio para disfrutar cócteles, granizados, licores, micheladas, bolivianas, cervezas y café. También cuenta con bolirana para compartir y divertirse con amigos.",
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
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      },
      {
        "@type": "WebPage",
        "@id": "https://www.chapitour.co/bar/Pictograma/actividades/index.php#webpage",
        "url": "https://www.chapitour.co/bar/Pictograma/actividades/index.php",
        "name": "Actividades y promociones | Pictogramas Cafe Bar",
        "description": "Página de actividades, promociones, bebidas y planes de Pictogramas Cafe Bar.",
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

  <div class="container_reservas">
    <?php include "../../../bar/Pictograma/actividades/actividades/actividades.php"; ?>
  </div>

  <?php include "../../../bar/Pictograma/global/boton/boton.php"; ?>

  <?php include "../../../bar/Pictograma/global/pag_footer/pag_footer.php"; ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573138846378?text=Hola%2C%20vengo%20desde%20la%20p%C3%A1gina%20de%20actividades%20de%20Pictogramas%20Cafe%20Bar%20y%20quiero%20conocer%20las%20promociones%20disponibles."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Consultar actividades y promociones de Pictogramas Cafe Bar por WhatsApp"
  >
    <img
      src="../../../global/img/img_whatsApp.png"
      alt="Contactar a Pictogramas Cafe Bar por WhatsApp"
      decoding="async"
    >
  </a>

</body>
</html>
