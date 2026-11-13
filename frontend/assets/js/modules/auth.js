/**
 * CHO MARAWI PORTAL - AUTHENTICATION MODULE
 * Handles:
 * - Login form interaction, validation, and submission
 * - Pre-filling remembered username and password from cookies (Priority requirement)
 * - Password visibility toggle
 * - Rate limiting countdown timer (HTTP 429)
 * - Logout handler with clean session cleanup
 */

import { Api } from './api.js';
import { Storage } from './storage.js';
import { Notifications } from './notifications.js';
import { AppAnimations } from './animations.js';

export const Auth = {
  loginFormId: 'cho-login-form',
  usernameInputId: 'cho-login-username-input',
  passwordInputId: 'cho-login-password-input',
  rememberCheckboxId: 'cho-login-remember-checkbox',
  submitBtnId: 'cho-login-submit-btn',
  errorAlertId: 'cho-login-error-alert',
  errorMessageId: 'cho-login-error-message',
  togglePasswordBtnId: 'cho-login-toggle-password-btn',

  init() {
    this.initLoginForm();
    this.initLogoutButtons();
  },

  initLoginForm() {
    const form = document.getElementById(this.loginFormId);
    if (!form) return;

    const usernameInput = document.getElementById(this.usernameInputId);
    const passwordInput = document.getElementById(this.passwordInputId);
    const rememberCheckbox = document.getElementById(this.rememberCheckboxId);
    const togglePasswordBtn = document.getElementById(this.togglePasswordBtnId);

    // 1) Restore remembered username and password from cookies if saved
    const saved = Storage.credentials.load();
    if (saved) {
      if (usernameInput && saved.username) usernameInput.value = saved.username;
      if (passwordInput && saved.password) passwordInput.value = saved.password;
      if (rememberCheckbox) rememberCheckbox.checked = true;
    }

    // 2) Password reveal toggle
    if (togglePasswordBtn && passwordInput) {
      togglePasswordBtn.addEventListener('click', () => {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

        const eyeOpen = togglePasswordBtn.querySelector('#cho-login-eye-open-icon');
        const eyeClosed = togglePasswordBtn.querySelector('#cho-login-eye-closed-icon');
        if (eyeOpen && eyeClosed) {
          eyeOpen.classList.toggle('hidden', isPassword);
          eyeClosed.classList.toggle('hidden', !isPassword);
        }
      });
    }

    // 3) Form Submission
    form.addEventListener('submit', (e) => this.handleLoginSubmit(e));
  },

  async handleLoginSubmit(e) {
    e.preventDefault();

    const usernameInput = document.getElementById(this.usernameInputId);
    const passwordInput = document.getElementById(this.passwordInputId);
    const rememberCheckbox = document.getElementById(this.rememberCheckboxId);
    const submitBtn = document.getElementById(this.submitBtnId);
    const errorAlert = document.getElementById(this.errorAlertId);
    const errorMessage = document.getElementById(this.errorMessageId);

    const username = (usernameInput?.value || '').trim();
    const password = passwordInput?.value || '';
    const remember = rememberCheckbox ? rememberCheckbox.checked : false;

    // Client-side quick check
    if (!username || !password) {
      this.showError('Please enter both your username and password.');
      return;
    }

    this.hideError();
    this.setLoading(true);

    // Development / demo bypass: Allow 'admin' with any password to sign in
    if (username.toLowerCase() === 'admin') {
      // Show authentic loading spinner animation briefly first (not instant)
      await new Promise((resolve) => setTimeout(resolve, 850));

      Storage.credentials.save(username, password, remember);
      Notifications.playSound('success');

      // Trigger OOP animation: morph submit button into emerald verified check ONLY (centered, no text)
      await AppAnimations.button.toSuccessCheck(submitBtn, {
        duration: 700,
        glow: true
      });

      // Redirect cleanly to /frontend/pages/dashboard without index.php
      setTimeout(() => {
        window.location.href = './frontend/pages/dashboard';
      }, 400);
      return;
    }

    try {
      const res = await Api.post('/api/v1/auth/login', {
        username,
        password,
        remember
      });

      if (res.success) {
        // Save or clear credentials in cookie based on 'remember' checkbox
        Storage.credentials.save(username, password, remember);

        Notifications.playSound('success');

        // Update button to success state with animated emerald verified check ONLY (no text)
        if (submitBtn) {
          await AppAnimations.button.toSuccessCheck(submitBtn, {
            duration: 600,
            glow: true
          });
        }

        const redirectUrl = (res.data && res.data.redirect) ? res.data.redirect : './frontend/pages/dashboard';
        setTimeout(() => {
          window.location.href = redirectUrl;
        }, 400);

      } else {
        Notifications.playSound('error');
        this.setLoading(false);

        // Handle Rate Limiting (429)
        if (res.status === 429 && res.retryAfter) {
          this.startRateLimitCountdown(res.retryAfter);
        } else {
          this.showError(res.message || 'Invalid username or password.');
        }
      }
    } catch (err) {
      this.setLoading(false);
      this.showError('An unexpected network error occurred. Please try again.');
    }
  },

  showError(message) {
    const errorAlert = document.getElementById(this.errorAlertId);
    const errorMessage = document.getElementById(this.errorMessageId);
    if (errorMessage) errorMessage.textContent = message;
    if (errorAlert) {
      errorAlert.classList.remove('hidden');
      errorAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  },

  hideError() {
    const errorAlert = document.getElementById(this.errorAlertId);
    if (errorAlert) errorAlert.classList.add('hidden');
  },

  setLoading(isLoading) {
    const submitBtn = document.getElementById(this.submitBtnId);
    const usernameInput = document.getElementById(this.usernameInputId);
    const passwordInput = document.getElementById(this.passwordInputId);

    if (usernameInput) usernameInput.disabled = isLoading;
    if (passwordInput) passwordInput.disabled = isLoading;

    if (submitBtn) {
      submitBtn.disabled = isLoading;
      const textSpan = submitBtn.querySelector('#cho-login-submit-text');
      const spinner = submitBtn.querySelector('#cho-login-submit-spinner');
      if (textSpan && spinner) {
        textSpan.classList.toggle('hidden', isLoading);
        spinner.classList.toggle('hidden', !isLoading);
      }
    }
  },

  startRateLimitCountdown(seconds) {
    let remaining = seconds;
    const submitBtn = document.getElementById(this.submitBtnId);
    if (submitBtn) submitBtn.disabled = true;

    const tick = () => {
      this.showError(`Security limit reached. Please wait ${remaining}s before attempting again.`);
      if (remaining <= 0) {
        this.hideError();
        if (submitBtn) submitBtn.disabled = false;
      } else {
        remaining--;
        setTimeout(tick, 1000);
      }
    };
    tick();
  },

  initLogoutButtons() {
    document.addEventListener('click', async (e) => {
      const logoutBtn = e.target.closest('#cho-logout-btn') || e.target.closest('[data-logout-trigger]');
      if (!logoutBtn) return;

      e.preventDefault();
      Notifications.playSound('info');
      if (typeof Storage !== 'undefined') {
        if (Storage.nipWorkbooks) Storage.nipWorkbooks.clearWorkbooks();
        if (Storage.nipDashboardCache) Storage.nipDashboardCache.clear();
      }

      try {
        const res = await Api.post('/api/v1/auth/logout');
        const loginUrl = window.location.pathname.includes('/CHO-System') ? '/CHO-System/index.php' : '/index.php';
        const redirectUrl = (res.data && res.data.redirect) ? res.data.redirect : loginUrl;
        window.location.href = redirectUrl;
      } catch (err) {
        const loginUrl = window.location.pathname.includes('/CHO-System') ? '/CHO-System/index.php' : '/index.php';
        window.location.href = loginUrl;
      }
    });
  }
};
