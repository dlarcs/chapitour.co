<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Qué hacer en Chapinero: restaurantes, cafés y planes | Chapitour.co</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/style.css';
  $jsFile  = $base . '/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <!-- SEO principal para Google -->
  <meta name="description" content="Descubre qué hacer en Chapinero, Bogotá: restaurantes, cafés, bares, cultura, alojamiento, promociones y planes locales recomendados por Chapitour.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://chapitour.co/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
<!-- Palabras clave en español e inglés -->
<meta name="keywords" content="
Chapitour,
Chapinero,
Bogotá,
que hacer en chapinero,
chapinero, Chapinero,
almorzar en chapinero,
lugares cheveres en chapinero,
que hacer en Chapinero,
turismo en Chapinero,
lugares para visitar en Chapinero,
planes en Chapinero,
qué hacer en Chapinero,
guía turística de Chapinero,
guía digital de Chapinero,
turismo en Bogotá,
lugares turísticos de Bogotá,
planes en Bogotá,
restaurantes en Chapinero,
cafés en Chapinero,
bares en Chapinero,
gastrobares en Chapinero,
comida rápida en Chapinero,
panaderías en Chapinero,
pastelerías en Chapinero,
gastronomía en Chapinero,
alojamiento en Chapinero,
hoteles en Chapinero,
hostales en Chapinero,
cultura en Chapinero,
arte en Chapinero,
artesanías en Chapinero,
naturaleza urbana en Bogotá,
turismo urbano en Bogotá,
comercio local en Chapinero,
negocios en Chapinero,
promociones en Chapinero,
eventos en Chapinero,
vida nocturna en Chapinero,
planes de día en Chapinero,
planes de noche en Chapinero,
sitios recomendados en Chapinero,
experiencias en Chapinero,
actividades en Chapinero,
rutas turísticas en Chapinero,
destinos en Bogotá,
comida colombiana en Chapinero,
comida internacional en Chapinero,
cafeterías en Bogotá,
restaurantes recomendados en Bogotá,
bares recomendados en Bogotá,
turismo local en Colombia,
viajar a Bogotá,
visitar Chapinero,
descubrir Chapinero,
directorio comercial de Chapinero,

Chapitour guide,
Chapinero district,
Bogota Colombia,
tourism in Chapinero,
places to visit in Chapinero,
things to do in Chapinero,
what to do in Chapinero,
Chapinero travel guide,
Chapinero digital guide,
tourism in Bogota,
tourist places in Bogota,
things to do in Bogota,
restaurants in Chapinero,
cafes in Chapinero,
bars in Chapinero,
gastropubs in Chapinero,
fast food in Chapinero,
bakeries in Chapinero,
pastry shops in Chapinero,
gastronomy in Chapinero,
accommodation in Chapinero,
hotels in Chapinero,
hostels in Chapinero,
culture in Chapinero,
art in Chapinero,
handicrafts in Chapinero,
urban nature in Bogota,
urban tourism in Bogota,
local businesses in Chapinero,
businesses in Chapinero,
special offers in Chapinero,
events in Chapinero,
nightlife in Chapinero,
daytime activities in Chapinero,
night-time activities in Chapinero,
recommended places in Chapinero,
experiences in Chapinero,
activities in Chapinero,
tourist routes in Chapinero,
destinations in Bogota,
Colombian food in Chapinero,
international food in Chapinero,
coffee shops in Bogota,
recommended restaurants in Bogota,
recommended bars in Bogota,
local tourism in Colombia,
travel to Bogota,
visit Chapinero,
discover Chapinero,
Chapinero business directory
">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Chapitour | Lugares para visitar en Chapinero, Bogotá">
  <meta property="og:description" content="Explora Chapinero, Bogotá: cafés, restaurantes, bares, cultura, planes locales, alojamiento y lugares para visitar.">
  <meta property="og:url" content="https://chapitour.co/">
  <meta property="og:image" content="https://chapitour.co/home/img/letrero_bogota.png">
  <meta property="og:image:secure_url" content="https://chapitour.co/home/img/letrero_bogota.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="Guía digital Chapitour de Chapinero, Bogotá">
  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Chapitour | Lugares para visitar en Chapinero, Bogotá">
  <meta name="twitter:description" content="Descubre cafés, restaurantes, cultura, bares, alojamiento y planes locales en Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://chapitour.co/home/img/letrero_bogota.png">
  <meta name="twitter:image:alt" content="Guía digital Chapitour de Chapinero, Bogotá">


  <!-- Iconos -->
  <link rel="icon" href="/home/img/letrero_bogota.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/letrero_bogota.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/letrero_bogota.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/letrero_bogota.png">

  <!-- CSS -->
  <link rel="stylesheet" href="style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "description": "Catálogo turístico y comercial de lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "TouristDestination",
        "@id": "https://chapitour.co/#destination",
        "name": "Chapinero, Bogotá",
        "description": "Zona de Bogotá con cafés, restaurantes, bares, cultura, comercio local, alojamiento y planes para visitar.",
        "url": "https://chapitour.co/",
        "image": "https://chapitour.co/home/img/letrero_bogota.png",
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
  <script defer src="app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>
<body>
  <!-- <div class="preloader" id="preloader">
    <div class="loader">
    </div>
  </div> -->
  <script src="soporte.js"></script>
  <?php include "home/menu/menu.php" ?>
  <div class="container_body">
    <?php include "home/slider/slider.php" ?>
    <?php include "home/categorias/categoria.php" ?>
    <?php include "home/destacados/destacados.php" ?>
    <?php include "home/interes/interes.php" ?>
    <?php include "home/maps/maps.php" ?>
  </div>
  <?php include "home/footer/footer.php" ?>
</body>
</html>
