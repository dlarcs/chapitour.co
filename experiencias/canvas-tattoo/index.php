<?php
declare(strict_types=1);

// The ally maintains its own website. Keep a local catalog route with a fixed destination.
header('Cache-Control: no-store');
header('Location: https://www.canvastattoocolombia.com/', true, 302);
exit;
