<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/juegos/JimarFactory/reservas/style.css';
  $cssVer  = is_file($cssFile) ? filemtime($cssFile) : '';

  $canonicalUrl = 'https://www.chapitour.co/juegos/JimarFactory/reservas/';
  $businessUrl  = 'https://www.chapitour.co/juegos/JimarFactory/';
  $socialImage  = 'https://www.chapitour.co/juegos/JimarFactory/img/general3.jpeg';
  ?>

  <title>Reservas de billar | Jimar Factory Chapinero</title>

  <!-- SEO principal para Google -->
  <meta
    name="description"
    content="Reserva tu mesa en Jimar Factory Chapinero. Billar de tres bandas y pool, bebidas, cócteles, café y entretenimiento para mayores de 18 años."
  >

  <meta
    name="keywords"
    content="Jimar Factory Chapinero, billar en Chapinero, billar en Bogotá, billar tres bandas, mesas de pool, reservar mesa de billar, cócteles en Chapinero"
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
    href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>"
  >

  <meta name="theme-color" content="#005548">

  <!-- Open Graph: WhatsApp y Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:locale" content="es_CO">
  <meta property="og:site_name" content="Jimar Factory Chapinero">

  <meta
    property="og:title"
    content="Reservas de billar | Jimar Factory Chapinero"
  >

  <meta
    property="og:description"
    content="Reserva tu mesa y disfruta de billar a tres bandas, pool, bebidas, cócteles, café y entretenimiento en la calle 58 #13-93, Chapinero."
  >

  <meta
    property="og:url"
    content="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>"
  >

  <meta
    property="og:image"
    content="<?= htmlspecialchars($socialImage, ENT_QUOTES, 'UTF-8') ?>"
  >

  <meta
    property="og:image:secure_url"
    content="<?= htmlspecialchars($socialImage, ENT_QUOTES, 'UTF-8') ?>"
  >

  <meta property="og:image:type" content="image/jpeg">

  <meta
    property="og:image:alt"
    content="Mesas de billar en Jimar Factory Chapinero"
  >

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">

  <meta
    name="twitter:title"
    content="Reservas de billar | Jimar Factory Chapinero"
  >

  <meta
    name="twitter:description"
    content="Billar a tres bandas y pool, bebidas, cócteles, café y entretenimiento para mayores de 18 años en Chapinero, Bogotá."
  >

  <meta
    name="twitter:image"
    content="<?= htmlspecialchars($socialImage, ENT_QUOTES, 'UTF-8') ?>"
  >

  <meta
    name="twitter:image:alt"
    content="Mesas de billar en Jimar Factory Chapinero"
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
    href="../../../juegos/JimarFactory/reservas/style.css<?= $cssVer ? '?v=' . $cssVer : '' ?>"
  >

  <!-- Datos estructurados para Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebSite",
        "@id": "<?= $businessUrl ?>#website",
        "name": "Jimar Factory Chapinero",
        "url": "<?= $businessUrl ?>",
        "inLanguage": "es-CO",
        "description": "Billar de tres bandas y pool, bebidas, cócteles, café y entretenimiento para mayores de 18 años en Chapinero, Bogotá."
      },
      {
        "@type": "WebPage",
        "@id": "<?= $canonicalUrl ?>#webpage",
        "name": "Reservas de billar | Jimar Factory Chapinero",
        "url": "<?= $canonicalUrl ?>",
        "isPartOf": {
          "@id": "<?= $businessUrl ?>#website"
        },
        "about": {
          "@id": "<?= $businessUrl ?>#business"
        },
        "inLanguage": "es-CO",
        "description": "Página de reservas de Jimar Factory Chapinero para disfrutar del servicio de billar a tres bandas y pool."
      },
      {
        "@type": [
          "LocalBusiness",
          "SportsActivityLocation"
        ],
        "@id": "<?= $businessUrl ?>#business",
        "name": "Jimar Factory Chapinero",
        "description": "Billar y centro de entretenimiento en Chapinero con servicio de tres bandas, pool, bebidas, cócteles, café y venta de materiales de juego para mayores de 18 años.",
        "url": "<?= $businessUrl ?>",
        "image": [
          "<?= $socialImage ?>",
          "https://www.chapitour.co/juegos/JimarFactory/img/general17.jpeg"
        ],
        "slogan": "El mejor billar de Bogotá",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Calle 58 #13-93",
          "addressLocality": "Chapinero",
          "addressRegion": "Bogotá D.C.",
          "addressCountry": "CO"
        },
        "areaServed": {
          "@type": "City",
          "name": "Bogotá"
        },
        "openingHoursSpecification": {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "https://schema.org/Monday",
            "https://schema.org/Tuesday",
            "https://schema.org/Wednesday",
            "https://schema.org/Thursday",
            "https://schema.org/Friday",
            "https://schema.org/Saturday",
            "https://schema.org/Sunday"
          ],
          "opens": "09:00",
          "closes": "00:00"
        },
        "audience": {
          "@type": "PeopleAudience",
          "requiredMinAge": 18
        },
        "hasOfferCatalog": {
          "@type": "OfferCatalog",
          "name": "Servicios de Jimar Factory Chapinero",
          "itemListElement": [
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Billar a tres bandas",
                "description": "Servicio de mesas para jugar billar a tres bandas."
              }
            },
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Pool",
                "description": "Mesas de pool para jugar y compartir con amigos."
              }
            },
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Bebidas, cócteles y café",
                "description": "Variedad de bebidas con y sin alcohol, cócteles y servicio de café."
              }
            },
            {
              "@type": "Offer",
              "itemOffered": {
                "@type": "Service",
                "name": "Materiales para juegos de billar",
                "description": "Venta de materiales y accesorios para juegos de billar."
              }
            }
          ]
        }
      }
    ]
  }
  </script>
</head>

<body>

  <?php include "../../../juegos/JimarFactory/global/pag_nav/pag_nav.php"; ?>

  <main class="container_reservas">
    <?php include "../../../juegos/JimarFactory/reservas/reservas/reservas.php"; ?>
  </main>

  <?php include "../../../juegos/JimarFactory/global/boton/boton.php"; ?>

  <?php include "../../../juegos/JimarFactory/global/pag_footer/pag_footer.php"; ?>

  <!--
    El botón de WhatsApp anterior pertenecía a Garage Disco Bar.
    Agrega aquí el número oficial de Jimar Factory cuando esté disponible.
  -->

</body>
</html>
