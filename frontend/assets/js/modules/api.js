/**
 * CHO MARAWI PORTAL - API MODULE
 * Hardened Fetch Client with:
 * - Automatic CSRF token injection (via meta tag or /api/v1/auth/csrf refresh)
 * - Rate Limiting (HTTP 429) detection & Retry-After countdown feedback
 * - Standard JSON envelope handling: { success, message, data, errors }
 * - Same-Origin verification headers
 */

export const Api = {
  csrfToken: null,

  /**
   * Get current CSRF token from page meta tag or cached value
   */
  getCsrfToken() {
    if (this.csrfToken) return this.csrfToken;
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta && meta.content) {
      this.csrfToken = meta.content;
      return this.csrfToken;
    }
    return '';
  },

  /**
   * Refresh CSRF token from backend
   */
  async refreshCsrfToken() {
    try {
      const res = await fetch('/api/v1/auth/csrf', {
        headers: { 'Accept': 'application/json' }
      });
      const json = await res.json();
      if (json && json.data && json.data.csrf_token) {
        this.csrfToken = json.data.csrf_token;
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) meta.content = this.csrfToken;
        return this.csrfToken;
      }
    } catch (e) {
      console.error('[CHO API] Failed to refresh CSRF token:', e);
    }
    return null;
  },

  /**
   * Master Request Method
   * @param {string} endpoint - e.g. '/api/v1/auth/login'
   * @param {object} options - fetch options
   */
  async request(endpoint, options = {}) {
    const method = (options.method || 'GET').toUpperCase();
    const headers = {
      'Accept': 'application/json',
      ...(options.headers || {})
    };

    // Attach Content-Type for JSON body if not FormData
    if (options.body && !(options.body instanceof FormData) && typeof options.body === 'object') {
      headers['Content-Type'] = 'application/json';
      options.body = JSON.stringify(options.body);
    }

    // Attach CSRF token on mutating requests
    if (['POST', 'PUT', 'DELETE', 'PATCH'].includes(method)) {
      headers['X-CSRF-Token'] = this.getCsrfToken();
    }

    try {
      const response = await fetch(endpoint, {
        ...options,
        method,
        headers,
        credentials: 'same-origin'
      });

      // Handle HTTP 429 Rate Limiting
      if (response.status === 429) {
        const retryAfter = response.headers.get('Retry-After') || '60';
        const errorJson = await response.json().catch(() => ({}));
        return {
          success: false,
          status: 429,
          retryAfter: parseInt(retryAfter, 10),
          message: errorJson.message || `Rate limit reached. Please wait ${retryAfter} seconds before trying again.`,
          data: null,
          errors: errorJson.errors || null
        };
      }

      // Handle HTTP 419 CSRF Token Expired (auto-refresh and retry once)
      if (response.status === 419 && !options._retried) {
        console.warn('[CHO API] CSRF expired, refreshing token...');
        const newToken = await this.refreshCsrfToken();
        if (newToken) {
          return this.request(endpoint, { ...options, _retried: true });
        }
      }

      const contentType = response.headers.get('content-type') || '';
      let data = null;
      if (contentType.includes('application/json')) {
        data = await response.json();
      } else {
        const text = await response.text();
        data = { success: response.ok, message: text, data: null };
      }

      // If backend rotated CSRF token in response payload, sync it
      if (data && data.data && data.data.csrf_token) {
        this.csrfToken = data.data.csrf_token;
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) meta.content = this.csrfToken;
      }

      return {
        success: response.ok && (data.success !== false),
        status: response.status,
        message: data.message || (response.ok ? 'OK' : 'An error occurred'),
        data: data.data !== undefined ? data.data : data,
        errors: data.errors || null
      };

    } catch (networkError) {
      console.error('[CHO API] Network error:', networkError);
      return {
        success: false,
        status: 0,
        message: 'Unable to reach the server. Please check your internet or local network connection.',
        data: null,
        errors: null
      };
    }
  },

  get(endpoint, headers = {}) {
    return this.request(endpoint, { method: 'GET', headers });
  },

  post(endpoint, body = {}, headers = {}) {
    return this.request(endpoint, { method: 'POST', body, headers });
  },

  put(endpoint, body = {}, headers = {}) {
    return this.request(endpoint, { method: 'PUT', body, headers });
  },

  delete(endpoint, headers = {}) {
    return this.request(endpoint, { method: 'DELETE', headers });
  }
};
