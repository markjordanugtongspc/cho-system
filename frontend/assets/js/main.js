/**
 * CHO MARAWI CITY PORTAL - MASTER JAVASCRIPT ENTRYPOINT
 * Stack: Vanilla ES Modules + Vite + Tailwind CSS v4 + Flowbite v4 + ApexCharts
 *
 * This file imports all modules and automatically boots them on DOM ready.
 * It is bundled via Vite into a single production script or served directly
 * via Vite Dev Server during development with zero manual imports in PHP!
 */

// 1) Import Stylesheet & Flowbite bundle
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
  Animations: AppAnimations,
  version: '1.0.0'
};

// 4) Lifecycle Bootstrapper
document.addEventListener('DOMContentLoaded', () => {
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

  console.log('[CHO Marawi Portal] Client application successfully mounted.');
});
