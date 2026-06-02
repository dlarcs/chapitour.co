<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Chapitour | Lugares LGBTIQ+ en Chapinero, Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/5.LGBTIQ+/style.css';
  $jsFile  = $base . '/5.LGBTIQ+/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Encuentra lugares LGBTIQ+ en Chapinero, Bogotá con Chapitour: bares, discotecas, gastrobares, cafés, shows, espacios culturales y lugares seguros para compartir.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/categorias/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Chapitour | Lugares LGBTIQ+ en Chapinero, Bogotá">
  <meta property="og:description" content="Explora lugares LGBTIQ+ en Chapinero: bares, discotecas, gastrobares, cafés, shows, espacios culturales y lugares seguros para compartir.">
  <meta property="og:url" content="https://www.chapitour.co/categorias/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Chapitour | Lugares LGBTIQ+ en Chapinero, Bogotá">
  <meta name="twitter:description" content="Descubre bares, discotecas, gastrobares, cafés, shows, espacios culturales y lugares seguros para la comunidad LGBTIQ+ en Chapinero.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/logo1.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../5.LGBTIQ+/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "description": "Guía digital para descubrir lugares LGBTIQ+, bares, discotecas, gastrobares, cafés, cultura y planes locales en Chapinero, Bogotá."
      },
      {
        "@type": "ItemList",
        "@id": "https://www.chapitour.co/categorias/#lugares-lgbtiq",
        "name": "Lugares LGBTIQ+ en Chapinero",
        "description": "Listado de bares, discotecas, gastrobares, cafés, shows, espacios culturales y lugares seguros para la comunidad LGBTIQ+ en Chapinero.",
        "url": "https://www.chapitour.co/categorias/",
        "itemListOrder": "https://schema.org/ItemListOrderAscending"
      },
      {
        "@type": "TouristDestination",
        "@id": "https://www.chapitour.co/#destination",
        "name": "Chapinero, Bogotá",
        "description": "Zona de Bogotá reconocida por su diversidad, vida nocturna, bares, discotecas, gastrobares, cafés, cultura, espacios seguros y lugares para compartir.",
        "url": "https://www.chapitour.co/",
        "image": "https://www.chapitour.co/home/img/logo1.jpeg",
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
  <script defer src="../5.LGBTIQ+/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>
<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->
  <script src="soporte.js"></script>

  <?php include "../global/menu/menu.php" ?>
  <?php include "../5.LGBTIQ+/seccion/LGBTIQ+.php" ?>
  <?php include "../global/footer/footer.php" ?>

</body>
</html>
