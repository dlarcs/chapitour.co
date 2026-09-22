<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Menú | Street Grill Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/gastronomia/streetgrill/menu/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Conoce el menú de Street Grill en Chapinero, Bogotá: carnes al barril, carnes ahumadas, bondiola de cerdo, hamburguesas artesanales, chorizos, choripán y acompañamientos.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Street Grill">
  <meta property="og:title" content="Street Grill | Carnes al barril en Chapinero, Bogotá">
  <meta property="og:description" content="Descubre Street Grill, un gastrobar de carnes al barril en Chapinero con carnes ahumadas, bondiola, hamburguesas artesanales, chorizos y mucho sabor.">
  <meta property="og:url" content="https://www.chapitour.co/">
  <meta property="og:image" content="https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Street Grill | Carnes al barril en Chapinero">
  <meta name="twitter:description" content="Carnes ahumadas, bondiola, hamburguesas artesanales, chorizos y preparaciones al barril en Street Grill, Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/gastronomia/streetgrill/img/logo.jpeg">

  <!-- Iconos -->
  <link rel="icon" href="/gastronomia/streetgrill/img/logo.jpeg">
  <link rel="icon" type="image/png" sizes="32x32" href="../gastronomia/streetgrill/img/logo.jpeg">
  <link rel="icon" type="image/png" sizes="16x16" href="../gastronomia/streetgrill/img/logo.jpeg">
  <link rel="apple-touch-icon" sizes="180x180" href="../gastronomia/streetgrill/img/logo.jpeg">

  <!-- CSS -->
  <link rel="stylesheet" href="../../../gastronomia/streetgrill/menu/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "Restaurant",
        "@id": "https://www.chapitour.co/#streetgrill",
        "name": "Street Grill",
        "description": "Gastrobar de carnes al barril en Chapinero, Bogotá, especializado en carnes ahumadas, bondiola de cerdo, carne de res, hamburguesas artesanales, chorizos y preparaciones de sabor artesanal.",
        "url": "https://www.chapitour.co/",
        "image": "https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg",
        "servesCuisine": [
          "Carnes al barril",
          "Carnes ahumadas",
          "Hamburguesas artesanales",
          "Parrilla"
        ],
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Cra. 9 #57-85",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      },
      {
        "@type": "WebPage",
        "@id": "https://www.chapitour.co/#streetgrill-menu",
        "name": "Menú de Street Grill",
        "description": "Menú de Street Grill con carnes al barril, preparaciones ahumadas, hamburguesas artesanales, chorizos, choripán, acompañamientos y bebidas.",
        "url": "https://www.chapitour.co/",
        "inLanguage": "es-CO",
        "about": {
          "@id": "https://www.chapitour.co/#streetgrill"
        }
      }
    ]
  }
  </script>

</head>
<body>

  <?php include "../../../gastronomia/streetgrill/global/pag_nav/pag_nav.php" ?>

  <div class="container_menu">
    <?php include "../../../gastronomia/streetgrill/menu/menu/menu.php" ?>
  </div>

  <?php include "../../../gastronomia/streetgrill/global/boton/boton.php" ?>

  <?php include "../../../gastronomia/streetgrill/global/pag_footer/pag_footer.php" ?>

  <a class="whatsapp-fab"
    href="https://wa.me/573143580355?text=Hola%2C%20vengo%20desde%20la%20web%20de%20Street%20Grill"
    target="_blank"
    rel="noopener"
    aria-label="Chatear con Street Grill por WhatsApp">
    <img
      src="../../global/img/img_whatsApp.png"
      alt="Contactar a Street Grill por WhatsApp"
      decoding="async">
  </a>

</body>

</html>
