/**
 * CHO MARAWI CITY PORTAL - MASTER JAVASCRIPT ENTRYPOINT
 * Stack: Vanilla ES Modules + Vite + Tailwind CSS v4 + Flowbite v4 + ApexCharts
 *
 * This file imports all modules and automatically boots them on DOM ready.
 * It is bundled via Vite into a single production script or served directly
 * via Vite Dev Server during development with zero manual imports in PHP!
 */

// 1) Import Self-Hosted Fonts, Stylesheet & Flowbite bundle
import '@fontsource/poppins/300.css';
import '@fontsource/poppins/400.css';
import '@fontsource/poppins/500.css';
import '@fontsource/poppins/600.css';
import '@fontsource/poppins/700.css';
import '@fontsource/poppins/800.css';
import '@fontsource/poppins/400-italic.css';
import '@fontsource/poppins/600-italic.css';
import '@fontsource/outfit/500.css';
import '@fontsource/outfit/600.css';
import '@fontsource/outfit/700.css';
import '@fontsource/outfit/800.css';
import '@fontsource/outfit/900.css';
import '../css/style.css';
import 'flowbite';

// 2) Import all modular sub-scripts
import { Storage } from './modules/storage.js';
import { Api } from './modules/api.js';
import { Theme } from './modules/theme.js';
import { Notifications } from './modules/notifications.js';
import { Auth } from './modules/auth.js';
import { Sidebar } from './modules/sidebar.js';
import { Charts } from './modules/charts.js';
import { Components } from './modules/components.js';
import { AppAnimations } from './modules/animations.js';
import { Drawers, Drawer } from './modules/drawer.js';
import { Tables } from './modules/tables.js';

// 3) Expose global namespace for PHP inline helpers or console debugging
window.CHO = {
  Storage,
  Api,
  Theme,
  Notifications,
  Auth,
  Sidebar,
  Charts,
  Components,
  Drawer,
  Drawers,
  Tables,
  Animations: AppAnimations,
  version: '1.0.0'
};

// 4) Flowbite Image Loading Skeleton Helper (for all lazy-loaded images)
function initImageSkeletons() {
  document.querySelectorAll('img[loading="lazy"]').forEach((img) => {
    if (img.closest('#cho-quick-access-grid, #cho-systems-grid, #landing-carousel')) return;
    if ((img.getAttribute('src') || '').toLowerCase().endsWith('.svg')) return;
    if (img.complete && img.naturalWidth > 0) return;
    const parent = img.parentElement;
    if (!parent) return;

    if (getComputedStyle(parent).position === 'static') {
      parent.classList.add('relative');
    }

    const skeleton = document.createElement('div');
    skeleton.setAttribute('role', 'status');
    skeleton.dataset.choImgSkeleton = '1';
    skeleton.className = 'absolute inset-0 z-[2] animate-pulse bg-slate-200/75 dark:bg-slate-700/75 flex items-center justify-center pointer-events-none rounded-[inherit]';
    skeleton.innerHTML = `
      <svg class="w-6 h-6 text-slate-400/70" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
        <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
      </svg>
      <span class="sr-only">Loading image...</span>
    `;
    parent.insertBefore(skeleton, img);

    const cleanup = () => {
      skeleton.remove();
    };
    img.addEventListener('load', cleanup, { once: true });
    img.addEventListener('error', cleanup, { once: true });
  });
}

// 5) Lifecycle Bootstrapper
document.addEventListener('DOMContentLoaded', () => {
  initImageSkeletons();
  // Initialize Theme & Typography (Light/Dark mode & Font Size)
  Theme.init();

  // Initialize Authentication & Login form (with cookie remember-me prefill)
  Auth.init();

  // Initialize Sidebar & Navigation Drawer
  Sidebar.init();

  // Initialize Flowbite Right Drawer for NIP & Excel operations
  Drawer.init();

  // Initialize ApexCharts if chart containers exist on the page
  Charts.init();

  // Initialize Flowbite + ExcelJS Data Tables
  Tables.init();

  // Initialize UI micro-interactions & hover animations
  AppAnimations.init();

  console.log('[CHO Marawi Portal] Client application successfully mounted.');
});
