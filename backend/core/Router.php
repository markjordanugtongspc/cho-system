<?php
/**
 * Minimal API router.
 * Routes are declared in backend/routes/api.php:
 *   $router->post('v1/auth/login', [AuthController::class, 'login'], ['csrf', 'throttle:login']);
 */

declare(strict_types=1);

namespace App\Core;

use App\Middleware\Authenticate;
use App\Middleware\VerifyCsrf;

final class Router
{
    /** @var array<string, array<string, array{handler: callable|array, middleware: string[]}>> */
    private array $routes = [];

    public function get(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    public function put(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->add('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->add('DELETE', $path, $handler, $middleware);
    }

    private function add(string $method, string $path, callable|array $handler, array $middleware): void
    {
        $this->routes[trim(strtolower($path), '/')][$method] = ['handler' => $handler, 'middleware' => $middleware];
    }

    public function dispatch(string $method, string $path): never
    {
        if (!isset($this->routes[$path])) {
            Response::error('Endpoint not found.', 404);
        }

        if (!isset($this->routes[$path][$method])) {
            Response::error('Method not allowed.', 405, null, ['Allow' => implode(', ', array_keys($this->routes[$path]))]);
        }

        $route = $this->routes[$path][$method];

        foreach ($route['middleware'] as $mw) {
            match ($mw) {
                'auth'  => Authenticate::handle(),
                'csrf'  => VerifyCsrf::handle(),
                default => null,
            };
        }

        $handler = $route['handler'];
        if (is_array($handler)) {
            [$class, $action] = $handler;
            (new $class())->{$action}();
        } else {
            $handler();
        }

        // Controllers must always respond; this is a safety net.
        Response::error('No response generated.', 500);
    }
}
