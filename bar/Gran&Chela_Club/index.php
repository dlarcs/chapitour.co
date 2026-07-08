<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Gran&Chela Club | Bar y discoteca LGBTIQ+ en Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/Gran&Chela_Club/style.css';
  $jsFile  = $base . '/bar/Gran&Chela_Club/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Gran&Chela Club es un bar y discoteca LGBTIQ+ en Chapinero, Bogotá. Un lugar para bailar, tomar, disfrutar promociones por día y reservar eventos especiales.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://chapitour.co/bar/Gran&Chela_Club/">
  <meta name="theme-color" content="#5b1380">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Gran&Chela Club | Bar y discoteca LGBTIQ+ en Chapinero">
  <meta property="og:description" content="Un espacio diverso para bailar, tomar, celebrar eventos y disfrutar promociones especiales según el día en Chapinero, Bogotá.">
  <meta property="og:url" content="https://chapitour.co/bar/Gran&Chela_Club/">
  <meta property="og:image" content="https://chapitour.co/bar/Gran&Chela_Club/img/general17.jpg">
  <meta property="og:image:secure_url" content="https://chapitour.co/bar/Gran&Chela_Club/img/general17.jpg">
  <meta property="og:image:type" content="image/jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Gran&Chela Club | Bar y discoteca LGBTIQ+ en Chapinero">
  <meta name="twitter:description" content="Bar, discoteca y espacio LGBTIQ+ para bailar, tomar, reservar eventos y disfrutar promociones por día.">
  <meta name="twitter:image" content="https://chapitour.co/bar/Gran&Chela_Club/img/general17.jpg">

  <!-- Iconos -->
  <link rel="icon" href="/bar/Gran&Chela_Club/img/general17.jpg">
  <link rel="icon" type="image/jpg" sizes="32x32" href="/bar/Gran&Chela_Club/img/general17.jpg">
  <link rel="icon" type="image/jpg" sizes="16x16" href="/bar/Gran&Chela_Club/img/general17.jpg">
  <link rel="apple-touch-icon" sizes="180x180" href="/bar/Gran&Chela_Club/img/general17.jpg">

  <!-- CSS -->
  <link rel="stylesheet" href="../../bar/Gran&Chela_Club/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebSite",
        "@id": "https://chapitour.co/#website",
        "name": "Chapitour",
        "url": "https://chapitour.co/",
        "inLanguage": "es-CO",
        "description": "Guía turística y comercial de lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "BarOrPub",
        "@id": "https://chapitour.co/bar/Gran&Chela_Club/#business",
        "name": "Gran&Chela Club",
        "description": "Bar y discoteca LGBTIQ+ en Chapinero, Bogotá. Un lugar para bailar, tomar, celebrar eventos especiales y disfrutar promociones según el día.",
        "url": "https://chapitour.co/bar/Gran&Chela_Club/",
        "image": "https://chapitour.co/bar/Gran&Chela_Club/img/general17.jpg",
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
            "name": "Ambiente LGBTIQ+",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Discoteca y baile",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Reserva para eventos",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Promociones por día",
            "value": true
          }
        ]
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../../bar/Gran&Chela_Club/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>

  <?php include "../../bar/Gran&Chela_Club/home/nav/nav.php" ?>
  <?php include "../../bar/Gran&Chela_Club/home/slider/slider.php" ?>

  <div class="container_gran_chela_club">
    <?php include "../../bar/Gran&Chela_Club/home/menu/menu.php" ?>
    <?php include "../../bar/Gran&Chela_Club/home/galeria/galeria.php" ?>
    <?php include "../../bar/Gran&Chela_Club/home/acerca_nosotros/acerca_nosotros.php" ?>
    <?php include "../../bar/Gran&Chela_Club/home/ubicacion/ubicacion.php" ?>
  </div>

  <?php include "../../bar/Gran&Chela_Club/home/footer/footer.php" ?>

  <a class="whatsapp-fab"
    href="https://wa.me/573007795016?text=Hola%20vengo%20desde%20la%20web%20de%20Gran%26Chela%20Club%20y%20quiero%20mas%20informacion"
    target="_blank" rel="noopener"
    aria-label="Chatear por WhatsApp">
    <img src="../../global/img/img_whatsApp.png"
      alt="Contactar a Gran&Chela Club por WhatsApp" decoding="async">
  </a>

</body>
</html>
