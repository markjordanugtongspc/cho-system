/**
 * CHO MARAWI PORTAL - THEME & TYPOGRAPHY MODULE
 * Controls:
 * - Theme: Light / Dark / System modes with immediate DOM sync
 * - Font Size: Accessible scaling ('sm' | 'md' | 'lg' | 'xl')
 * - Automatic persistence to cookie (for zero-flash PHP SSR) & localStorage
 */

import { Storage } from './storage.js';

export const Theme = {
  MEDIA_QUERY: '(prefers-color-scheme: dark)',

  init() {
    // If no explicit preference has been saved yet or if it was unset, ensure 'light' is preferred
    const currentTheme = Storage.preferences.get('theme') || 'light';
    this.applyTheme(currentTheme);
    this.applyFontSize(Storage.preferences.get('fontSize') || 'md');

    // Watch OS color scheme changes only if explicitly set to 'system'
    window.matchMedia(this.MEDIA_QUERY).addEventListener('change', (e) => {
      if (Storage.preferences.get('theme') === 'system') {
        this.updateDomTheme(e.matches ? 'dark' : 'light', 'system');
      }
    });

    // Bind theme toggle buttons if present on the page
    this.bindDomListeners();
  },

  setTheme(theme) {
    if (!['light', 'dark', 'system'].includes(theme)) return;
    Storage.preferences.set('theme', theme);
    this.applyTheme(theme);
  },

  getTheme() {
    return Storage.preferences.get('theme');
  },

  applyTheme(theme) {
    let effectiveTheme = theme;
    if (theme === 'system') {
      const prefersDark = window.matchMedia(this.MEDIA_QUERY).matches;
      effectiveTheme = prefersDark ? 'dark' : 'light';
    }
    this.updateDomTheme(effectiveTheme, theme);
  },

  updateDomTheme(effectiveTheme, rawSetting) {
    const root = document.documentElement;
    if (effectiveTheme === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
    root.setAttribute('data-theme', rawSetting);

    // Update active state on any theme switchers in UI
    document.querySelectorAll('[data-theme-btn]').forEach((btn) => {
      const btnTheme = btn.getAttribute('data-theme-btn');
      if (btnTheme === rawSetting) {
        btn.classList.add('active', 'border-cho-blue-600', 'bg-cho-blue-50', 'dark:bg-cho-blue-900/40');
      } else {
        btn.classList.remove('active', 'border-cho-blue-600', 'bg-cho-blue-50', 'dark:bg-cho-blue-900/40');
      }
    });
  },

  toggle() {
    const current = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    this.setTheme(next);
    return next;
  },

  /* ------------------------------------------------------------------
   * Font Size Scaling ('sm', 'md', 'lg', 'xl')
   * ------------------------------------------------------------------ */
  setFontSize(size) {
    if (!['sm', 'md', 'lg', 'xl'].includes(size)) return;
    Storage.preferences.set('fontSize', size);
    this.applyFontSize(size);
  },

  getFontSize() {
    return Storage.preferences.get('fontSize');
  },

  applyFontSize(size) {
    document.documentElement.setAttribute('data-font-size', size);

    // Update active state in UI buttons
    document.querySelectorAll('[data-font-size-btn]').forEach((btn) => {
      const btnSize = btn.getAttribute('data-font-size-btn');
      if (btnSize === size) {
        btn.classList.add('active', 'ring-2', 'ring-cho-blue-600');
      } else {
        btn.classList.remove('active', 'ring-2', 'ring-cho-blue-600');
      }
    });
  },

  bindDomListeners() {
    // Quick toggle button for navbar or header
    document.addEventListener('click', (e) => {
      const toggleBtn = e.target.closest('#cho-theme-toggle-btn');
      if (toggleBtn) {
        this.toggle();
      }

      const explicitThemeBtn = e.target.closest('[data-theme-btn]');
      if (explicitThemeBtn) {
        const targetTheme = explicitThemeBtn.getAttribute('data-theme-btn');
        if (targetTheme) this.setTheme(targetTheme);
      }

      const fontSizeBtn = e.target.closest('[data-font-size-btn]');
      if (fontSizeBtn) {
        const targetSize = fontSizeBtn.getAttribute('data-font-size-btn');
        if (targetSize) this.setFontSize(targetSize);
      }
    });
  }
};
