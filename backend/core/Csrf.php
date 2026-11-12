<?php
/**
 * CSRF protection (synchronizer token pattern).
 * Token lives in the session, is printed in <meta name="csrf-token">,
 * and must be sent back by JS in the "X-CSRF-Token" header on every non-GET request.
 */

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function verify(?string $token): bool
    {
        return is_string($token)
            && !empty($_SESSION[self::KEY])
            && hash_equals($_SESSION[self::KEY], $token);
    }

    /** Issue a fresh token (call after login/logout). */
    public static function rotate(): string
    {
        unset($_SESSION[self::KEY]);
        return self::token();
    }
}
