<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/bar/Gran&Chela_Club/actividades/actividades/actividades.css';
$jsFile  = $base . '/bar/Gran&Chela_Club/actividades/actividades/actividades.js';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
?>

<link rel="stylesheet" href="../../../bar/Gran&Chela_Club/actividades/actividades/actividades.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

<section class="business-section visible">

    <span class="section-label">Actividades & Promociones</span>

    <h2>Rumba, sabor y buenos momentos</h2>

    <p>
        Descubre la programación semanal de Gran&Chela Club. Disfruta noches de
        karaoke, salsa, flow urbano, crossover y las mejores promociones en
        micheladas, cerveza, granizados y cubetazos, en un ambiente ideal para
        cantar, bailar, brindar y compartir con amigos.
    </p>

    <section class="cards-section">

        <div class="cards-grid">

            <!-- MIÉRCOLES -->
            <article class="card promo-card" data-code="GRAN-CHELA-KARAOKE-001">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general7.jpg"
                        alt="Miércoles de Voces y Copas en Gran&Chela Club">

                    <span class="card-badge">Miércoles</span>
                </div>

                <div class="card-content">

                    <h3>Miércoles de Voces y Copas</h3>

                    <p>
                        Noche de karaoke para cantar, brindar y disfrutar.
                        Reúne a tus amigos y vive una experiencia llena de música,
                        diversión y buen ambiente.
                    </p>

                    <span class="card-location">
                        <span>&#128467;&#65039;</span>
                        Todos los miércoles
                    </span>

                </div>

            </article>

            <!-- JUEVES -->
            <article class="card promo-card" data-code="GRAN-CHELA-SALSA-002">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general3.jpg"
                        alt="Jueves Salsero Azúcar y Sabor">

                    <span class="card-badge">Jueves</span>
                </div>

                <div class="card-content">

                    <h3>Jueves Salsero: Azúcar y Sabor</h3>

                    <p>
                        Ritmos salseros para bailar, gozar y vivir la noche.
                        Disfruta los mejores clásicos y un ambiente lleno de sabor.
                    </p>

                    <span class="card-location">
                        <span>&#128467;&#65039;</span>
                        Todos los jueves
                    </span>

                </div>

            </article>

            <!-- VIERNES -->
            <article class="card promo-card" data-code="GRAN-CHELA-FLOW-003">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general8.jpg"
                        alt="Viernes de Flow Urbano">

                    <span class="card-badge">Viernes</span>
                </div>

                <div class="card-content">

                    <h3>Viernes de Flow Urbano</h3>

                    <p>
                        Perreo, dancehall y la mejor energía para comenzar
                        el fin de semana con la mejor rumba.
                    </p>

                    <span class="card-location">
                        <span>&#128467;&#65039;</span>
                        Todos los viernes
                    </span>

                </div>

            </article>

            <!-- SÁBADO -->
            <article class="card promo-card" data-code="GRAN-CHELA-SABADO-004">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general10.jpg"
                        alt="Sábado de Fiesta Total Crossover">

                    <span class="card-badge">Sábado</span>
                </div>

                <div class="card-content">

                    <h3>Sábado de Fiesta Total Crossover</h3>

                    <p>
                        Los mejores éxitos para bailar, brindar y disfrutar.
                        Salsa, merengue, bachata, reguetón y crossover durante toda la noche.
                    </p>

                    <span class="card-location">
                        <span>&#128467;&#65039;</span>
                        Todos los sábados
                    </span>

                </div>

            </article>

            <!-- EVENTOS -->
            <article class="card promo-card" data-code="GRAN-CHELA-EVENTOS-005">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general12.jpg"
                        alt="Eventos especiales">

                    <span class="card-badge">Reserva</span>
                </div>

                <div class="card-content">

                    <h3>Eventos Especiales</h3>

                    <p>
                        ¿Quieres alquilar el lugar para tu evento?
                        Reserva tu espacio para cumpleaños, reuniones,
                        celebraciones y eventos privados.
                    </p>

                    <span class="card-location">
                        <span>&#128197;</span>
                        Reserva previa
                    </span>

                </div>

            </article>

            <!-- MICHELADAS -->
            <article class="card promo-card" data-code="GRAN-CHELA-PROMO-006">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general1.jpg"
                        alt="Micheladas 2x1">

                    <span class="card-badge">2x1</span>
                </div>

                <div class="card-content">

                    <h3>Micheladas 2x1</h3>

                    <p>
                        Aprovecha nuestra promoción de micheladas 2x1.
                        Comparte con tus amigos y disfruta una noche llena
                        de buena música, diversión y excelente ambiente.
                    </p>

                    <span class="card-location">
                        <span>&#127867;</span>
                        Promoción especial
                    </span>

                </div>

            </article>
            <!-- CERVEZA 2x1 -->
            <article class="card promo-card" data-code="GRAN-CHELA-PROMO-007">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general5.jpg"
                        alt="Cerveza 2x1 en Gran&Chela Club">

                    <span class="card-badge">2x1</span>
                </div>

                <div class="card-content">

                    <h3>Cerveza 2x1</h3>

                    <p>
                        Disfruta cerveza 2x1 y vive una noche llena de buena música,
                        amigos y el mejor ambiente para brindar en Gran&Chela Club.
                    </p>

                    <span class="card-location">
                        <span>&#127866;</span>
                        Promoción especial
                    </span>

                </div>

            </article>

            <!-- GRANIZADO MONSTER -->
            <article class="card promo-card" data-code="GRAN-CHELA-PROMO-008">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general4.jpg"
                        alt="Granizado Monster 2x1">

                    <span class="card-badge">Monster</span>
                </div>

                <div class="card-content">

                    <h3>Granizado Monster 2x1</h3>

                    <p>
                        Refresca tu noche con nuestros granizados Monster 2x1.
                        Una mezcla perfecta para compartir y disfrutar con amigos.
                    </p>

                    <span class="card-location">
                        <span>&#127865;</span>
                        Promoción especial
                    </span>

                </div>

            </article>

            <!-- GRANIZADO FOUR LOKO -->
            <article class="card promo-card" data-code="GRAN-CHELA-PROMO-009">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general9.jpg"
                        alt="Granizado Four Loko 2x1">

                    <span class="card-badge">Four Loko</span>
                </div>

                <div class="card-content">

                    <h3>Granizado Four Loko 2x1</h3>

                    <p>
                        Disfruta el sabor y la energía de nuestros granizados
                        Four Loko 2x1. Una promoción ideal para comenzar la rumba.
                    </p>

                    <span class="card-location">
                        <span>&#127865;</span>
                        Promoción especial
                    </span>

                </div>

            </article>

            <!-- CUBETAZO -->
            <article class="card promo-card" data-code="GRAN-CHELA-PROMO-010">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general6.jpg"
                        alt="Cubetazo de Cervezas">

                    <span class="card-badge">Cubetazo</span>
                </div>

                <div class="card-content">

                    <h3>Cubetazo de Cervezas</h3>

                    <p>
                        Comparte un cubetazo de cervezas con tus amigos y disfruta
                        una noche de karaoke, salsa, crossover y mucha diversión.
                    </p>

                    <span class="card-location">
                        <span>&#127866;</span>
                        Promoción especial
                    </span>

                </div>

            </article>

            <!-- RUMBA -->
            <article class="card promo-card" data-code="GRAN-CHELA-RUMBA-011">

                <div class="card-image">
                    <img src="../../../bar/Gran&Chela_Club/img/general11.jpg"
                        alt="Noches de Rumba en Gran&Chela Club">

                    <span class="card-badge">Rumba</span>
                </div>

                <div class="card-content">

                    <h3>Noches de Rumba y Amigos</h3>

                    <p>
                        Licores, cerveza, micheladas, karaoke y salsa en un ambiente
                        ideal para bailar, celebrar y crear grandes momentos con tus amigos.
                    </p>

                    <span class="card-location">
                        <span>&#127925;</span>
                        Viernes y sábados
                    </span>

                </div>

            </article>

            </div>

            </section>

            </section>

<script defer src="../../../bar/Gran&Chela_Club/actividades/actividades/actividades.js<?= $jsVer ? '?v=' . $jsVer : '' ?>"></script>
