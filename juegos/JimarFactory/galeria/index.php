<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Galería | Jimar Factory Chapinero</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/juegos/JimarFactory/galeria/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Conoce la galería de Jimar Factory Chapinero, un espacio para disfrutar pool, billar y tres bandas en Bogotá. Descubre su ambiente, mesas de juego, bebidas, licores, café y entretenimiento."
  >

  <meta
    name="robots"
    content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1"
  >

  <meta
    name="googlebot"
    content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1"
  >

  <link
    rel="canonical"
    href="https://www.chapitour.co/juegos/JimarFactory/galeria/index.php"
  >

  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Jimar Factory Chapinero">

  <meta
    property="og:title"
    content="Galería | Jimar Factory Chapinero"
  >

  <meta
    property="og:description"
    content="Conoce Jimar Factory Chapinero y disfruta pool, billar, tres bandas, bebidas, licores, café y un excelente ambiente para compartir y jugar."
  >

  <meta
    property="og:url"
    content="https://www.chapitour.co/juegos/JimarFactory/galeria/index.php"
  >

  <meta
    property="og:image"
    content="https://www.chapitour.co/juegos/JimarFactory/img/logo.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://www.chapitour.co/juegos/JimarFactory/img/logo.jpeg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">

  <meta
    property="og:image:alt"
    content="Jimar Factory Chapinero, billar, pool y tres bandas en Bogotá"
  >

  <meta property="og:locale" content="es_CO">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Galería | Jimar Factory Chapinero"
  >

  <meta
    name="twitter:description"
    content="Descubre la galería de Jimar Factory Chapinero: pool, billar, tres bandas, bebidas, licores, café y entretenimiento."
  >

  <meta
    name="twitter:image"
    content="https://www.chapitour.co/juegos/JimarFactory/img/logo.jpeg"
  >

  <meta
    name="twitter:image:alt"
    content="Logo de Jimar Factory Chapinero"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    type="image/jpeg"
    href="/juegos/JimarFactory/img/logo.jpeg"
  >

  <link
    rel="apple-touch-icon"
    href="/juegos/JimarFactory/img/logo.jpeg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../../juegos/JimarFactory/galeria/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
  >

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
        "inLanguage": "es-CO"
      },
      {
        "@type": "BarOrPub",
        "@id": "https://www.chapitour.co/juegos/JimarFactory/index.php#bar",
        "name": "Jimar Factory Chapinero",
        "description": "Jimar Factory Chapinero es un espacio de entretenimiento en Bogotá para disfrutar pool, billar y tres bandas. Cuenta con bebidas, licores, cócteles, café y un ambiente ideal para compartir y jugar.",
        "url": "https://www.chapitour.co/juegos/JimarFactory/index.php",
        "image": "https://www.chapitour.co/juegos/JimarFactory/img/logo.jpeg",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Calle 58 #13-93",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      },
      {
        "@type": "CollectionPage",
        "@id": "https://www.chapitour.co/juegos/JimarFactory/galeria/index.php#webpage",
        "url": "https://www.chapitour.co/juegos/JimarFactory/galeria/index.php",
        "name": "Galería | Jimar Factory Chapinero",
        "description": "Galería de Jimar Factory Chapinero con imágenes de sus mesas de pool, billar, tres bandas, bebidas, licores, café y ambiente.",
        "inLanguage": "es-CO",
        "isPartOf": {
          "@id": "https://www.chapitour.co/#website"
        },
        "about": {
          "@id": "https://www.chapitour.co/juegos/JimarFactory/index.php#bar"
        }
      }
    ]
  }
  </script>

</head>

<body>


  <div class="container_galeria">
    <?php include "../../../juegos/JimarFactory/global/pag_nav/pag_nav.php" ?>
    <?php include "../../../juegos/JimarFactory/galeria/galeria/galeria.php" ?>
  </div>

  <?php include "../../../juegos/JimarFactory/global/boton/boton.php" ?>

  <?php include "../../../juegos/JimarFactory/global/pag_footer/pag_footer.php" ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573165180649?text=Hola%2C%20vengo%20desde%20la%20galer%C3%ADa%20de%20Jimar%20Factory%20Chapinero%20y%20quiero%20recibir%20m%C3%A1s%20informaci%C3%B3n."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Contactar a Jimar Factory Chapinero por WhatsApp"
  >
    <img
      src="../../../global/img/img_whatsApp.png"
      alt="Contactar a Jimar Factory Chapinero por WhatsApp"
      decoding="async"
    >
  </a>

</body>
</html>
