<?php
/**
 * Read-only access to the current HTTP request.
 */

declare(strict_types=1);

namespace App\Core;

final class Request
{
    private const MAX_JSON_BYTES = 65536; // 64 KB cap on JSON bodies

    private static ?array $json = null;

    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /** Route path for the API front controller, e.g. "v1/auth/login". */
    public static function route(): string
    {
        $route = (string) ($_GET['route'] ?? '');
        $route = trim($route, '/');
        // allow only safe characters in routes
        return preg_match('#^[a-z0-9/_-]*$#i', $route) ? strtolower($route) : '__invalid__';
    }

    public static function header(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return isset($_SERVER[$key]) ? (string) $_SERVER[$key] : null;
    }

    /** Parsed JSON body (empty array if none / invalid). */
    public static function json(): array
    {
        if (self::$json !== null) {
            return self::$json;
        }

        $type = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
        if (!str_contains($type, 'application/json')) {
            return self::$json = [];
        }

        $raw = file_get_contents('php://input', false, null, 0, self::MAX_JSON_BYTES + 1);
        if ($raw === false || strlen($raw) > self::MAX_JSON_BYTES) {
            return self::$json = [];
        }

        $data = json_decode($raw, true);
        return self::$json = is_array($data) ? $data : [];
    }

    public static function input(string $key, mixed $default = null): mixed
    {
        return self::json()[$key] ?? $_POST[$key] ?? $default;
    }

    public static function string(string $key, int $maxLength = 255): string
    {
        $value = self::input($key, '');
        if (!is_string($value)) {
            return '';
        }
        return mb_substr(trim($value), 0, $maxLength);
    }

    /**
     * Client IP. Only trusts X-Forwarded-For when REMOTE_ADDR is a configured trusted proxy,
     * otherwise attackers could spoof their IP to bypass rate limits.
     */
    public static function ip(): string
    {
        $remote = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $trusted = config('security.trusted_proxies');

        if ($trusted && in_array($remote, $trusted, true) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $first = trim(explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
            if (filter_var($first, FILTER_VALIDATE_IP)) {
                return $first;
            }
        }

        return filter_var($remote, FILTER_VALIDATE_IP) ? $remote : '0.0.0.0';
    }

    public static function userAgent(): string
    {
        return mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
    }

    /** Same-origin check for state-changing requests (defense-in-depth on top of CSRF). */
    public static function isSameOrigin(): bool
    {
        $origin = self::header('Origin') ?? self::header('Referer');
        if ($origin === null) {
            return true; // some same-origin requests omit both; CSRF token still required
        }
        $parts = parse_url($origin);
        if (!is_array($parts) || empty($parts['host'])) {
            return false;
        }
        $originHost = strtolower($parts['host'] . (isset($parts['port']) ? ':' . $parts['port'] : ''));
        return hash_equals(strtolower((string) ($_SERVER['HTTP_HOST'] ?? '')), $originHost);
    }
}
