<?php

/* =========================================================
   ARCHIVOS CSS, JAVASCRIPT Y PDF
========================================================= */

$base = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

$cssPublicPath = '/gastrobar/Garage9-39C/home/menu/menu.css';
$jsPublicPath  = '/gastrobar/Garage9-39C/home/menu/menu.js';
$pdfPublicPath = '/gastrobar/Garage9-39C/img/Garage_Menu.pdf';

$cssFile = $base . $cssPublicPath;
$jsFile  = $base . $jsPublicPath;
$pdfFile = $base . $pdfPublicPath;

$cssVer = is_file($cssFile) ? filemtime($cssFile) : '';
$jsVer  = is_file($jsFile) ? filemtime($jsFile) : '';
$pdfVer = is_file($pdfFile) ? filemtime($pdfFile) : '';

/* =========================================================
   FUNCIÓN PARA MOSTRAR PRECIOS COLOMBIANOS
========================================================= */

if (!function_exists('garageFormatPrice')) {
    function garageFormatPrice(?int $price): string
    {
        if ($price === null) {
            return '—';
        }

        return '$' . number_format($price, 0, ',', '.');
    }
}

/* =========================================================
   LICORES

   Precios tomados de la página 2 del PDF.
========================================================= */

$liquorCategories = [
    [
        'title' => 'Whisky',
        'items' => [
            [
                'name'   => 'Something',
                'half'   => 130000,
                'bottle' => 205000,
            ],
            [
                'name'   => 'Sello Rojo',
                'half'   => 130000,
                'bottle' => 205000,
            ],
            [
                'name'   => "Buchanan's",
                'half'   => 205000,
                'bottle' => 310000,
            ],
            [
                'name'   => 'Old Parr',
                'half'   => 225000,
                'bottle' => 310000,
            ],
            [
                'name'   => "Jack Daniel's",
                'half'   => 180000,
                'bottle' => 295000,
            ],
        ],
    ],

    [
        'title' => 'Tequila',
        'items' => [
            [
                'name'   => 'Olmeca Reposado',
                'half'   => 130000,
                'bottle' => 225000,
            ],
            [
                'name'   => 'Jimador',
                'half'   => 145000,
                'bottle' => 235000,
            ],
            [
                'name'   => 'José Cuervo',
                'half'   => 130000,
                'bottle' => 225000,
            ],
            [
                'name'   => 'Don Julio Reposado',
                'half'   => null,
                'bottle' => 598000,
            ],
        ],
    ],

    [
        'title' => 'Ginebra',
        'items' => [
            [
                'name'   => 'Tanqueray',
                'half'   => null,
                'bottle' => 335000,
            ],
            [
                'name'   => "Gordon's",
                'half'   => null,
                'bottle' => 198000,
            ],
        ],
    ],

    [
        'title' => 'Vodka',
        'items' => [
            [
                'name'   => 'Absolut',
                'half'   => 130000,
                'bottle' => 210000,
            ],
            [
                'name'   => 'Smirnoff',
                'half'   => 130000,
                'bottle' => 210000,
            ],
            [
                'name'   => 'Smirnoff Lulo',
                'half'   => 85000,
                'bottle' => 145000,
            ],
        ],
    ],

    [
        'title' => 'Ron',
        'items' => [
            [
                'name'   => 'Medellín',
                'half'   => 85000,
                'bottle' => 148000,
            ],
            [
                'name'   => 'Santa Fe',
                'half'   => 85000,
                'bottle' => 148000,
            ],
            [
                'name'   => 'Caldas',
                'half'   => 85000,
                'bottle' => 148000,
            ],
            [
                'name'   => 'Bacardí Limón',
                'half'   => 85000,
                'bottle' => 148000,
            ],
        ],
    ],

    [
        'title' => 'Aguardiente',
        'items' => [
            [
                'name'   => 'Antioqueño rojo, verde o azul',
                'half'   => 85000,
                'bottle' => 148000,
            ],
            [
                'name'   => 'Néctar verde',
                'half'   => 85000,
                'bottle' => 148000,
            ],
            [
                'name'   => 'Amarillo de Manzanares',
                'half'   => 85000,
                'bottle' => 148000,
            ],
        ],
    ],
];

