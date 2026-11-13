/**
 * CHO MARAWI PORTAL - FLOWBITE RIGHT DRAWER MODULE
 * Handles:
 * - Flowbite Right Drawer display, dismissal, and animations
 * - Separate standalone right drawers for NIP Excel actions:
 *   1. Load / Switch Active Excel Sheet (#drawer-nip-load)
 *   2. Generate Blank Year Sheet (#drawer-nip-generate)
 *   3. Quarter Input Reset Tool (#drawer-nip-reset)
 *   4. Upload Updated Field Sheet (#drawer-nip-upload)
 * - Backdrop lifecycle management (clean dismiss, prevents backdrop sticking, keeps sidebar behind drawer)
 *
 * Architecture: Object-Oriented (OOP) format with parent class and specialized subchild classes.
 * All subchild functions contain strict START and END comments.
 */

import { Api } from './api.js';
import { Notifications } from './notifications.js';
import { Charts } from './charts.js';
import { Storage } from './storage.js';
import ExcelJS from 'exceljs';

// =============================================================================
// PARENT CLASS: BaseDrawerController
// Provides universal drawer orchestration, backdrop lifecycle, and DOM binds
// =============================================================================
export class BaseDrawerController {
  constructor(drawerId) {
    this.drawerId = drawerId;
    this.drawerEl = null;
    this.backdropEl = null;
    this.isOpen = false;
  }

  // START - init: Initializes element references and attaches event listeners
  init() {
    this.drawerEl = document.getElementById(this.drawerId);
    if (!this.drawerEl) return;

    this.bindGlobalTriggers();
    this.createBackdrop();
  }
  // END - init

  // START - createBackdrop: Builds and manages the translucent backdrop overlay
  createBackdrop() {
    let backdrop = document.getElementById(`${this.drawerId}-backdrop`);
    if (!backdrop) {
      backdrop = document.createElement('div');
      backdrop.id = `${this.drawerId}-backdrop`;
      backdrop.className = 'fixed inset-0 z-45 bg-slate-900/50 backdrop-blur-xs transition-opacity opacity-0 pointer-events-none hidden';
      backdrop.addEventListener('click', () => this.hide());
      document.body.appendChild(backdrop);
    }
    this.backdropEl = backdrop;
  }
  // END - createBackdrop

  // START - bindGlobalTriggers: Attaches listeners to all custom and data-drawer-target buttons
  bindGlobalTriggers() {
    document.querySelectorAll(`[data-cho-drawer-target="${this.drawerId}"], [data-drawer-show="${this.drawerId}"], [data-drawer-target="${this.drawerId}"]`).forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        this.show();
      });
    });

    document.querySelectorAll(`[data-cho-drawer-hide="${this.drawerId}"], [data-drawer-hide="${this.drawerId}"]`).forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        this.hide();
      });
    });

    // Close on Escape key press
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.hide();
      }
    });
  }
  // END - bindGlobalTriggers

  // START - show: Slides the right drawer into view and displays the backdrop
  show() {
    if (!this.drawerEl) this.drawerEl = document.getElementById(this.drawerId);
    if (!this.drawerEl) return;

    // Close any other open drawers first
    document.querySelectorAll('[data-cho-nip-drawer]').forEach((el) => {
      if (el.id !== this.drawerId) {
        el.classList.add('translate-x-full');
        el.classList.remove('transform-none');
        el.setAttribute('aria-hidden', 'true');
        el.removeAttribute('aria-modal');
        const otherBackdrop = document.getElementById(`${el.id}-backdrop`);
        if (otherBackdrop) {
          otherBackdrop.classList.add('hidden', 'opacity-0', 'pointer-events-none');
          otherBackdrop.classList.remove('opacity-100');
        }
      }
    });

    this.drawerEl.classList.remove('translate-x-full');
    this.drawerEl.classList.add('transform-none');
    this.drawerEl.setAttribute('aria-modal', 'true');
    this.drawerEl.removeAttribute('aria-hidden');

    if (this.backdropEl) {
      this.backdropEl.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
      this.backdropEl.classList.add('opacity-100');
    }

    this.isOpen = true;
    document.body.classList.add('overflow-hidden');
  }
  // END - show

  // START - hide: Slides the right drawer off-screen and cleans up the backdrop
  hide() {
    if (!this.drawerEl) this.drawerEl = document.getElementById(this.drawerId);
    if (!this.drawerEl) return;

    this.drawerEl.classList.remove('transform-none');
    this.drawerEl.classList.add('translate-x-full');
    this.drawerEl.setAttribute('aria-hidden', 'true');
    this.drawerEl.removeAttribute('aria-modal');

    if (this.backdropEl) {
      this.backdropEl.classList.remove('opacity-100');
      this.backdropEl.classList.add('opacity-0', 'pointer-events-none');
      setTimeout(() => {
        if (!this.isOpen && this.backdropEl) {
          this.backdropEl.classList.add('hidden');
        }
      }, 200);
    }

    this.isOpen = false;
    document.body.classList.remove('overflow-hidden');

    // Clean up any stray Flowbite-generated or unmanaged backdrops
    document.querySelectorAll('[drawer-backdrop]').forEach((el) => el.remove());
  }
  // END - hide
}

// =============================================================================
// SUBCHILD CLASS 1: NipLoadDrawer
// Handles scanning, selecting, and applying active Excel workbooks
// =============================================================================
export class NipLoadDrawer extends BaseDrawerController {
  constructor() {
    super('drawer-nip-load');
    this.activeFile = null;
    this.filesList = [];
  }

  // START - init: Mounts drawer triggers, dropzone handlers, and restores cached Excel dashboard if present
  init() {
    super.init();
    if (!this.drawerEl) return;

    this.bindActionHandlers();
    this.bindDropzoneHandlers();
    this.renderStoredWorkbooks();

    const cached = Storage.nipDashboardCache?.get();
    if (cached) {
      this.activeFile = cached.source_file || this.activeFile;
      this.applyDashboardData(cached);
    }
  }
  // END - init

