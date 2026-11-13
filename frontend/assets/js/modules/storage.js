/**
 * CHO MARAWI PORTAL - STORAGE MODULE
 * Handles Cookies, LocalStorage, In-Memory Cache, and User Preferences.
 * Specifically manages:
 * - Cookie store & retrieve (Priority: Username & Password when 'Remember Me' is checked)
 * - User preferences: Theme (light/dark), Font Size (sm/md/lg/xl), Sidebar & Dropdown toggle states
 */

export const Storage = {
  /**
   * Cookie Management
   */
  cookies: {
    set(name, value, days = 30, path = '/') {
      let expires = '';
      if (days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = '; expires=' + date.toUTCString();
      }
      const secure = window.location.protocol === 'https:' ? '; Secure' : '';
      document.cookie = `${encodeURIComponent(name)}=${encodeURIComponent(value)}${expires}; path=${path}; SameSite=Lax${secure}`;
    },

    get(name) {
      const nameEQ = encodeURIComponent(name) + '=';
      const ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) {
          return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
      }
      return null;
    },

    remove(name, path = '/') {
      this.set(name, '', -1, path);
    }
  },

  /**
   * LocalStorage with JSON parsing & error safety
   */
  local: {
    get(key, defaultValue = null) {
      try {
        const item = localStorage.getItem(key);
        return item !== null ? JSON.parse(item) : defaultValue;
      } catch (e) {
        console.warn(`[CHO Storage] Error reading localStorage key "${key}":`, e);
        return defaultValue;
      }
    },

    set(key, value) {
      try {
        localStorage.setItem(key, JSON.stringify(value));
      } catch (e) {
        console.warn(`[CHO Storage] Error setting localStorage key "${key}":`, e);
      }
    },

    remove(key) {
      try {
        localStorage.removeItem(key);
      } catch (e) {
        console.warn(`[CHO Storage] Error removing localStorage key "${key}":`, e);
      }
    }
  },

  /**
   * Simple In-Memory Cache with TTL (in seconds)
   */
  cache: {
    _data: new Map(),

    set(key, value, ttlSeconds = 300) {
      const expiry = Date.now() + ttlSeconds * 1000;
      this._data.set(key, { value, expiry });
    },

    get(key, defaultValue = null) {
      const item = this._data.get(key);
      if (!item) return defaultValue;
      if (Date.now() > item.expiry) {
        this._data.delete(key);
        return defaultValue;
      }
      return item.value;
    },

    has(key) {
      return this.get(key) !== null;
    },

    remove(key) {
      this._data.delete(key);
    },

    clear() {
      this._data.clear();
    }
  },

  /**
   * Remember Me Credentials Manager
   * As requested: Stores username and password in client cookies when Remember Me is enabled.
   * Uses base64 obfuscation for cookie transport.
   */
  credentials: {
    COOKIE_USER: 'cho_rem_user',
    COOKIE_PASS: 'cho_rem_token',
    COOKIE_REMEMBER: 'cho_rem_flag',

    save(username, password, remember = true) {
      if (remember) {
        Storage.cookies.set(this.COOKIE_USER, username, 30);
        // Base64 encode password for cookie storage
        try {
          const encoded = btoa(unescape(encodeURIComponent(password)));
          Storage.cookies.set(this.COOKIE_PASS, encoded, 30);
          Storage.cookies.set(this.COOKIE_REMEMBER, '1', 30);
        } catch (e) {
          Storage.cookies.set(this.COOKIE_PASS, btoa(password), 30);
          Storage.cookies.set(this.COOKIE_REMEMBER, '1', 30);
        }
      } else {
        this.clear();
      }
    },

    load() {
      const isRemembered = Storage.cookies.get(this.COOKIE_REMEMBER) === '1';
      if (!isRemembered) return null;

      const username = Storage.cookies.get(this.COOKIE_USER) || '';
      const encodedPass = Storage.cookies.get(this.COOKIE_PASS) || '';
      let password = '';

      if (encodedPass) {
        try {
          password = decodeURIComponent(escape(atob(encodedPass)));
        } catch (e) {
          try {
            password = atob(encodedPass);
          } catch (err) {
            password = '';
          }
        }
      }

      return {
        username,
        password,
        remember: true
      };
    },

    clear() {
      Storage.cookies.remove(this.COOKIE_USER);
      Storage.cookies.remove(this.COOKIE_PASS);
      Storage.cookies.remove(this.COOKIE_REMEMBER);
    }
  },

  /**
   * User UI Preferences (Theme, Font Size, Dropdowns, Sidebar)
   * Stored in cookie 'cho_prefs' so PHP reads it server-side to prevent render flickers.
   */
  preferences: {
    COOKIE_NAME: 'cho_prefs',
    DEFAULTS: {
      theme: 'light',            // Force 'light' mode by default
      fontSize: 'md',            // 'sm' | 'md' | 'lg' | 'xl'
      dropdownsOpen: true,       // Default toggle ON for dropdowns
      sidebarCollapsed: false,   // Default full sidebar
      soundEffects: true         // Sound effects for notifications
    },

    getAll() {
      const raw = Storage.cookies.get(this.COOKIE_NAME);
      if (!raw) return { ...this.DEFAULTS };
      try {
        const parsed = JSON.parse(raw);
        // Guarantee valid theme setting, defaulting to 'light'
        if (!parsed.theme || !['light', 'dark', 'system'].includes(parsed.theme)) {
          parsed.theme = 'light';
        }
        return { ...this.DEFAULTS, ...parsed };
      } catch (e) {
        return { ...this.DEFAULTS };
      }
    },

    get(key) {
      const prefs = this.getAll();
      return prefs[key] ?? this.DEFAULTS[key];
    },

    set(key, value) {
      const prefs = this.getAll();
      prefs[key] = value;
      Storage.cookies.set(this.COOKIE_NAME, JSON.stringify(prefs), 365);
      Storage.local.set(this.COOKIE_NAME, prefs);
      return prefs;
    },

    saveAll(prefsObj) {
      const merged = { ...this.getAll(), ...prefsObj };
      Storage.cookies.set(this.COOKIE_NAME, JSON.stringify(merged), 365);
      Storage.local.set(this.COOKIE_NAME, merged);
      return merged;
    }
  },

  // START - UserPreferenceCookies: Subchild helper for individual and mapped UI preference cookies
  userPreferences: {
    COOKIE_PREFIX: 'cho_pref_',

    /**
     * Gets a specific UI preference from cookies
     * @param {string} key Preference identifier (e.g. 'dropdown_cho-nip-dropdown-menu')
     * @param {*} defaultValue Fallback value if not found
     * @returns {*}
     */
    get(key, defaultValue = null) {
      const val = Storage.cookies.get(this.COOKIE_PREFIX + key);
      if (val === null) return defaultValue;
      if (val === 'true' || val === '1') return true;
      if (val === 'false' || val === '0') return false;
      try {
        return JSON.parse(val);
      } catch (e) {
        return val;
      }
    },

    /**
     * Sets a specific UI preference cookie
     * @param {string} key Preference identifier
     * @param {*} value Boolean, string, or object
     * @param {number} days Expiration in days (default 365 days)
     */
    set(key, value, days = 365) {
      const serialized = typeof value === 'object' ? JSON.stringify(value) : String(value);
      Storage.cookies.set(this.COOKIE_PREFIX + key, serialized, days);
    },

    /**
     * Removes a specific UI preference cookie
     * @param {string} key Preference identifier
     */
    remove(key) {
      Storage.cookies.remove(this.COOKIE_PREFIX + key);
    },

    /**
     * Saves the toggle state of an accordion or dropdown menu
     * @param {string} menuId Element ID of the dropdown menu container
     * @param {boolean} isOpen Whether the dropdown is expanded/open
     */
    saveDropdownState(menuId, isOpen) {
      this.set(`dropdown_${menuId}`, isOpen ? '1' : '0');
    },

    /**
     * Loads the saved toggle state of an accordion or dropdown menu
     * @param {string} menuId Element ID of the dropdown menu container
     * @param {boolean} defaultState Default state if no cookie is found (defaults to false)
     * @returns {boolean}
     */
    getDropdownState(menuId, defaultState = false) {
      const stored = this.get(`dropdown_${menuId}`);
      if (stored === null) return defaultState;
      return stored === true || stored === '1';
    }
  },
  // END - UserPreferenceCookies

  // START - NipWorkbookStorage: Remembers uploaded/imported NIP Excel workbooks in localStorage
  nipWorkbooks: {
    LIST_KEY: 'cho_nip_uploaded_workbooks',
    ACTIVE_KEY: 'cho_nip_active_workbook',

    getUploadedWorkbooks() {
      return Storage.local.get(this.LIST_KEY, []);
    },

    saveUploadedWorkbook(fileMeta) {
      if (!fileMeta || !fileMeta.name) return [];
      const list = this.getUploadedWorkbooks().filter((item) => item.name !== fileMeta.name);
      list.unshift({
        name: fileMeta.name,
        size: fileMeta.size || 0,
        lastModified: fileMeta.lastModified || Date.now(),
        uploadedAt: new Date().toISOString()
      });
      Storage.local.set(this.LIST_KEY, list);
      this.setActiveWorkbook(fileMeta.name);
      return list;
    },

    getActiveWorkbook() {
      return Storage.local.get(this.ACTIVE_KEY, null);
    },

    setActiveWorkbook(filename) {
      Storage.local.set(this.ACTIVE_KEY, filename || null);
    },

    clearWorkbooks() {
      Storage.local.remove(this.LIST_KEY);
      Storage.local.remove(this.ACTIVE_KEY);
    }
  },
  // END - NipWorkbookStorage

  // START - NipDashboardCache: Persists parsed Excel stats & chart data across reloads until logout or new upload
  nipDashboardCache: {
    CACHE_KEY: 'cho_nip_dashboard_cache',

    save(kpiData) {
      if (!kpiData) return;
      Storage.local.set(this.CACHE_KEY, kpiData);
    },

    get() {
      return Storage.local.get(this.CACHE_KEY, null);
    },

    clear() {
      Storage.local.remove(this.CACHE_KEY);
    }
  }
  // END - NipDashboardCache
};
