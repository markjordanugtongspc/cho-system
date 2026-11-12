/**
 * CHO MARAWI PORTAL - REUSABLE FRONTEND COMPONENTS MODULE
 * Provides helper builders to render and dynamically insert UI components
 * into any target container by ID. Every generated component includes
 * unique semantic IDs for easy automation, testing, and styling.
 */

export const Components = {
  /**
   * Render a Metric Stats Card
   */
  createStatsCard({ id, title, value, change, changeType = 'positive', icon, subtitle = '' }) {
    const isPos = changeType === 'positive';
    const changeColor = isPos
      ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-400'
      : 'text-rose-600 bg-rose-50 dark:bg-rose-950/60 dark:text-rose-400';

    return `
      <div id="${id}" class="p-5 bg-white rounded-2xl border border-gray-100 dark:border-gray-800 dark:bg-gray-900 shadow-xs hover:shadow-md transition-all duration-200">
        <div id="${id}-header" class="flex items-center justify-between">
          <span id="${id}-title" class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">${title}</span>
          <div id="${id}-icon-wrap" class="w-10 h-10 rounded-xl bg-cho-blue-50 dark:bg-cho-blue-900/40 text-cho-blue-800 dark:text-cho-blue-300 flex items-center justify-center">
            ${icon}
          </div>
        </div>
        <div id="${id}-body" class="mt-4 flex items-baseline justify-between">
          <div id="${id}-value" class="text-2xl font-bold font-heading text-gray-900 dark:text-white tracking-tight">${value}</div>
          ${change ? `
            <span id="${id}-badge" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${changeColor}">
              ${isPos ? '↑' : '↓'} ${change}
            </span>
          ` : ''}
        </div>
        ${subtitle ? `<p id="${id}-subtitle" class="mt-1 text-xs text-gray-500 dark:text-gray-400">${subtitle}</p>` : ''}
      </div>
    `;
  },

  /**
   * Render an Alert Banner
   */
  createAlert({ id, message, type = 'info', dismissible = true }) {
    const typeClasses = {
      info: 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-950/50 dark:border-blue-800 dark:text-blue-300',
      warning: 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/50 dark:border-amber-800 dark:text-amber-300',
      danger: 'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-950/50 dark:border-rose-800 dark:text-rose-300',
      success: 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-300'
    };

    return `
      <div id="${id}" class="p-4 rounded-xl border flex items-center justify-between text-sm ${typeClasses[type] || typeClasses.info}" role="alert">
        <div id="${id}-text" class="flex-1 font-medium">${message}</div>
        ${dismissible ? `
          <button id="${id}-close-btn" type="button" class="ml-3 text-current opacity-70 hover:opacity-100 p-1" onclick="document.getElementById('${id}').remove()" aria-label="Close">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        ` : ''}
      </div>
    `;
  },

  /**
   * Inject HTML string into a container by containerId
   */
  inject(containerId, html, position = 'beforeend') {
    const container = document.getElementById(containerId);
    if (!container) return false;
    container.insertAdjacentHTML(position, html);
    return true;
  }
};
