<?php
// Basic configuration for the PDPA Assessment app
return [
    'db_host'    => getenv('DB_HOST') ?: '127.0.0.1',
    'db_name'    => getenv('DB_NAME') ?: 'pdpa_system',
    'db_user'    => getenv('DB_USER') ?: 'root',
    'db_pass'    => getenv('DB_PASS') ?: '123456',
    'db_charset' => 'utf8mb4',
    // App settings
    'app_name'   => 'PDPA Assessment',
];
