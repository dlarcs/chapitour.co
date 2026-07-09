<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Garage 9-39C | Disco bar y gastrobar LGBTIQ+ en Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/gastrobar/Garage9-39C/style.css';
  $jsFile  = $base . '/gastrobar/Garage9-39C/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Garage 9-39C es un disco bar y gastrobar LGBTIQ+ en Chapinero, Bogotá, con música, ambiente diverso, rumba, comida, bebidas y una experiencia nocturna llena de energía.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://chapitour.co/gastrobar/Garage9-39C/index.php">
  <meta name="theme-color" content="#5b1380">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Garage 9-39C | Disco bar y gastrobar LGBTIQ+">
  <meta property="og:description" content="Garage 9-39C es un espacio LGBTIQ+ en Chapinero para disfrutar música, rumba, comida, bebidas y un ambiente diverso con buena energía.">
  <meta property="og:url" content="https://chapitour.co/gastrobar/Garage9-39C/">
  <meta property="og:image" content="https://chapitour.co/gastrobar/Garage9-39C/img/general11.jpg">
  <meta property="og:image:secure_url" content="https://chapitour.co/gastrobar/Garage9-39C/img/general11.jpg">
  <meta property="og:image:type" content="image/jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Garage 9-39C | Disco bar y gastrobar LGBTIQ+">
  <meta name="twitter:description" content="Disco bar y gastrobar LGBTIQ+ en Chapinero con música, comida, bebidas, rumba y ambiente diverso.">
  <meta name="twitter:image" content="https://chapitour.co/gastrobar/Garage9-39C/img/general11.jpg">

  <!-- Iconos -->
  <link rel="icon" href="/gastrobar/Garage9-39C/img/general11.jpg">
  <link rel="icon" type="image/jpg" sizes="32x32" href="/gastrobar/Garage9-39C/img/general11.jpg">
  <link rel="icon" type="image/jpg" sizes="16x16" href="/gastrobar/Garage9-39C/img/general11.jpg">
  <link rel="apple-touch-icon" sizes="180x180" href="/gastrobar/Garage9-39C/img/general11.jpg">

  <!-- CSS -->
  <link rel="stylesheet" href="../../gastrobar/Garage9-39C/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "@id": "https://chapitour.co/gastrobar/Garage9-39C/#business",
        "name": "Garage 9-39C",
        "description": "Disco bar y gastrobar LGBTIQ+ en Chapinero, Bogotá, ideal para disfrutar rumba, música, comida, bebidas y un ambiente diverso, libre y lleno de energía.",
        "url": "https://chapitour.co/gastrobar/Garage9-39C/",
        "image": "https://chapitour.co/gastrobar/Garage9-39C/img/general11.jpg",
        "telephone": "+573007795016",
        "servesCuisine": "Gastrobar",
        "priceRange": "$$",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Cra. 9 #39C",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        },
        "areaServed": {
          "@type": "Place",
          "name": "Chapinero, Bogotá"
        }
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../../gastrobar/Garage9-39C/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>

  <?php include "../../gastrobar/Garage9-39C/home/nav/nav.php" ?>
  <?php include "../../gastrobar/Garage9-39C/home/slider/slider.php" ?>

  <div class="container_garage939c">
    <?php include "../../gastrobar/Garage9-39C/home/menu/menu.php" ?>
    <?php include "../../gastrobar/Garage9-39C/home/galeria/galeria.php" ?>
    <?php include "../../gastrobar/Garage9-39C/home/acerca_nosotros/acerca_nosotros.php" ?>
    <?php include "../../gastrobar/Garage9-39C/home/ubicacion/ubicacion.php" ?>
  </div>

  <?php include "../../gastrobar/Garage9-39C/home/footer/footer.php" ?>

  <a class="whatsapp-fab"
    href="https://wa.me/573156175056?text=Hola%20vengo%20desde%20la%20web%20de%20Garage%209-39C"
    target="_blank" rel="noopener"
    aria-label="Chatear por WhatsApp">
    <img src="../../global/img/img_whatsApp.png"
      alt="Contactar a Garage 9-39C por WhatsApp" decoding="async">
  </a>

</body>
</html>
