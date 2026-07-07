<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Chapitour | Tipos de bares en Chapinero, Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/categorias/1.tipo_bares/style.css';
  $jsFile  = $base . '/categorias/1.tipo_bares/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Explora los diferentes tipos de bares en Chapinero, Bogotá con Chapitour: gastrobares, bares de cocteles, bares LGBTIQ+, bares con música en vivo, bares para bailar y lugares para compartir.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/categorias/1.tipo_bares/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Chapitour | Tipos de bares en Chapinero, Bogotá">
  <meta property="og:description" content="Descubre tipos de bares en Chapinero: gastrobares, coctelerías, bares LGBTIQ+, música en vivo, rumba, planes nocturnos y espacios para compartir.">
  <meta property="og:url" content="https://www.chapitour.co/categorias/1.tipo_bares/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Chapitour | Tipos de bares en Chapinero, Bogotá">
  <meta name="twitter:description" content="Encuentra gastrobares, bares de cocteles, bares LGBTIQ+, bares con música, rumba y planes nocturnos en Chapinero.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/logocategorias/1.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../../categorias/1.tipo_bares/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "description": "Guía digital para descubrir bares, gastronomía, cafés, cultura y planes locales en Chapinero, Bogotá."
      },
      {
        "@type": "ItemList",
        "@id": "https://www.chapitour.co/categorias/1.tipo_bares/#tipos-de-bares",
        "name": "Tipos de bares en Chapinero",
        "description": "Listado de tipos de bares en Chapinero, incluyendo gastrobares, bares de cocteles, bares LGBTIQ+, bares con música en vivo, bares para bailar y espacios para compartir.",
        "url": "https://www.chapitour.co/categorias/1.tipo_bares/",
        "itemListOrder": "https://schema.org/ItemListOrderAscending"
      },
      {
        "@type": "TouristDestination",
        "@id": "https://www.chapitour.co/#destination",
        "name": "Chapinero, Bogotá",
        "description": "Zona de Bogotá donde puedes encontrar diferentes tipos de bares, gastrobares, restaurantes, cafés, cultura, comercio local y planes nocturnos.",
        "url": "https://www.chapitour.co/",
        "image": "https://www.chapitour.co/home/img/logocategorias/1.jpeg",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../../categorias/1.tipo_bares/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->


  <?php include "../../global/menu/menu.php" ?>
  <?php include "../../categorias/1.tipo_bares/seccion/tipo_bares.php" ?>
  <?php include "../../global/footer/footer.php" ?>

</body>
</html>