/* =========================================================
   CERVEZAS
========================================================= */

$importedBeers = [
    [
        'name'  => 'Corona o Heineken',
        'price' => 15000,
    ],
    [
        'name'  => 'Stella Artois',
        'price' => 15000,
    ],
    [
        'name'  => 'Budweiser',
        'price' => 8500,
    ],
    [
        'name'  => 'Smirnoff Ice',
        'price' => 20000,
    ],
];

$nationalBeers = [
    [
        'name'  => 'Costeña, Águila o Poker',
        'price' => 6500,
    ],
    [
        'name'  => 'Redd’s',
        'price' => 8500,
    ],
    [
        'name'  => 'Club Colombia dorada, roja o negra',
        'price' => 8500,
    ],
    [
        'name'  => 'Águila Light o Águila Cero',
        'price' => 8500,
    ],
    [
        'name'  => 'Michelada',
        'price' => 12000,
    ],
];

$coloredBeers = [
    [
        'name'        => 'Cerveza roja',
        'description' => 'Granadina y vodka.',
        'price'       => 28000,
    ],
    [
        'name'        => 'Cerveza verde',
        'description' => 'Tequila, limón y curaçao azul.',
        'price'       => 28000,
    ],
    [
        'name'        => 'Cerveza morada',
        'description' => 'Mezcla especial de la casa.',
        'price'       => 28000,
    ],
];

/* =========================================================
   OTRAS BEBIDAS
========================================================= */

$otherDrinks = [
    [
        'name'  => 'Gatorade',
        'price' => 10000,
    ],
    [
        'name'  => 'Ginger, Coca-Cola o soda',
        'price' => 6500,
    ],
    [
        'name'  => 'Botella de agua',
        'price' => 6500,
    ],
    [
        'name'  => 'Red Bull',
        'price' => 18000,
    ],
    [
        'name'  => 'Electrolit',
        'price' => 18000,
    ],
];

/* =========================================================
   COCTELES

   Precios tomados de la página 3 del PDF.
========================================================= */

$cocktails = [
    [
        'name'  => 'Super Garage',
        'price' => 53000,
    ],
    [
        'name'  => 'Smirnoff Ice',
        'price' => 18000,
    ],
    [
        'name'  => 'Vino caliente',
        'price' => 28000,
    ],
    [
        'name'  => 'Tom Collins',
        'price' => 29500,
    ],
    [
        'name'  => 'Destornillador',
        'price' => 29500,
    ],
    [
        'name'  => 'Cuba Libre',
        'price' => 29500,
    ],
    [
        'name'  => 'Daiquiri',
        'price' => 29500,
    ],
    [
        'name'  => 'Azul profundo',
        'price' => 29500,
    ],
    [
        'name'  => 'Mojito cubano',
        'price' => 29500,
    ],
    [
        'name'  => 'Mojito de sabores',
        'price' => 29500,
    ],
    [
        'name'  => 'Tequila Sunrise',
        'price' => 29500,
    ],
    [
        'name'  => 'Orgasmo',
        'price' => 33500,
    ],
    [
        'name'  => 'Piña colada',
        'price' => 33500,
    ],
    [
        'name'  => 'Margarita',
        'price' => 29500,
    ],
    [
        'name'  => 'Margarita pasión',
        'price' => 29500,
    ],
    [
        'name'  => 'Margarita delirio',
        'price' => 29500,
    ],
    [
        'name'  => 'Margarita mango biche',
        'price' => 29500,
    ],
    [
        'name'  => 'Gin Tonic',
        'price' => 29500,
    ],
    [
        'name'  => 'Dry Martini',
        'price' => 29500,
    ],
    [
        'name'  => 'Alexander',
        'price' => 29500,
    ],
    [
        'name'  => 'Ruso blanco',
        'price' => 29500,
    ],
    [
        'name'  => 'Lychee Martini',
        'price' => 29500,
    ],
    [
        'name'  => 'Cosmopolitan',
        'price' => 29500,
    ],
    [
        'name'  => 'Caipiriña',
        'price' => 29500,
    ],
    [
        'name'  => 'Caipiroska',
        'price' => 29500,
    ],
    [
        'name'  => 'Cabeza de jabalí',
        'price' => 53000,
    ],
    [
        'name'  => 'Manhattan',
        'price' => 29500,
    ],
    [
        'name'  => 'Padrino',
        'price' => 33500,
    ],
    [
        'name'  => 'Apple Vodka',
        'price' => 29500,
    ],
];