  // START - show: Opens the load drawer and refreshes stored workbooks list
  show() {
    super.show();
    this.renderStoredWorkbooks();
  }
  // END - show

  // START - renderStoredWorkbooks: Renders uploaded workbooks from Storage.nipWorkbooks as a Flowbite dropdown
  renderStoredWorkbooks() {
    const fieldEl = document.getElementById('nip-drawer-load-field');
    const listEl = document.getElementById('nip-drawer-load-files-list');
    if (!fieldEl || !listEl) return;

    const workbooks = Storage.nipWorkbooks.getUploadedWorkbooks();
    const activeName = Storage.nipWorkbooks.getActiveWorkbook() || (workbooks[0]?.name ?? '');

    if (!workbooks.length) {
      fieldEl.classList.add('hidden');
      listEl.innerHTML = '';
      return;
    }

    fieldEl.classList.remove('hidden');
    listEl.innerHTML = `
      <div class="relative w-full">
        <button id="nip-dropdown-btn-active-file"
                data-dropdown-toggle="nip-dropdown-menu-active-file"
                class="w-full inline-flex items-center justify-between text-slate-800 bg-slate-50 border border-slate-300 hover:bg-slate-100 focus:ring-4 focus:ring-slate-200 shadow-xs font-medium leading-5 rounded-lg text-xs sm:text-sm px-3.5 py-2.5 focus:outline-none cursor-pointer transition-colors"
                type="button">
          <span id="nip-dropdown-label-active-file" class="truncate">${activeName}</span>
          <svg class="w-4 h-4 ms-1.5 -me-0.5 shrink-0 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
        </button>

        <!-- Dropdown menu -->
        <div id="nip-dropdown-menu-active-file" class="z-10 hidden bg-white border border-slate-200 rounded-xl shadow-lg w-full max-h-56 overflow-y-auto">
          <ul class="p-2 text-xs sm:text-sm text-slate-700 font-medium" aria-labelledby="nip-dropdown-btn-active-file">
            ${workbooks.map((wb) => `
              <li>
                <a href="#" data-val="${wb.name}" class="nip-dropdown-item inline-flex items-center justify-between w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg">
                  <span class="truncate">${wb.name}</span>
                  <span class="text-[11px] text-slate-400 shrink-0 ml-2">${wb.size ? `${(wb.size / 1024).toFixed(1)} KB` : ''}</span>
                </a>
              </li>
            `).join('')}
          </ul>
        </div>
      </div>
      <select id="nip-select-active-file" name="filename" class="hidden">
        ${workbooks.map((wb) => `<option value="${wb.name}" ${wb.name === activeName ? 'selected' : ''}>${wb.name}</option>`).join('')}
      </select>
    `;

    const btn = document.getElementById('nip-dropdown-btn-active-file');
    const menu = document.getElementById('nip-dropdown-menu-active-file');
    const label = document.getElementById('nip-dropdown-label-active-file');
    const selectEl = document.getElementById('nip-select-active-file');

    if (typeof initDropdowns === 'function') {
      initDropdowns();
    } else if (btn && menu) {
      btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
      });
    }

    listEl.querySelectorAll('.nip-dropdown-item').forEach((item) => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const val = item.getAttribute('data-val');
        if (label) label.textContent = val;
        if (selectEl) selectEl.value = val;
        if (menu) menu.classList.add('hidden');
      });
    });
  }
  // END - renderStoredWorkbooks

  // START - bindActionHandlers: Binds form submit and button listeners for applying selected active file
  bindActionHandlers() {
    const form = document.getElementById('nip-drawer-load-form');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleSetActiveWorkbook();
      });
    }

    const btnSubmit = document.getElementById('nip-btn-submit-load') || document.getElementById('nip-load-btn-apply');
    if (btnSubmit && !form) {
      btnSubmit.addEventListener('click', (e) => {
        e.preventDefault();
        this.handleSetActiveWorkbook();
      });
    }
  }
  // END - bindActionHandlers

  // START - bindDropzoneHandlers: Manages drag-and-drop & file selection for workbook upload in load drawer
  bindDropzoneHandlers() {
    const dropzoneInput = document.getElementById('nip-load-dropzone-file');
    const dropzoneLabel = dropzoneInput ? dropzoneInput.closest('label') : null;
    const filenameDisplay = document.getElementById('nip-load-dropzone-filename');
    const btnSubmit = document.getElementById('nip-btn-submit-load');

    if (!dropzoneInput || !dropzoneLabel) return;

    // Drag-over styling
    ['dragenter', 'dragover'].forEach((eventName) => {
      dropzoneLabel.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzoneLabel.classList.add('border-slate-500', 'bg-slate-100');
      });
    });

    // Drag-leave styling
    ['dragleave', 'drop'].forEach((eventName) => {
      dropzoneLabel.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzoneLabel.classList.remove('border-slate-500', 'bg-slate-100');
      });
    });

    // Handle Drop
    dropzoneLabel.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      if (dt && dt.files && dt.files.length) {
        dropzoneInput.files = dt.files;
        this.updateDropzoneUI(dt.files[0]);
      }
    });

    // Handle Change via Click
    dropzoneInput.addEventListener('change', () => {
      if (dropzoneInput.files && dropzoneInput.files.length) {
        this.updateDropzoneUI(dropzoneInput.files[0]);
      }
    });
  }
  // END - bindDropzoneHandlers

  // START - updateDropzoneUI: Updates dropzone label and submit button when file is selected
  updateDropzoneUI(file) {
    const filenameDisplay = document.getElementById('nip-load-dropzone-filename');
    const btnSubmit = document.getElementById('nip-btn-submit-load');

    if (filenameDisplay && file) {
      const sizeKb = (file.size / 1024).toFixed(1);
      filenameDisplay.textContent = `📄 ${file.name} (${sizeKb} KB)`;
      filenameDisplay.classList.remove('hidden');
    }

    if (btnSubmit && file) {
      btnSubmit.textContent = 'Upload & Switch Active Sheet';
    }
  }
  // END - updateDropzoneUI

  // START - loadWorkbookList: Scans d:/laragon/www/SHEETS via backend API
  async loadWorkbookList() {
    const container = document.getElementById('nip-drawer-load-files-list');
    const selectEl = document.getElementById('nip-select-active-file');
    if (!container && !selectEl) return;

    try {
      if (container) {
        container.innerHTML = `
          <div id="nip-drawer-load-loading" class="flex items-center justify-center p-4 text-xs text-slate-500">
            <svg class="w-4 h-4 animate-spin mr-2 text-slate-600" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Scanning server storage (d:/laragon/www/SHEETS)...
          </div>
        `;
      }

      const res = await Api.request('/api/v1/nip/files');
      if (res && res.success && res.data && Array.isArray(res.data.files)) {
        this.filesList = res.data.files;
        if (res.data.active_default) {
          this.activeFile = res.data.active_default;
        }
        this.renderFilesList(res.data.files, this.activeFile);
        this.syncDropdownsAcrossDrawers(res.data.files);
      } else {
        if (container) {
          container.innerHTML = '<div class="p-3 text-xs text-rose-600 bg-rose-50 border border-rose-200 rounded-lg">No Excel workbooks discovered in repository.</div>';
        }
      }
    } catch (err) {
      console.error('[NIP Load Drawer] Error loading files:', err);
      if (container) {
        container.innerHTML = '<div class="p-3 text-xs text-rose-600 bg-rose-50 border border-rose-200 rounded-lg">Failed to connect to file repository API.</div>';
      }
    }
  }
  // END - loadWorkbookList

  // START - renderFilesList: Renders selectable radio items for each discovered workbook
  renderFilesList(files, defaultFile) {
    const container = document.getElementById('nip-drawer-load-files-list');
    if (!container) return;

    if (!files.length) {
      container.innerHTML = '<div class="p-3 text-xs text-slate-400 bg-slate-50 rounded-lg">No workbooks found in workspace.</div>';
      return;
    }

    let html = '';
    files.forEach((file, idx) => {
      const isSelected = file.name === this.activeFile;
      html += `
        <label id="nip-file-item-${idx}" class="flex items-start gap-3 p-3 rounded-xl border ${isSelected ? 'border-slate-800 bg-slate-50 shadow-xs ring-1 ring-slate-800/10' : 'border-slate-200 bg-white hover:border-slate-300'} cursor-pointer transition-all">
          <input type="radio" name="nip_active_file" value="${file.name}" ${isSelected ? 'checked' : ''} class="mt-1 text-slate-900 focus:ring-slate-800" />
          <div id="nip-file-meta-${idx}" class="min-w-0 flex-1">
            <div id="nip-file-name-${idx}" class="text-xs font-bold text-slate-900 truncate">
              ${file.name}
            </div>
            <div id="nip-file-info-${idx}" class="text-[11px] text-slate-500 flex items-center gap-2 mt-0.5">
              <span>${file.size_kb} KB</span>
              <span>•</span>
              <span>${file.modified}</span>
              ${file.is_default ? '<span class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 text-[10px] font-semibold">Master</span>' : ''}
            </div>
          </div>
        </label>
      `;
    });

    container.innerHTML = html;

    container.querySelectorAll('input[name="nip_active_file"]').forEach((radio) => {
      radio.addEventListener('change', (e) => {
        this.activeFile = e.target.value;
        const selectEl = document.getElementById('nip-select-active-file');
        if (selectEl) selectEl.value = this.activeFile;
        const dropdownLabel = document.getElementById('nip-dropdown-label-active-file');
        if (dropdownLabel) dropdownLabel.textContent = this.activeFile;
        this.renderFilesList(this.filesList, this.activeFile);
      });
    });
  }
  // END - renderFilesList

  // START - syncDropdownsAcrossDrawers: Updates file selector options and Flowbite dropdown menu items
  syncDropdownsAcrossDrawers(files) {
    const selects = [
      document.getElementById('nip-select-active-file'),
      document.getElementById('nip-gen-source-file'),
      document.getElementById('nip-reset-source-file')
    ];

    selects.forEach((sel) => {
      if (!sel) return;
      const currentVal = sel.value;
      sel.innerHTML = '';
      files.forEach((file) => {
        const opt = document.createElement('option');
        opt.value = file.name;
        opt.textContent = `${file.name} (${file.size_kb} KB)`;
        if (file.name === this.activeFile) opt.selected = true;
        sel.appendChild(opt);
      });
      if (currentVal && files.some((f) => f.name === currentVal)) {
        sel.value = currentVal;
      }
    });

    // Update Flowbite Dropdown button label & menu list
    const dropdownLabel = document.getElementById('nip-dropdown-label-active-file');
    const dropdownList = document.getElementById('nip-dropdown-list-active-file');
    const dropdownMenu = document.getElementById('nip-dropdown-menu-active-file');

    if (dropdownLabel) {
      dropdownLabel.textContent = this.activeFile || 'Select active Excel file';
    }

    if (dropdownList && files.length > 0) {
      let menuHtml = '';
      files.forEach((file) => {
        const isSelected = file.name === this.activeFile;
        menuHtml += `
          <li>
            <button type="button" data-val="${file.name}" class="nip-dropdown-item inline-flex items-center justify-between w-full p-2.5 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors ${isSelected ? 'font-bold bg-slate-50 text-cho-blue-900' : ''}">
              <span class="truncate">${file.name}</span>
              <span class="text-[11px] text-slate-400 ms-2 shrink-0">${file.size_kb} KB</span>
            </button>
          </li>
        `;
      });
      dropdownList.innerHTML = menuHtml;

      // Attach click listeners to Flowbite dropdown items
      dropdownList.querySelectorAll('.nip-dropdown-item').forEach((itemBtn) => {
        itemBtn.addEventListener('click', (e) => {
          e.preventDefault();
          const chosenName = itemBtn.getAttribute('data-val');
          if (!chosenName) return;

          this.activeFile = chosenName;
          if (dropdownLabel) dropdownLabel.textContent = chosenName;

          const selectEl = document.getElementById('nip-select-active-file');
          if (selectEl) selectEl.value = chosenName;

          // Sync radio checked state
          this.renderFilesList(this.filesList, this.activeFile);

          // Hide Flowbite dropdown menu
          if (dropdownMenu) dropdownMenu.classList.add('hidden');
        });
      });
    }
  }
  // END - syncDropdownsAcrossDrawers

  // START - handleSetActiveWorkbook: Validates dropzone Excel file, saves to Storage.nipWorkbooks, and reveals #cho-systems-grid
  async handleSetActiveWorkbook() {
    const dropzoneInput = document.getElementById('nip-load-dropzone-file');
    const feedbackBox = document.getElementById('nip-load-feedback');

    // Case 1: A file was selected or dropped into the dropzone
    if (dropzoneInput && dropzoneInput.files && dropzoneInput.files.length > 0) {
      const file = dropzoneInput.files[0];
      const isExcel = /\.(xlsx|xls)$/i.test(file.name);

      if (!isExcel) {
        if (feedbackBox) {
          feedbackBox.classList.remove('hidden');
          feedbackBox.className = 'p-3 rounded-xl text-xs bg-rose-50 text-rose-800 border border-rose-200';
          feedbackBox.textContent = `Invalid file "${file.name}". Please upload a valid NIP Program Excel (.xlsx or .xls).`;
        }
        if (typeof Notifications.error === 'function') {
          Notifications.error('Please upload a valid NIP Program Excel (.xlsx or .xls).');
        }
        return;
      }

      Storage.nipWorkbooks.saveUploadedWorkbook(file);
      this.activeFile = file.name;
      await this.parseWorkbookDashboardStats(file);
      this.renderStoredWorkbooks();

      if (feedbackBox) {
        feedbackBox.classList.remove('hidden');
        feedbackBox.className = 'p-3 rounded-xl text-xs bg-emerald-50 text-emerald-800 border border-emerald-200';
        feedbackBox.textContent = `Loaded '${file.name}' into workspace.`;
      }

      if (Drawers.gate) {
        Drawers.gate.revealSystemsGrid();
      }

      if (typeof Notifications.success === 'function') {
        Notifications.success(`NIP Excel "${file.name}" loaded successfully.`);
      }

      setTimeout(() => {
        this.hide();
      }, 300);
      return;
    }

    // Case 2: Switch to a previously uploaded workbook from Storage.nipWorkbooks dropdown
    const selectEl = document.getElementById('nip-select-active-file');
    const chosenFile = selectEl ? selectEl.value : null;
    if (chosenFile) {
      Storage.nipWorkbooks.setActiveWorkbook(chosenFile);
      this.activeFile = chosenFile;
      this.renderStoredWorkbooks();

      if (feedbackBox) {
        feedbackBox.classList.remove('hidden');
        feedbackBox.className = 'p-3 rounded-xl text-xs bg-emerald-50 text-emerald-800 border border-emerald-200';
        feedbackBox.textContent = `Switched active workbook to '${chosenFile}'.`;
      }

      if (Drawers.gate) {
        Drawers.gate.revealSystemsGrid();
      }

      if (typeof Notifications.success === 'function') {
        Notifications.success(`Active NIP Excel switched to "${chosenFile}".`);
      }

      setTimeout(() => {
        this.hide();
      }, 300);
      return;
    }

    if (feedbackBox) {
      feedbackBox.classList.remove('hidden');
      feedbackBox.className = 'p-3 rounded-xl text-xs bg-amber-50 text-amber-800 border border-amber-200';
      feedbackBox.textContent = 'Please select or drop a NIP Program Excel (.xlsx) file first.';
    }

    if (typeof Notifications.warning === 'function') {
      Notifications.warning('Please select or drop a NIP Program Excel (.xlsx) file first.');
    }
  }
  // END - handleSetActiveWorkbook

  // START - parseWorkbookDashboardStats: Reads uploaded Excel via ExcelJS, caches KPIs & charts, and applies to dashboard
  async parseWorkbookDashboardStats(file) {
    try {
      const buffer = await file.arrayBuffer();
      const wb = new ExcelJS.Workbook();
      await wb.xlsx.load(buffer);

      const getNum = (val) => {
        const raw = typeof val === 'object' && val !== null ? (val.result ?? 0) : val;
        const num = Number(raw || 0);
        return Number.isNaN(num) ? 0 : num;
      };

      const getText = (val, fallback = '') => {
        if (val === null || val === undefined) return fallback;
        const raw = typeof val === 'object' ? (val.result ?? val.text ?? '') : val;
        const str = String(raw).trim();
        return str !== '' ? str : fallback;
      };

      const dashSheet = wb.getWorksheet('DASHBOARD');
      const hlSheet = wb.getWorksheet('H-L');

      // 1. Barangays from DASHBOARD!B8 & B9
      const barangayVal = dashSheet ? getNum(dashSheet.getCell('B8').value) : 0;
      const barangaySub = dashSheet ? getText(dashSheet.getCell('B9').value, 'No Barangay Data') : 'No Barangay Data';

      // 2. Total Population from H-L!B3:B76 & label from DASHBOARD!D9
      let totalPop = 0;
      if (hlSheet) {
        for (let r = 3; r <= 76; r++) {
          totalPop += getNum(hlSheet.getCell(`B${r}`).value);
        }
      }
      const popSub = dashSheet ? getText(dashSheet.getCell('D9').value, 'Marawi City Master Population') : 'Marawi City Master Population';

      // 3. FIC Accomplished from DASHBOARD!F8 & F9
      const ficAccomplished = dashSheet ? getNum(dashSheet.getCell('F8').value) : 0;
      const ficSub = dashSheet ? getText(dashSheet.getCell('F9').value, 'Fully Immunized Children') : 'Fully Immunized Children';

      // 4. FIC Coverage from DASHBOARD!H8 & H9 (fallback to ficAccomplished / totalPop if cached formula is stale)
      const rawCoverage = dashSheet ? getNum(dashSheet.getCell('H8').value) : 0;
      let coveragePct = rawCoverage > 0 && rawCoverage <= 1 ? (rawCoverage * 100).toFixed(1) : rawCoverage.toFixed(1);
      if (totalPop > 0 && ficAccomplished > 0) {
        coveragePct = ((ficAccomplished / totalPop) * 100).toFixed(1);
      }
      const covSub = dashSheet ? getText(dashSheet.getCell('H9').value, 'No Coverage Data') : 'No Coverage Data';

      // 5. 18 Antigens Performance (DASHBOARD!B16:H33)
      const antigens = [];
      if (dashSheet) {
        for (let r = 16; r <= 33; r++) {
          const name = getText(dashSheet.getCell(`B${r}`).value);
          if (name) {
            antigens.push({
              name,
              target: Math.round(getNum(dashSheet.getCell(`C${r}`).value)),
              total: Math.round(getNum(dashSheet.getCell(`H${r}`).value))
            });
          }
        }
      }

      // 6. 9 Catchments Share (DASHBOARD!J22:L30)
      const catchments = [];
      if (dashSheet) {
        for (let r = 22; r <= 30; r++) {
          const name = getText(dashSheet.getCell(`J${r}`).value);
          if (name) {
            catchments.push({
              name,
              barangays: Math.round(getNum(dashSheet.getCell(`K${r}`).value)),
              fic_total: Math.round(getNum(dashSheet.getCell(`L${r}`).value))
            });
          }
        }
      }

      const kpiData = {
        source_file: file.name,
        active_year: dashSheet ? getText(dashSheet.getCell('C5').value, '2026') : '2026',
        active_quarter: dashSheet ? getText(dashSheet.getCell('E5').value, 'Q1') : 'Q1',
        stats: {
          barangayVal,
          barangaySub,
          totalPop,
          popSub: totalPop > 0 ? popSub : 'No Population Data',
          ficAccomplished,
          ficSub: ficAccomplished > 0 ? ficSub : 'No Accomplished Data',
          coveragePct,
          covSub
        },
        antigens,
        catchments
      };

      Storage.nipDashboardCache?.save(kpiData);
      this.applyDashboardData(kpiData);
    } catch (err) {
      console.warn('[NipLoadDrawer] Could not parse workbook stats via ExcelJS:', err);
    }
  }
  // END - parseWorkbookDashboardStats

  // START - applyDashboardData: Populates stat cards, charts, filename indicator, and reveals #cho-systems-grid
  applyDashboardData(kpiData) {
    if (!kpiData || !kpiData.stats) return;
    const { barangayVal, barangaySub, totalPop, popSub, ficAccomplished, ficSub, coveragePct, covSub } = kpiData.stats;

    const elBrgyVal = document.getElementById('cho-stat-val-barangays');
    const elBrgySub = document.getElementById('cho-stat-sub-barangays');
    const elPopVal = document.getElementById('cho-stat-val-infants');
    const elPopSub = document.getElementById('cho-stat-sub-infants');
    const elChildVal = document.getElementById('cho-stat-val-childrens');
    const elChildSub = document.getElementById('cho-stat-sub-childrens');
    const elCovVal = document.getElementById('cho-stat-val-coverage');
    const elCovSub = document.getElementById('cho-stat-sub-coverage');
    const elCovPill = document.getElementById('cho-stat-pill-coverage');

    if (elBrgyVal) elBrgyVal.textContent = Number(barangayVal || 0).toLocaleString();
    if (elBrgySub) elBrgySub.textContent = barangaySub;

    if (elPopVal) elPopVal.textContent = Number(totalPop || 0).toLocaleString();
    if (elPopSub) elPopSub.textContent = popSub;

    if (elChildVal) elChildVal.textContent = Number(ficAccomplished || 0).toLocaleString();
    if (elChildSub) elChildSub.textContent = ficSub;

    if (elCovVal) elCovVal.textContent = `${coveragePct}%`;
    if (elCovSub) elCovSub.textContent = covSub;

    if (elCovPill) {
      const gap = Math.max(0, 95.0 - Number(coveragePct)).toFixed(1);
      elCovPill.innerHTML = `<span>📊</span> Gap: ${gap}%`;
    }

    // Update #cho-nip-active-file-code with the real uploaded filename & bind click-to-copy path
    const fileCodeEl = document.getElementById('cho-nip-active-file-code');
    const fileBtnEl = document.getElementById('cho-nip-active-file-btn');
    if (fileCodeEl && kpiData.source_file) fileCodeEl.textContent = kpiData.source_file;
    if (fileBtnEl && kpiData.source_file) {
      fileBtnEl.onclick = async () => {
        const fullPath = `d:/laragon/www/SHEETS/${kpiData.source_file}`;
        try {
          await navigator.clipboard.writeText(fullPath);
          if (typeof Notifications.success === 'function') {
            Notifications.success(`Copied path: ${fullPath}`);
          }
        } catch {
          if (typeof Notifications.info === 'function') {
            Notifications.info(`Workbook path: ${fullPath}`);
          }
        }
      };
    }

    // Hide #cho-nip-canvas-card and render ApexCharts inside #cho-nip-charts-container
    if (Charts && typeof Charts.initNipDashboardCharts === 'function') {
      Charts.initNipDashboardCharts(kpiData);
    }

    if (Drawers.gate) {
      Drawers.gate.revealSystemsGrid();
    }
  }
  // END - applyDashboardData

  // START - refreshDashboardKpis: Fetches KPIs and triggers Charts module to render
  async refreshDashboardKpis(filename) {
    if (!filename) return;
    try {
      const res = await Api.request(`/api/v1/nip/kpis?file=${encodeURIComponent(filename)}`);
      if (res && res.success && res.data) {
        const kpi = res.data;

        // Update top 4 stat cards if present
        const valPop = document.getElementById('cho-stat-val-barangays');
        const valTarget = document.getElementById('cho-stat-val-infants');
        const valFic = document.getElementById('cho-stat-val-childrens');
        const valCoverage = document.getElementById('cho-stat-val-coverage');

        if (valPop && kpi.total_population) valPop.textContent = Number(kpi.total_population).toLocaleString();
        if (valTarget && kpi.annual_target) valTarget.textContent = Number(kpi.annual_target).toLocaleString();
        if (valFic && kpi.fic_accomplished) valFic.textContent = Number(kpi.fic_accomplished).toLocaleString();
        if (valCoverage && kpi.fic_coverage_pct !== undefined) valCoverage.textContent = `${kpi.fic_coverage_pct}%`;

        // Render dynamic charts into #cho-nip-charts-container
        if (Charts && typeof Charts.initNipDashboardCharts === 'function') {
          Charts.initNipDashboardCharts(kpi);
        }
      }
    } catch (err) {
      console.warn('[NipLoadDrawer] Could not refresh dashboard KPIs:', err);
    }
  }
  // END - refreshDashboardKpis
}

