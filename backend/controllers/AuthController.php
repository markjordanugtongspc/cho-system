<?php
/**
 * Auth endpoints:
 *   GET  /api/v1/auth/csrf     -> fresh CSRF token
 *   POST /api/v1/auth/login    -> sign in
 *   POST /api/v1/auth/logout   -> sign out
 *   GET  /api/v1/auth/session  -> current user
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

final class AuthController
{
    public function csrf(): never
    {
        Response::success(['csrf_token' => Csrf::token()]);
    }

    public function login(): never
    {
        $username = Request::string('username', 100);
        $password = Request::input('password', '');
        $remember = filter_var(Request::input('remember', false), FILTER_VALIDATE_BOOLEAN);

        $errors = [];
        if ($username === '') {
            $errors['username'] = 'Please enter your username.';
        } elseif (!preg_match('/^[A-Za-z0-9._@\-]{3,100}$/', $username)) {
            $errors['username'] = 'Username contains invalid characters.';
        }
        if (!is_string($password) || $password === '') {
            $errors['password'] = 'Please enter your password.';
        } elseif (strlen($password) > 255) {
            $errors['password'] = 'Password is too long.';
        }

        if ($errors) {
            Response::error('Please check the highlighted fields.', 422, $errors);
        }

        $result = AuthService::attempt($username, $password, $remember);

        if (!$result['ok']) {
            if ($result['status'] === 429) {
                Response::tooManyRequests($result['retry_after'], $result['message']);
            }
            Response::error($result['message'], $result['status']);
        }

        Response::success([
            'user'       => $result['user'],
            'redirect'   => url('/dashboard'),
            'csrf_token' => Csrf::token(), // rotated on login
        ], $result['message']);
    }

    public function logout(): never
    {
        AuthService::logout();
        Response::success(['redirect' => url('/index.php'), 'csrf_token' => Csrf::token()], 'You have been signed out.');
    }

    public function session(): never
    {
        Response::success(['user' => AuthService::user()]);
    }
}
