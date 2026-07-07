<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Gastronomía en Chapinero | Chapitour Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/categorias/7.gastronomia/style.css';
  $jsFile  = $base . '/categorias/7.gastronomia/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Descubre lugares gastronómicos en Chapinero, Bogotá: restaurantes, sabores locales, comida colombiana, cocina internacional, almuerzos, cenas y experiencias para disfrutar.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/7.gastronomia/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Gastronomía en Chapinero | Chapitour Bogotá">
  <meta property="og:description" content="Explora lugares gastronómicos en Chapinero: restaurantes, comida colombiana, cocina internacional, almuerzos, cenas y sabores locales en Bogotá.">
  <meta property="og:url" content="https://www.chapitour.co/7.gastronomia/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/chapitour-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Gastronomía en Chapinero | Chapitour Bogotá">
  <meta name="twitter:description" content="Descubre restaurantes, comida colombiana, cocina internacional, almuerzos, cenas y experiencias gastronómicas en Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/logo1.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../../categorias/7.gastronomia/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "@id": "https://www.chapitour.co/7.gastronomia/#collection",
        "name": "Gastronomía en Chapinero",
        "url": "https://www.chapitour.co/7.gastronomia/",
        "inLanguage": "es-CO",
        "description": "Guía de lugares gastronómicos en Chapinero, Bogotá, con restaurantes, comida colombiana, cocina internacional, almuerzos, cenas y experiencias culinarias.",
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
  <script defer src="../../categorias/7.gastronomia/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>
<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->
  <script src="soporte.js"></script>
  <?php include "../../global/menu/menu.php" ?>
  <?php include "../../categorias/7.gastronomia/seccion/gastronomia.php" ?>
  <?php include "../../global/footer/footer.php" ?>

</body>
</html>
