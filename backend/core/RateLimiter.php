<?php
/**
 * File-based fixed-window rate limiter (no Redis needed on Laragon).
 * Keys are HMAC-hashed so raw usernames/IPs never appear on disk.
 * Uses flock() so concurrent requests cannot race past the limit.
 */

declare(strict_types=1);

namespace App\Core;

final class RateLimiter
{
    private static function file(string $key): string
    {
        $dir = base_path('storage/ratelimit');
        if (!is_dir($dir)) {
            mkdir($dir, 0750, true);
        }
        return $dir . DIRECTORY_SEPARATOR . hash_hmac('sha256', $key, app_key()) . '.json';
    }

    /**
     * Register a hit and report whether it is allowed.
     *
     * @return array{allowed: bool, remaining: int, retry_after: int, limit: int}
     */
    public static function hit(string $key, int $max, int $windowSeconds): array
    {
        self::maybeCollectGarbage();

        $now = time();
        $fh  = fopen(self::file($key), 'c+');
        if ($fh === false) {
            // Fail closed would lock everybody out on a disk issue; fail open but log it.
            Logger::error('RateLimiter could not open storage file', ['key' => substr(hash('sha256', $key), 0, 12)]);
            return ['allowed' => true, 'remaining' => $max, 'retry_after' => 0, 'limit' => $max];
        }

        flock($fh, LOCK_EX);
        $raw  = stream_get_contents($fh);
        $data = json_decode($raw ?: '', true);

        if (!is_array($data) || ($data['reset_at'] ?? 0) <= $now) {
            $data = ['count' => 0, 'reset_at' => $now + $windowSeconds];
        }

        $data['count']++;
        $allowed = $data['count'] <= $max;

        ftruncate($fh, 0);
        rewind($fh);
        fwrite($fh, json_encode($data));
        fflush($fh);
        flock($fh, LOCK_UN);
        fclose($fh);

        return [
            'allowed'     => $allowed,
            'remaining'   => max(0, $max - $data['count']),
            'retry_after' => $allowed ? 0 : max(1, $data['reset_at'] - $now),
            'limit'       => $max,
        ];
    }

    /** Check without counting a hit. */
    public static function tooMany(string $key, int $max): int
    {
        $file = self::file($key);
        if (!is_file($file)) {
            return 0;
        }
        $data = json_decode((string) file_get_contents($file), true);
        if (!is_array($data) || ($data['reset_at'] ?? 0) <= time()) {
            return 0;
        }
        return $data['count'] >= $max ? max(1, $data['reset_at'] - time()) : 0;
    }

    public static function clear(string $key): void
    {
        $file = self::file($key);
        if (is_file($file)) {
            @unlink($file);
        }
    }

    /** ~1% of requests clean up expired buckets older than a day. */
    private static function maybeCollectGarbage(): void
    {
        if (random_int(1, 100) !== 1) {
            return;
        }
        foreach (glob(base_path('storage/ratelimit/*.json')) ?: [] as $file) {
            if (filemtime($file) < time() - 86400) {
                @unlink($file);
            }
        }
    }
}
