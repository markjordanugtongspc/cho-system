<?php
/**
 * Global helper functions available everywhere after bootstrap.php.
 */

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Env;
use App\Core\Vite;

/* ---------------------------------------------------------------------
 | Environment & configuration
 * ------------------------------------------------------------------- */

function env(string $key, mixed $default = null): mixed
{
    return Env::get($key, $default);
}

/** Dot-notation config reader: config('security.session.name') */
function config(string $key, mixed $default = null): mixed
{
    static $cache = [];

    $segments = explode('.', $key);
    $file     = array_shift($segments);

    if (!array_key_exists($file, $cache)) {
        $path = BASE_PATH . '/config/' . basename($file) . '.php';
        $cache[$file] = is_file($path) ? require $path : [];
    }

    $value = $cache[$file];
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

function base_path(string $path = ''): string
{
    return BASE_PATH . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
}

function app_key(): string
{
    $key = config('app.key');
    if ($key === '' || strlen($key) < 32) {
        if (config('app.env') !== 'local') {
            throw new RuntimeException('APP_KEY is missing or too short. Generate one: node -e "console.log(require(\'crypto\').randomBytes(32).toString(\'hex\'))"');
        }
        return 'local-insecure-development-key-change-me';
    }
    return $key;
}

/* ---------------------------------------------------------------------
 | URLs & output
 * ------------------------------------------------------------------- */

/** Build an app-relative URL that respects APP_BASE_PATH. */
function url(string $path = '/'): string
{
    $base = config('app.base_path');
    if ($base === '' || $base === null) {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $base = preg_replace('#/frontend/(pages|components)/.*$#', '', $scriptDir);
        $base = ($base === '/' || $base === '\\' || $base === '.') ? '' : rtrim($base, '/');
    }
    return rtrim((string)$base, '/') . '/' . ltrim($path, '/');
}

/** URL for a static file inside frontend/assets (images, icons, sounds...). */
function asset(string $path): string
{
    $relative = 'frontend/assets/' . ltrim($path, '/');
    $full     = base_path($relative);
    $version  = is_file($full) ? '?v=' . filemtime($full) : '';
    return url($relative) . $version;
}

/** Escape for HTML output — ALWAYS use this when printing variables. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . url($path), true, 302);
    exit;
}

function vite_tags(): string
{
    return Vite::tags();
}

function csrf_token(): string
{
    return Csrf::token();
}

/* ---------------------------------------------------------------------
 | Cookies & transport
 * ------------------------------------------------------------------- */

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
}

function cookie_path(): string
{
    return config('app.base_path') !== '' ? config('app.base_path') . '/' : '/';
}

/** Secure-by-default cookie writer. */
function set_secure_cookie(string $name, string $value, int $expires, bool $httpOnly = true): void
{
    if (headers_sent()) {
        return;
    }
    setcookie($name, $value, [
        'expires'  => $expires,
        'path'     => cookie_path(),
        'domain'   => '',
        'secure'   => is_https(),
        'httponly' => $httpOnly,
        'samesite' => 'Lax',
    ]);
}

function forget_cookie(string $name, bool $httpOnly = true): void
{
    set_secure_cookie($name, '', time() - 3600, $httpOnly);
    unset($_COOKIE[$name]);
}

/* ---------------------------------------------------------------------
 | UI preferences (cookie: cho_prefs) — read server-side to avoid theme "flash"
 * ------------------------------------------------------------------- */

function prefs(): array
{
    static $prefs = null;
    if ($prefs !== null) {
        return $prefs;
    }

    $defaults = [
        'theme'            => 'light',  // light | dark | system
        'fontSize'         => 'md',     // sm | md | lg | xl
        'sidebarCollapsed' => false,
        'dropdownsOpen'    => true,     // sidebar dropdown groups open by default
    ];

    $raw  = (string) ($_COOKIE[config('security.prefs_cookie')] ?? '');
    $data = strlen($raw) < 2048 ? json_decode($raw, true) : null;
    if (!is_array($data)) {
        return $prefs = $defaults;
    }

    return $prefs = [
        'theme'            => in_array($data['theme'] ?? '', ['light', 'dark', 'system'], true) ? $data['theme'] : $defaults['theme'],
        'fontSize'         => in_array($data['fontSize'] ?? '', ['sm', 'md', 'lg', 'xl'], true) ? $data['fontSize'] : $defaults['fontSize'],
        'sidebarCollapsed' => (bool) ($data['sidebarCollapsed'] ?? $defaults['sidebarCollapsed']),
        'dropdownsOpen'    => (bool) ($data['dropdownsOpen'] ?? $defaults['dropdownsOpen']),
    ];
}

/** Attributes for the <html> tag based on the saved preferences. */
function html_attributes(): string
{
    $p     = prefs();
    $class = $p['theme'] === 'dark' ? 'dark' : '';
    return sprintf('class="%s" data-theme="%s" data-font-size="%s"', e($class), e($p['theme']), e($p['fontSize']));
}
