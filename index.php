<?php
/**
 * CHO MARAWI PORTAL - LOGIN & AUTHENTICATION GATEWAY
 * Root entrypoint: index.php
 *
 * Architecture:
 * - Left Grid: Official CHO Marawi Healthcare Staff team imagery with UHC BARMM mission
 * - Right Grid: Frameless 35+ mature government login form (medium text & inputs, Poppins & Averta font)
 * - Remember-Me: Automatically pre-fills username & password from cookies when previously checked
 * - Every single container, button, SVG, and input has a unique semantic ID.
 */

declare(strict_types=1);

require __DIR__ . '/backend/bootstrap.php';

// Redirect if already authenticated
if (\App\Services\AuthService::check()) {
    redirect('/dashboard');
}

$pageTitle = 'Sign In | City Health Office - Marawi City';
$prefs     = prefs();
?>
<!DOCTYPE html>
<html lang="en" <?= html_attributes() ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <meta name="description" content="City Health Office (CHO) - Islamic City of Marawi, BARMM. Official healthcare personnel authentication portal.">
    <title><?= e($pageTitle) ?></title>

    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= asset('images/cho-logo.png') ?>">

    <!-- Anti-flicker Theme Sync -->
    <script>
      (function() {
        try {
          const raw = document.cookie.match(/(^|;)\s*cho_prefs\s*=\s*([^;]+)/);
          if (raw) {
            const p = JSON.parse(decodeURIComponent(raw[2]));
            // Only apply dark if user explicitly chose 'dark' (not system)
            if (p.theme === 'dark') {
              document.documentElement.classList.add('dark');
            } else {
              document.documentElement.classList.remove('dark');
            }
            if (p.fontSize) document.documentElement.setAttribute('data-font-size', p.fontSize);
          } else {
            // Default is strictly lightmode
            document.documentElement.classList.remove('dark');
          }
        } catch (e) {}
      })();
    </script>

    <!-- Vite Bundled Scripts & CSS -->
    <?= vite_tags() ?>