// =============================================================================
// SUBCHILD CLASS 2: NipGenerateDrawer
// Handles blank year creation (zeroing 26,418 raw cells, intact formulas)
// =============================================================================
export class NipGenerateDrawer extends BaseDrawerController {
  constructor() {
    super('drawer-nip-generate');
  }

  // START - init: Initializes form handlers for blank year generation
  init() {
    super.init();
    if (!this.drawerEl) return;

    this.bindFormHandlers();
  }
  // END - init

  // START - bindFormHandlers: Attaches submit event listener to generate year form
  bindFormHandlers() {
    const formGenerate = document.getElementById('nip-drawer-generate-form') || document.getElementById('nip-form-generate-year');
    if (formGenerate) {
      formGenerate.addEventListener('submit', (e) => this.handleGenerateYear(e));
    }
  }
  // END - bindFormHandlers

  // START - handleGenerateYear: Executes blank year generation via API with period parameters
  async handleGenerateYear(e) {
    e.preventDefault();
    const btnSubmit = document.getElementById('nip-btn-submit-generate');
    const inputYear = document.getElementById('nip-input-generate-year') || document.getElementById('nip-gen-year');
    const selectSource = document.getElementById('nip-gen-source-file') || document.getElementById('nip-select-active-file');
    const feedbackBox = document.getElementById('nip-generate-feedback') || document.getElementById('nip-gen-feedback');

    const payload = {
      year: parseInt(inputYear?.value || '2027', 10),
      source_file: selectSource?.value || 'MARAWI NIP DATA - UPGRADED.xlsx'
    };

    if (btnSubmit) {
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = `
        <svg class="w-4 h-4 animate-spin -ml-1 mr-2 text-white" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        Zeroing 26,418 cells...
      `;
    }

    if (feedbackBox) {
      feedbackBox.classList.remove('hidden');
      feedbackBox.className = 'p-3 rounded-xl text-xs bg-slate-100 text-slate-800 border border-slate-200';
      feedbackBox.textContent = `Cloning 29 sheets from ${payload.source_file} and initializing Year ${payload.year}...`;
    }

    try {
      const res = await Api.request('/api/v1/nip/generate-year', {
        method: 'POST',
        body: payload
      });

      if (res && res.success) {
        if (typeof Notifications.success === 'function') {
          Notifications.success(res.message || `Clean Year ${payload.year} initialized with 8,500+ formulas intact.`);
        }

        if (feedbackBox) {
          const generatedFile = res.data?.filename || `MARAWI NIP DATA - ${payload.year}.xlsx`;
          const basePath = window.location.pathname.includes('/CHO-System') ? '/CHO-System' : '';
          const rawUrl = res.download_url || `/api/v1/nip/download?file=${encodeURIComponent(generatedFile)}`;
          const downloadUrl = (rawUrl.startsWith('/api/') && basePath) ? `${basePath}${rawUrl}` : rawUrl;

          feedbackBox.className = 'w-full';
          feedbackBox.innerHTML = `
            <div id="toast-generate-success" class="flex items-start w-full p-4 text-slate-700 bg-white rounded-xl shadow-xs border border-slate-200" role="alert">
              <div class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-emerald-600 bg-emerald-100 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/></svg>
                <span class="sr-only">Check icon</span>
              </div>
              <div class="ms-3 text-xs font-normal flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                  <button type="button" id="nip-btn-save-as-generated" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 hover:underline cursor-pointer">📥 Choose Folder & Save (.xlsx)</button>
                  <button type="button" id="nip-btn-copy-generated-path" class="text-[11px] font-mono text-cho-blue-700 hover:underline cursor-pointer truncate">${generatedFile}</button>
                </div>
              </div>
              <button type="button" onclick="document.getElementById('toast-generate-success')?.remove()" class="ms-auto flex items-center justify-center text-slate-400 hover:text-slate-700 bg-transparent border border-transparent hover:bg-slate-100 rounded-lg text-sm h-7 w-7 focus:outline-none cursor-pointer" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
              </button>
            </div>
          `;

          const saveBtn = document.getElementById('nip-btn-save-as-generated');
          if (saveBtn) {
            saveBtn.addEventListener('click', async () => {
              try {
                const fileRes = await fetch(downloadUrl, { credentials: 'same-origin' });
                const blob = await fileRes.blob();
                if (typeof window.showSaveFilePicker === 'function') {
                  const handle = await window.showSaveFilePicker({
                    suggestedName: generatedFile,
                    types: [{
                      description: 'Excel Workbook',
                      accept: { 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': ['.xlsx'] }
                    }]
                  });
                  const writable = await handle.createWritable();
                  await writable.write(blob);
                  await writable.close();
                  if (typeof Notifications.success === 'function') {
                    Notifications.success(`Saved "${generatedFile}" to your chosen folder!`);
                  }
                } else {
                  const a = document.createElement('a');
                  a.href = URL.createObjectURL(blob);
                  a.download = generatedFile;
                  a.click();
                  URL.revokeObjectURL(a.href);
                }
              } catch (saveErr) {
                if (saveErr.name !== 'AbortError') {
                  console.warn('[NipGenerateDrawer] Save As failed:', saveErr);
                }
              }
            });
          }

          const copyBtn = document.getElementById('nip-btn-copy-generated-path');
          if (copyBtn) {
            copyBtn.addEventListener('click', async () => {
              const fullPath = `d:/laragon/www/SHEETS/${generatedFile}`;
              try {
                await navigator.clipboard.writeText(fullPath);
                if (typeof Notifications.success === 'function') {
                  Notifications.success(`Copied path: ${fullPath}`);
                }
              } catch {
                if (typeof Notifications.info === 'function') {
                  Notifications.info(`Workbook path: ${fullPath}`);
                }
              }
            });
          }

          // Show floating Flowbite toast at bottom-right while drawer is hidden
          document.getElementById('cho-floating-generate-toast')?.remove();
          const floatingToast = document.createElement('div');
          floatingToast.id = 'cho-floating-generate-toast';
          floatingToast.className = 'fixed bottom-5 right-5 z-[9999] flex items-center w-full max-w-sm p-4 text-slate-700 bg-white rounded-xl shadow-lg border border-slate-200';
          floatingToast.setAttribute('role', 'alert');
          floatingToast.innerHTML = `
            <div class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-emerald-600 bg-emerald-100 rounded-lg">
              <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/></svg>
              <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">Generated <strong>${generatedFile}</strong> successfully.</div>
            <button type="button" onclick="this.parentElement.remove()" class="ms-auto flex items-center justify-center text-slate-400 hover:text-slate-700 bg-transparent border border-transparent hover:bg-slate-100 rounded-lg text-sm h-8 w-8 focus:outline-none cursor-pointer" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
            </button>
          `;
          document.body.appendChild(floatingToast);

          // Temporarily hide drawer, show bottom-right toast, then reopen drawer & trigger Choose Folder & Save
          this.hide();
          setTimeout(() => {
            floatingToast.remove();
            this.show();
            saveBtn?.click();
          }, 1800);
        }

        // Notify load drawer to reload available files
        if (window.CHO?.Drawers?.load) {
          window.CHO.Drawers.load.loadWorkbookList();
        }
      } else {
        throw new Error(res?.message || 'Failed to generate workbook');
      }
    } catch (err) {
      console.error('[NIP Generate Drawer] Generate year failed:', err);
      if (typeof Notifications.error === 'function') {
        Notifications.error(err.message || 'An error occurred while generating blank year sheet.');
      }

      if (feedbackBox) {
        feedbackBox.className = 'p-3 rounded-xl text-xs bg-rose-50 text-rose-800 border border-rose-200';
        feedbackBox.textContent = `Error: ${err.message}`;
      }
    } finally {
      if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.textContent = 'Generate Pristine Workbook';
      }
    }
  }
  // END - handleGenerateYear
}

