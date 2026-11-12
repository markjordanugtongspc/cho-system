<?php
/**
 * Application bootstrap — required at the top of EVERY entry point
 * (index.php, frontend/pages/** /index.php, backend/api/index.php).
 */

declare(strict_types=1);

use App\Core\Env;
use App\Core\Logger;
use App\Core\Session;

define('BASE_PATH', dirname(__DIR__));

/* ---------------------------------------------------------------------
 | Autoloader: App\Core\Env -> backend/core/Env.php
 * ------------------------------------------------------------------- */
spl_autoload_register(static function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $parts = explode('\\', substr($class, 4));
    $file  = array_pop($parts);
    $dir   = implode(DIRECTORY_SEPARATOR, array_map('strtolower', $parts));
    $path  = __DIR__ . DIRECTORY_SEPARATOR . ($dir !== '' ? $dir . DIRECTORY_SEPARATOR : '') . $file . '.php';
    if (is_file($path)) {
        require $path;
    }
});

require __DIR__ . '/helpers.php';

Env::load(BASE_PATH . '/.env');

date_default_timezone_set((string) config('app.timezone', 'Asia/Manila'));
mb_internal_encoding('UTF-8');

/* ---------------------------------------------------------------------
 | Error handling — never leak stack traces / SQL to the browser
 * ------------------------------------------------------------------- */
$debug = (bool) config('app.debug') && config('app.env') === 'local';

error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', base_path('storage/logs/php-error.log'));
ini_set('expose_php', '0');

set_exception_handler(static function (Throwable $e) use ($debug): void {
    Logger::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);

    if (!headers_sent()) {
        http_response_code(500);
    }

    $isApi = str_contains((string) ($_SERVER['SCRIPT_NAME'] ?? ''), '/backend/api/');
    if ($isApi) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode([
            'success' => false,
            'message' => $debug ? $e->getMessage() : 'A server error occurred. Please try again later.',
            'data'    => null,
            'errors'  => null,
        ]);
        return;
    }

    echo $debug
        ? '<pre>' . e($e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine()) . '</pre>'
        : '<h1>Something went wrong</h1><p>Please try again later or contact the system administrator.</p>';
});

Session::start();
