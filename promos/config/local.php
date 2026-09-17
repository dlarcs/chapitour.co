<?php
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
return array (
  'dsn' => 'mysql:host=127.0.0.1;dbname=chapitour_promos;charset=utf8mb4',
  'db_user' => 'chapitour_app',
  'db_password' => 'e2de8c627453f630a257800049658c45b8ac2a460a6b6f1b',
  'app_key' => '6efd0aff3e094cc0bb9969fc24b3b0e5accbcc583f421655c5852b0f524b8e2f',
  'base_url' => 'http://localhost/ChapiTour',
  'secure_cookies' => false,
);
