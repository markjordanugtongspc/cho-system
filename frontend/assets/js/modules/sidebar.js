/**
 * CHO MARAWI PORTAL - SIDEBAR & NAVIGATION MODULE
 * Controls:
 * - Mobile sidebar drawer toggle & navigation controls
 * - Active navigation item state highlighting
 * - Multi-level dropdown menus with persistent state in preferences cookie
 *
 * Architecture: Object-Oriented (OOP) format with subchild functions and START/END comments.
 */

import { Storage } from './storage.js';

// START - NavigationHighlighter: Highlights current active route in the sidebar navigation
class NavigationHighlighter {
  constructor() {
    this.currentPath = window.location.pathname.toLowerCase().replace(/\/index\.php$/, '');
  }

  /**
   * Applies active styling to sidebar navigation items based on current URL path
   */
  highlightActive() {
    this.clearActive();

    const path = this.currentPath.replace(/\/$/, '');
    const isNip2Dashboard = path.includes('/nip2/dashboard');
    const isNip2Population = path.includes('/nip2/newborns');
    const isNipDashboard = path.includes('/nip/dashboard');
    const isNipPopulation = path.includes('/nip/population');
    const isMainHome = (path.includes('/dashboard') && !path.includes('/nip') && !path.includes('/nip2')) ||
      path.endsWith('/cho-system') ||
      path.endsWith('/cho-system/');

    const routeMap = [
      { match: isNip2Dashboard, linkId: 'cho-sidebar-link-nip-dash2', iconId: 'cho-sidebar-icon-nip-dash2' },
      { match: isNip2Population, linkId: 'cho-sidebar-link-nip-population2', iconId: 'cho-sidebar-icon-nip-population2' },
      { match: isNipDashboard, linkId: 'cho-sidebar-link-nip-dash', iconId: 'cho-sidebar-icon-nip-dash' },
      { match: isNipPopulation, linkId: 'cho-sidebar-link-nip-population', iconId: 'cho-sidebar-icon-nip-population' },
      { match: isMainHome, linkId: 'cho-sidebar-link-dashboard', iconId: 'cho-sidebar-icon-dashboard' }
    ];

    const activeRoute = routeMap.find((r) => r.match);
    if (!activeRoute) return;

    const link = document.getElementById(activeRoute.linkId);
    const icon = document.getElementById(activeRoute.iconId);
    if (!link) return;

    link.classList.remove('text-white/80', 'text-white/85', 'text-white/90', 'text-white/95', 'hover:bg-white/10', 'hover:text-white');
    link.classList.add('bg-white', 'text-cho-blue-900', 'font-bold', 'shadow-xs');
    if (icon) {
      icon.classList.remove('text-white/75', 'text-white/80', 'text-white/85', 'text-white/90', 'group-hover:text-white');
      icon.classList.add('text-cho-blue-900');
    }
    link.setAttribute('aria-current', 'page');
  }

  /**
   * Clears active highlights across all sidebar links
   */
  clearActive() {
    document.querySelectorAll('#cho-sidebar-nav-list a').forEach((link) => {
      link.classList.remove('bg-white', 'text-cho-blue-900', 'font-bold', 'shadow-xs');
      link.removeAttribute('aria-current');
      const icon = link.querySelector('svg');
      if (icon) {
        icon.classList.remove('text-cho-blue-900');
      }
    });
  }
}
// END - NavigationHighlighter

// START - ActiveRouteDetector: Subchild detector ensuring robust path & active-state recognition
class ActiveRouteDetector {
  /**
   * Determines if the current window location matches a target route identifier
   * @param {string} routeName - e.g. 'dashboard', 'kanban', 'inbox'
   * @returns {boolean}
   */
  static isCurrentRoute(routeName) {
    const path = window.location.pathname.toLowerCase();
    return path.includes(`/${routeName.toLowerCase()}`);
  }

  /**
   * Automatically scans all nav links and dynamically applies active state
   * matching any route segment in current path
   */
  static applyDynamicActiveState() {
    const navList = document.getElementById('cho-sidebar-nav-list');
    if (!navList) return;

    const currentPath = window.location.pathname.toLowerCase().replace(/\/index\.php$/, '').replace(/\/$/, '');
    const isNipPage = currentPath.includes('/nip/dashboard');

    const homeLink = document.getElementById('cho-sidebar-link-dashboard');
    const nipLink = document.getElementById('cho-sidebar-link-nip-dash');

    if (isNipPage) {
      // Deactivate Homepage link
      if (homeLink) {
        homeLink.classList.remove('bg-neutral-tertiary', 'text-fg-brand', 'font-bold', 'shadow-xs');
        homeLink.classList.add('text-body');
        homeLink.removeAttribute('aria-current');
        const hIcon = homeLink.querySelector('svg');
        if (hIcon) hIcon.classList.remove('text-fg-brand');
      }

      // Activate NIP Dashboard subitem
      if (nipLink) {
        nipLink.classList.remove('text-body', 'hover:bg-neutral-tertiary', 'hover:text-fg-brand');
        nipLink.classList.add('bg-neutral-tertiary', 'text-fg-brand', 'font-bold', 'shadow-xs');
        nipLink.setAttribute('aria-current', 'page');
        const nIcon = document.getElementById('cho-sidebar-icon-nip-dash');
        if (nIcon) {
          nIcon.classList.remove('text-slate-400', 'group-hover:text-fg-brand');
          nIcon.classList.add('text-fg-brand');
        }
      }
    }
  }
}
// END - ActiveRouteDetector

