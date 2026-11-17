/**
 * CHO MARAWI PORTAL - TABLES MODULE
 * Interactive Flowbite + ExcelJS Data Tables with Search, Filter, Sort, Striped Columns, and Infinite Scroll.
 *
 * Architecture: Object-Oriented (OOP) with a PARENT base class (`BaseExcelTable`),
 * specialized SUBCHILD class (`MarawiPopulationTable`), and master orchestrator (`TablesManager`).
 * All subchild functions contain strict START and END comments.
 */

import ExcelJS from 'exceljs';
import { Notifications } from './notifications.js';
import { Storage } from './storage.js';
import { AppAnimations } from './animations.js';

// =============================================================================
// PARENT CLASS: BaseExcelTable
// Provides shared ExcelJS cell parsing, sorting, escaping, and infinite scroll
// =============================================================================
// START - BaseExcelTable
export class BaseExcelTable {
  constructor(tableId) {
    this.tableId = tableId;
    this.rows = [];
    this.filteredRows = [];
    this.renderedCount = 0;
    this.batchSize = 25;
    this.sortKey = 'row_index';
    this.sortDir = 'asc';
    this.scrollObserver = null;
  }

  // START - getNum: Safely extracts numeric value from ExcelJS cell or formula result
  getNum(val) {
    const raw = typeof val === 'object' && val !== null ? (val.result ?? 0) : val;
    const num = Number(raw || 0);
    return Number.isNaN(num) ? 0 : num;
  }
  // END - getNum

  // START - getText: Safely extracts trimmed string from ExcelJS cell or formula result
  getText(val, fallback = '') {
    if (val === null || val === undefined) return fallback;
    const raw = typeof val === 'object' ? (val.result ?? val.text ?? '') : val;
    const str = String(raw).trim();
    return str !== '' ? str : fallback;
  }
  // END - getText

  // START - escapeHtml: Prevents XSS when rendering cell text into table HTML
  escapeHtml(str) {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }
  // END - escapeHtml

  // START - sortRows: Sorts an array of row objects by key and direction
  sortRows(rows, key, dir = 'asc') {
    const mult = dir === 'desc' ? -1 : 1;
    return [...rows].sort((a, b) => {
      const av = a[key];
      const bv = b[key];
      if (typeof av === 'number' && typeof bv === 'number') {
        return (av - bv) * mult;
      }
      return String(av ?? '').localeCompare(String(bv ?? ''), undefined, { numeric: true, sensitivity: 'base' }) * mult;
    });
  }
  // END - sortRows

  // START - updateSortHeaders: Updates sort arrow indicators in the table header
  updateSortHeaders(tableEl, activeKey, activeDir) {
    if (!tableEl) return;
    tableEl.querySelectorAll('thead th[data-sort-key]').forEach((th) => {
      const icon = th.querySelector('.cho-sort-icon');
      if (!icon) return;
      if (th.dataset.sortKey === activeKey) {
        icon.textContent = activeDir === 'asc' ? '▲' : '▼';
        icon.classList.remove('text-white/60');
        icon.classList.add('text-amber-300');
      } else {
        icon.textContent = '⇅';
        icon.classList.remove('text-amber-300');
        icon.classList.add('text-white/60');
      }
    });
  }
  // END - updateSortHeaders

  // START - bindInfiniteScroll: Observes bottom sentinel inside scroll container to load next batch
  bindInfiniteScroll(scrollContainer, sentinel, onLoadMore) {
    if (!scrollContainer || !sentinel || typeof IntersectionObserver === 'undefined') return;
    if (this.scrollObserver) {
      this.scrollObserver.disconnect();
    }
    this.scrollObserver = new IntersectionObserver(
      (entries) => {
        if (entries[0]?.isIntersecting) {
          onLoadMore();
        }
      },
      { root: scrollContainer, rootMargin: '120px', threshold: 0.01 }
    );
    this.scrollObserver.observe(sentinel);
  }
  // END - bindInfiniteScroll
}
// END - BaseExcelTable