// =============================================================================
// SUBCHILD CLASS 3: NipResetDrawer
// Handles raw input resets for specific quarter or month slots
// =============================================================================
export class NipResetDrawer extends BaseDrawerController {
  constructor() {
    super('drawer-nip-reset');
  }

  // START - init: Initializes form handlers and period dropdown for quarter input reset
  init() {
    super.init();
    if (!this.drawerEl) return;

    this.bindFormHandlers();
    this.bindPeriodDropdown();
  }
  // END - init

  // START - bindPeriodDropdown: Binds Flowbite dropdown selection for target quarter
  bindPeriodDropdown() {
    const dropdownList = document.getElementById('nip-dropdown-list-reset-period');
    const dropdownLabel = document.getElementById('nip-dropdown-label-reset-period');
    const dropdownMenu = document.getElementById('nip-dropdown-menu-reset-period');
    const hiddenSelect = document.getElementById('nip-select-reset-period');

    if (!dropdownList) return;

    dropdownList.querySelectorAll('.nip-period-dropdown-item').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const periodVal = btn.getAttribute('data-val');
        const periodText = btn.textContent.trim();

        if (dropdownLabel) dropdownLabel.textContent = periodText;
        if (hiddenSelect) hiddenSelect.value = periodVal;
        if (dropdownMenu) dropdownMenu.classList.add('hidden');
      });
    });
  }
  // END - bindPeriodDropdown

  // START - bindFormHandlers: Attaches submit event listener to quarter reset form
  bindFormHandlers() {
    const formReset = document.getElementById('nip-drawer-reset-form') || document.getElementById('nip-form-reset-quarter');
    if (formReset) {
      formReset.addEventListener('submit', (e) => this.handleResetQuarter(e));
    }
  }
  // END - bindFormHandlers

  // START - handleResetQuarter: Resets raw input cells for selected quarter via API
  async handleResetQuarter(e) {
    e.preventDefault();
    const btnSubmit = document.getElementById('nip-btn-submit-reset');
    const selectQuarter = document.getElementById('nip-select-reset-period') || document.getElementById('nip-reset-quarter');
    const inputDate = document.getElementById('nip-input-reset-effective-date');
    const selectSource = document.getElementById('nip-reset-source-file') || document.getElementById('nip-select-active-file');
    const feedbackBox = document.getElementById('nip-reset-feedback');

    const quarter = selectQuarter?.value || 'Q1';
    const effectiveDate = inputDate?.value || new Date().toISOString().split('T')[0];
    const sourceFile = selectSource?.value || 'MARAWI NIP DATA - UPGRADED.xlsx';

    if (!confirm(`Are you sure you want to reset raw inputs for ${quarter} on '${sourceFile}'? All raw entries for this period will be zeroed, keeping all formulas intact.`)) {
      return;
    }

    if (btnSubmit) {
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = `
        <svg class="w-4 h-4 animate-spin -ml-1 mr-2 text-white" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        Resetting ${quarter} cells...
      `;
    }

    try {
      const res = await Api.request('/api/v1/nip/reset-quarter', {
        method: 'POST',
        body: {
          source_file: sourceFile,
          quarter: quarter,
          effective_date: effectiveDate
        }
      });

      if (res && res.success) {
        Notifications.show({
          title: 'Quarter Reset Complete',
          message: res.message || `Successfully reset raw inputs for ${quarter}.`,
          type: 'success'
        });

        if (feedbackBox) {
          feedbackBox.classList.remove('hidden');
          feedbackBox.className = 'p-3 rounded-xl text-xs bg-emerald-50 text-emerald-800 border border-emerald-200';
          feedbackBox.textContent = res.message || `Quarter ${quarter} raw inputs successfully cleared.`;
        }
      } else {
        throw new Error(res?.message || 'Failed to reset quarter');
      }
    } catch (err) {
      console.error('[NIP Reset Drawer] Quarter reset error:', err);
      Notifications.show({
        title: 'Reset Error',
        message: err.message || 'Could not reset quarter inputs.',
        type: 'error'
      });

      if (feedbackBox) {
        feedbackBox.classList.remove('hidden');
        feedbackBox.className = 'p-3 rounded-xl text-xs bg-rose-50 text-rose-800 border border-rose-200';
        feedbackBox.textContent = `Error: ${err.message}`;
      }
    } finally {
      if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.textContent = 'Confirm & Reset Quarter Raw Inputs';
      }
    }
  }
  // END - handleResetQuarter
}

