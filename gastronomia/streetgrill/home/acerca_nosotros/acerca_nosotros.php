```php
<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/gastronomia/streetgrill/home/acerca_nosotros/acerca_nosotros.css';
$jsFile  = $base . '/gastronomia/streetgrill/home/acerca_nosotros/acerca_nosotros.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../gastronomia/streetgrill/home/acerca_nosotros/acerca_nosotros.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section business-about visible" id="acerca_nosotros">
  <div class="about-text">
    <span class="section-label">Acerca de nosotros</span>
    <h2>Un gastrobar donde el humo, el barril y la carne son protagonistas</h2>

    <p>
      Street Grill es un gastrobar de carnes en Chapinero, Bogotá, creado para quienes disfrutan
      el sabor intenso de la carne al barril, las preparaciones ahumadas, la parrilla y una cocina
      artesanal llena de carácter.
    </p>

    <p>
      Hacemos parte de Chapitour.co para conectar con más personas, dar a conocer nuestra propuesta
      gastronómica y seguir fortaleciendo el comercio local de Chapinero con una experiencia casual,
      urbana y hecha para los amantes de la carne.
    </p>
  </div>

  <img src="../../gastronomia/streetgrill/img/logo.jpeg" alt="Experiencia gastronómica en Street Grill">

</section>

<a href="../../gastronomia/streetgrill/reservas/index.php">
  <div class="button_container">
    <button class="street-btn street-btn30" type="button" name="button">Reservas</button>
  </div>
</a>

<script defer src="../../gastronomia/streetgrill/home/acerca_nosotros/acerca_nosotros.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
```
