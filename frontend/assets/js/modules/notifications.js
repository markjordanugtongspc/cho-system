/**
 * CHO MARAWI PORTAL - NOTIFICATIONS & SOUND EFFECTS MODULE
 * Provides:
 * - Floating Toast alerts with auto-dismiss and progress timers
 * - Web Audio API synthesized chime effects (no external audio assets required)
 * - Sound toggle preference integration
 */

import { Storage } from './storage.js';

export const Notifications = {
  containerId: 'cho-toast-container',

  /**
   * Play clean synthesized audio chimes for user actions
   */
  playSound(type = 'success') {
    const soundEnabled = Storage.preferences.get('soundEffects');
    if (!soundEnabled) return;

    try {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      if (!AudioContext) return;
      const ctx = new AudioContext();

      const osc = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.connect(gain);
      gain.connect(ctx.destination);

      const now = ctx.currentTime;
      gain.gain.setValueAtTime(0.08, now);

      if (type === 'success') {
        // High pleasant major third chime
        osc.frequency.setValueAtTime(587.33, now);       // D5
        osc.frequency.setValueAtTime(880.00, now + 0.08); // A5
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
        osc.start(now);
        osc.stop(now + 0.35);
      } else if (type === 'error') {
        // Warning low frequency double buzz
        osc.type = 'sawtooth';
        osc.frequency.setValueAtTime(220.00, now); // A3
        osc.frequency.setValueAtTime(196.00, now + 0.1); // G3
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
        osc.start(now);
        osc.stop(now + 0.4);
      } else if (type === 'warning') {
        osc.frequency.setValueAtTime(440.00, now);
        osc.frequency.setValueAtTime(493.88, now + 0.09);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
        osc.start(now);
        osc.stop(now + 0.3);
      } else {
        // Neutral tap
        osc.frequency.setValueAtTime(523.25, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.15);
        osc.start(now);
        osc.stop(now + 0.15);
      }
    } catch (e) {
      // Audio context might be restricted before user gesture
    }
  },

  getContainer() {
    let container = document.getElementById(this.containerId);
    if (!container) {
      container = document.createElement('div');
      container.id = this.containerId;
      container.className = 'fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none';
      document.body.appendChild(container);
    }
    return container;
  },

  /**
   * Show a modern toast notification
   * @param {string} message
   * @param {'success'|'error'|'warning'|'info'} type
   * @param {number} durationMs
   */
  toast(message, type = 'info', durationMs = 4500) {
    this.playSound(type);

    const container = this.getContainer();
    const id = `cho-toast-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

    const typeStyles = {
      success: {
        bg: 'bg-emerald-50 text-emerald-900 border-emerald-300 dark:bg-emerald-950/80 dark:text-emerald-100 dark:border-emerald-700',
        icon: '<svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
      },
      error: {
        bg: 'bg-rose-50 text-rose-900 border-rose-300 dark:bg-rose-950/80 dark:text-rose-100 dark:border-rose-700',
        icon: '<svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
      },
      warning: {
        bg: 'bg-amber-50 text-amber-900 border-amber-300 dark:bg-amber-950/80 dark:text-amber-100 dark:border-amber-700',
        icon: '<svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
      },
      info: {
        bg: 'bg-blue-50 text-blue-900 border-blue-300 dark:bg-blue-950/80 dark:text-blue-100 dark:border-blue-700',
        icon: '<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
      }
    };

    const config = typeStyles[type] || typeStyles.info;

    const toastEl = document.createElement('div');
    toastEl.id = id;
    toastEl.className = `pointer-events-auto flex items-start gap-3 p-4 rounded-xl border shadow-lg transition-all duration-300 translate-y-2 opacity-0 ${config.bg}`;
    toastEl.innerHTML = `
      <div id="${id}-icon" class="shrink-0 mt-0.5">${config.icon}</div>
      <div id="${id}-content" class="flex-1 text-sm font-medium leading-relaxed">${message}</div>
      <button id="${id}-dismiss-btn" type="button" class="shrink-0 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg p-1 transition" aria-label="Close">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    `;

    container.appendChild(toastEl);

    // Trigger enter transition
    requestAnimationFrame(() => {
      toastEl.classList.remove('translate-y-2', 'opacity-0');
      toastEl.classList.add('translate-y-0', 'opacity-100');
    });

    const dismiss = () => {
      toastEl.classList.add('translate-y-2', 'opacity-0');
      setTimeout(() => toastEl.remove(), 300);
    };

    toastEl.querySelector(`#${id}-dismiss-btn`).addEventListener('click', dismiss);
    if (durationMs > 0) {
      setTimeout(dismiss, durationMs);
    }
  }
};
