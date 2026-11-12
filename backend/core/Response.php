<?php
/**
 * Consistent JSON envelope for every API response:
 * { "success": bool, "message": string, "data": mixed, "errors": object|null }
 */

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function json(array $payload, int $status = 200, array $headers = []): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, private');
        header('Pragma: no-cache');
        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        exit;
    }

    public static function success(mixed $data = null, string $message = 'OK', int $status = 200): never
    {
        self::json(['success' => true, 'message' => $message, 'data' => $data, 'errors' => null], $status);
    }

    public static function error(string $message, int $status = 400, ?array $errors = null, array $headers = []): never
    {
        self::json(['success' => false, 'message' => $message, 'data' => null, 'errors' => $errors], $status, $headers);
    }

    public static function tooManyRequests(int $retryAfter, string $message = 'Too many requests. Please slow down.'): never
    {
        self::error($message, 429, ['retry_after' => $retryAfter], ['Retry-After' => (string) $retryAfter]);
    }
}
