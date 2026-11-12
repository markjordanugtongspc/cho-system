<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;

/** Requires same-origin + valid X-CSRF-Token header on state-changing requests. */
final class VerifyCsrf
{
    public static function handle(): void
    {
        if (in_array(Request::method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return;
        }

        if (!Request::isSameOrigin()) {
            Response::error('Cross-origin request blocked.', 403);
        }

        if (!Csrf::verify(Request::header('X-CSRF-Token'))) {
            Response::error('Your security token expired. Please refresh the page and try again.', 419);
        }
    }
}
