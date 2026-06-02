<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Panaderías en Chapinero | Chapitour Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/9.panaderia/style.css';
  $jsFile  = $base . '/9.panaderia/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Descubre panaderías en Chapinero, Bogotá: pan fresco, pastelería, café, desayunos, onces, postres, tortas, productos artesanales y lugares para disfrutar.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/9.panaderia/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Panaderías en Chapinero | Chapitour Bogotá">
  <meta property="og:description" content="Explora panaderías en Chapinero: pan fresco, pastelería, café, desayunos, onces, postres, tortas y productos artesanales en Bogotá.">
  <meta property="og:url" content="https://www.chapitour.co/9.panaderia/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Panaderías en Chapinero | Chapitour Bogotá">
  <meta name="twitter:description" content="Descubre pan fresco, pastelería, café, desayunos, onces, postres y productos artesanales en panaderías de Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/logo1.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../9.panaderia/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "description": "Catálogo turístico y comercial de lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "CollectionPage",
        "@id": "https://www.chapitour.co/9.panaderia/#collection",
        "name": "Panaderías en Chapinero",
        "url": "https://www.chapitour.co/9.panaderia/",
        "inLanguage": "es-CO",
        "description": "Guía de panaderías en Chapinero, Bogotá, con pan fresco, pastelería, café, desayunos, onces, postres, tortas y productos artesanales.",
        "isPartOf": {
          "@id": "https://www.chapitour.co/#website"
        },
        "about": {
          "@type": "Place",
          "name": "Chapinero, Bogotá",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Chapinero",
            "addressRegion": "Bogotá D.C.",
            "addressCountry": "CO"
          }
        }
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../9.panaderia/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>
<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->
  <script src="soporte.js"></script>
  <?php include "../global/menu/menu.php" ?>
  <?php include "../9.panaderia/seccion/panaderia.php" ?>
  <?php include "../global/footer/footer.php" ?>

</body>
</html>
