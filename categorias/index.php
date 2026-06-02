<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Categorías en Chapitour | Explora lugares en Chapinero, Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/categorias/style.css';
  $jsFile  = $base . '/categorias/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Explora las categorías de Chapitour en Chapinero, Bogotá: tipos de bares, planes en bares, café, comida rápida, gastronomía, artesanías, panaderías, pastelerías, LGBTIQ+, postres y más lugares para visitar.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/categorias/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Categorías en Chapitour | Explora Chapinero, Bogotá">
  <meta property="og:description" content="Encuentra en Chapitour tipos de bares, planes en bares, café, comida rápida, gastronomía, artesanías, panaderías, pastelerías, LGBTIQ+, postres y más lugares en Chapinero.">
  <meta property="og:url" content="https://www.chapitour.co/categorias/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Categorías en Chapitour | Lugares para visitar en Chapinero">
  <meta name="twitter:description" content="Explora categorías como bares, café, comida rápida, gastronomía, artesanías, panaderías, LGBTIQ+, postres y más en Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/logo1.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../categorias/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebPage",
        "@id": "https://www.chapitour.co/categorias/#webpage",
        "name": "Categorías en Chapitour",
        "url": "https://www.chapitour.co/categorias/",
        "inLanguage": "es-CO",
        "description": "Página de categorías de Chapitour para explorar lugares, planes y experiencias en Chapinero, Bogotá."
      },
      {
        "@type": "ItemList",
        "@id": "https://www.chapitour.co/categorias/#categories",
        "name": "Categorías de Chapitour",
        "description": "Listado de categorías para descubrir lugares en Chapinero, Bogotá.",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "Tipos de bares"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "Planes en bares"
          },
          {
            "@type": "ListItem",
            "position": 3,
            "name": "Café"
          },
          {
            "@type": "ListItem",
            "position": 4,
            "name": "Comida rápida"
          },
          {
            "@type": "ListItem",
            "position": 5,
            "name": "Gastronomía"
          },
          {
            "@type": "ListItem",
            "position": 6,
            "name": "Artesanías"
          },
          {
            "@type": "ListItem",
            "position": 7,
            "name": "Panaderías y pastelerías"
          },
          {
            "@type": "ListItem",
            "position": 8,
            "name": "LGBTIQ+"
          },
          {
            "@type": "ListItem",
            "position": 9,
            "name": "Postres y más"
          }
        ]
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../categorias/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->

  <script src="soporte.js"></script>

  <?php include "../global/menu/menu.php" ?>
  <?php include "../categorias/seccion/categoria.php" ?>
  <?php include "../global/footer/footer.php" ?>

</body>
</html>
