<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Chapitour | Planes en bares en Chapinero, Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/2.planes_bares/style.css';
  $jsFile  = $base . '/2.planes_bares/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Encuentra planes en bares en Chapinero, Bogotá con Chapitour: cocteles, música, rumba, gastrobares, bares para compartir y lugares para disfrutar la noche.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/2.planes_bares/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Chapitour | Planes en bares en Chapinero, Bogotá">
  <meta property="og:description" content="Descubre bares en Chapinero para tomar algo, bailar, escuchar música, compartir con amigos y vivir una noche con estilo.">
  <meta property="og:url" content="https://www.chapitour.co/2.planes_bares/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Chapitour | Planes en bares en Chapinero, Bogotá">
  <meta name="twitter:description" content="Encuentra bares, cocteles, música, rumba y planes nocturnos para disfrutar en Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/logo1.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../2.planes_bares/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "description": "Guía digital para encontrar planes, bares, gastronomía, cultura y lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "ItemList",
        "@id": "https://www.chapitour.co/2.planes_bares/#bares",
        "name": "Planes en bares en Chapinero",
        "description": "Listado de bares y lugares en Chapinero para tomar cocteles, escuchar música, bailar, compartir con amigos y disfrutar planes nocturnos.",
        "url": "https://www.chapitour.co/2.planes_bares/",
        "itemListOrder": "https://schema.org/ItemListOrderAscending"
      },
      {
        "@type": "TouristDestination",
        "@id": "https://www.chapitour.co/#destination",
        "name": "Chapinero, Bogotá",
        "description": "Zona de Bogotá donde puedes encontrar bares, gastrobares, cocteles, música, cultura, comercio local, restaurantes y planes para visitar.",
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
  <script defer src="../2.planes_bares/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->

  <script src="soporte.js"></script>

  <?php include "../global/menu/menu.php" ?>
  <?php include "../2.planes_bares/seccion/planes_bares.php" ?>
  <?php include "../global/footer/footer.php" ?>

</body>
</html>
