/**
 * CHO MARAWI PORTAL - ANIMATIONS MODULE
 * Interactive UI micro-interactions, button transitions, and verification effects.
 *
 * Architecture: Object-Oriented (OOP) with modular subchild managers:
 * - ButtonAnimator
 * - IconMorpher
 * - ElementTransition
 */

// START - ButtonAnimator: Handles dynamic state transitions, progress spinners, and morphing for action buttons
class ButtonAnimator {
  /**
   * Morphs a button into an animated rounded emerald verified check badge
   * Displays only a clean centered rounded verified checkmark (no text)
   * @param {HTMLElement|string} button - Target button element or selector
   * @param {Object} options - Configuration options
   * @returns {Promise<void>} Resolves when animation completes
   */
  async toSuccessCheck(button, options = {}) {
    const btn = typeof button === 'string' ? document.querySelector(button) : button;
    if (!btn) return;

    const {
      duration = 600,
      glow = true
    } = options;

    // Smoothly transition background and border colors to emerald
    btn.classList.remove(
      'bg-cho-blue-800',
      'hover:bg-cho-blue-900',
      'bg-cho-blue-900',
      'hover:opacity-90',
      'active:opacity-85'
    );
    btn.classList.add(
      'bg-emerald-600',
      'hover:bg-emerald-700',
      'text-white',
      'transition-all',
      'duration-500',
      'scale-[1.01]'
    );

    if (glow) {
      btn.classList.add('shadow-lg', 'shadow-emerald-500/30', 'ring-4', 'ring-emerald-300/40');
    }

    // Render centered verified check icon ONLY with smooth scale-in checkmark (no extra text)
    btn.innerHTML = `
      <span class="inline-flex items-center justify-center transform transition-all duration-300 ease-out scale-100">
        <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0 shadow-inner">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        </span>
      </span>
    `;

    return new Promise((resolve) => setTimeout(resolve, duration));
  }

  /**
   * Resets a button back to default state
   * @param {HTMLElement|string} button
   * @param {string} originalHtml
   */
  reset(button, originalHtml = '') {
    const btn = typeof button === 'string' ? document.querySelector(button) : button;
    if (!btn) return;

    btn.classList.remove(
      'bg-emerald-600',
      'hover:bg-emerald-700',
      'shadow-emerald-500/30',
      'ring-4',
      'ring-emerald-300/40',
      'scale-[1.01]'
    );
    btn.classList.add('bg-cho-blue-800', 'hover:bg-cho-blue-900');
    if (originalHtml) {
      btn.innerHTML = originalHtml;
    }
  }
}
// END - ButtonAnimator

// START - IconMorpher: Handles SVG path transitions, rotations, and icon swaps
class IconMorpher {
  /**
   * Rotates an element 360 degrees
   * @param {HTMLElement|string} element
   * @param {number} durationMs
   */
  rotate(element, durationMs = 400) {
    const el = typeof element === 'string' ? document.querySelector(element) : element;
    if (!el) return;
    el.style.transition = `transform ${durationMs}ms cubic-bezier(0.4, 0, 0.2, 1)`;
    el.style.transform = 'rotate(360deg)';
    setTimeout(() => {
      el.style.transform = '';
    }, durationMs);
  }

  /**
   * Pulses an element subtly to attract attention
   * @param {HTMLElement|string} element
   */
  pulse(element) {
    const el = typeof element === 'string' ? document.querySelector(element) : element;
    if (!el) return;
    el.classList.add('animate-pulse');
    setTimeout(() => el.classList.remove('animate-pulse'), 1500);
  }
}
// END - IconMorpher

// START - ElementTransition: Handles element fade, slide, and stagger transitions
class ElementTransition {
  /**
   * Fades out an element smoothly
   * @param {HTMLElement|string} element
   * @param {number} durationMs
   * @returns {Promise<void>}
   */
  fadeOut(element, durationMs = 300) {
    const el = typeof element === 'string' ? document.querySelector(element) : element;
    if (!el) return Promise.resolve();

    return new Promise((resolve) => {
      el.style.transition = `opacity ${durationMs}ms ease-out`;
      el.style.opacity = '0';
      setTimeout(() => {
        el.classList.add('hidden');
        resolve();
      }, durationMs);
    });
  }

  /**
   * Fades in an element smoothly
   * @param {HTMLElement|string} element
   * @param {number} durationMs
   * @returns {Promise<void>}
   */
  fadeIn(element, durationMs = 300) {
    const el = typeof element === 'string' ? document.querySelector(element) : element;
    if (!el) return Promise.resolve();

    el.classList.remove('hidden');
    el.style.opacity = '0';
    el.style.transition = `opacity ${durationMs}ms ease-in`;

    return new Promise((resolve) => {
      requestAnimationFrame(() => {
        el.style.opacity = '1';
        setTimeout(resolve, durationMs);
      });
    });
  }
}
// END - ElementTransition

// START - Animations: Master OOP orchestrator containing subchild functionality
class Animations {
  constructor() {
    this.button = new ButtonAnimator();
    this.icon = new IconMorpher();
    this.transition = new ElementTransition();
  }
}
// END - Animations

export const AppAnimations = new Animations();

