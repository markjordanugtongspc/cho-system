<?php
/**
 * API route table.
 * Naming convention:  /api/v{version}/{resource}/{action}
 * Middleware:         'auth' = must be signed in, 'csrf' = must send X-CSRF-Token
 *
 * @var \App\Core\Router $router
 */

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\NipController;
use App\Core\Response;

// Health check (no sensitive info)
$router->get('v1/health', static fn () => Response::success(['status' => 'ok', 'time' => date(DATE_ATOM)]));

// Authentication
$router->get('v1/auth/csrf',     [AuthController::class, 'csrf']);
$router->post('v1/auth/login',   [AuthController::class, 'login'],   ['csrf']);
$router->post('v1/auth/logout',  [AuthController::class, 'logout'],  ['auth', 'csrf']);
$router->get('v1/auth/session',  [AuthController::class, 'session'], ['auth']);

// NIP / EPI Sheets Integration
$router->get('v1/nip/files',         [NipController::class, 'files']);
$router->post('v1/nip/upload',       [NipController::class, 'upload']);
$router->get('v1/nip/kpis',          [NipController::class, 'kpis']);
$router->post('v1/nip/generate-year',[NipController::class, 'generateYear']);
$router->post('v1/nip/reset-quarter',[NipController::class, 'resetQuarter']);
$router->get('v1/nip/download',      [NipController::class, 'download']);

// Future modules go here, e.g.:
// $router->get('v1/patients',        [PatientController::class, 'index'], ['auth']);
// $router->post('v1/patients',       [PatientController::class, 'store'], ['auth', 'csrf']);
