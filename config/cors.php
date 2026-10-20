<?php

$origins = array_values(array_filter(array_map(
    'trim',
    explode(',', (string) env('API_CORS_ORIGINS', ''))
)));

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_origins' => $origins,
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'Authorization', 'Accept', 'X-Requested-With'],
    'exposed_headers' => [],
    'max_age' => 86400,
    // Jangan pakai wildcard + credentials: hanya origin yang didaftarkan
    // di API_CORS_ORIGINS yang boleh akses browser (Flutter Web).
    'supports_credentials' => false,
];
