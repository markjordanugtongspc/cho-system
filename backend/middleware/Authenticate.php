<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Response;
use App\Services\AuthService;

/** Rejects API calls from users who are not signed in. */
final class Authenticate
{
    public static function handle(): void
    {
        if (!AuthService::check()) {
            Response::error('You are not signed in or your session has expired.', 401);
        }
    }
}
