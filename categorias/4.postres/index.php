<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Postres en Chapinero | Chapitour Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/categorias/4.postres/style.css';
  $jsFile  = $base . '/categorias/4.postres/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Descubre negocios de postres en Chapinero, Bogotá: pastelerías, reposterías, heladerías, cafés dulces y lugares ideales para disfrutar algo delicioso.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/postres/">
  <meta name="theme-color" content="#b17ad0">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Postres en Chapinero | Chapitour Bogotá">
  <meta property="og:description" content="Encuentra negocios de postres en Chapinero: tortas, helados, cafés dulces, repostería, pastelerías y lugares para compartir algo delicioso.">
  <meta property="og:url" content="https://www.chapitour.co/postres/">
  <meta property="og:image" content="https://www.chapitour.co/home/img/postres-og.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/home/img/postres-og.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Postres en Chapinero | Chapitour Bogotá">
  <meta name="twitter:description" content="Descubre pastelerías, heladerías, cafés dulces y negocios de postres en Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://www.chapitour.co/home/img/postres-og.jpg">

  <!-- Iconos -->
  <link rel="icon" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/home/img/logo_pw.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/home/img/logo_pw.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/home/img/logo_pw.png">

  <!-- CSS -->
  <link rel="stylesheet" href="../../categorias/4.postres/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "description": "Catálogo turístico y comercial de lugares, negocios y experiencias en Chapinero, Bogotá."
      },
      {
        "@type": "CollectionPage",
        "@id": "https://www.chapitour.co/postres/#postres",
        "name": "Postres en Chapinero",
        "url": "https://www.chapitour.co/postres/",
        "inLanguage": "es-CO",
        "description": "Selección de negocios de postres en Chapinero, Bogotá, incluyendo pastelerías, reposterías, heladerías, cafés dulces y lugares para disfrutar algo especial.",
        "isPartOf": {
          "@id": "https://www.chapitour.co/#website"
        },
        "about": {
          "@type": "LocalBusiness",
          "name": "Negocios de postres en Chapinero",
          "description": "Lugares locales de Chapinero dedicados a postres, repostería, pastelería, helados, cafés dulces y productos para compartir.",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Chapinero",
            "addressRegion": "Bogotá D.C.",
            "addressCountry": "CO"
          }
        },
        "image": "https://www.chapitour.co/home/img/postres-og.jpg"
      }
    ]
  }
  </script>

  <!-- JS -->
  <script defer src="../../categorias/4.postres/app.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
</head>

<body>

  <script src="soporte.js"></script>

  <?php include "../../global/menu/menu.php" ?>
  <?php include "../../categorias/4.postres/seccion/postres.php" ?>
  <?php include "../../global/footer/footer.php" ?>

</body>
</html>