// =============================================================================
// SUBCHILD CLASS: MarawiPopulationTable
// Parses MARAWI POP 2024 from NIP Excel workbooks via ExcelJS & renders table
// =============================================================================
// START - MarawiPopulationTable
export class MarawiPopulationTable extends BaseExcelTable {
  constructor() {
    super('cho-nip-pop-table');
    this.searchQuery = '';
    this.filterMode = 'all';
    this.activeWorkbook = 'MARAWI NIP DATA - UPGRADED.xlsx';
    this.citySummary = { totalPop: 0, households: 0, nipTarget: 0 };
  }

  // START - init: Initializes controls, infinite scroll, and loads cached population data from Storage
  init() {
    this.tableEl = document.getElementById('cho-nip-pop-table');
    this.tbodyEl = document.getElementById('cho-nip-pop-tbody');
    this.scrollContainer = document.getElementById('cho-nip-pop-scroll-container');
    this.sentinelEl = document.getElementById('cho-nip-pop-scroll-sentinel');
    this.cardEl = document.getElementById('cho-nip-population-table-card');
    if (!this.tableEl || !this.tbodyEl) return;

    this.bindControls();
    this.bindInfiniteScroll(this.scrollContainer, this.sentinelEl, () => this.renderNextBatch());
    this.loadFromStorage();
  }
  // END - init

  // START - bindControls: Binds search input, filter dropdown, and sortable headers
  bindControls() {
    const searchInput = document.getElementById('cho-nip-pop-search');
    const searchClear = document.getElementById('cho-nip-pop-search-clear');
    const filterSelect = document.getElementById('cho-nip-pop-filter');

    if (searchInput) {
      searchInput.addEventListener('input', () => {
        this.searchQuery = searchInput.value.trim().toLowerCase();
        if (searchClear) {
          searchClear.classList.toggle('hidden', this.searchQuery === '');
        }
        this.applyFilterAndSort();
      });
    }

    if (searchClear && searchInput) {
      searchClear.addEventListener('click', () => {
        searchInput.value = '';
        this.searchQuery = '';
        searchClear.classList.add('hidden');
        this.applyFilterAndSort();
        searchInput.focus();
      });
    }

    if (filterSelect) {
      filterSelect.addEventListener('change', () => {
        this.filterMode = filterSelect.value || 'all';
        this.applyFilterAndSort();
      });
    }

    // Delegated click listener for row Edit pencil action button
    this.tbodyEl.addEventListener('click', (e) => {
      const editBtn = e.target.closest('[data-pop-edit-index]');
      if (!editBtn) return;
      const idx = Number(editBtn.dataset.popEditIndex);
      const row = this.rows.find((r) => r.row_index === idx);
      if (row && window.CHO?.Drawers?.popEdit) {
        window.CHO.Drawers.popEdit.openForRow(row);
      }
    });

    this.tableEl.querySelectorAll('thead th[data-sort-key]').forEach((th) => {
      th.addEventListener('click', () => {
        const key = th.dataset.sortKey;
        if (!key) return;
        if (this.sortKey === key) {
          this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
          this.sortKey = key;
          this.sortDir = 'asc';
        }
        this.applyFilterAndSort();
      });
    });
  }
  // END - bindControls

