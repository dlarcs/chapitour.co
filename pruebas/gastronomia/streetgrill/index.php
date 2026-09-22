<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Street Grill | Carne al barril y carnes ahumadas en Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/gastronomia/streetgrill/style.css';
  $jsFile  = $base . '/gastronomia/streetgrill/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Street Grill es un gastrobar de carnes al barril en Chapinero, Bogotá, especializado en carne ahumada, bondiola, carne de res, hamburguesas artesanales, chorizos y preparaciones llenas de sabor."
  >

  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">

  <link rel="canonical" href="https://chapitour.co/gastronomia/streetgrill/index.php">

  <meta name="theme-color" content="#111111">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Street Grill">

  <meta
    property="og:title"
    content="Street Grill | Carne al barril y carnes ahumadas en Chapinero"
  >

  <meta
    property="og:description"
    content="Descubre Street Grill en Chapinero: carnes al barril, preparaciones ahumadas, bondiola, hamburguesas artesanales, chorizos y mucho más."
  >

  <meta property="og:url" content="https://chapitour.co/gastronomia/streetgrill/">

  <meta
    property="og:image"
    content="https://chapitour.co/gastronomia/streetgrill/img/logo.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://chapitour.co/gastronomia/streetgrill/img/logo.jpeg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Street Grill | Carne al barril en Chapinero"
  >

  <meta
    name="twitter:description"
    content="Gastrobar de carnes al barril en Chapinero con carne ahumada, bondiola, hamburguesas artesanales, chorizos y más."
  >

  <meta
    name="twitter:image"
    content="https://chapitour.co/gastronomia/streetgrill/img/logo.jpeg"
  >

  <!-- Iconos -->
  <link rel="icon" href="/gastronomia/streetgrill/img/logo.jpeg">
  <link rel="icon" type="image/jpeg" sizes="32x32" href="/gastronomia/streetgrill/img/logo.jpeg">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="/gastronomia/streetgrill/img/logo.jpeg">
  <link rel="apple-touch-icon" sizes="180x180" href="/gastronomia/streetgrill/img/logo.jpeg">

  <!-- CSS -->
  <link rel="stylesheet" href="../../gastronomia/streetgrill/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "@type": "Restaurant",
        "@id": "https://chapitour.co/gastronomia/streetgrill/#business",
        "name": "Street Grill",
        "description": "Gastrobar de carnes al barril en Chapinero, Bogotá, especializado en carnes ahumadas, bondiola de cerdo, carne de res, hamburguesas artesanales, chorizos, choripán y otras preparaciones.",
        "url": "https://chapitour.co/gastronomia/streetgrill/",
        "image": "https://chapitour.co/gastronomia/streetgrill/img/logo.jpeg",
        "telephone": "+573143580355",
        "servesCuisine": [
          "Carnes al barril",
          "Carnes ahumadas",
          "Hamburguesas artesanales",
          "Chorizos",
          "Gastrobar"
        ],
        "priceRange": "$$",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Cra. 9 #57-85",
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
  <script defer src="../../gastronomia/streetgrill/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>

  <?php include "../../gastronomia/streetgrill/home/nav/nav.php" ?>
  <?php include "../../gastronomia/streetgrill/home/slider/slider.php" ?>

  <div class="container_GarageDiscoBar">
    <?php include "../../gastronomia/streetgrill/home/menu/menu.php" ?>
    <?php include "../../gastronomia/streetgrill/home/galeria/galeria.php" ?>
    <?php include "../../gastronomia/streetgrill/home/acerca_nosotros/acerca_nosotros.php" ?>
    <?php include "../../gastronomia/streetgrill/home/ubicacion/ubicacion.php" ?>
  </div>

  <?php include "../../gastronomia/streetgrill/home/footer/footer.php" ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573143580355?text=Hola%2C%20vengo%20desde%20la%20web%20de%20Street%20Grill%20y%20quiero%20más%20información."
    target="_blank"
    rel="noopener"
    aria-label="Chatear con Street Grill por WhatsApp"
  >

    <img
      src="../../global/img/img_whatsApp.png"
      alt="Contactar a Street Grill por WhatsApp"
      decoding="async"
    >

  </a>

</body>
</html>
