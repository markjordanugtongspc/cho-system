<?php
/**
 * Vite <-> PHP bridge.
 * - DEV  : when `npm run dev` is running, vite.config.js writes storage/vite.hot
 *          and we load scripts straight from the Vite dev server (HMR).
 * - PROD : after `npm run build`, we read dist/.vite/manifest.json for hashed files.
 */

declare(strict_types=1);

namespace App\Core;

final class Vite
{
    public const ENTRY = 'frontend/assets/js/main.js';

    private static ?array $manifest = null;

    /** Dev server origin, only honoured in the local environment. */
    public static function devOrigin(): string
    {
        static $origin = null;
        if ($origin !== null) {
            return $origin;
        }

        $hot = base_path('storage/vite.hot');
        if (config('app.env') !== 'local' || !is_file($hot)) {
            return $origin = '';
        }

        $url = trim((string) file_get_contents($hot));
        return $origin = preg_match('#^https?://[a-z0-9.\-]+(:\d+)?$#i', $url) ? rtrim($url, '/') : '';
    }

    public static function tags(string $entry = self::ENTRY): string
    {
        $dev = self::devOrigin();

        if ($dev !== '') {
            return sprintf(
                '<script type="module" src="%1$s/@vite/client"></script>' . "\n" .
                '    <script type="module" src="%1$s/%2$s"></script>',
                e($dev),
                e($entry)
            );
        }

        $manifest = self::manifest();
        if (!isset($manifest[$entry])) {
            return '<!-- Vite manifest missing: run "npm run build" or "npm run dev" -->';
        }

        $chunk = $manifest[$entry];
        $html  = [];

        foreach (self::collectCss($manifest, $entry) as $css) {
            $html[] = sprintf('<link rel="stylesheet" href="%s">', e(url('/dist/' . $css)));
        }
        foreach ($chunk['imports'] ?? [] as $import) {
            if (isset($manifest[$import]['file'])) {
                $html[] = sprintf('<link rel="modulepreload" href="%s">', e(url('/dist/' . $manifest[$import]['file'])));
            }
        }
        $html[] = sprintf('<script type="module" src="%s"></script>', e(url('/dist/' . $chunk['file'])));

        return implode("\n    ", $html);
    }

    private static function manifest(): array
    {
        if (self::$manifest !== null) {
            return self::$manifest;
        }
        $file = base_path('dist/.vite/manifest.json');
        self::$manifest = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        return self::$manifest;
    }

    private static function collectCss(array $manifest, string $key, array &$seen = []): array
    {
        if (isset($seen[$key]) || !isset($manifest[$key])) {
            return [];
        }
        $seen[$key] = true;
        $css = $manifest[$key]['css'] ?? [];
        foreach ($manifest[$key]['imports'] ?? [] as $import) {
            $css = array_merge($css, self::collectCss($manifest, $import, $seen));
        }
        return array_values(array_unique($css));
    }
}
