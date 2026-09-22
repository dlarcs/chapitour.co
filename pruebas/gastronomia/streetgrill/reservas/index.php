<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Reservas | Street Grill Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/gastronomia/streetgrill/reservas/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Reserva en Street Grill, gastrobar de carnes al barril en Chapinero, Bogotá. Disfruta carnes ahumadas, bondiola, hamburguesas artesanales, chorizos y más."
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
    content="Reservas | Street Grill Chapinero"
  >

  <meta
    property="og:description"
    content="Planea tu visita a Street Grill en Chapinero y disfruta carne al barril, carnes ahumadas, hamburguesas, chorizos y preparaciones llenas de sabor."
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
    content="Reservas | Street Grill Chapinero"
  >

  <meta
    name="twitter:description"
    content="Disfruta Street Grill en Chapinero: carne al barril, carnes ahumadas, hamburguesas artesanales, chorizos y más."
  >

  <meta
    name="twitter:image"
    content="https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg"
  >

  <!-- Iconos -->
  <link rel="icon" href="/gastronomia/streetgrill/img/logo.jpeg">
  <link rel="icon" type="image/png" sizes="32x32" href="/gastronomia/streetgrill/img/logo.jpeg">
  <link rel="icon" type="image/png" sizes="16x16" href="/gastronomia/streetgrill/img/logo.jpeg">
  <link rel="apple-touch-icon" sizes="180x180" href="/gastronomia/streetgrill/img/logo.jpeg">

  <!-- CSS -->
  <link rel="stylesheet" href="../../../gastronomia/streetgrill/reservas/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "Restaurant",
        "@id": "https://www.chapitour.co/#street-grill",
        "name": "Street Grill",
        "url": "https://www.chapitour.co/",
        "description": "Gastrobar de carnes al barril en Chapinero, Bogotá, especializado en carnes ahumadas, bondiola, hamburguesas artesanales, chorizos y otras preparaciones.",
        "image": "https://www.chapitour.co/gastronomia/streetgrill/img/logo.jpeg",
        "servesCuisine": [
          "Carnes al barril",
          "Carnes ahumadas",
          "Hamburguesas artesanales",
          "Chorizos",
          "Gastrobar"
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
    <?php include "../../../gastronomia/streetgrill/reservas/reservas/reservas.php" ?>
  </div>

  <?php include "../../../gastronomia/streetgrill/global/boton/boton.php" ?>

  <?php include "../../../gastronomia/streetgrill/global/pag_footer/pag_footer.php" ?>

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
