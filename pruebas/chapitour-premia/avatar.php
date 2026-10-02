<?php
declare(strict_types=1);
ini_set('display_errors','0');
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');
header('X-Robots-Tag: noindex, nofollow');
header("Content-Security-Policy: default-src 'none'");
require __DIR__.'/lib/Http.php';
chapitourSession();
require __DIR__.'/lib/Panel.php';
try {
    if ($_SERVER['REQUEST_METHOD']!=='GET') { http_response_code(405);exit; }
    $db=require __DIR__.'/config/database.php';
    $actor=(new ChapitourPanel($db))->actor();
    session_write_close();
    if (!$actor || $actor['role']!=='client') { http_response_code(404);exit; }
    // Always the authenticated account; request parameters cannot choose another person's photo.
    $jpeg=(new ChapitourCommunity($db))->photo($actor['db_id']);
    if ($jpeg===null) { http_response_code(404);exit; }
    header('Content-Type: image/jpeg');
    header('Content-Length: '.strlen($jpeg));
    echo $jpeg;
} catch (Throwable $e) { http_response_code(503); }
