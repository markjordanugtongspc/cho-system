<?php
/**
 * HTTP security headers (CSP, clickjacking, MIME sniffing, referrer, permissions, HSTS).
 */

declare(strict_types=1);

namespace App\Core;

final class Security
{
    /** Headers for HTML pages. */
    public static function pageHeaders(): void
    {
        self::common();

        $vite   = Vite::devOrigin();                         // only set while `npm run dev` is running locally
        $viteWs = $vite ? preg_replace('#^http#', 'ws', $vite) : '';

        $csp = [
            "default-src 'self'",
            "script-src 'self' {$vite}",
            // 'unsafe-inline' styles are required by Vite HMR, Flowbite & ApexCharts runtime styles
            "style-src 'self' 'unsafe-inline' {$vite}",
            "img-src 'self' data: blob:",
            "font-src 'self' data: {$vite}",
            "connect-src 'self' {$vite} {$viteWs}",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ];

        header('Content-Security-Policy: ' . preg_replace('/\s+/', ' ', implode('; ', $csp)));
        header('Cache-Control: no-store, private'); // authenticated pages must never be cached
    }

    /** Headers for JSON API responses. */
    public static function apiHeaders(): void
    {
        self::common();
        header("Content-Security-Policy: default-src 'none'; frame-ancestors 'none'");
    }

    private static function common(): void
    {
        header_remove('X-Powered-By');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()');
        header('Cross-Origin-Opener-Policy: same-origin');

        if (is_https()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}
