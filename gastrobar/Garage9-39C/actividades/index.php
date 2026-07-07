<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Actividades y promociones</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/gastrobar/Garage9-39C/actividades/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta name="description" content="Descubre Chapinero, Bogotá con Chapitour: cafés, restaurantes, bares, cultura, alojamiento, planes locales y lugares para visitar.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="https://www.chapitour.co/">
  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Chapitour">
  <meta property="og:title" content="Chapitour | Lugares para visitar en Chapinero, Bogotá">
  <meta property="og:description" content="Explora Chapinero, Bogotá: cafés, restaurantes, bares, cultura, planes locales, alojamiento y lugares para visitar.">
  <meta property="og:url" content="https://www.chapitour.co/">
  <meta property="og:image" content="https://www.chapitour.co/gastrobar/Garage9-39C/img/general11.jpg">
  <meta property="og:image:secure_url" content="https://www.chapitour.co/gastrobar/Garage9-39C/img/general11.jpg">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Chapitour | Lugares para visitar en Chapinero, Bogotá">
  <meta name="twitter:description" content="Descubre cafés, restaurantes, cultura, bares, alojamiento y planes locales en Chapinero, Bogotá.">
  <meta name="twitter:image" content="https://www.chapitour.co/gastrobar/Garage9-39C/img/general11.jpg">

  <!-- Iconos -->
  <link rel="icon" href="/gastrobar/Garage9-39C/img/general11.jpg">
  <link rel="icon" type="image/png" sizes="32x32" href="/gastrobar/Garage9-39C/img/general11.jpg">
  <link rel="icon" type="image/png" sizes="16x16" href="/gastrobar/Garage9-39C/img/general11.jpg">
  <link rel="apple-touch-icon" sizes="180x180" href="/gastrobar/Garage9-39C/img/general11.jpg">

  <!-- CSS -->
  <link rel="stylesheet" href="../../../gastrobar/Garage9-39C/actividades/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

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
        "description": "Catálogo turístico y comercial de lugares para visitar en Chapinero, Bogotá."
      },
      {
        "@type": "TouristDestination",
        "@id": "https://www.chapitour.co/#destination",
        "name": "Chapinero, Bogotá",
        "description": "Zona de Bogotá con cafés, restaurantes, bares, cultura, comercio local, alojamiento y planes para visitar.",
        "url": "https://www.chapitour.co/",
        "image": "https://www.chapitour.co/home/img/logo1.jpeg",
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

</head>
<body>

  <?php include "../../../gastrobar/Garage9-39C/global/pag_nav/pag_nav.php" ?>
  <div class="container_reservas">
    <?php include "../../../gastrobar/Garage9-39C/actividades/actividades/actividades.php" ?>

  </div>
  <?php include "../../../gastrobar/Garage9-39C/global/boton/boton.php" ?>
  <?php include "../../../gastrobar/Garage9-39C/global/pag_footer/pag_footer.php" ?>
  <a class="whatsapp-fab"
		href="https://wa.me/573138846378?text=Hola%20vengo%20desde%20la%20web%20de%20Bar%20Restaurant"
		target="_blank" rel="noopener"
		aria-label="Chatear por WhatsApp" >
		<img src="../../../global/img/img_whatsApp.png"
		 alt="Contactar por WhatsApp" decoding="async">
	</a>
</body>

</html>