// =============================================================================
// SUBCHILD CLASS 4: NipUploadDrawer
// Handles uploading updated field workbooks (.xlsx or .xlsm)
// =============================================================================
export class NipUploadDrawer extends BaseDrawerController {
  constructor() {
    super('drawer-nip-upload');
  }

  // START - init: Initializes form handlers for file upload
  init() {
    super.init();
    if (!this.drawerEl) return;

    this.bindFormHandlers();
  }
  // END - init

  // START - bindFormHandlers: Attaches submit event listener to upload form
  bindFormHandlers() {
    const formUpload = document.getElementById('nip-drawer-upload-form') || document.getElementById('nip-form-upload');
    if (formUpload) {
      formUpload.addEventListener('submit', (e) => this.handleUpload(e));
    }
  }
  // END - bindFormHandlers

  // START - handleUpload: Uploads custom Excel workbook file via multipart FormData
  async handleUpload(e) {
    e.preventDefault();
    const fileInput = document.getElementById('nip-upload-file-input');
    const btnSubmit = document.getElementById('nip-btn-submit-upload');
    const feedbackBox = document.getElementById('nip-upload-feedback');

    if (!fileInput || !fileInput.files.length) {
      Notifications.show({
        title: 'File Required',
        message: 'Please choose an Excel file (.xlsx or .xlsm) to upload.',
        type: 'warning'
      });
      return;
    }

    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('workbook', file);

    if (btnSubmit) {
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = `
        <svg class="w-4 h-4 animate-spin -ml-1 mr-2 text-white" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        Uploading workbook...
      `;
    }

    try {
      const res = await Api.request('/api/v1/nip/upload', {
        method: 'POST',
        body: formData
      });

      if (res && res.success) {
        Notifications.show({
          title: 'Workbook Uploaded!',
          message: res.message || `File ${file.name} saved to repository.`,
          type: 'success'
        });

        if (feedbackBox) {
          feedbackBox.classList.remove('hidden');
          feedbackBox.className = 'p-3 rounded-xl text-xs bg-emerald-50 text-emerald-800 border border-emerald-200';
          feedbackBox.textContent = res.message || 'File uploaded successfully.';
        }

        fileInput.value = '';

        // Refresh file list in Load drawer
        if (window.CHO?.Drawers?.load) {
          window.CHO.Drawers.load.loadWorkbookList();
        }
      } else {
        throw new Error(res?.message || 'Failed to upload workbook.');
      }
    } catch (err) {
      console.error('[NIP Upload Drawer] Upload error:', err);
      Notifications.show({
        title: 'Upload Failed',
        message: err.message || 'Could not upload workbook to server.',
        type: 'error'
      });

      if (feedbackBox) {
        feedbackBox.classList.remove('hidden');
        feedbackBox.className = 'p-3 rounded-xl text-xs bg-rose-50 text-rose-800 border border-rose-200';
        feedbackBox.textContent = `Error: ${err.message}`;
      }
    } finally {
      if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.textContent = 'Upload Excel Workbook';
      }
    }
  }
  // END - handleUpload
}

