<?php
/**
 * Tiny daily-rotating file logger -> storage/logs/app-YYYY-MM-DD.log
 * Never pass passwords, tokens or full personal health data in $context.
 */

declare(strict_types=1);

namespace App\Core;

final class Logger
{
    private const REDACT = ['password', 'pass', 'token', 'csrf', 'validator', 'secret', 'remember'];

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        foreach ($context as $k => $v) {
            foreach (self::REDACT as $needle) {
                if (stripos((string) $k, $needle) !== false) {
                    $context[$k] = '[REDACTED]';
                }
            }
        }

        $dir = base_path('storage/logs');
        if (!is_dir($dir)) {
            mkdir($dir, 0750, true);
        }

        $line = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            $level,
            str_replace(["\r", "\n"], ' ', $message),
            $context ? json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : ''
        );

        @file_put_contents($dir . '/app-' . date('Y-m-d') . '.log', $line, FILE_APPEND | LOCK_EX);
    }
}