</head>
<body id="cho-login-page-body" class="bg-zinc-100 text-zinc-900 dark:bg-slate-950 dark:text-zinc-100 min-h-screen selection:bg-cho-blue-800 selection:text-white antialiased">

  <div id="cho-login-root-container" class="min-h-screen flex flex-col justify-between">

    <!-- Master Two-Column Grid -->
    <main id="cho-login-main-grid" class="flex-1 grid grid-cols-1 lg:grid-cols-12 min-h-screen">
      
      <!-- =================================================================
           LEFT GRID: CHO MARAWI STAFF IMAGERY & UNIVERSAL HEALTHCARE MANDATE
           ================================================================= -->
      <section id="cho-login-left-panel" class="lg:col-span-7 relative hidden lg:flex flex-col justify-between p-6 sm:p-8 lg:p-10 overflow-hidden bg-cho-blue-950 text-white">
        
        <!-- Flowbite Carousel Background Slider without controls or indicators -->
        <div id="landing-carousel" class="absolute inset-0 w-full h-full z-0" data-carousel="slide">
          <div class="relative h-full w-full overflow-hidden">
            <?php
            $landingDir = __DIR__ . '/frontend/assets/images/landing';
            $landingImages = glob($landingDir . '/*.webp');
            if (!empty($landingImages)) {
                natsort($landingImages);
                foreach ($landingImages as $index => $imgPath) {
                    $filename = basename($imgPath);
                    $webPath = asset('images/landing/' . $filename);
                    ?>
                    <div class="hidden duration-1000 ease-in-out transition-transform" data-carousel-item>
                      <img src="<?= e($webPath) ?>" class="absolute block w-full h-full object-cover object-center" alt="City Health Office Facility <?= $index + 1 ?>" />
                    </div>
                    <?php
                }
            }
            ?>
          </div>
        </div>

        <!-- Brand Tint Overlays for Rich Contrast and Brand Wash -->
        <div id="cho-login-left-overlay-1" class="absolute inset-0 bg-cho-blue-950/50 backdrop-blur-[0.5px] pointer-events-none z-10"></div>
        <div id="cho-login-left-overlay-2" class="absolute inset-0 bg-gradient-to-t from-cho-blue-950/80 via-transparent to-cho-blue-950/40 pointer-events-none z-10"></div>

        <!-- Brand Header: Upper-Left Placed -->
        <div class="relative z-20 text-white max-w-2xl space-y-3">
            <div class="flex items-center gap-3.5">
                <!-- Circular logo frame -->
                <div
                    class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 rounded-full overflow-hidden ring-2 ring-white/60 shadow-xl flex items-center justify-center bg-transparent transition transform hover:scale-105 duration-300">
                    <img src="<?= asset('images/cho-logo.png') ?>"
                        alt="City Health Office Logo"
                        class="w-full h-full object-cover scale-105 rounded-full drop-shadow-sm" />
                </div>
                <div class="space-y-0.5">
                    <!-- Bolder All-Caps CITY HEALTH OFFICE with increased font size -->
                    <p class="text-base sm:text-lg lg:text-xl font-black tracking-widest uppercase text-white drop-shadow-md">
                        CITY HEALTH OFFICE
                    </p>
                    <p class="text-xs sm:text-sm text-white/90 font-medium tracking-wide drop-shadow-sm">
                        Ministry of Health - BARMM • Islamic City of Marawi
                    </p>
                </div>
            </div>
        </div>

        <!-- Center Highlight: Title & Mission Subheader -->
        <div id="cho-login-hero-center" class="relative z-20 my-auto py-12 max-w-2xl mx-auto text-center">
            <h1 id="cho-login-hero-title" class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight font-heading leading-tight drop-shadow-[0_4px_16px_rgba(0,0,0,0.7)]">
                <span class="bg-gradient-to-r from-cho-gold-300 via-amber-200 to-cho-gold-400 bg-clip-text text-transparent">CHO SYSTEM</span>
                <span class="bg-gradient-to-r from-cho-blue-300 via-cho-blue-400 to-cho-blue-500 bg-clip-text text-transparent">PORTAL</span>
            </h1>
            <p id="cho-login-hero-subtitle" class="mt-4 text-base sm:text-lg text-slate-100 font-normal leading-relaxed drop-shadow-md max-w-lg mx-auto">
                Unified public health intelligence and clinical services for Marawi City.
            </p>
        </div>

        <!-- Bottom Footer Branding / Legal Badge -->
        <div id="cho-login-left-bottom-bar" class="relative z-20 flex items-center justify-between text-xs text-white/70 pt-6 border-t border-white/10">
            <span>Republic of the Philippines • BARMM</span>
            <span>Universal Health Care (UHC) Program</span>
        </div>

      </section>

      <!-- =================================================================
           RIGHT GRID: 35+ YEARS OLD MATURE, UNBOXED FORM DESIGN
           (Generous sizing, high legibility, larger touch targets & fonts)
           ================================================================= -->
      <section id="cho-login-right-panel" class="lg:col-span-5 flex flex-col justify-center px-6 sm:px-12 md:px-16 lg:px-20 py-16 lg:py-24 bg-zinc-100 transition-colors">
        
        <div id="cho-login-form-area" class="w-full max-w-lg mx-auto text-zinc-900">
          
          <!-- Official Header without duplicate logo -->
          <div id="cho-login-header-group" class="mb-8 text-left">
            <h2 id="cho-login-main-title" class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight text-zinc-900">
              Personnel Sign In
            </h2>
            <p id="cho-login-subtitle" class="mt-2 text-base text-zinc-600 font-normal">
              City Health Office — Islamic City of Marawi, BARMM
            </p>
          </div>

          <!-- Error Alert Banner (Hidden by default, shown via JS) -->
          <div id="cho-login-error-alert" class="hidden mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3 transition-all" role="alert">
            <svg id="cho-login-error-icon" class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span id="cho-login-error-message" class="font-medium"></span>
          </div>

          <!-- The Mature 35+ Form (Clean, frameless, legible, medium/large size for senior staff) -->
          <form id="cho-login-form" class="space-y-6" method="POST" autocomplete="on">
            
            <!-- Username Field -->
            <div id="cho-login-username-group">
              <label id="cho-login-username-label" for="cho-login-username-input" class="block mb-2.5 text-base font-semibold text-zinc-900">
                Staff Username or Employee ID
              </label>
              <div id="cho-login-username-wrap" class="relative">
                <div id="cho-login-username-icon-box" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-zinc-400">
                  <svg id="cho-login-username-svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <input 
                  type="text" 
                  id="cho-login-username-input" 
                  name="username" 
                  class="cho-input-field block w-full pl-12 pr-4 py-3.5 text-base text-zinc-900 bg-white border border-zinc-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-cho-blue-800 focus:border-cho-blue-800 transition placeholder-zinc-400" 
                  placeholder="e.g. admin" 
                  required 
                  autofocus 
                  autocomplete="username"
                >
              </div>
            </div>

            <!-- Password Field with Show/Hide Toggle -->
            <div id="cho-login-password-group">
              <div id="cho-login-password-header-wrap" class="flex items-center justify-between mb-2.5">
                <label id="cho-login-password-label" for="cho-login-password-input" class="text-base font-semibold text-zinc-900">
                  Account Password
                </label>
                <a id="cho-login-forgot-link" href="#" class="text-sm font-semibold text-cho-blue-800 hover:underline">
                  Forgot Password?
                </a>
              </div>
              <div id="cho-login-password-wrap" class="relative">
                <div id="cho-login-password-icon-box" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-zinc-400">
                  <svg id="cho-login-password-svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input 
                  type="password" 
                  id="cho-login-password-input" 
                  name="password" 
                  class="cho-input-field block w-full pl-12 pr-12 py-3.5 text-base text-zinc-900 bg-white border border-zinc-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-cho-blue-800 focus:border-cho-blue-800 transition placeholder-zinc-400" 
                  placeholder="••••••••••••" 
                  required 
                  autocomplete="current-password"
                >
                <button 
                  type="button" 
                  id="cho-login-toggle-password-btn" 
                  class="absolute inset-y-0 right-0 flex items-center pr-4 text-zinc-400 hover:text-zinc-600 focus:outline-none transition" 
                  aria-label="Toggle password visibility"
                >
                  <svg id="cho-login-eye-open-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <svg id="cho-login-eye-closed-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </button>
              </div>
            </div>

            <!-- Priority Requirement: Remember Me Checkbox (Stores username & password in cookie) -->
            <div id="cho-login-options-row" class="flex items-center justify-between pt-1">
              <div id="cho-login-remember-wrap" class="flex items-center">
                <input 
                  id="cho-login-remember-checkbox" 
                  name="remember" 
                  type="checkbox" 
                  class="w-5 h-5 text-cho-blue-800 bg-white border-zinc-300 rounded-md focus:ring-cho-blue-800 focus:ring-2 cursor-pointer"
                >
                <label id="cho-login-remember-label" for="cho-login-remember-checkbox" class="ml-2.5 text-sm sm:text-base text-zinc-800 select-none cursor-pointer font-medium">
                  Remember my credentials on this workstation
                </label>
              </div>
            </div>

            <!-- Submit Button (Primary CHO Navy with Gold accent - Enlarged for 35+ users) -->
            <div id="cho-login-submit-group" class="pt-3">
              <button 
                type="submit" 
                id="cho-login-submit-btn" 
                class="w-full flex items-center justify-center py-4 px-6 text-base sm:text-lg font-bold rounded-xl text-white bg-cho-blue-800 hover:bg-cho-blue-900 hover:opacity-90 active:opacity-85 focus:ring-4 focus:ring-cho-blue-300 shadow-md hover:shadow-lg transition-all duration-200 active:scale-[0.99] cursor-pointer"
              >
                <span id="cho-login-submit-text">Sign In to CHO Portal</span>
                <svg id="cho-login-submit-spinner" class="hidden w-5 h-5 ml-2.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
              </button>
            </div>

          </form>

          <!-- Security Notice & Assistance -->
          <div id="cho-login-security-notice" class="mt-8 pt-6 border-t border-zinc-200 text-center">
            <button type="button" id="cho-login-security-badge" class="inline-flex items-center gap-2 text-xs sm:text-sm text-zinc-500 hover:text-cho-blue-800 transition-colors cursor-pointer group">
              <svg id="cho-login-shield-svg" class="w-4 h-4 text-cho-gold-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
              <span class="underline decoration-dotted underline-offset-2">Protected by Rate Limiting & Audit Trail (RA 10173)</span>
            </button>
          </div>

        </div>

      </section>

    </main>

  </div>

</body>
</html>
