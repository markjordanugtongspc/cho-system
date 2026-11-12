<?php
/**
 * Application configuration.
 * All values are read from .env — never hard-code secrets here.
 */

declare(strict_types=1);

return [
    'name'      => env('APP_NAME', 'CHO Marawi Portal'),
    'short'     => env('APP_SHORT_NAME', 'CHO Marawi'),
    'env'       => env('APP_ENV', 'production'),   // local | staging | production
    'debug'     => (bool) env('APP_DEBUG', false),  // NEVER true in production
    'url'       => rtrim((string) env('APP_URL', 'http://cho-system.test'), '/'),
    'base_path' => rtrim((string) env('APP_BASE_PATH', ''), '/'), // e.g. "/CHO-System" when served from a sub-folder
    'timezone'  => env('APP_TIMEZONE', 'Asia/Manila'),
    'key'       => (string) env('APP_KEY', ''),     // 64-char hex secret used for HMAC hashing
];