// =============================================================================
// SUBCHILD CLASS 5: NipWorkspaceGate
// Gates #cho-systems-grid behind initial NIP Program Excel import/load
// =============================================================================
export class NipWorkspaceGate {
  // START - constructor: Caches #cho-systems-grid and #cho-nip-canvas-card elements
  constructor() {
    this.gridEl = null;
    this.canvasCardEl = null;
  }
  // END - constructor

  // START - init: Ensures #cho-systems-grid starts hidden only on NIP Dashboard (where #cho-nip-canvas-card exists)
  init() {
    this.gridEl = document.getElementById('cho-systems-grid');
    this.canvasCardEl = document.getElementById('cho-nip-canvas-card');
    if (!this.canvasCardEl) return;
    this.lockSystemsGrid();
  }
  // END - init

  // START - lockSystemsGrid: Hides #cho-systems-grid and expands #cho-nip-canvas-card
  lockSystemsGrid() {
    if (this.gridEl) {
      this.gridEl.classList.add('hidden');
    }
    if (this.canvasCardEl) {
      this.canvasCardEl.classList.remove('min-h-[44vh]');
      this.canvasCardEl.classList.add('min-h-[65vh]');
    }
  }
  // END - lockSystemsGrid

  // START - revealSystemsGrid: Reveals #cho-systems-grid once valid NIP Excel is loaded
  revealSystemsGrid() {
    if (this.gridEl) {
      this.gridEl.classList.remove('hidden');
    }
    if (this.canvasCardEl) {
      this.canvasCardEl.classList.remove('min-h-[65vh]');
      this.canvasCardEl.classList.add('min-h-[44vh]');
    }
  }
  // END - revealSystemsGrid
}

// =============================================================================
// UNIFIED DRAWER MANAGER
// Bootstraps and exports all individual subchild drawers
// =============================================================================
export const Drawers = {
  load: new NipLoadDrawer(),
  generate: new NipGenerateDrawer(),
  reset: new NipResetDrawer(),
  upload: new NipUploadDrawer(),
  gate: new NipWorkspaceGate(),

  init() {
    this.gate.init();
    this.load.init();
    this.generate.init();
    this.reset.init();
    this.upload.init();
  }
};

export const Drawer = Drawers;
