<?php
/**
 * Hardened PHP session handling.
 * - Strict mode, cookie-only, HttpOnly, SameSite=Lax, Secure on HTTPS
 * - Idle timeout + absolute lifetime
 * - Periodic session-id rotation (mitigates fixation / hijack window)
 */

declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE || PHP_SAPI === 'cli') {
            return;
        }

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.gc_maxlifetime', (string) (config('security.session.absolute_hours') * 3600));

        session_name(config('security.session.name'));
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => cookie_path(),
            'domain'   => '',
            'secure'   => is_https(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
        self::enforceLifetime();
    }

    private static function enforceLifetime(): void
    {
        $now      = time();
        $idle     = config('security.session.idle_minutes') * 60;
        $absolute = config('security.session.absolute_hours') * 3600;

        $expired = (isset($_SESSION['_last_activity']) && ($now - $_SESSION['_last_activity']) > $idle)
                || (isset($_SESSION['_started_at']) && ($now - $_SESSION['_started_at']) > $absolute);

        if ($expired) {
            self::destroy();
            session_start();
            $_SESSION['_flash']['notice'] = 'Your session expired. Please sign in again.';
        }

        $_SESSION['_started_at']    ??= $now;
        $_SESSION['_rotated_at']    ??= $now;
        $_SESSION['_last_activity']   = $now;

        if (($now - $_SESSION['_rotated_at']) > config('security.session.regenerate_sec')) {
            self::regenerate();
        }
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        $_SESSION['_rotated_at'] = time();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Read-once flash message. */
    public static function flash(string $key): ?string
    {
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            setcookie(session_name(), '', [
                'expires'  => time() - 42000,
                'path'     => cookie_path(),
                'secure'   => is_https(),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
