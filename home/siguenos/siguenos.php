<?php
$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssFile = $base . '/home/siguenos/siguenos.css';

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
?>

<link rel="stylesheet" href="home/siguenos/siguenos.css<?= $cssVer ? '?v=' . $cssVer : '' ?>">

    <main class="page">

        <section class="follow-section">

            <!-- Decorative background -->
            <div class="follow-section__glow" aria-hidden="true"></div>
            <div class="follow-section__map" aria-hidden="true"></div>
            <div class="follow-section__dots" aria-hidden="true"></div>

            <!-- Main content -->
            <div class="follow-section__content">

                <div class="follow-section__text">

                    <p class="follow-section__eyebrow">
                        Conoce nuevos lugares
                    </p>

                    <h2 class="follow-section__title">
                        Síguenos
                    </h2>

                    <div
                        class="follow-section__brush"
                        aria-hidden="true"
                    ></div>

                    <p class="follow-section__description">
                        Descubre restaurantes, cafés, bares, cultura,
                        promociones y planes increíbles en Chapinero.
                    </p>

                    <!-- Decorative arrow -->
                    <svg
                        class="follow-section__arrow"
                        viewBox="0 0 430 150"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true"
                    >
                        <path
                            class="follow-section__arrow-line"
                            d="M20 40C130 125 285 125 380 63"
                        />

                        <path
                            class="follow-section__arrow-head"
                            d="M337 50L388 60L366 105"
                        />
                    </svg>

                </div>

                <!-- Instagram button -->
                <div class="follow-section__social">

                    <a
                        class="instagram-button"
                        href="https://www.instagram.com/chapitour.co/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Seguir a Chapitour en Instagram"
                    >

                        <span
                            class="instagram-button__ring"
                            aria-hidden="true"
                        ></span>

                        <span class="instagram-button__icon">

                            <svg
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <defs>
                                    <linearGradient
                                        id="instagramGradient"
                                        x1="0%"
                                        y1="100%"
                                        x2="100%"
                                        y2="0%"
                                    >
                                        <stop
                                            offset="0%"
                                            stop-color="#ffcc00"
                                        />

                                        <stop
                                            offset="35%"
                                            stop-color="#ff5b45"
                                        />

                                        <stop
                                            offset="65%"
                                            stop-color="#ef018d"
                                        />

                                        <stop
                                            offset="100%"
                                            stop-color="#9c27ff"
                                        />
                                    </linearGradient>
                                </defs>

                                <rect
                                    x="3"
                                    y="3"
                                    width="18"
                                    height="18"
                                    rx="5"
                                    fill="none"
                                    stroke="url(#instagramGradient)"
                                    stroke-width="2"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="4"
                                    fill="none"
                                    stroke="url(#instagramGradient)"
                                    stroke-width="2"
                                />

                                <circle
                                    cx="17.4"
                                    cy="6.7"
                                    r="1.2"
                                    fill="url(#instagramGradient)"
                                />
                            </svg>

                        </span>

                    </a>

                    <a
                        class="instagram-username"
                        href="https://www.instagram.com/chapitour.co/"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        @chapitour.co
                    </a>

                </div>

            </div>

    

        </section>

    </main>
