<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Capital Queer | Bar en Chapinero para mujeres y diversidad</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/CapitalQueer/style.css';
  $jsFile  = $base . '/bar/CapitalQueer/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Capital Queer es un bar en Chapinero, Bogotá, creado especialmente para mujeres y abierto a todas las personas. Un lugar diverso para compartir, bailar, tomar y disfrutar con respeto y buena energía.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://chapitour.co/bar/CapitalQueer/">
  <meta name="theme-color" content="#5b1380">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Capital Queer | Bar en Chapinero para mujeres y diversidad">
  <meta property="og:description" content="Un espacio en Chapinero creado especialmente para mujeres, abierto a todas las personas que quieran disfrutar con respeto, libertad, música y buena energía.">
  <meta property="og:url" content="https://chapitour.co/bar/CapitalQueer/">
  <meta property="og:image" content="https://chapitour.co/bar/CapitalQueer/img/logo.jpg">
  <meta property="og:image:secure_url" content="https://chapitour.co/bar/CapitalQueer/img/logo.jpg">
  <meta property="og:image:type" content="image/jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Capital Queer | Bar en Chapinero para mujeres y diversidad">
  <meta name="twitter:description" content="Bar en Chapinero creado especialmente para mujeres y abierto a todas las personas. Música, bebidas, respeto y un ambiente diverso.">
  <meta name="twitter:image" content="https://chapitour.co/bar/CapitalQueer/img/logo.jpg">

  <!-- Iconos -->
  <link rel="icon" href="/bar/CapitalQueer/img/logo.jpg">
  <link rel="icon" type="image/jpg" sizes="32x32" href="/bar/CapitalQueer/img/logo.jpg">
  <link rel="icon" type="image/jpg" sizes="16x16" href="/bar/CapitalQueer/img/logo.jpg">
  <link rel="apple-touch-icon" sizes="180x180" href="/bar/CapitalQueer/img/logo.jpg">

  <!-- CSS -->
  <link rel="stylesheet" href="../../bar/CapitalQueer/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebSite",
        "@id": "https://chapitour.co/bar/CapitalQueer/index.php",
        "name": "Chapitour",
        "url": "https://chapitour.co/bar/CapitalQueer/index.php",
        "inLanguage": "es-CO",
        "description": "Guía turística y comercial de lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "BarOrPub",
        "@id": "https://chapitour.co/bar/CapitalQueer/#business",
        "name": "Capital Queer",
        "description": "Bar en Chapinero, Bogotá, creado especialmente para mujeres y abierto a todas las personas. Un espacio diverso para compartir, bailar, tomar y disfrutar con respeto y buena energía.",
        "url": "https://chapitour.co/bar/CapitalQueer/index.php",
        "image": "https://chapitour.co/bar/CapitalQueer/img/logo.jpg",
        "telephone": "+573007795016",
        "servesCuisine": "Bar",
        "priceRange": "$$",
        "address": {
          "@type": "PostalAddress",
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
            "name": "Espacio pensado para mujeres",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Abierto a todas las personas",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Ambiente diverso e inclusivo",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Música, bebidas y baile",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Espacio para compartir con respeto",
            "value": true
          }
        ]
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../../bar/CapitalQueer/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>

  <?php include "../../bar/CapitalQueer/home/nav/nav.php" ?>
  <?php include "../../bar/CapitalQueer/home/slider/slider.php" ?>

  <div class="container_capital_queer">
    <?php include "../../bar/CapitalQueer/home/menu/menu.php" ?>
    <?php include "../../bar/CapitalQueer/home/galeria/galeria.php" ?>
    <?php include "../../bar/CapitalQueer/home/acerca_nosotros/acerca_nosotros.php" ?>
    <?php include "../../bar/CapitalQueer/home/ubicacion/ubicacion.php" ?>
  </div>

  <?php include "../../bar/CapitalQueer/home/footer/footer.php" ?>

  <a class="whatsapp-fab"
  href="https://wa.me/573007795016?text=Hola%20vengo%20desde%20la%20web%20de%20Capital%20Queer"
    target="_blank" rel="noopener"
    aria-label="Chatear por WhatsApp">
    <img src="../../global/img/img_whatsApp.png"
      alt="Contactar a Capital Queer por WhatsApp" decoding="async">
  </a>

</body>
</html>
