<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/0.invitación/seccion/invitacion.css';
$jsFile  = $base . '/0.invitación/seccion/invitacion.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../0.invitación/seccion/invitacion.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">
<section class="join-chapitour">
  <div class="join-chapitour__box">
    <span>Chapitour.co</span>

    <h1>Haz parte de Chapitour.co</h1>

    <p>
    Únete a una red digital diseñada para visibilizar negocios, emprendimientos
    y empresas relacionadas con el turismo, la gastronomía y el comercio local
    en Chapinero.
  </p>

  <p>
    Cada participante contará con una página esencial, un enlace propio y presencia
    dentro de la plataforma para presentar sus servicios y conectar con nuevos clientes.
  </p>

  <p>
    Impulsaremos cada negocio mediante publicidad orgánica, campañas pagas,
    promoción física y contenido digital estratégico.
  </p>

  <p>
    Más que una vitrina, Chapitour.co busca formar una red colaborativa de comerciantes
    que fortalezca el turismo local y cree nuevas oportunidades para todos.
  </p>

    <a href="https://wa.me/573138846378">
      Comunícate al 313 884 6378
    </a>
  </div>
</section>
<script defer src="0.invitación/seccion/invitacion.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
