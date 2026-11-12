<?php
/**
 * Users table access. Never return password_hash to the frontend — use publicProfile().
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User
{
    public static function findByUsername(string $username): ?array
    {
        return Database::first(
            'SELECT u.id, u.username, u.password_hash, u.first_name, u.last_name, u.status,
                    u.failed_login_count, u.locked_until, r.slug AS role, r.name AS role_name
               FROM users u
               JOIN roles r ON r.id = u.role_id
              WHERE u.username = ?
              LIMIT 1',
            [$username]
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::first(
            'SELECT u.id, u.username, u.first_name, u.last_name, u.status, u.locked_until,
                    r.slug AS role, r.name AS role_name
               FROM users u
               JOIN roles r ON r.id = u.role_id
              WHERE u.id = ?
              LIMIT 1',
            [$id]
        );
    }

    /** Increment failures; lock the account once the threshold is reached. */
    public static function recordFailure(int $id, int $threshold, int $minutes): void
    {
        // MySQL evaluates SET assignments left-to-right, so the second one sees the incremented value.
        Database::query(
            'UPDATE users
                SET failed_login_count = failed_login_count + 1,
                    locked_until = IF(failed_login_count >= ?, DATE_ADD(NOW(), INTERVAL ? MINUTE), locked_until)
              WHERE id = ?',
            [$threshold, $minutes, $id]
        );
    }

    public static function recordSuccess(int $id, string $ip): void
    {
        Database::query(
            'UPDATE users
                SET failed_login_count = 0, locked_until = NULL, last_login_at = NOW(), last_login_ip = ?
              WHERE id = ?',
            [$ip, $id]
        );
    }

    public static function updatePasswordHash(int $id, string $hash): void
    {
        Database::query('UPDATE users SET password_hash = ? WHERE id = ?', [$hash, $id]);
    }

    /** Safe subset of user data for sessions / API responses. */
    public static function publicProfile(array $user): array
    {
        return [
            'id'         => (int) $user['id'],
            'username'   => $user['username'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'full_name'  => trim($user['first_name'] . ' ' . $user['last_name']),
            'role'       => $user['role'],
            'role_name'  => $user['role_name'],
        ];
    }

    public static function hashPassword(string $plain): string
    {
        return password_hash($plain, defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT);
    }

    public static function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT);
    }
}