// START - DrawerManager: Manages mobile off-canvas drawer open/close behavior and backdrop states
class DrawerManager {
  constructor() {
    this.sidebarId = 'logo-sidebar';
    this.mobileOpenBtnId = 'cho-sidebar-mobile-open-btn';
    this.mobileCloseBtnId = 'cho-sidebar-mobile-close-btn';
    this.backdropId = 'cho-sidebar-mobile-backdrop';
    this.backdropEl = null;
  }

  // START - createBackdrop: Builds dedicated backdrop for mobile offcanvas sidebar
  createBackdrop() {
    let backdrop = document.getElementById(this.backdropId);
    if (!backdrop) {
      backdrop = document.createElement('div');
      backdrop.id = this.backdropId;
      backdrop.className = 'fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-xs transition-opacity opacity-0 pointer-events-none hidden sm:hidden';
      backdrop.addEventListener('click', () => this.closeSidebar());
      document.body.appendChild(backdrop);
    }
    this.backdropEl = backdrop;
  }
  // END - createBackdrop

  // START - openSidebar: Displays mobile sidebar and shows backdrop
  openSidebar() {
    const sidebar = document.getElementById(this.sidebarId);
    if (!sidebar) return;

    sidebar.classList.remove('translate-x-full');
    if (!this.backdropEl) this.createBackdrop();
    if (this.backdropEl) {
      this.backdropEl.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
      this.backdropEl.classList.add('opacity-100');
    }
    document.body.classList.add('overflow-hidden');
  }
  // END - openSidebar

  // START - closeSidebar: Closes mobile sidebar and cleanly removes backdrop
  closeSidebar() {
    const sidebar = document.getElementById(this.sidebarId);
    if (sidebar) {
      sidebar.classList.add('translate-x-full');
    }

    if (this.backdropEl) {
      this.backdropEl.classList.remove('opacity-100');
      this.backdropEl.classList.add('opacity-0', 'pointer-events-none');
      setTimeout(() => {
        if (this.backdropEl) {
          this.backdropEl.classList.add('hidden');
        }
      }, 200);
    }
    document.body.classList.remove('overflow-hidden');

    // Remove any accidental stuck Flowbite backdrop attribute
    document.querySelectorAll('[drawer-backdrop]').forEach((el) => el.remove());
  }
  // END - closeSidebar

  /**
   * Binds click events for opening and closing the mobile right-side offcanvas drawer
   */
  initEvents() {
    this.createBackdrop();

    const openBtn = document.getElementById(this.mobileOpenBtnId) || document.querySelector('[data-drawer-toggle="logo-sidebar"]');
    const closeBtn = document.getElementById(this.mobileCloseBtnId) || document.querySelector('[data-drawer-hide="logo-sidebar"]');
    const sidebar = document.getElementById(this.sidebarId);

    if (openBtn && sidebar) {
      openBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.openSidebar();
      });
    }

    if (closeBtn && sidebar) {
      closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.closeSidebar();
      });
    }

    // Auto close drawer when clicking any sidebar link on mobile screens
    document.querySelectorAll('#cho-sidebar-nav-list a').forEach((link) => {
      link.addEventListener('click', () => {
        if (window.innerWidth < 640) {
          this.closeSidebar();
        }
      });
    });
  }
}
// END - DrawerManager

