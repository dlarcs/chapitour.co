<!DOCTYPE html>
<html class="html_home" lang="es-CO" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-language" content="es-CO">

  <title>Chapitour | Lugares para visitar en Chapinero, Bogotá</title>

  <?php
  $base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

  $cssFile = $base . '/1.1.bartin/style.css';
  $jsFile  = $base . '/1.1.bartin/app.js';

  $cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
  $jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
  ?>

</head>
<body>

  <?php include "../1.1.bartin/home/nav/nav.php" ?>
  <?php include "../1.1.bartin/home/slider/slider.php" ?>
  <div class="container_bartin">
    <?php include "../1.1.bartin/home/menu/menu.php" ?>
    <?php include "../1.1.bartin/home/galeria/galeria.php" ?>
    <?php include "../1.1.bartin/home/acerca_nosotros/acerca_nosotros.php" ?>
    <?php include "../1.1.bartin/home/ubicacion/ubicacion.php" ?>
  </div>
  <?php include "../1.1.bartin/home/footer/footer.php" ?>
  <a class="whatsapp-fab"
    id="boton_click"
		href="#"
		rel="noopener"
		aria-label="Chatear por WhatsApp" >
		<img src="../global/img/img_whatsApp.png"
		 alt="Contactar por WhatsApp" decoding="async">
	</a>
</body>

</html>
