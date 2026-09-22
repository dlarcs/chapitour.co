<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Street Grill | Carne al barril y carnes ahumadas en Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/gastronomia/streetgrill/actividades/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Descubre Street Grill en Chapinero, Bogotá: gastrobar especializado en carne al barril, carnes ahumadas, bondiola de cerdo, hamburguesas artesanales, chorizos y más."
  >

  <meta
    name="keywords"
    content="Street Grill Bogotá, Street Grill Chapinero, carne al barril Bogotá, carne ahumada Chapinero, hamburguesas Chapinero, gastrobar Chapinero, restaurante de carnes Bogotá, bondiola ahumada, chorizo artesanal"
  >

  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">

  <link rel="canonical" href="https://www.chapitour.co/">

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
    content="Disfruta el sabor de Street Grill en Chapinero: carne al barril, carnes ahumadas, hamburguesas artesanales, bondiola de cerdo, chorizos y mucho más."
  >

  <meta property="og:url" content="https://www.chapitour.co/">

  <meta
    property="og:image"
    content="https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg"
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
    content="Carne ahumada, carne al barril, bondiola, hamburguesas artesanales, chorizos y más en Street Grill, Chapinero, Bogotá."
  >

  <meta
    name="twitter:image"
    content="https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    href="/gastronomia/streetgrill/img/logo.jpeg"
  >

  <link
    rel="icon"
    type="image/png"
    sizes="32x32"
    href="/gastronomia/streetgrill/img/logo.jpeg"
  >

  <link
    rel="icon"
    type="image/png"
    sizes="16x16"
    href="/gastronomia/streetgrill/img/logo.jpeg"
  >

  <link
    rel="apple-touch-icon"
    sizes="180x180"
    href="/gastronomia/streetgrill/img/logo.jpeg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../../gastronomia/streetgrill/actividades/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
  >

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "Restaurant",
        "@id": "https://www.chapitour.co/#street-grill",
        "name": "Street Grill",
        "description": "Gastrobar de carnes al barril en Chapinero, Bogotá, especializado en carne ahumada, bondiola de cerdo, carne de res, hamburguesas artesanales y chorizos.",
        "url": "https://www.chapitour.co/",
        "image": "https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg",
        "servesCuisine": [
          "Carnes al barril",
          "Carnes ahumadas",
          "Hamburguesas artesanales",
          "Chorizos",
          "Gastronomía colombiana"
        ],
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Cra. 9 #57-85",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      }
    ]
  }
  </script>

</head>

<body>

  <?php include "../../../gastronomia/streetgrill/global/pag_nav/pag_nav.php" ?>

  <div class="container_reservas">

    <?php include "../../../gastronomia/streetgrill/actividades/actividades/actividades.php" ?>

  </div>

  <?php include "../../../gastronomia/streetgrill/global/boton/boton.php" ?>

  <?php include "../../../gastronomia/streetgrill/global/pag_footer/pag_footer.php" ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573143580355?text=Hola%2C%20vengo%20desde%20la%20web%20de%20Street%20Grill%20y%20quiero%20conocer%20más%20sobre%20su%20menú."
    target="_blank"
    rel="noopener"
    aria-label="Contactar a Street Grill por WhatsApp"
  >

    <img
      src="../../global/img/img_whatsApp.png"
      alt="Contactar a Street Grill por WhatsApp"
      decoding="async"
    >

  </a>

</body>

</html>
