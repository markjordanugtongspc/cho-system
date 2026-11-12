<?php
/**
 * Security configuration: sessions, remember-me, rate limiting, lockout, cookies.
 */

declare(strict_types=1);

return [

    'session' => [
        'name'           => env('SESSION_NAME', 'CHOSESSID'),
        'idle_minutes'   => (int) env('SESSION_IDLE_MINUTES', 30),   // auto-logout after inactivity
        'absolute_hours' => (int) env('SESSION_ABSOLUTE_HOURS', 12), // hard max session age
        'regenerate_sec' => 900,                                      // rotate session id every 15 min
    ],

    'remember' => [
        'cookie'      => 'cho_remember',      // HttpOnly selector:validator token (NO password stored)
        'user_cookie' => 'cho_remember_user', // HttpOnly username-only cookie to prefill the login form
        'days'        => (int) env('REMEMBER_DAYS', 30),
    ],

    'rate_limit' => [
        // Per username + IP
        'login_max'       => (int) env('RATE_LIMIT_LOGIN_MAX', 5),
        'login_window'    => (int) env('RATE_LIMIT_LOGIN_WINDOW', 900),
        // Per IP (stops one machine spraying many usernames)
        'login_ip_max'    => (int) env('RATE_LIMIT_LOGIN_IP_MAX', 20),
        // Global API throttle per IP
        'api_max'         => (int) env('RATE_LIMIT_API_MAX', 60),
        'api_window'      => (int) env('RATE_LIMIT_API_WINDOW', 60),
    ],

    'lockout' => [
        'threshold' => (int) env('ACCOUNT_LOCK_THRESHOLD', 10), // consecutive failures before DB lock
        'minutes'   => (int) env('ACCOUNT_LOCK_MINUTES', 30),
    ],

    'password' => [
        'min_length' => 10,
    ],

    // Non-sensitive UI preferences cookie (readable by JS; never put secrets here)
    'prefs_cookie' => 'cho_prefs',

    // Comma-separated proxy IPs allowed to set X-Forwarded-For (leave empty on Laragon)
    'trusted_proxies' => array_filter(array_map('trim', explode(',', (string) env('TRUSTED_PROXIES', '')))),
];