// START - DropdownManager: Handles accordion persistence and toggles with cookie preferences
class DropdownManager {
  /**
   * Initializes all collapsible sidebar dropdowns:
   * - Restores previous toggle state from cookies (closed by default)
   * - Automatically opens and preserves state if current route is inside the dropdown's subitems
   * - Listens to clicks, toggles visibility, and updates cookie preferences
   */
  initDropdownPersistence() {
    const currentPath = window.location.pathname.toLowerCase();
    const isNipRoute = currentPath.includes('/nip/');
    const isNip2Route = currentPath.includes('/nip2/');

    // Query all dropdown toggle buttons with data-collapse-toggle attribute
    const dropdownTriggers = document.querySelectorAll('[data-collapse-toggle]');

    dropdownTriggers.forEach((trigger) => {
      const targetId = trigger.getAttribute('data-collapse-toggle');
      if (!targetId) return;

      const targetMenu = document.getElementById(targetId);
      if (!targetMenu) return;

      // 1. Retrieve saved user preference from cookies (default is false / closed)
      const savedState = Storage.userPreferences.getDropdownState(targetId, false);

      // 2. Active route override: if user is on this subroute, ensure it's open & update cookie
      let shouldBeOpen = savedState;
      if (targetId === 'cho-nip-dropdown-menu' && isNipRoute) {
        shouldBeOpen = true;
      } else if (targetId === 'cho-nip-dropdown-menu2' && isNip2Route) {
        shouldBeOpen = true;
      }

      // 3. Apply state to DOM
      this.applyState(trigger, targetMenu, shouldBeOpen);

      // If active route forced it open and it wasn't saved, save it to cookie
      if (shouldBeOpen && !savedState) {
        Storage.userPreferences.saveDropdownState(targetId, true);
      }

      // 4. Bind click event with cookie persistence
      trigger.addEventListener('click', (e) => {
        // Prevent default button jump if wrapped or inside form
        e.preventDefault();

        const isCurrentlyHidden = targetMenu.classList.contains('hidden');
        const willBeOpen = isCurrentlyHidden; // If hidden, click opens it

        this.applyState(trigger, targetMenu, willBeOpen);

        // Save new toggle state into user preference cookies
        Storage.userPreferences.saveDropdownState(targetId, willBeOpen);
      });
    });
  }

  /**
   * Applies the open or closed state to the dropdown trigger and menu container
   * @param {HTMLElement} trigger
   * @param {HTMLElement} targetMenu
   * @param {boolean} isOpen
   */
  applyState(trigger, targetMenu, isOpen) {
    if (isOpen) {
      targetMenu.classList.remove('hidden');
      trigger.setAttribute('aria-expanded', 'true');
    } else {
      targetMenu.classList.add('hidden');
      trigger.setAttribute('aria-expanded', 'false');
    }

    // Toggle chevron rotation if present inside button
    const chevronIcon = trigger.querySelector('.cho-dropdown-chevron, [data-dropdown-chevron]');
    if (chevronIcon) {
      if (isOpen) {
        chevronIcon.classList.add('rotate-180');
      } else {
        chevronIcon.classList.remove('rotate-180');
      }
    }
  }
}
// END - DropdownManager

// START - BackgroundSlideshow: Flowbite/TailwindCSS 15s crossfade slideshow for sidebar background images
class BackgroundSlideshow {
  constructor() {
    this.intervalMs = 15000;
    this.maxErrors = 3;
    this.errorCount = 0;
    this.isPaused = false;
    this.showingSecond = false;
    this.timerId = null;
    this.slide1 = null;
    this.slide2 = null;
    this.slide2Failed = false;
  }

  /**
   * Initializes the 15-second sidebar background slideshow with bounded error handling
   */
  init() {
    this.slide1 = document.getElementById('cho-sidebar-bg-slide-1');
    this.slide2 = document.getElementById('cho-sidebar-bg-slide-2');
    if (!this.slide1 || !this.slide2) return;

    this.slide2.addEventListener('error', () => {
      this.slide2Failed = true;
      this.handleImageError(this.slide2?.getAttribute('src') || 'CHO-Building2.webp');
    });

    this.slide2.addEventListener('load', () => {
      if (this.slide2.naturalWidth > 0) {
        this.slide2Failed = false;
      }
    });

    this.timerId = window.setInterval(() => this.swapSlide(), this.intervalMs);
  }

  /**
   * Crossfades between CHO-Building.webp and CHO-Building2.webp; falls back to CHO-Building.webp on missing image
   */
  swapSlide() {
    if (this.isPaused || !this.slide1 || !this.slide2) return;

    if (!this.showingSecond) {
      const isSlide2Ready = !this.slide2Failed && this.slide2.complete && this.slide2.naturalWidth > 0;
      if (!isSlide2Ready) {
        this.stayOnPrimary();
        this.handleImageError(this.slide2.getAttribute('src') || 'CHO-Building2.webp');
        return;
      }

      this.slide1.classList.remove('opacity-100');
      this.slide1.classList.add('opacity-0');
      this.slide2.classList.remove('opacity-0');
      this.slide2.classList.add('opacity-100');
      this.showingSecond = true;
    } else {
      this.stayOnPrimary();
      this.showingSecond = false;
    }
  }

