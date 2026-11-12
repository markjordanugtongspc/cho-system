<?php
/**
 * "Remember me" tokens using the split-token (selector + validator) pattern.
 * - Cookie holds  selector:validator  (random, meaningless, HttpOnly — NOT the password)
 * - DB holds      selector + SHA-256(validator)
 * A stolen database therefore cannot be used to forge cookies.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class RememberToken
{
    /** @return string cookie value "selector:validator" */
    public static function create(int $userId, int $days, string $userAgent): string
    {
        $selector  = bin2hex(random_bytes(12)); // 24 hex chars
        $validator = bin2hex(random_bytes(32)); // 64 hex chars

        Database::query(
            'INSERT INTO user_remember_tokens (user_id, selector, validator_hash, user_agent_hash, expires_at)
             VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))',
            [$userId, $selector, hash('sha256', $validator), hash('sha256', $userAgent), $days]
        );

        return $selector . ':' . $validator;
    }

    /** @return array{0:string,1:string}|null */
    public static function parse(string $cookie): ?array
    {
        if (!preg_match('/^([a-f0-9]{24}):([a-f0-9]{64})$/', $cookie, $m)) {
            return null;
        }
        return [$m[1], $m[2]];
    }

    public static function findValid(string $selector): ?array
    {
        return Database::first(
            'SELECT id, user_id, validator_hash, user_agent_hash
               FROM user_remember_tokens
              WHERE selector = ? AND expires_at > NOW()
              LIMIT 1',
            [$selector]
        );
    }

    public static function deleteBySelector(string $selector): void
    {
        Database::query('DELETE FROM user_remember_tokens WHERE selector = ?', [$selector]);
    }

    /** Used when token theft is suspected or the user changes password. */
    public static function deleteAllForUser(int $userId): void
    {
        Database::query('DELETE FROM user_remember_tokens WHERE user_id = ?', [$userId]);
    }

    public static function purgeExpired(): void
    {
        Database::query('DELETE FROM user_remember_tokens WHERE expires_at <= NOW()');
    }
}
