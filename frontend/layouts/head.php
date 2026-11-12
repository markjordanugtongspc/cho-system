<?php
/**
 * Global <head> partial for CHO Marawi Portal pages.
 * Includes CSRF meta, zero-flicker theme init, typography, and Vite bundle tags.
 *
 * @var string $pageTitle
 */

declare(strict_types=1);

$pageTitle = ($pageTitle ?? 'Portal') . ' | ' . config('app.name');
$prefs     = prefs();
?>
<!DOCTYPE html>
<html lang="en" <?= html_attributes() ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <meta name="description" content="City Health Office (CHO) Portal - Islamic City of Marawi, BARMM. Universal healthcare, epidemiological surveillance, and public clinical services.">
    <title><?= e($pageTitle) ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= asset('images/cho-logo.png') ?>">

    <!-- Anti-Flicker Inline Script: syncs theme and font size instantly before CSS renders -->
    <script>
      (function() {
        try {
          const raw = document.cookie.match(/(^|;)\s*cho_prefs\s*=\s*([^;]+)/);
          if (raw) {
            const p = JSON.parse(decodeURIComponent(raw[2]));
            if (p.theme === 'dark') {
              document.documentElement.classList.add('dark');
            } else {
              document.documentElement.classList.remove('dark');
            }
            if (p.fontSize) document.documentElement.setAttribute('data-font-size', p.fontSize);
          } else {
            document.documentElement.classList.remove('dark');
          }
        } catch (e) {}
      })();
    </script>

    <!-- Vite Scripts & CSS (hot in dev, hashed in prod) -->
    <?= vite_tags() ?>
</head>
<body id="cho-body-root" class="bg-gray-50 text-gray-900 dark:bg-slate-950 dark:text-gray-100 antialiased min-h-screen selection:bg-cho-blue-800 selection:text-white">