  /**
   * Keeps CHO-Building.webp visible as the safe fallback background
   */
  stayOnPrimary() {
    if (!this.slide1 || !this.slide2) return;
    this.slide2.classList.remove('opacity-100');
    this.slide2.classList.add('opacity-0');
    this.slide1.classList.remove('opacity-0');
    this.slide1.classList.add('opacity-100');
    this.showingSecond = false;
  }

  /**
   * Logs up to 3 errors to console, then pauses slideshow until page refresh
   * @param {string} imageSrc
   */
  handleImageError(imageSrc) {
    if (this.isPaused) return;
    this.errorCount += 1;

    console.error(
      `[Sidebar Slideshow] Image not detected (${this.errorCount}/${this.maxErrors}): ${imageSrc}. Staying on CHO-Building.webp.`
    );

    if (this.errorCount >= this.maxErrors) {
      this.isPaused = true;
      if (this.timerId !== null) {
        window.clearInterval(this.timerId);
        this.timerId = null;
      }
      console.warn('[Sidebar Slideshow] Paused after 3 image errors. Refresh the page to retry.');
    }
  }
}
// END - BackgroundSlideshow

// START - SidebarSearch: Subchild smart case-insensitive menu filter above Homepage
class SidebarSearch {
  constructor() {
    this.inputId = 'cho-sidebar-search-input';
    this.clearId = 'cho-sidebar-search-clear';
    this.navListId = 'cho-sidebar-nav-list';
  }

  /**
   * Initializes live smart search filtering across top-level links and dropdown subitems
   */
  init() {
    const input = document.getElementById(this.inputId);
    const clearBtn = document.getElementById(this.clearId);
    const navList = document.getElementById(this.navListId);
    if (!input || !navList) return;

    const applySearch = () => {
      const raw = input.value.trim().toLowerCase();
      const tokens = raw.split(/\s+/).filter(Boolean);
      if (clearBtn) clearBtn.classList.toggle('hidden', raw.length === 0);

      const topItems = Array.from(navList.children);
      topItems.forEach((li) => {
        const trigger = li.querySelector('[data-collapse-toggle]');
        const submenuId = trigger?.getAttribute('data-collapse-toggle');
        const submenu = submenuId ? document.getElementById(submenuId) : null;

        if (!submenu) {
          const text = `${li.dataset.sidebarParent || ''} ${li.textContent || ''}`.toLowerCase();
          const matched = tokens.length === 0 || tokens.every((t) => text.includes(t));
          li.classList.toggle('hidden', !matched);
          return;
        }

        const parentText = (trigger?.textContent || '').toLowerCase();
        const subItems = Array.from(submenu.children);

        if (tokens.length === 0) {
          li.classList.remove('hidden');
          subItems.forEach((sub) => sub.classList.remove('hidden'));
          const saved = Storage.userPreferences.getDropdownState(submenuId, false);
          const path = window.location.pathname.toLowerCase();
          const forceOpen =
            (submenuId === 'cho-nip-dropdown-menu' && path.includes('/nip/')) ||
            (submenuId === 'cho-nip-dropdown-menu2' && path.includes('/nip2/'));
          Sidebar.dropdown.applyState(trigger, submenu, saved || forceOpen);
          return;
        }

        const parentMatches = tokens.every((t) => parentText.includes(t));
        let anyChildMatched = false;

        subItems.forEach((sub) => {
          const subText = `${parentText} ${sub.dataset.sidebarParent || ''} ${sub.textContent || ''}`.toLowerCase();
          const childMatches = tokens.every((t) => subText.includes(t));
          if (childMatches) anyChildMatched = true;
          sub.classList.toggle('hidden', !parentMatches && !childMatches);
        });

        const showGroup = parentMatches || anyChildMatched;
        li.classList.toggle('hidden', !showGroup);
        if (showGroup) {
          Sidebar.dropdown.applyState(trigger, submenu, true);
        }
      });
    };

    input.addEventListener('input', applySearch);
    clearBtn?.addEventListener('click', () => {
      input.value = '';
      applySearch();
      input.focus();
    });
  }
}
// END - SidebarSearch

// START - Sidebar: Main export unifying navigation highlights, drawer control, dropdowns, search, and background slideshow
export const Sidebar = {
  highlighter: new NavigationHighlighter(),
  routeDetector: ActiveRouteDetector,
  drawer: new DrawerManager(),
  dropdown: new DropdownManager(),
  search: new SidebarSearch(),
  slideshow: new BackgroundSlideshow(),

  init() {
    this.drawer.initEvents();
    this.highlighter.highlightActive();
    ActiveRouteDetector.applyDynamicActiveState();
    this.dropdown.initDropdownPersistence();
    this.search.init();
    this.slideshow.init();
  }
};
// END - Sidebar
