```php
<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Actividades y promociones | Capital Queer</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/CapitalQueer/actividades/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Conoce las actividades, promociones y descuentos de Capital Queer, un bar en Chapinero creado especialmente para mujeres y abierto a todas las personas. Disfruta música, eventos especiales y planes para compartir."
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
    href="https://www.chapitour.co/bar/CapitalQueer/actividades/index.php"
  >

  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Capital Queer">

  <meta
    property="og:title"
    content="Actividades y promociones | Capital Queer"
  >

  <meta
    property="og:description"
    content="Descubre las actividades, eventos, descuentos y promociones de Capital Queer, un espacio creado especialmente para mujeres y abierto a todas las personas."
  >

  <meta
    property="og:url"
    content="https://www.chapitour.co/bar/CapitalQueer/actividades/index.php"
  >

  <meta
    property="og:image"
    content="https://www.chapitour.co/bar/CapitalQueer/img/logoCapitalQueer.jpg"
  >

  <meta
    property="og:image:secure_url"
    content="https://www.chapitour.co/bar/CapitalQueer/img/logoCapitalQueer.jpg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">

  <meta
    property="og:image:alt"
    content="Logo de Capital Queer, bar en Chapinero creado especialmente para mujeres"
  >

  <meta property="og:locale" content="es_CO">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Actividades y promociones | Capital Queer"
  >

  <meta
    name="twitter:description"
    content="Conoce los eventos, actividades, descuentos y promociones de Capital Queer en Chapinero."
  >

  <meta
    name="twitter:image"
    content="https://www.chapitour.co/bar/CapitalQueer/img/logoCapitalQueer.jpg"
  >

  <meta
    name="twitter:image:alt"
    content="Logo de Capital Queer"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    type="image/jpeg"
    href="/bar/CapitalQueer/img/logoCapitalQueer.jpg"
  >

  <link
    rel="apple-touch-icon"
    href="/bar/CapitalQueer/img/logoCapitalQueer.jpg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../../bar/CapitalQueer/actividades/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
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
        "@id": "https://www.chapitour.co/bar/CapitalQueer/index.php#bar",
        "name": "Capital Queer",
        "description": "Capital Queer es un bar en Chapinero creado especialmente para mujeres y abierto a todas las personas. Ofrece actividades, descuentos, promociones, música y eventos especiales en un ambiente diverso, seguro y respetuoso.",
        "url": "https://www.chapitour.co/bar/CapitalQueer/index.php",
        "image": "https://www.chapitour.co/bar/CapitalQueer/img/logoCapitalQueer.jpg",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      },
      {
        "@type": "WebPage",
        "@id": "https://www.chapitour.co/bar/CapitalQueer/actividades/index.php#webpage",
        "url": "https://www.chapitour.co/bar/CapitalQueer/actividades/index.php",
        "name": "Actividades y promociones | Capital Queer",
        "description": "Página de actividades, eventos, descuentos y promociones de Capital Queer en Chapinero.",
        "inLanguage": "es-CO",
        "isPartOf": {
          "@id": "https://www.chapitour.co/#website"
        },
        "about": {
          "@id": "https://www.chapitour.co/bar/CapitalQueer/index.php#bar"
        }
      }
    ]
  }
  </script>

</head>

<body>

  <?php include "../../../bar/CapitalQueer/global/pag_nav/pag_nav.php" ?>

  <div class="container_reservas">
    <?php include "../../../bar/CapitalQueer/actividades/actividades/actividades.php" ?>
  </div>

  <?php include "../../../bar/CapitalQueer/global/boton/boton.php" ?>

  <?php include "../../../bar/CapitalQueer/global/pag_footer/pag_footer.php" ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573138846378?text=Hola%2C%20vengo%20desde%20la%20p%C3%A1gina%20de%20actividades%20de%20Capital%20Queer%20y%20quiero%20conocer%20las%20promociones%20disponibles."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Consultar actividades y promociones de Capital Queer por WhatsApp"
  >
    <img
      src="../../../global/img/img_whatsApp.png"
      alt="Contactar a Capital Queer por WhatsApp"
      decoding="async"
    >
  </a>

</body>
</html>
```
