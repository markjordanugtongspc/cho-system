<?php
/**
 * CHO MARAWI PORTAL - PREFERENCES & DISPLAY SETTINGS
 * Location: frontend/pages/settings/index.php
 *
 * Allows healthcare personnel to configure:
 * - Theme (Light / Dark / System)
 * - Accessible Font Size (sm / md / lg / xl)
 * - Dropdowns default toggle behavior (ON / OFF)
 * - Sound effects & notifications
 * - Saved login credentials management (Remember-Me cookie purge)
 */

declare(strict_types=1);

require dirname(dirname(dirname(__DIR__))) . '/backend/bootstrap.php';

$currentUser = \App\Services\AuthService::user() ?? [
    'id'         => 1,
    'username'   => 'dr_norhata',
    'first_name' => 'Dr. Norhata',
    'last_name'  => 'Alonto',
    'full_name'  => 'Dr. Norhata Alonto',
    'role'       => 'city_health_officer',
    'role_name'  => 'City Health Officer'
];

$pageTitle  = 'Portal Settings & Accessibility';
$activePage = 'settings';
$prefs      = prefs();

require BASE_PATH . '/frontend/layouts/head.php';
require BASE_PATH . '/frontend/layouts/sidebar.php';
?>

<!-- Main Content Canvas -->
<div id="cho-settings-main-canvas" class="p-4 sm:ml-64 pt-20 transition-all duration-200">
  
  <div id="cho-settings-inner-container" class="max-w-4xl mx-auto space-y-8 pb-12">

    <!-- Header Section -->
    <div id="cho-settings-header-box" class="border-b border-gray-200 dark:border-slate-800 pb-5">
      <h1 id="cho-settings-main-heading" class="text-2xl sm:text-3xl font-extrabold font-heading text-gray-900 dark:text-white tracking-tight">
        Portal Settings & Workstation Preferences
      </h1>
      <p id="cho-settings-subheading" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Customize visual contrast, accessible font sizing, navigation behaviors, and local workstation credentials.
      </p>
    </div>

    <!-- Section 1: Visual Theme & Appearance -->
    <div id="cho-settings-card-theme" class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xs space-y-6">
      
      <div id="cho-settings-theme-header" class="flex items-center gap-3">
        <div id="cho-theme-icon-wrap" class="w-10 h-10 rounded-xl bg-cho-blue-50 dark:bg-cho-blue-900/40 text-cho-blue-800 dark:text-cho-blue-300 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
        </div>
        <div>
          <h2 id="cho-settings-theme-title" class="text-base font-bold font-heading text-gray-900 dark:text-white">Visual Mode & Contrast</h2>
          <p id="cho-settings-theme-desc" class="text-xs text-gray-500 dark:text-gray-400">Select your preferred color scheme. Synchronized across sessions via cookies.</p>
        </div>
      </div>

      <div id="cho-theme-options-grid" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Light Mode Button -->
        <button id="cho-theme-btn-light" type="button" data-theme-btn="light" class="p-4 rounded-2xl border text-left transition hover:shadow-xs focus:outline-none <?= $prefs['theme'] === 'light' ? 'border-cho-blue-800 bg-cho-blue-50/50 dark:bg-cho-blue-900/20 ring-2 ring-cho-blue-800' : 'border-gray-200 dark:border-slate-800 hover:border-gray-300' ?>">
          <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-gray-900 dark:text-white">Clean Light</span>
            <span class="w-4 h-4 rounded-full bg-amber-400"></span>
          </div>
          <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">High clarity for daytime clinical documentation.</p>
        </button>

        <!-- Dark Mode Button -->
        <button id="cho-theme-btn-dark" type="button" data-theme-btn="dark" class="p-4 rounded-2xl border text-left transition hover:shadow-xs focus:outline-none <?= $prefs['theme'] === 'dark' ? 'border-cho-blue-800 bg-cho-blue-50/50 dark:bg-cho-blue-900/20 ring-2 ring-cho-blue-800' : 'border-gray-200 dark:border-slate-800 hover:border-gray-300' ?>">
          <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-gray-900 dark:text-white">Hospital Dark</span>
            <span class="w-4 h-4 rounded-full bg-slate-900 border border-slate-700"></span>
          </div>
          <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Sleek dark interface reduced eye strain in night shifts.</p>
        </button>

        <!-- System Mode Button -->
        <button id="cho-theme-btn-system" type="button" data-theme-btn="system" class="p-4 rounded-2xl border text-left transition hover:shadow-xs focus:outline-none <?= $prefs['theme'] === 'system' ? 'border-cho-blue-800 bg-cho-blue-50/50 dark:bg-cho-blue-900/20 ring-2 ring-cho-blue-800' : 'border-gray-200 dark:border-slate-800 hover:border-gray-300' ?>">
          <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-gray-900 dark:text-white">Auto System</span>
            <span class="w-4 h-4 rounded-full bg-gradient-to-r from-amber-400 to-slate-800"></span>
          </div>
          <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Matches your operating system settings automatically.</p>
        </button>

      </div>

    </div>

    <!-- Section 2: Accessible Font Size Sizing (Requirement: Settings font scaler) -->
    <div id="cho-settings-card-typography" class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xs space-y-6">
      
      <div id="cho-settings-type-header" class="flex items-center gap-3">
        <div id="cho-type-icon-wrap" class="w-10 h-10 rounded-xl bg-cho-gold-50 dark:bg-cho-gold-950/60 text-cho-gold-700 dark:text-cho-gold-400 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 5v14m6-7h6m-3 0v7"/></svg>
        </div>
        <div>
          <h2 id="cho-settings-type-title" class="text-base font-bold font-heading text-gray-900 dark:text-white">Accessible Font Sizing</h2>
          <p id="cho-settings-type-desc" class="text-xs text-gray-500 dark:text-gray-400">Designed for 35+ senior physicians and healthcare workers. Instantly scales typography across the entire system.</p>
        </div>
      </div>

      <div id="cho-font-size-options" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        
        <button id="cho-font-size-option-sm" type="button" data-font-size-btn="sm" class="p-3 text-center rounded-xl border border-gray-200 dark:border-slate-800 hover:border-cho-blue-800 transition">
          <span class="block text-xs font-bold text-gray-900 dark:text-white">Small (14px)</span>
          <span class="block text-[11px] text-gray-500 mt-1">Compact views</span>
        </button>

        <button id="cho-font-size-option-md" type="button" data-font-size-btn="md" class="p-3 text-center rounded-xl border border-gray-200 dark:border-slate-800 hover:border-cho-blue-800 transition">
          <span class="block text-sm font-bold text-gray-900 dark:text-white">Medium (16px)</span>
          <span class="block text-[11px] text-gray-500 mt-1">Default balanced</span>
        </button>

        <button id="cho-font-size-option-lg" type="button" data-font-size-btn="lg" class="p-3 text-center rounded-xl border border-gray-200 dark:border-slate-800 hover:border-cho-blue-800 transition">
          <span class="block text-base font-bold text-gray-900 dark:text-white">Large (18px)</span>
          <span class="block text-[11px] text-gray-500 mt-1">Enhanced reading</span>
        </button>

        <button id="cho-font-size-option-xl" type="button" data-font-size-btn="xl" class="p-3 text-center rounded-xl border border-gray-200 dark:border-slate-800 hover:border-cho-blue-800 transition">
          <span class="block text-lg font-bold text-gray-900 dark:text-white">Extra Large (20px)</span>
          <span class="block text-[11px] text-gray-500 mt-1">Maximum accessibility</span>
        </button>

      </div>

      <!-- Live Text Preview -->
      <div id="cho-font-preview-box" class="p-4 rounded-2xl bg-gray-50 dark:bg-slate-800/60 border border-gray-200 dark:border-slate-700">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Live Text Scale Preview:</span>
        <p id="cho-font-preview-text" class="text-gray-800 dark:text-gray-200 font-medium">
          "The City Health Office of Marawi delivers Universal Health Care across 96 barangays and transitional resettlement sites with patient dignity and cultural integration."
        </p>
      </div>

    </div>

    <!-- Section 3: Navigation & Dropdown Behaviors (Requirement: Dropdown toggle default ON) -->
    <div id="cho-settings-card-navigation" class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xs space-y-5">
      
      <div id="cho-settings-nav-header" class="flex items-center gap-3">
        <div id="cho-nav-icon-wrap" class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
        </div>
        <div>
          <h2 id="cho-settings-nav-title" class="text-base font-bold font-heading text-gray-900 dark:text-white">Sidebar & Menu Preferences</h2>
          <p id="cho-settings-nav-desc" class="text-xs text-gray-500 dark:text-gray-400">Configure how multi-level service menus behave on navigation.</p>
        </div>
      </div>

      <div id="cho-nav-toggles-list" class="space-y-4 pt-2">
        
        <!-- Dropdowns Open by Default Toggle -->
        <div id="cho-toggle-dropdowns-row" class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/70 dark:bg-slate-800/40">
          <div>
            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Expand Clinical Service Dropdowns by Default</span>
            <span class="block text-xs text-gray-500 dark:text-gray-400">Keep Outpatient, MCHN, and Surveillance sub-menus open for fast access without clicking.</span>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input id="cho-pref-dropdowns-toggle" type="checkbox" class="sr-only peer" <?= $prefs['dropdownsOpen'] ? 'checked' : '' ?> onchange="CHO.Storage.preferences.set('dropdownsOpen', this.checked); CHO.Notifications.toast('Dropdown preference saved to cookies.', 'success');">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-cho-blue-800"></div>
          </label>
        </div>

        <!-- Synthesized Audio Chimes Toggle -->
        <div id="cho-toggle-audio-row" class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/70 dark:bg-slate-800/40">
          <div>
            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Synthesized Audio Notifications</span>
            <span class="block text-xs text-gray-500 dark:text-gray-400">Play pleasant audio confirmation chimes for patient admission, alerts, and saves.</span>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input id="cho-pref-sounds-toggle" type="checkbox" class="sr-only peer" checked onchange="CHO.Storage.preferences.set('soundEffects', this.checked); CHO.Notifications.playSound('success');">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-cho-blue-800"></div>
          </label>
        </div>

      </div>

    </div>

    <!-- Section 4: Workstation Credentials & Remember-Me Purge -->
    <div id="cho-settings-card-security" class="p-6 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xs space-y-5">
      
      <div id="cho-settings-sec-header" class="flex items-center gap-3">
        <div id="cho-sec-icon-wrap" class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div>
          <h2 id="cho-settings-sec-title" class="text-base font-bold font-heading text-gray-900 dark:text-white">Workstation Credentials & Privacy (RA 10173)</h2>
          <p id="cho-settings-sec-desc" class="text-xs text-gray-500 dark:text-gray-400">Manage 'Remember Me' cookies stored on this local terminal.</p>
        </div>
      </div>

      <div id="cho-credentials-management-box" class="p-4 rounded-2xl border border-amber-200 bg-amber-50/60 dark:bg-amber-950/30 dark:border-amber-900/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span class="block text-sm font-semibold text-amber-950 dark:text-amber-200">Local 'Remember Me' Workstation Cookies</span>
          <p class="text-xs text-amber-800 dark:text-amber-400 mt-0.5">
            If you signed in on a shared terminal, purge stored login cookies to prevent unauthorized sign-in.
          </p>
        </div>
        <button id="cho-btn-purge-credentials" type="button" class="shrink-0 px-4 py-2 text-xs font-semibold rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-xs transition" onclick="CHO.Storage.credentials.clear(); CHO.Notifications.toast('Stored workstation credentials have been completely wiped.', 'success');">
          Wipe Remembered Login
        </button>
      </div>

    </div>

  </div>

</div>

<?php require BASE_PATH . '/frontend/layouts/footer.php'; ?>
