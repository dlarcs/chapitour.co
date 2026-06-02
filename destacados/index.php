<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Destacados y recomendados | Chapitour en Chapinero, Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/destacados/style.css';
  $jsFile  = $base . '/destacados/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Encuentra lugares destacados y recomendados en Chapinero, Bogotá con Chapitour: bares, cafés, restaurantes, gastronomía, comida rápida, postres, artesanías, panaderías, pastelerías y planes locales.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/destacados/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Destacados y recomendados | Chapitour en Chapinero">
  <meta property="og:description" content="Explora lugares destacados y recomendados en Chapinero: bares, cafés, restaurantes, comida rápida, gastronomía, postres, artesanías, panaderías, pastelerías y planes locales.">
  <meta property="og:url" content="https://www.chapitour.co/destacados/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Destacados y recomendados | Chapitour en Chapinero">
  <meta name="twitter:description" content="Descubre lugares recomendados en Chapinero, Bogotá: bares, cafés, restaurantes, comida rápida, gastronomía, postres, artesanías y más.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/logo1.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../destacados/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebPage",
        "@id": "https://www.chapitour.co/destacados/#webpage",
        "name": "Destacados y recomendados en Chapitour",
        "url": "https://www.chapitour.co/destacados/",
        "inLanguage": "es-CO",
        "description": "Página de lugares destacados y recomendados de Chapitour para descubrir negocios, planes y experiencias en Chapinero, Bogotá."
      },
      {
        "@type": "ItemList",
        "@id": "https://www.chapitour.co/destacados/#recommended",
        "name": "Lugares destacados y recomendados en Chapitour",
        "description": "Selección de lugares recomendados para visitar en Chapinero, Bogotá.",
        "itemListOrder": "https://schema.org/ItemListOrderAscending",
        "numberOfItems": 0
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../destacados/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->

  <script src="soporte.js"></script>

  <?php include "../global/menu/menu.php" ?>
  <?php include "../destacados/seccion/destacado.php" ?>
  <?php include "../global/footer/footer.php" ?>

</body>
</html>
