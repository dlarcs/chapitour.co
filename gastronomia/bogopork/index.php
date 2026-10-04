<?php
declare(strict_types=1);
$version = '20261003-1';
$menu = 'https://drive.google.com/file/d/1NxTi2q2hGL9h2nha0IA5f3Z5myMcbeqE/view';
$whatsapp = 'https://wa.me/573053861333?text=' . rawurlencode('¡Hola, BogoPork! Los encontré en Chapitour y quiero conocer su menú.');
$maps = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('BogoPork Carrera 8B 57-14 Bogotá Colombia');
?>
<!doctype html>
<html lang="es-CO">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BogoPork | Costillas, pulled pork y panceta en Chapinero</title>
  <meta name="description" content="Descubre BogoPork en Chapinero: costillas, pulled pork y pork belly. Consulta su menú, horarios y ubicación en la Carrera 8B #57-14, Bogotá.">
  <meta name="theme-color" content="#0c0c0d">
  <link rel="canonical" href="https://chapitour.co/gastronomia/bogopork/">
  <meta property="og:type" content="website">
  <meta property="og:title" content="BogoPork · El santuario del cerdo en Chapinero">
  <meta property="og:description" content="Costillas, pulled pork y panceta. Descubre el menú y arma tu próximo plan con Chapitour.">
  <meta property="og:url" content="https://chapitour.co/gastronomia/bogopork/">
  <meta property="og:image" content="https://chapitour.co/gastronomia/bogopork/img/pork-belly.jpg">
  <meta property="og:locale" content="es_CO">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="icon" href="img/logo.jpg" type="image/jpeg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css?v=<?= $version ?>">
  <script src="script.js?v=<?= $version ?>" defer></script>
  <script type="application/ld+json"><?= json_encode([
    '@context'=>'https://schema.org', '@type'=>'Restaurant', 'name'=>'BogoPork',
    'url'=>'https://chapitour.co/gastronomia/bogopork/',
    'image'=>'https://chapitour.co/gastronomia/bogopork/img/pork-belly.jpg',
    'telephone'=>'+573053861333', 'servesCuisine'=>'Cerdo', 'hasMenu'=>$menu,
    'address'=>['@type'=>'PostalAddress','streetAddress'=>'Carrera 8B #57-14','addressLocality'=>'Bogotá','addressRegion'=>'Bogotá D.C.','addressCountry'=>'CO'],
    'sameAs'=>['https://www.instagram.com/bogopork/','https://linktr.ee/bogopork/'],
    'openingHoursSpecification'=>[
      ['@type'=>'OpeningHoursSpecification','dayOfWeek'=>['Monday','Tuesday'],'opens'=>'12:00','closes'=>'18:00'],
      ['@type'=>'OpeningHoursSpecification','dayOfWeek'=>['Wednesday','Thursday','Friday','Saturday'],'opens'=>'12:00','closes'=>'21:00']
    ]
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>
<a class="skip-link" href="#contenido">Saltar al contenido</a>
<header class="site-header">
  <a class="brand" href="#inicio" aria-label="BogoPork, inicio"><img src="img/logo.jpg" width="52" height="52" alt="Logo de BogoPork"><span>BOGO<span class="pink">PORK</span><small>CHAPINERO · BOGOTÁ</small></span></a>
  <button class="menu-toggle" aria-controls="navigation" aria-expanded="false">Menú <span aria-hidden="true">☰</span></button>
  <nav id="navigation" aria-label="Navegación del negocio">
    <a href="#especialidades">Especialidades</a><a href="#galeria">Galería</a><a href="#visitanos">Visítanos</a><a class="back-link" href="/#explorar">Volver a Chapitour <span aria-hidden="true">↗</span></a>
  </nav>
</header>
<main id="contenido">
  <section class="hero wrap" id="inicio">
    <div class="hero-copy"><span class="eyebrow"><span class="dot"></span> UN ANTOJO EN CHAPINERO</span><h1>Enamórate<br>del <em>cerdo.</em></h1><p>Costillas, pulled pork y panceta carnuda.<br>Bienvenido al santuario del cerdo en Bogotá.</p><div class="actions"><a class="button primary" href="<?= $menu ?>" target="_blank" rel="noopener noreferrer">Ver menú <span aria-hidden="true">↗</span></a><a class="button secondary" href="#visitanos">Cómo llegar <span aria-hidden="true">↓</span></a></div><a class="hero-address" href="<?= htmlspecialchars($maps) ?>" target="_blank" rel="noopener noreferrer"><span aria-hidden="true">⌖</span> Carrera 8B #57-14 · Chapinero</a></div>
    <div class="hero-photo"><img src="img/pork-belly.jpg" width="1440" height="1428" alt="Pork belly de BogoPork servido con papas, encurtidos y limón" fetchpriority="high"><div class="photo-note"><span>PORK BELLY</span><strong>Un antojo que se ve.<br>Un sabor que se vive.</strong></div><span class="photo-stamp">BOGOTÁ<br><b>CON SABOR</b></span></div>
  </section>
  <div class="flavor-strip" aria-hidden="true"><span>COSTILLAS</span><i>✳</i><span>PULLED PORK</span><i>✳</i><span>PORK BELLY</span><i>✳</i><span>BOGOPORK</span></div>
  <section class="wrap section" id="especialidades">
    <div class="section-heading"><div><span class="eyebrow">EL CERDO ES EL PROTAGONISTA</span><h2>Tres razones<br>para venir con hambre.</h2></div><p>Conoce las especialidades que BogoPork comparte en su Instagram y encuentra tu próximo antojo en su menú.</p></div>
    <div class="specialties"><article><span class="number">01 /</span><h3>Costillas</h3><p>Las ribs tienen su lugar en el santuario. Una de las especialidades de la casa para descubrir en Chapinero.</p><span class="dish-tag">RIBS</span></article><article><span class="number">02 /</span><h3>Pulled pork</h3><p>El cerdo desmechado también es protagonista. Explora sus preparaciones en el menú del negocio.</p><span class="dish-tag">PULLED PORK</span></article><article><span class="number">03 /</span><h3>Panceta carnuda</h3><p>Su pork belly, de cerca: cerdo, textura y mucho antojo. Mira la galería y conoce esta especialidad.</p><span class="dish-tag">PORK BELLY</span></article></div>
    <a class="text-link" href="<?= $menu ?>" target="_blank" rel="noopener noreferrer">Consulta platos y precios en el menú oficial <span aria-hidden="true">↗</span></a>
  </section>
  <section class="gallery-section" id="galeria"><div class="wrap section">
    <div class="section-heading"><div><span class="eyebrow">DE SU COCINA A TU PRÓXIMO PLAN</span><h2>Así se ve el antojo.</h2></div><a class="text-link" href="https://www.instagram.com/bogopork/" target="_blank" rel="noopener noreferrer">Más en @bogopork <span aria-hidden="true">↗</span></a></div>
    <div class="gallery"><figure><img src="img/pork-belly.jpg" width="1440" height="1428" loading="lazy" alt="Plato de pork belly de BogoPork, fotografiado desde arriba"><figcaption><span>01</span> Pork belly, en todas sus dimensiones.</figcaption></figure><figure><img src="img/panceta.jpg" width="1440" height="1433" loading="lazy" alt="Detalle de la panceta carnuda con encurtidos de BogoPork"><figcaption><span>02</span> Los detalles también dan hambre.</figcaption></figure></div>
    <p class="photo-credit">Fotografías de <a href="https://www.instagram.com/bogopork/p/C-84QxIJtht/" target="_blank" rel="noopener noreferrer">BogoPork</a> · Fotografía: <a href="https://www.instagram.com/renekimaru_art/" target="_blank" rel="noopener noreferrer">@renekimaru_art</a>.</p>
  </div></section>
  <section class="wrap section visit" id="visitanos">
    <div class="visit-copy"><span class="eyebrow">NOS VEMOS EN CHAPI</span><h2>Tu próxima parada:<br><span class="pink">BogoPork.</span></h2><p>Un lugar dedicado al cerdo en el corazón de Chapinero. Encuéntralos, consulta su menú y conversa directamente con el negocio.</p><div class="actions"><a class="button primary" href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer">Escribir por WhatsApp <span aria-hidden="true">↗</span></a></div><a class="instagram-link" href="https://www.instagram.com/bogopork/" target="_blank" rel="noopener noreferrer">Instagram · @bogopork ↗</a></div>
    <div class="visit-details"><div class="location"><span class="eyebrow">UBICACIÓN</span><h3>Carrera 8B #57-14</h3><p>Chapinero, Bogotá, Colombia</p><a class="text-link" href="<?= htmlspecialchars($maps) ?>" target="_blank" rel="noopener noreferrer">Abrir en Google Maps ↗</a></div><div class="hours"><span class="eyebrow">HORARIOS</span><dl><div><dt>Lunes y martes</dt><dd>12:00 m. – 6:00 p. m.</dd></div><div><dt>Miércoles a sábado</dt><dd>12:00 m. – 9:00 p. m.</dd></div><div><dt>Domingo</dt><dd>Cerrado</dd></div></dl><p class="muted">Horarios publicados en su Instagram. Consulta con el negocio los cambios en días festivos.</p></div><a class="contact" href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer"><span>WHATSAPP</span><strong>+57 305 386 1333 ↗</strong></a></div>
  </section>
</main>
<footer class="site-footer wrap"><a class="chapitour" href="/">CHAPI<span>TOUR.CO</span></a><p>Negocios del barrio. Historias por descubrir.</p><a href="/#explorar">Conoce más aliados ↗</a></footer>
<a class="whatsapp-fab" href="<?= htmlspecialchars($whatsapp) ?>" target="_blank" rel="noopener noreferrer" aria-label="Contactar a BogoPork por WhatsApp"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M20.5 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.1-4.9A8.5 8.5 0 1 1 20.5 11.5Z"/><path d="M8 7.5c-.6.3-.9 1-.7 1.8.7 2.8 2.9 5 5.7 5.7.8.2 1.5-.1 1.8-.7l.5-1.1-2.2-1.1-.8.8a6.3 6.3 0 0 1-2.9-2.9l.8-.8-1.1-2.2Z"/></svg></a>
</body>
</html>
