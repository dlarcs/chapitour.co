```php
<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Galería | Capital Queer</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/CapitalQueer/galeria/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Conoce la galería de Capital Queer, un bar en Chapinero creado especialmente para mujeres y abierto a todas las personas. Descubre su ambiente, actividades, promociones, eventos y momentos especiales."
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
    href="https://www.chapitour.co/bar/CapitalQueer/galeria/index.php"
  >

  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Capital Queer">

  <meta
    property="og:title"
    content="Galería | Capital Queer en Chapinero"
  >

  <meta
    property="og:description"
    content="Conoce el ambiente de Capital Queer, un espacio creado especialmente para mujeres y abierto a todas las personas. Descubre actividades, promociones, eventos, música y momentos especiales."
  >

  <meta
    property="og:url"
    content="https://www.chapitour.co/bar/CapitalQueer/galeria/index.php"
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
    content="Galería | Capital Queer en Chapinero"
  >

  <meta
    name="twitter:description"
    content="Conoce la galería de Capital Queer y descubre su ambiente, actividades, promociones, eventos y momentos especiales."
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
    href="../../../bar/CapitalQueer/galeria/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
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
        "description": "Capital Queer es un bar en Chapinero creado especialmente para mujeres y abierto a todas las personas. Ofrece actividades, promociones, descuentos, música y eventos en un ambiente diverso, seguro y respetuoso.",
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
        "@type": "CollectionPage",
        "@id": "https://www.chapitour.co/bar/CapitalQueer/galeria/index.php#webpage",
        "url": "https://www.chapitour.co/bar/CapitalQueer/galeria/index.php",
        "name": "Galería | Capital Queer",
        "description": "Galería de Capital Queer con imágenes de su ambiente, actividades, promociones, eventos y momentos especiales.",
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

  <div class="container_galeria">
    <?php include "../../../bar/CapitalQueer/galeria/galeria/galeria.php" ?>
  </div>

  <?php include "../../../bar/CapitalQueer/global/boton/boton.php" ?>

  <?php include "../../../bar/CapitalQueer/global/pag_footer/pag_footer.php" ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573007795016?text=Hola%2C%20vengo%20desde%20la%20galer%C3%ADa%20de%20Capital%20Queer%20y%20quiero%20recibir%20m%C3%A1s%20informaci%C3%B3n."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Contactar a Capital Queer por WhatsApp"
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