  // START - loadFromStorage: Reads MARAWI POP 2024 data uploaded via #drawer-nip-load from Storage.nipPopulationCache
  loadFromStorage() {
    const cachedPop = Storage.nipPopulationCache?.get?.();
    const workbookBadgeName = document.getElementById('cho-nip-pop-active-workbook-name');
    const workbookBadgeDot = document.getElementById('cho-nip-pop-active-dot');
    const sourceStatus = document.getElementById('cho-nip-pop-source-status');
    const countStatus = document.getElementById('cho-nip-pop-count-status');

    if (!cachedPop || !Array.isArray(cachedPop.rows) || cachedPop.rows.length === 0) {
      this.rows = [];
      this.filteredRows = [];
      this.citySummary = { totalPop: 0, householdPop: 0, households: 0 };

      if (workbookBadgeName) workbookBadgeName.textContent = 'No Workbook Uploaded';
      if (workbookBadgeDot) {
        workbookBadgeDot.classList.remove('bg-emerald-500');
        workbookBadgeDot.classList.add('bg-slate-400');
      }
      if (sourceStatus) sourceStatus.textContent = 'Source: None (Upload via NIP Dashboard)';
      if (countStatus) countStatus.textContent = 'Showing 0 of 0 records';

      this.tbodyEl.innerHTML = `
        <tr>
          <td colspan="9" class="px-4 py-12 text-center text-slate-500">
            <div class="space-y-1.5">
              <p class="text-sm font-semibold text-slate-700">No Excel workbook uploaded yet</p>
              <p class="text-xs text-slate-500">
                Go to <a href="../dashboard/" class="text-cho-blue-700 font-semibold hover:underline">NIP Dashboard</a> and upload a workbook using <strong>Load Active Excel Sheet</strong> to populate this table.
              </p>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    this.activeWorkbook = cachedPop.source_file || 'Uploaded Workbook';
    this.citySummary = cachedPop.citySummary || { totalPop: 0, householdPop: 0, households: 0 };
    this.rows = cachedPop.rows;

    if (workbookBadgeName) workbookBadgeName.textContent = this.activeWorkbook;
    if (workbookBadgeDot) {
      workbookBadgeDot.classList.remove('bg-slate-400');
      workbookBadgeDot.classList.add('bg-emerald-500');
    }
    if (sourceStatus) sourceStatus.textContent = `Source: ${this.activeWorkbook} • Sheet: MARAWI POP 2024`;

    this.updateSummaryCards();
    this.applyFilterAndSort();
  }
  // END - loadFromStorage

  // START - updateSummaryCards: Updates top 4 summary cards with MARAWI POP 2024 totals using MoneyCounterRunner
  updateSummaryCards() {
    const elRecords = document.getElementById('cho-nip-pop-stat-records-val');
    const elTotal = document.getElementById('cho-nip-pop-stat-total-val');
    const elHouseholds = document.getElementById('cho-nip-pop-stat-households-val');
    const elTarget = document.getElementById('cho-nip-pop-stat-target-val');

    if (elRecords) AppAnimations.counter.rollTo(elRecords, this.rows.length, { duration: 650 });
    if (elTotal) AppAnimations.counter.rollTo(elTotal, this.citySummary.totalPop, { duration: 850 });
    if (elHouseholds) AppAnimations.counter.rollTo(elHouseholds, this.citySummary.householdPop, { duration: 850 });
    if (elTarget) AppAnimations.counter.rollTo(elTarget, this.citySummary.households, { duration: 800 });
  }
  // END - updateSummaryCards

  // START - applyFilterAndSort: Filters rows by search/dropdown, sorts, and renders first batch
  applyFilterAndSort() {
    const q = this.searchQuery;
    const mode = this.filterMode;

    const filtered = this.rows.filter((row) => {
      if (mode === 'nip_only' && row.nip_pop <= 0) return false;
      if (mode === 'pop_high' && row.total_pop < 3000) return false;
      if (mode === 'pop_mid' && (row.total_pop < 1500 || row.total_pop >= 3000)) return false;
      if (mode === 'pop_low' && row.total_pop >= 1500) return false;

      if (!q) return true;
      return (
        row.barangay.toLowerCase().includes(q) ||
        row.psgc10.toLowerCase().includes(q) ||
        String(row.total_pop).includes(q) ||
        String(row.nip_target).includes(q)
      );
    });

    this.filteredRows = this.sortRows(filtered, this.sortKey, this.sortDir);
    this.renderedCount = 0;
    this.tbodyEl.innerHTML = '';

    if (this.scrollContainer) {
      this.scrollContainer.scrollTop = 0;
    }

    this.updateSortHeaders(this.tableEl, this.sortKey, this.sortDir);

    if (this.filteredRows.length === 0) {
      this.tbodyEl.innerHTML = `
        <tr>
          <td colspan="9" class="px-4 py-10 text-center text-slate-400 text-xs font-medium">
            No matching barangay records found.
          </td>
        </tr>
      `;
      this.updateCountStatus();
      return;
    }

    this.renderNextBatch();
  }
  // END - applyFilterAndSort

  // START - renderNextBatch: Appends next batch of rows with Flowbite striped columns & hover state
  renderNextBatch() {
    if (this.renderedCount >= this.filteredRows.length) return;

    const nextSlice = this.filteredRows.slice(this.renderedCount, this.renderedCount + this.batchSize);
    const isInitialBatch = this.renderedCount === 0;
    const html = nextSlice.map((row) => {
      const hasNip = row.nip_pop > 0;
      const rollAttr = (val, decimals = 0) => isInitialBatch
        ? `data-cho-roll-val="${val}" data-cho-roll-dec="${decimals}"`
        : '';
      return `
        <tr class="bg-white hover:bg-blue-50/70 transition-colors">
          <td class="px-4 py-3 text-xs font-money font-semibold text-slate-400 whitespace-nowrap">${row.row_index}</td>
          <td class="px-4 py-3 font-mono text-xs text-slate-600 whitespace-nowrap bg-slate-50/80">${this.escapeHtml(row.psgc10)}</td>
          <td class="px-4 py-3 font-semibold text-slate-900 whitespace-nowrap">${this.escapeHtml(row.barangay)}</td>
          <td class="px-4 py-3 text-right font-money font-bold text-slate-800 whitespace-nowrap bg-slate-50/80" ${rollAttr(row.total_pop)}>${isInitialBatch ? '0' : row.total_pop.toLocaleString()}</td>
          <td class="px-4 py-3 text-right font-money text-slate-600 whitespace-nowrap" ${rollAttr(row.household_pop)}>${isInitialBatch ? '0' : row.household_pop.toLocaleString()}</td>
          <td class="px-4 py-3 text-right font-money text-slate-600 whitespace-nowrap bg-slate-50/80" ${rollAttr(row.households)}>${isInitialBatch ? '0' : row.households.toLocaleString()}</td>
          <td class="px-4 py-3 text-right font-money font-medium ${hasNip ? 'text-emerald-600' : 'text-slate-400'} whitespace-nowrap" ${hasNip ? rollAttr(row.nip_pop) : ''}>${hasNip ? (isInitialBatch ? '0' : row.nip_pop.toLocaleString()) : '—'}</td>
          <td class="px-4 py-3 text-right font-money font-bold ${hasNip ? 'text-cho-blue-900' : 'text-slate-400'} whitespace-nowrap bg-slate-50/80" ${hasNip ? rollAttr(row.nip_target, 1) : ''}>${hasNip ? (isInitialBatch ? '0' : row.nip_target.toLocaleString()) : '—'}</td>
          <td class="px-4 py-3 text-center whitespace-nowrap">
            <button type="button"
                    data-pop-edit-index="${row.row_index}"
                    title="Edit ${this.escapeHtml(row.barangay)} in Excel"
                    aria-label="Edit ${this.escapeHtml(row.barangay)}"
                    class="inline-flex items-center justify-center p-1.5 text-cho-blue-900 hover:text-white hover:bg-cho-blue-900 rounded-lg transition-colors cursor-pointer">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
              </svg>
            </button>
          </td>
        </tr>
      `;
    }).join('');

    this.tbodyEl.insertAdjacentHTML('beforeend', html);
    if (isInitialBatch) {
      this.tbodyEl.querySelectorAll('[data-cho-roll-val]').forEach((cell) => {
        const val = Number(cell.dataset.choRollVal || 0);
        const dec = Number(cell.dataset.choRollDec || 0);
        cell.removeAttribute('data-cho-roll-val');
        cell.removeAttribute('data-cho-roll-dec');
        AppAnimations.counter.rollTo(cell, val, { duration: 600, decimals: dec });
      });
    }
    this.renderedCount += nextSlice.length;
    this.updateCountStatus();
  }
  // END - renderNextBatch

  // START - updateCountStatus: Updates footer showing visible vs total filtered records
  updateCountStatus() {
    const statusEl = document.getElementById('cho-nip-pop-count-status');
    if (!statusEl) return;
    statusEl.textContent = `Showing ${this.renderedCount} of ${this.filteredRows.length} records (${this.rows.length} total in sheet)`;
  }
  // END - updateCountStatus
}
// END - MarawiPopulationTable

// =============================================================================
// ORCHESTRATOR CLASS: TablesManager
// Boots all table subchild instances on DOM ready
// =============================================================================
// START - TablesManager
class TablesManager {
  constructor() {
    this.marawiPopulation = new MarawiPopulationTable();
  }

  // START - init: Initializes active page tables
  init() {
    this.marawiPopulation.init();
  }
  // END - init
}
// END - TablesManager

export const Tables = new TablesManager();