$alcoholFreeCocktails = [
    [
        'name'  => 'Zanahorio',
        'price' => 22000,
    ],
    [
        'name'  => 'Mojigato',
        'price' => 22000,
    ],
];

?>

<link
    rel="stylesheet"
    href="<?= htmlspecialchars($cssPublicPath, ENT_QUOTES, 'UTF-8') ?><?= $cssVer ? '?v=' . $cssVer : '' ?>"
>

<section class="business-section visible" id="menu">

    <span class="section-label">Menú Garage 9-39C</span>

    <h2>Sabores y bebidas para disfrutar la rumba</h2>

    <p>
        Descubre nuestra selección de licores, cervezas, cocteles y bebidas
        para compartir durante una noche llena de música y buena energía
        en Chapinero.
    </p>

    <div class="menu-contact">
        <p>
            <strong>Reservas:</strong>
            <a href="tel:+573156175056">315 617 5056</a>
        </p>

        <p>
            <strong>Ubicación:</strong>
            Calle 59 #9-34, Chapinero, Bogotá
        </p>
    </div>

    <div class="button_container">

        <?php if (is_file($pdfFile)): ?>

            <a
                href="<?= htmlspecialchars($pdfPublicPath, ENT_QUOTES, 'UTF-8') ?><?= $pdfVer ? '?v=' . $pdfVer : '' ?>"
                class="btn btn30 dowload download-link"
                download="Menu-Garage-9-39C.pdf"
                aria-label="Descargar el menú de Garage 9-39C en formato PDF"
            >
                Descargar menú en PDF
            </a>

        <?php else: ?>

            <span class="pdf-error">
                El archivo Garage_Menu.pdf no fue encontrado.
            </span>

        <?php endif; ?>

    </div>

    <div class="menu-grid">

        <!-- ==========================================
             LICORES
        =========================================== -->

        <article class="menu-card menu-card-large">

            <img
                src="/gastrobar/Garage9-39C/img/general.jpg"
                alt="Selección de licores de Garage 9-39C"
                loading="lazy"
            >

            <div class="menu-card-content">

                <h3>Licores</h3>

                <p class="descripcion-card">
                    Whisky, tequila, ginebra, vodka, ron y aguardiente.
                </p>

                <?php foreach ($liquorCategories as $category): ?>

                    <div class="menu-category">

                        <h4>
                            <?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?>
                        </h4>

                        <div class="menu-table-wrapper">

                            <table class="menu-table">

                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Media</th>
                                        <th>Botella</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach ($category['items'] as $item): ?>

                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?= garageFormatPrice($item['half']) ?>
                                            </td>

                                            <td>
                                                <?= garageFormatPrice($item['bottle']) ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </article>

        <!-- ==========================================
             CERVEZAS
        =========================================== -->

        <article class="menu-card">

            <img
                src="/gastrobar/Garage9-39C/img/general2.jpg"
                alt="Cervezas de Garage 9-39C"
                loading="lazy"
            >

            <div class="menu-card-content">

                <h3>Cervezas</h3>

                <p class="descripcion-card">
                    Cervezas importadas, nacionales y preparaciones especiales.
                </p>

                <div class="menu-category">

                    <h4>Cervezas importadas</h4>

                    <ul class="menu-price-list">

                        <?php foreach ($importedBeers as $beer): ?>

                            <li>
                                <span>
                                    <?= htmlspecialchars($beer['name'], ENT_QUOTES, 'UTF-8') ?>
                                </span>

                                <strong>
                                    <?= garageFormatPrice($beer['price']) ?>
                                </strong>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

                <div class="menu-category">

                    <h4>Cervezas nacionales</h4>

                    <ul class="menu-price-list">

                        <?php foreach ($nationalBeers as $beer): ?>

                            <li>
                                <span>
                                    <?= htmlspecialchars($beer['name'], ENT_QUOTES, 'UTF-8') ?>
                                </span>

                                <strong>
                                    <?= garageFormatPrice($beer['price']) ?>
                                </strong>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            </div>

        </article>

        <!-- ==========================================
             CERVEZAS DE COLORES
        =========================================== -->

        <article class="menu-card">

            <img
                src="/gastrobar/Garage9-39C/img/general3.jpg"
                alt="Cervezas de colores de Garage 9-39C"
                loading="lazy"
            >

            <div class="menu-card-content">

                <h3>Cervezas de colores</h3>

                <p class="descripcion-card">
                    Preparaciones especiales, llamativas y llenas de sabor.
                </p>

                <ul class="menu-price-list menu-price-list-description">

                    <?php foreach ($coloredBeers as $beer): ?>

                        <li>

                            <div>
                                <span>
                                    <?= htmlspecialchars($beer['name'], ENT_QUOTES, 'UTF-8') ?>
                                </span>

                                <small>
                                    <?= htmlspecialchars($beer['description'], ENT_QUOTES, 'UTF-8') ?>
                                </small>
                            </div>

                            <strong>
                                <?= garageFormatPrice($beer['price']) ?>
                            </strong>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </article>

        <!-- ==========================================
             OTRAS BEBIDAS
        =========================================== -->

        <article class="menu-card">

            <img
                src="/gastrobar/Garage9-39C/img/general4.jpg"
                alt="Otras bebidas de Garage 9-39C"
                loading="lazy"
            >

            <div class="menu-card-content">

                <h3>Otras bebidas</h3>

                <p class="descripcion-card">
                    Bebidas refrescantes y energizantes para acompañar la noche.
                </p>

                <ul class="menu-price-list">

                    <?php foreach ($otherDrinks as $drink): ?>

                        <li>
                            <span>
                                <?= htmlspecialchars($drink['name'], ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <strong>
                                <?= garageFormatPrice($drink['price']) ?>
                            </strong>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </article>

        <!-- ==========================================
             COCTELES
        =========================================== -->

        <article class="menu-card menu-card-large">

            <img
                src="/gastrobar/Garage9-39C/img/general2.jpg"
                alt="Cocteles de Garage 9-39C"
                loading="lazy"
            >

            <div class="menu-card-content">

                <h3>Cocteles</h3>

                <p class="descripcion-card">
                    Cocteles clásicos y preparaciones especiales de Garage 9-39C.
                </p>

                <ul class="menu-price-list cocktail-list">

                    <?php foreach ($cocktails as $cocktail): ?>

                        <li>
                            <span>
                                <?= htmlspecialchars($cocktail['name'], ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <strong>
                                <?= garageFormatPrice($cocktail['price']) ?>
                            </strong>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </article>

        <!-- ==========================================
             COCTELES SIN LICOR
        =========================================== -->

        <article class="menu-card">

            <img
                src="/gastrobar/Garage9-39C/img/general3.jpg"
                alt="Cocteles sin licor de Garage 9-39C"
                loading="lazy"
            >

            <div class="menu-card-content">

                <h3>Cocteles sin licor</h3>

                <p class="descripcion-card">
                    Opciones refrescantes para disfrutar sin alcohol.
                </p>

                <ul class="menu-price-list">

                    <?php foreach ($alcoholFreeCocktails as $cocktail): ?>

                        <li>
                            <span>
                                <?= htmlspecialchars($cocktail['name'], ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <strong>
                                <?= garageFormatPrice($cocktail['price']) ?>
                            </strong>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </article>

    </div>

    <div class="button_container">

        <a
            href="/gastrobar/Garage9-39C/actividades/index.php"
            class="btn btn30 dowload"
        >
            Ver actividades y promociones
        </a>

    </div>

</section>

<script
    defer
    src="<?= htmlspecialchars($jsPublicPath, ENT_QUOTES, 'UTF-8') ?><?= $jsVer ? '?v=' . $jsVer : '' ?>"
></script>
