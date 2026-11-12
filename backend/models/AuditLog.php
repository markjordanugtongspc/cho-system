<?php
/**
 * Login attempt history + general audit trail (Data Privacy Act / RA 10173 accountability).
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use Throwable;

final class AuditLog
{
    public static function loginAttempt(string $username, bool $success, string $reason): void
    {
        try {
            Database::query(
                'INSERT INTO login_attempts (username, ip_address, user_agent, successful, reason) VALUES (?, ?, ?, ?, ?)',
                [mb_substr($username, 0, 100), Request::ip(), Request::userAgent(), $success ? 1 : 0, $reason]
            );
        } catch (Throwable $e) {
            Logger::error('Failed to write login attempt', ['error' => $e->getMessage()]);
        }
    }

    public static function record(string $action, ?int $userId = null, array $meta = []): void
    {
        try {
            Database::query(
                'INSERT INTO audit_logs (user_id, action, ip_address, user_agent, meta) VALUES (?, ?, ?, ?, ?)',
                [$userId, $action, Request::ip(), Request::userAgent(), $meta ? json_encode($meta) : null]
            );
        } catch (Throwable $e) {
            Logger::error('Failed to write audit log', ['action' => $action, 'error' => $e->getMessage()]);
        }
    }
}
