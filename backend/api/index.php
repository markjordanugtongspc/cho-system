<?php
/**
 * API front controller.
 * Every request to /api/* is rewritten here by the root .htaccess:
 *   /api/v1/auth/login  ->  backend/api/index.php?route=v1/auth/login
 */

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Security;

Security::apiHeaders();

// Global per-IP throttle for the whole API
$limit = config('security.rate_limit');
$hit   = RateLimiter::hit('api:' . Request::ip(), $limit['api_max'], $limit['api_window']);
header('X-RateLimit-Limit: ' . $hit['limit']);
header('X-RateLimit-Remaining: ' . $hit['remaining']);
if (!$hit['allowed']) {
    Response::tooManyRequests($hit['retry_after']);
}

$router = new Router();
require dirname(__DIR__) . '/routes/api.php';

$router->dispatch(Request::method(), Request::route());
