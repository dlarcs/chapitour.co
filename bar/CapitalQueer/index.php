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

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- CSS -->
  <link rel="stylesheet" href="../../bar/CapitalQueer/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebSite",
        "@id": "https://chapitour.co/bar/CapitalQueer/index.php#bar",
        "name": "Chapitour",
        "url": "https://chapitour.co/bar/CapitalQueer/index.php",
        "inLanguage": "es-CO",
        "description": "Guía turística y comercial de lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "BarOrPub",
        "@id": "https://chapitour.co/bar/CapitalQueer/index.php#bar",
        "name": "Capital Queer",
        "description": "Bar en Chapinero, Bogotá, diseñado especialmente para mujeres. Un espacio seguro, diverso y abierto para todas las personas que quieran disfrutar con respeto, libertad, música, bebidas y buena energía.",
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
            "name": "Espacio diseñado para mujeres",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Ambiente seguro y respetuoso",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Espacio diverso e inclusivo",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Abierto para todas las personas",
            "value": true
          },
          {
            "@type": "LocationFeatureSpecification",
            "name": "Bar LGBTIQ+ friendly",
            "value": true
          }
        ]
      }
    ]
  }
  </script>

</head>

<body>

  <?php include "../../bar/CapitalQueer/home/nav/nav.php" ?>
  <?php include "../../bar/CapitalQueer/home/slider/slider.php" ?>

  <div class="container_capitalQueer">
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
