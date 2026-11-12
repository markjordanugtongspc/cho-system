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
    const isNipDashboard = path.includes('/nip/dashboard');
    const isMainHome = (path.includes('/dashboard') && !path.includes('/nip')) || 
                       path.endsWith('/cho-system') || 
                       path.endsWith('/cho-system/');

    const homeLink = document.getElementById('cho-sidebar-link-dashboard');
    const homeIcon = document.getElementById('cho-sidebar-icon-dashboard');
    const nipLink = document.getElementById('cho-sidebar-link-nip-dash');
    const nipIcon = document.getElementById('cho-sidebar-icon-nip-dash');

    if (isNipDashboard && nipLink) {
      // START - Active State Styling for NIP Dashboard Subitem
      nipLink.classList.remove('text-body', 'hover:bg-neutral-tertiary', 'hover:text-fg-brand');
      nipLink.classList.add(
        'bg-neutral-tertiary',
        'text-fg-brand',
        'font-bold',
        'shadow-xs'
      );

      if (nipIcon) {
        nipIcon.classList.remove('text-slate-400', 'group-hover:text-fg-brand');
        nipIcon.classList.add('text-fg-brand');
      }

      nipLink.setAttribute('aria-current', 'page');
      // END - Active State Styling for NIP Dashboard Subitem
    } else if (isMainHome && homeLink) {
      // START - Active State Styling for Main Homepage Route
      homeLink.classList.remove('text-body', 'hover:bg-neutral-tertiary', 'hover:text-fg-brand');
      homeLink.classList.add(
        'bg-neutral-tertiary',
        'text-fg-brand',
        'font-bold',
        'shadow-xs'
      );

      if (homeIcon) {
        homeIcon.classList.remove('group-hover:text-fg-brand');
        homeIcon.classList.add('text-fg-brand');
      }

      homeLink.setAttribute('aria-current', 'page');
      // END - Active State Styling for Main Homepage Route
    }
  }

  /**
   * Clears active highlights across all sidebar links
   */
  clearActive() {
    document.querySelectorAll('#cho-sidebar-nav-list a').forEach((link) => {
      link.classList.remove('bg-neutral-tertiary', 'text-fg-brand', 'font-bold', 'shadow-xs');
      link.classList.add('text-body');
      link.removeAttribute('aria-current');
      const icon = link.querySelector('svg');
      if (icon) {
        icon.classList.remove('text-fg-brand');
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
    const isNipRoute = currentPath.includes('/nip/') || currentPath.includes('/nip');

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

// START - Sidebar: Main export unifying navigation highlights, drawer control, and dropdowns
export const Sidebar = {
  highlighter: new NavigationHighlighter(),
  routeDetector: ActiveRouteDetector,
  drawer: new DrawerManager(),
  dropdown: new DropdownManager(),

  init() {
    this.drawer.initEvents();
    this.highlighter.highlightActive();
    ActiveRouteDetector.applyDynamicActiveState();
    this.dropdown.initDropdownPersistence();
  }
};
// END - Sidebar
