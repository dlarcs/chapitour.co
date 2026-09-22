```php
<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Galería | Pictogramas</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/bar/Pictograma/galeria/style.css';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  ?>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Conoce la galería de Pictogramas, un bar en Chapinero donde puedes disfrutar granizados, café, boliranas y un ambiente ideal para compartir con amigos."
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
    href="https://www.chapitour.co/bar/Pictograma/galeria/index.php"
  >

  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Pictogramas">

  <meta
    property="og:title"
    content="Galería | Pictogramas en Chapinero"
  >

  <meta
    property="og:description"
    content="Descubre la galería de Pictogramas y conoce su ambiente, granizados, café, boliranas y diferentes momentos para compartir en Chapinero."
  >

  <meta
    property="og:url"
    content="https://www.chapitour.co/bar/Pictograma/galeria/index.php"
  >

  <meta
    property="og:image"
    content="https://www.chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta
    property="og:image:secure_url"
    content="https://www.chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">

  <meta
    property="og:image:alt"
    content="Logo de Pictogramas, bar en Chapinero"
  >

  <meta property="og:locale" content="es_CO">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Galería | Pictogramas en Chapinero"
  >

  <meta
    name="twitter:description"
    content="Conoce la galería de Pictogramas y descubre su ambiente, granizados, café, boliranas y momentos especiales."
  >

  <meta
    name="twitter:image"
    content="https://www.chapitour.co/bar/Pictograma/img/logo.jpeg"
  >

  <meta
    name="twitter:image:alt"
    content="Logo de Pictogramas"
  >

  <!-- Iconos -->
  <link
    rel="icon"
    type="image/jpeg"
    href="/bar/Pictograma/img/logo.jpeg"
  >

  <link
    rel="apple-touch-icon"
    href="/bar/Pictograma/img/logo.jpeg"
  >

  <!-- CSS -->
  <link
    rel="stylesheet"
    href="../../../bar/Pictograma/galeria/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
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
        "@id": "https://www.chapitour.co/bar/Pictograma/index.php#bar",
        "name": "Pictogramas",
        "description": "Pictogramas es un bar en Chapinero donde puedes disfrutar granizados, café, boliranas y diferentes bebidas en un ambiente ideal para compartir, conversar y pasar un buen momento con amigos.",
        "url": "https://www.chapitour.co/bar/Pictograma/index.php",
        "image": "https://www.chapitour.co/bar/Pictograma/img/logo.jpeg",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        }
      },
      {
        "@type": "CollectionPage",
        "@id": "https://www.chapitour.co/bar/Pictograma/galeria/index.php#webpage",
        "url": "https://www.chapitour.co/bar/Pictograma/galeria/index.php",
        "name": "Galería | Pictogramas",
        "description": "Galería de Pictogramas con imágenes de su ambiente, granizados, café, boliranas, bebidas y momentos para compartir en Chapinero.",
        "inLanguage": "es-CO",
        "isPartOf": {
          "@id": "https://www.chapitour.co/#website"
        },
        "about": {
          "@id": "https://www.chapitour.co/bar/Pictograma/index.php#bar"
        }
      }
    ]
  }
  </script>

</head>

<body>

  <?php include "../../../bar/Pictograma/global/pag_nav/pag_nav.php" ?>

  <div class="container_galeria">
    <?php include "../../../bar/Pictograma/galeria/galeria/galeria.php" ?>
    <?php include "../../../bar/Pictograma/global/boton/boton.php" ?>
  </div>

  <?php include "../../../bar/Pictograma/global/pag_footer/pag_footer.php" ?>

  <a
    class="whatsapp-fab"
    href="https://wa.me/573502835648?text=Hola%2C%20vengo%20desde%20la%20galer%C3%ADa%20de%20Pictogramas%20y%20quiero%20recibir%20m%C3%A1s%20informaci%C3%B3n."
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Contactar a Pictogramas por WhatsApp"
  >
    <img
      src="../../../global/img/img_whatsApp.png"
      alt="Contactar a Pictogramas por WhatsApp"
      decoding="async"
    >
  </a>

</body>
</html>
```
