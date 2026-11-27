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
import { AppAnimations } from './animations.js';
import { initTooltips } from 'flowbite';

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
        Drawers.drafts?.clearDrawerDrafts(this.drawerId);
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

    // Temporarily hide the top-center resync banner while any drawer is open
    const resyncBanner = document.getElementById('cho-nip-resync-toast');
    if (resyncBanner) resyncBanner.classList.add('hidden');
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

    // Restore the top-center resync banner if a resync is still pending
    const resyncBanner = document.getElementById('cho-nip-resync-toast');
    if (resyncBanner && Storage.nipResyncState?.getPending?.()) {
      resyncBanner.classList.remove('hidden');
    }
  }
  // END - hide
}

// =============================================================================
// SUBCHILD CLASS: NipPopWorksheetResolver
// Auto-detects the Marawi Population worksheet across yearly/semi-annual workbooks
// =============================================================================
// START - NipPopWorksheetResolver
export class NipPopWorksheetResolver {
  // START - resolve: Finds MARAWI POP <year>, MARAWI POP SEMI - <year>, or MARAWI POP* sheet
  resolve(workbook) {
    if (!workbook || !Array.isArray(workbook.worksheets)) {
      return { sheet: null, name: null };
    }

    const sheets = workbook.worksheets.filter((ws) => ws && typeof ws.name === 'string');
    const patterns = [
      /^MARAWI\s+POP\s+\d{4}$/i,
      /^MARAWI\s+POP\s+SEMI\s*-\s*\d{4}$/i,
      /^MARAWI\s+POP/i
    ];

    for (const pattern of patterns) {
      const match = sheets.find((ws) => pattern.test(ws.name.trim()));
      if (match) {
        return { sheet: match, name: match.name };
      }
    }

    return { sheet: null, name: null };
  }
  // END - resolve
}
// END - NipPopWorksheetResolver

// =============================================================================
// SUBCHILD CLASS 1: NipLoadDrawer
// Handles scanning, selecting, and applying active Excel workbooks
// =============================================================================
export class NipLoadDrawer extends BaseDrawerController {
  constructor() {
    super('drawer-nip-load');
    this.activeFile = null;
    this.filesList = [];
    this.popResolver = new NipPopWorksheetResolver();
  }

  // START - init: Mounts drawer triggers, dropzone handlers, and restores cached Excel dashboard if present
  init() {
    super.init();
    if (!this.drawerEl) return;

    this.bindActionHandlers();
    this.bindDropzoneHandlers();
    this.renderStoredWorkbooks();

    const cached = Storage.nipDashboardCache?.get();
    if (cached && cached.source_file && cached.stats) {
      this.activeFile = cached.source_file || this.activeFile;
      this.applyDashboardData(cached);
    } else {
      this.resetDashboardToSkeleton();
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

    const btnText = document.getElementById('nip-btn-submit-load-text');
    if (btnText && file) {
      btnText.textContent = 'Upload & Switch Active Sheet';
    } else if (btnSubmit && file) {
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
          <div id="nip-drawer-load-loading" role="status" class="p-3 space-y-3 animate-pulse">
            <div class="flex items-center justify-between"><div class="h-3 bg-slate-200 rounded-full w-40"></div><div class="h-3 bg-slate-200 rounded-full w-12"></div></div>
            <div class="flex items-center justify-between"><div class="h-3 bg-slate-200 rounded-full w-36"></div><div class="h-3 bg-slate-200 rounded-full w-10"></div></div>
            <div class="flex items-center justify-between"><div class="h-3 bg-slate-200 rounded-full w-44"></div><div class="h-3 bg-slate-200 rounded-full w-12"></div></div>
            <span class="sr-only">Scanning server storage...</span>
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
    const btnSubmit = document.getElementById('nip-btn-submit-load');
    const btnSpinner = document.getElementById('nip-btn-submit-load-spinner');
    const btnText = document.getElementById('nip-btn-submit-load-text');

    const setSubmitLoading = (isLoading, label = 'Processing Workbook...') => {
      if (!btnSubmit) return;
      btnSubmit.disabled = isLoading;
      if (btnSpinner) {
        btnSpinner.classList.toggle('hidden', !isLoading);
      }
      if (btnText) {
        if (isLoading) {
          btnText.dataset.prevText = btnText.textContent;
          btnText.textContent = label;
        } else if (btnText.dataset.prevText) {
          btnText.textContent = btnText.dataset.prevText;
          delete btnText.dataset.prevText;
        }
      }
    };

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

      setSubmitLoading(true, 'Processing Workbook...');
      try {
        // Yield a frame so the Flowbite spinner renders before heavy ExcelJS parsing
        await new Promise((resolve) => setTimeout(resolve, 450));

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
      } finally {
        setSubmitLoading(false);
      }
      return;
    }

    // Case 2: Switch to a previously uploaded workbook from Storage.nipWorkbooks dropdown
    const selectEl = document.getElementById('nip-select-active-file');
    const chosenFile = selectEl ? selectEl.value : null;
    if (chosenFile) {
      setSubmitLoading(true, 'Switching Active Sheet...');
      try {
        await new Promise((resolve) => setTimeout(resolve, 450));

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
      } finally {
        setSubmitLoading(false);
      }
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
      const { default: ExcelJS } = await import('exceljs');
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
      const annualSheet = wb.getWorksheet('ANNUAL');
      const hlSheet = wb.getWorksheet('H-L');
      const { sheet: popSheet, name: popSheetName } = this.popResolver.resolve(wb);
      const existingDash = Storage.nipDashboardCache?.get?.();
      const b8Val = dashSheet ? dashSheet.getCell('B8').value : null;
      const hasMissingCachedFormula = Boolean(
        b8Val && typeof b8Val === 'object' && b8Val.formula && (b8Val.result === undefined || b8Val.result === null)
      );

      if (hasMissingCachedFormula && existingDash && existingDash.source_file === file.name && existingDash.stats?.barangayVal > 0 && !annualSheet && !popSheet) {
        this.applyDashboardData(existingDash);
      } else {
        // 1. Total Population Card (#cho-stat-val-barangays) from auto-detected MARAWI POP C4 (fallback DASHBOARD!B8, ANNUAL!F5)
        const popC4 = popSheet ? Math.round(getNum(popSheet.getCell('C4').value)) : 0;
        const dashB8 = dashSheet ? Math.round(getNum(dashSheet.getCell('B8').value)) : 0;
        const annualF5 = annualSheet ? Math.round(getNum(annualSheet.getCell('F5').value)) : 0;
        const barangayVal = popC4 > 0 ? popC4 : (dashB8 > 0 ? dashB8 : annualF5);
        const barangaySub = popSheetName
          ? `${popSheetName} (C4)`
          : (dashSheet ? getText(dashSheet.getCell('B9').value, 'Marawi City Census') : 'Marawi City Census');

        // 2. Annual Eligible Target Card (#cho-stat-card-infants / #cho-stat-val-infants) from auto-detected MARAWI POP I4 (fallback DASHBOARD!D8, ANNUAL!C27)
        const popI4 = popSheet ? Math.round(getNum(popSheet.getCell('I4').value)) : 0;
        const dashD8 = dashSheet ? Math.round(getNum(dashSheet.getCell('D8').value)) : 0;
        const annualC27 = annualSheet ? Math.round(getNum(annualSheet.getCell('C27').value)) : 0;
        let totalPop = popI4 > 0 ? popI4 : (dashD8 > 0 ? dashD8 : annualC27);
        if (totalPop === 0 && hlSheet) {
          for (let r = 3; r <= 76; r++) {
            totalPop += getNum(hlSheet.getCell(`B${r}`).value);
          }
          totalPop = Math.round(totalPop);
        }
        const popSub = popSheetName
          ? `Annual Eligible Target (${popSheetName} I4)`
          : (dashSheet ? getText(dashSheet.getCell('D9').value, 'Annual Eligible Target') : 'Annual Eligible Target');

        // 3. FIC Accomplished Card (#cho-stat-val-childrens) from ANNUAL!F27 (=SUM(D27:E27))
        let ficAccomplished = annualSheet ? Math.round(getNum(annualSheet.getCell('F27').value)) : 0;
        if (annualSheet && ficAccomplished === 0) {
          const d27 = getNum(annualSheet.getCell('D27').value);
          const e27 = getNum(annualSheet.getCell('E27').value);
          if (d27 > 0 || e27 > 0) {
            ficAccomplished = Math.round(d27 + e27);
          }
        } else if (!annualSheet && dashSheet) {
          ficAccomplished = Math.round(getNum(dashSheet.getCell('F8').value));
        }
        const ficSub = dashSheet ? getText(dashSheet.getCell('F9').value, 'Fully Immunized Children') : 'Fully Immunized Children';

        // 4. FIC Coverage Card (#cho-stat-val-coverage) computed against Annual Eligible Target (I4) or ANNUAL!G27
        const rawCoverage = annualSheet
          ? getNum(annualSheet.getCell('G27').value)
          : (dashSheet ? getNum(dashSheet.getCell('H8').value) : 0);
        let coveragePct = rawCoverage > 0 && rawCoverage <= 1 ? (rawCoverage * 100).toFixed(1) : rawCoverage.toFixed(1);
        if (totalPop > 0 && ficAccomplished > 0) {
          coveragePct = ((ficAccomplished / totalPop) * 100).toFixed(1);
        }
        const covSub = dashSheet ? getText(dashSheet.getCell('H9').value, 'Annual Coverage Rate') : 'Annual Coverage Rate';

        // 5. 18 Antigens Performance (ANNUAL!A11:F28, with Q1..Q4 & DASHBOARD fallback)
        const antigens = [];
        if (annualSheet) {
          const qSheets = ['Q1', 'Q2', 'Q3', 'Q4'].map((qName) => wb.getWorksheet(qName)).filter(Boolean);
          const sharedTarget = Math.round(getNum(annualSheet.getCell('C27').value));

          for (let r = 11; r <= 28; r++) {
            const rawName = getText(annualSheet.getCell(`A${r}`).value);
            const name = rawName.replace(/\u00a0/g, ' ').replace(/\s+/g, ' ').trim();
            if (!name) continue;

            const rowTarget = Math.round(getNum(annualSheet.getCell(`C${r}`).value)) || sharedTarget;
            let male = Math.round(getNum(annualSheet.getCell(`D${r}`).value));
            let female = Math.round(getNum(annualSheet.getCell(`E${r}`).value));
            let total = Math.round(getNum(annualSheet.getCell(`F${r}`).value));

            if (male === 0 && female === 0 && total === 0 && qSheets.length > 0) {
              const qRow = 103 + (r - 11);
              for (const qs of qSheets) {
                male += Math.round(getNum(qs.getCell(`D${qRow}`).value));
                female += Math.round(getNum(qs.getCell(`E${qRow}`).value));
              }
            }
            if (total === 0 && (male > 0 || female > 0)) {
              total = male + female;
            }

            antigens.push({
              name,
              target: rowTarget,
              male,
              female,
              total
            });
          }
        } else if (dashSheet) {
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

        // 6. 9 Catchments Share (DASHBOARD!J22:M30)
        const catchments = [];
        if (dashSheet) {
          for (let r = 22; r <= 30; r++) {
            const name = getText(dashSheet.getCell(`J${r}`).value);
            if (name) {
              const ficTotal = Math.round(getNum(dashSheet.getCell(`L${r}`).value));
              const rawShare = getNum(dashSheet.getCell(`M${r}`).value);
              let sharePct = rawShare > 0 && rawShare <= 1 ? (rawShare * 100).toFixed(1) : rawShare.toFixed(1);
              if (ficAccomplished > 0) {
                sharePct = ((ficTotal / ficAccomplished) * 100).toFixed(1);
              }
              catchments.push({
                name,
                barangays: Math.round(getNum(dashSheet.getCell(`K${r}`).value)),
                fic_total: ficTotal,
                share_pct: sharePct
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
      }

      // 7. Parse auto-detected MARAWI POP worksheet and persist to Storage.nipPopulationCache
      if (popSheet) {
        const cityTotalPop = Math.round(getNum(popSheet.getCell('C4').value));
        const cityHouseholdPop = Math.round(getNum(popSheet.getCell('D4').value));
        const cityHouseholds = Math.round(getNum(popSheet.getCell('E4').value));

        const nipMap = new Map();
        for (let r = 5; r <= popSheet.rowCount; r++) {
          const nipName = getText(popSheet.getCell(`G${r}`).value);
          if (!nipName) continue;
          const nipPop = Math.round(getNum(popSheet.getCell(`H${r}`).value));
          const rawTarget = getNum(popSheet.getCell(`I${r}`).value);
          const nipTarget = rawTarget > 0 ? Number(rawTarget.toFixed(1)) : Number((nipPop * 0.02056).toFixed(1));
          nipMap.set(nipName.toLowerCase(), { nipPop, nipTarget, nipRow: r });
        }

        const popRows = [];
        for (let r = 5; r <= popSheet.rowCount; r++) {
          const psgc10 = getText(popSheet.getCell(`A${r}`).value);
          const barangay = getText(popSheet.getCell(`B${r}`).value);
          if (!psgc10 && !barangay) continue;

          const totalPop = Math.round(getNum(popSheet.getCell(`C${r}`).value));
          const householdPop = Math.round(getNum(popSheet.getCell(`D${r}`).value));
          const households = Math.round(getNum(popSheet.getCell(`E${r}`).value));
          const nipEntry = nipMap.get(barangay.toLowerCase()) || { nipPop: 0, nipTarget: 0, nipRow: null };

          popRows.push({
            row_index: popRows.length + 1,
            excel_row: r,
            nip_row: nipEntry.nipRow,
            psgc10: psgc10 || '-',
            barangay,
            total_pop: totalPop,
            household_pop: householdPop,
            households,
            nip_pop: nipEntry.nipPop,
            nip_target: nipEntry.nipTarget
          });
        }

        Storage.nipPopulationCache?.save({
          source_file: file.name,
          sheet_name: popSheetName || 'MARAWI POP 2024',
          citySummary: { totalPop: cityTotalPop, householdPop: cityHouseholdPop, households: cityHouseholds },
          rows: popRows
        });
      }
    } catch (err) {
      console.warn('[NipLoadDrawer] Could not parse workbook stats via ExcelJS:', err);
    }
  }
  // END - parseWorkbookDashboardStats

  // START - resetDashboardToSkeleton: Restores Flowbite skeleton loaders and hides charts when no Excel is loaded
  resetDashboardToSkeleton() {
    const chartsContainer = document.getElementById('cho-nip-charts-container');
    const chartsSkeleton = document.getElementById('cho-nip-charts-skeleton');
    const canvasPlaceholder = document.getElementById('cho-nip-canvas-card');

    if (chartsContainer) chartsContainer.classList.add('hidden');
    if (chartsSkeleton) chartsSkeleton.classList.add('hidden');
    if (canvasPlaceholder) canvasPlaceholder.classList.remove('hidden');

    if (Drawers.gate) {
      Drawers.gate.lockSystemsGrid();
    }

    const skeletonHtml = (widthClass = 'w-28') => `
      <div role="status" class="animate-pulse py-1.5">
        <div class="h-8 bg-white/30 rounded-md ${widthClass}"></div>
        <span class="sr-only">Loading...</span>
      </div>
    `;

    const cardSpecs = [
      { valId: 'cho-stat-val-barangays', subId: 'cho-stat-sub-barangays', width: 'w-28' },
      { valId: 'cho-stat-val-infants', subId: 'cho-stat-sub-infants', width: 'w-28' },
      { valId: 'cho-stat-val-childrens', subId: 'cho-stat-sub-childrens', width: 'w-28' },
      { valId: 'cho-stat-val-coverage', subId: 'cho-stat-sub-coverage', width: 'w-24' }
    ];

    cardSpecs.forEach(({ valId, subId, width }) => {
      const valEl = document.getElementById(valId);
      const subEl = document.getElementById(subId);
      if (valEl) valEl.innerHTML = skeletonHtml(width);
      if (subEl) subEl.textContent = 'Awaiting Excel Upload';
    });

    const fileCodeEl = document.getElementById('cho-nip-active-file-code');
    if (fileCodeEl) fileCodeEl.textContent = 'None';
  }
  // END - resetDashboardToSkeleton

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

    if (elBrgyVal) AppAnimations.counter.rollTo(elBrgyVal, Number(barangayVal || 0), { duration: 700 });
    if (elBrgySub) elBrgySub.textContent = barangaySub;

    if (elPopVal) AppAnimations.counter.rollTo(elPopVal, Number(totalPop || 0), { duration: 850 });
    if (elPopSub) elPopSub.textContent = popSub;

    if (elChildVal) AppAnimations.counter.rollTo(elChildVal, Number(ficAccomplished || 0), { duration: 800 });
    if (elChildSub) elChildSub.textContent = ficSub;

    if (elCovVal) AppAnimations.counter.rollTo(elCovVal, Number(coveragePct || 0), { duration: 750, decimals: 1, suffix: '%' });
    if (elCovSub) elCovSub.textContent = covSub;

    if (elCovPill) {
      const gap = Math.max(0, 95.0 - Number(coveragePct)).toFixed(1);
      elCovPill.innerHTML = `<span>📊</span> Gap: ${gap}%`;
    }

    // Update #cho-nip-active-file-code with the real uploaded filename & bind click-to-copy path
    const fileCodeEl = document.getElementById('cho-nip-active-file-code');
    const fileBtnEl = document.getElementById('cho-nip-active-file-btn');
    if (fileCodeEl && kpiData.source_file) fileCodeEl.textContent = kpiData.source_file;
    const downloadCard = document.getElementById('cho-nip-action-download-card');
    if (downloadCard) {
      const targetFile = kpiData.source_file || 'MARAWI NIP DATA - UPGRADED.xlsx';
      downloadCard.setAttribute('href', Api.resolveUrl(`/api/v1/nip/download?file=${encodeURIComponent(targetFile)}&_t=${Date.now()}`));
      downloadCard.onclick = () => {
        const activeFile = Storage.nipWorkbooks?.getActiveWorkbook?.() || targetFile;
        downloadCard.setAttribute('href', Api.resolveUrl(`/api/v1/nip/download?file=${encodeURIComponent(activeFile)}&_t=${Date.now()}`));
      };
    }
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

    // Reveal systems grid before measuring & rendering ApexCharts so container widths are settled
    if (Drawers.gate) {
      Drawers.gate.revealSystemsGrid();
    }

    // Hide #cho-nip-canvas-card and render ApexCharts inside #cho-nip-charts-container
    if (Charts && typeof Charts.initNipDashboardCharts === 'function') {
      Charts.initNipDashboardCharts(kpiData);
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
          const rawUrl = res.download_url || `/api/v1/nip/download?file=${encodeURIComponent(generatedFile)}`;
          const downloadUrl = Api.resolveUrl(rawUrl);

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
// SUBCHILD CLASS 6: NipPopAddDrawer
// Handles adding a new Barangay row to MARAWI POP 2024 (#drawer-nip-pop-add)
// =============================================================================
export class NipPopAddDrawer extends BaseDrawerController {
  // START - constructor
  constructor() {
    super('drawer-nip-pop-add');
  }
  // END - constructor

  // START - init: Binds add form submission
  init() {
    super.init();
    const form = document.getElementById('nip-pop-add-form');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleAdd();
      });
    }
  }
  // END - init

  // START - handleAdd: Sends new row to backend openpyxl mutator and updates Storage + Table
  async handleAdd() {
    const cachedPop = Storage.nipPopulationCache?.get?.();
    const sourceFile = cachedPop?.source_file || Storage.nipWorkbooks?.getActiveWorkbook?.() || 'MARAWI NIP DATA - UPGRADED.xlsx';
    const feedbackBox = document.getElementById('nip-pop-add-feedback');
    const btnSubmit = document.getElementById('nip-btn-submit-pop-add');
    const btnSpinner = document.getElementById('nip-btn-submit-pop-add-spinner');
    const btnText = document.getElementById('nip-btn-submit-pop-add-text');

    const payload = {
      source_file: sourceFile,
      psgc10: document.getElementById('nip-pop-add-psgc10')?.value.trim() || '',
      barangay: document.getElementById('nip-pop-add-barangay')?.value.trim() || '',
      total_pop: Number(document.getElementById('nip-pop-add-total-pop')?.value || 0),
      household_pop: Number(document.getElementById('nip-pop-add-household-pop')?.value || 0),
      households: Number(document.getElementById('nip-pop-add-households')?.value || 0),
      nip_pop: Number(document.getElementById('nip-pop-add-nip-pop')?.value || 0)
    };

    if (btnSubmit) btnSubmit.disabled = true;
    if (btnSpinner) btnSpinner.classList.remove('hidden');
    if (btnText) btnText.textContent = 'Saving...';

    try {
      if (!Drawers.offline?.isOnline) {
        throw new Error('OFFLINE_MODE');
      }
      const res = await Api.post('/api/v1/nip/population/add', payload);
      if (!res || !res.success) {
        throw new Error(res?.message || 'Failed to add row to Excel.');
      }

      const data = res.data || {};
      const current = Storage.nipPopulationCache?.get?.() || {
        source_file: sourceFile,
        citySummary: { totalPop: 0, householdPop: 0, households: 0 },
        rows: []
      };

      const newRow = {
        row_index: current.rows.length + 1,
        excel_row: data.excel_row,
        nip_row: data.nip_row || null,
        psgc10: payload.psgc10 || '-',
        barangay: payload.barangay,
        total_pop: payload.total_pop,
        household_pop: payload.household_pop,
        households: payload.households,
        nip_pop: payload.nip_pop,
        nip_target: data.nip_target ?? (payload.nip_pop > 0 ? Number((payload.nip_pop * 0.02056).toFixed(1)) : 0)
      };

      current.rows.push(newRow);
      if (current.citySummary) {
        current.citySummary.totalPop = (current.citySummary.totalPop || 0) + payload.total_pop;
        current.citySummary.householdPop = (current.citySummary.householdPop || 0) + payload.household_pop;
        current.citySummary.households = (current.citySummary.households || 0) + payload.households;
      }
      Storage.nipPopulationCache?.save(current);

      if (window.CHO?.Tables?.marawiPopulation) {
        window.CHO.Tables.marawiPopulation.loadFromStorage();
      }

      if (feedbackBox) {
        feedbackBox.classList.remove('hidden');
        feedbackBox.className = 'p-3 rounded-xl text-xs bg-emerald-50 text-emerald-800 border border-emerald-200';
        feedbackBox.textContent = `Added "${payload.barangay}" at Excel Row ${data.excel_row} in ${sourceFile}.`;
      }

      if (typeof Notifications.success === 'function') {
        Notifications.success(`Added "${payload.barangay}" to ${sourceFile}.`);
      }

      document.getElementById('nip-pop-add-form')?.reset();
      Drawers.drafts?.clearFormDraft('nip-pop-add-form');
      Storage.nipResyncState?.setPending(sourceFile);
      Drawers.drafts?.clearFormDraft('nip-pop-edit-form');
      Drawers.syncDownloadCardState();
      this.hide();
      Drawers.showStatusToast(true, 'Entry Saved', `Added "${payload.barangay}" at Excel Row ${data.excel_row}.`);
      Drawers.showResyncToast(sourceFile);
    } catch (err) {
      if (Drawers.offline?.isNetworkError(err)) {
        await Storage.offlineSync.enqueue({
          endpoint: '/api/v1/nip/population/add',
          method: 'POST',
          payload,
          label: `Add "${payload.barangay}"`
        });
        const current = Storage.nipPopulationCache?.get?.() || {
          source_file: sourceFile,
          citySummary: { totalPop: 0, householdPop: 0, households: 0 },
          rows: []
        };
        current.rows.push({
          row_index: current.rows.length + 1,
          excel_row: current.rows.length + 5,
          nip_row: null,
          psgc10: payload.psgc10 || '-',
          barangay: payload.barangay,
          total_pop: payload.total_pop,
          household_pop: payload.household_pop,
          households: payload.households,
          nip_pop: payload.nip_pop,
          nip_target: payload.nip_pop > 0 ? Number((payload.nip_pop * 0.02056).toFixed(1)) : 0
        });
        Storage.nipPopulationCache?.save(current);
        window.CHO?.Tables?.marawiPopulation?.loadFromStorage();
        document.getElementById('nip-pop-add-form')?.reset();
        this.hide();
        Drawers.offline.showOfflineToast(`"${payload.barangay}" saved locally in IndexedDB and queued for auto-sync.`);
        return;
      }
      if (feedbackBox) {
        feedbackBox.classList.remove('hidden');
        feedbackBox.className = 'p-3 rounded-xl text-xs bg-rose-50 text-rose-800 border border-rose-200';
        feedbackBox.textContent = `Error: ${err.message}`;
      }
      Drawers.showStatusToast(false, 'Add Failed', err.message || 'Could not add row to Excel.');
      if (typeof Notifications.error === 'function') {
        Notifications.error(err.message || 'Could not add row to Excel.');
      }
    } finally {
      if (btnSubmit) btnSubmit.disabled = false;
      if (btnSpinner) btnSpinner.classList.add('hidden');
      if (btnText) btnText.textContent = 'Save New Entry to Excel';
    }
  }
  // END - handleAdd
}

// =============================================================================
// SUBCHILD CLASS 7: NipPopEditDrawer
// Cloned from NipPopAddDrawer to edit existing rows in MARAWI POP 2024 (#drawer-nip-pop-edit)
// =============================================================================
export class NipPopEditDrawer extends BaseDrawerController {
  // START - constructor
  constructor() {
    super('drawer-nip-pop-edit');
  }
  // END - constructor

  // START - init: Binds edit form submission
  init() {
    super.init();
    const form = document.getElementById('nip-pop-edit-form');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleEdit();
      });
    }
  }
  // END - init

  // START - openForRow: Populates edit drawer fields from clicked table row and opens drawer
  openForRow(row) {
    if (!row) return;
    const excelRow = row.excel_row || (row.row_index + 4);
    document.getElementById('nip-pop-edit-excel-row').value = excelRow;
    document.getElementById('nip-pop-edit-nip-row').value = row.nip_row || '';
    document.getElementById('nip-pop-edit-row-index').value = row.row_index;
    document.getElementById('nip-pop-edit-psgc10').value = row.psgc10 === '-' ? '' : row.psgc10;
    document.getElementById('nip-pop-edit-barangay').value = row.barangay || '';
    document.getElementById('nip-pop-edit-total-pop').value = row.total_pop ?? 0;
    document.getElementById('nip-pop-edit-household-pop').value = row.household_pop ?? 0;
    document.getElementById('nip-pop-edit-households').value = row.households ?? 0;
    document.getElementById('nip-pop-edit-nip-pop').value = row.nip_pop ?? 0;

    const rowBadge = document.getElementById('nip-pop-edit-row-badge');
    if (rowBadge) rowBadge.textContent = `Row ${excelRow}`;

    const feedbackBox = document.getElementById('nip-pop-edit-feedback');
    if (feedbackBox) feedbackBox.classList.add('hidden');

    this.show();
  }
  // END - openForRow

  // START - handleEdit: Sends updated cell values to backend openpyxl mutator and updates Storage + Table
  async handleEdit() {
    const cachedPop = Storage.nipPopulationCache?.get?.();
    const sourceFile = cachedPop?.source_file || Storage.nipWorkbooks?.getActiveWorkbook?.() || 'MARAWI NIP DATA - UPGRADED.xlsx';
    const feedbackBox = document.getElementById('nip-pop-edit-feedback');
    const btnSubmit = document.getElementById('nip-btn-submit-pop-edit');
    const btnSpinner = document.getElementById('nip-btn-submit-pop-edit-spinner');
    const btnText = document.getElementById('nip-btn-submit-pop-edit-text');

    const rowIndex = Number(document.getElementById('nip-pop-edit-row-index')?.value || 0);
    const excelRow = Number(document.getElementById('nip-pop-edit-excel-row')?.value || 0);
    const nipRowVal = document.getElementById('nip-pop-edit-nip-row')?.value;

    const payload = {
      source_file: sourceFile,
      excel_row: excelRow,
      nip_row: nipRowVal ? Number(nipRowVal) : null,
      psgc10: document.getElementById('nip-pop-edit-psgc10')?.value.trim() || '',
      barangay: document.getElementById('nip-pop-edit-barangay')?.value.trim() || '',
      total_pop: Number(document.getElementById('nip-pop-edit-total-pop')?.value || 0),
      household_pop: Number(document.getElementById('nip-pop-edit-household-pop')?.value || 0),
      households: Number(document.getElementById('nip-pop-edit-households')?.value || 0),
      nip_pop: Number(document.getElementById('nip-pop-edit-nip-pop')?.value || 0)
    };

    const applyCachedRowUpdate = (data = {}) => {
      const latestCache = Storage.nipPopulationCache?.get?.() || cachedPop;
      if (!latestCache || !Array.isArray(latestCache.rows)) return;
      const target = latestCache.rows.find((r) => r.row_index === rowIndex || r.excel_row === excelRow);
      if (target) {
        target.excel_row = data.excel_row || excelRow;
        target.nip_row = data.nip_row || target.nip_row;
        target.psgc10 = payload.psgc10 || '-';
        target.barangay = payload.barangay;
        target.total_pop = payload.total_pop;
        target.household_pop = payload.household_pop;
        target.households = payload.households;
        target.nip_pop = payload.nip_pop;
        target.nip_target = data.nip_target ?? (payload.nip_pop > 0 ? Number((payload.nip_pop * 0.02056).toFixed(1)) : 0);
      }
      Storage.nipPopulationCache?.save(latestCache);
      window.CHO?.Tables?.marawiPopulation?.loadFromStorage();
    };

    if (!Drawers.offline?.isOnline) {
      await Storage.offlineSync.enqueue({
        endpoint: '/api/v1/nip/population/update',
        method: 'POST',
        payload,
        label: `Edit "${payload.barangay}"`
      });
      applyCachedRowUpdate();
      Drawers.drafts?.clearFormDraft('nip-pop-edit-form');
      this.hide();
      Drawers.offline.showOfflineToast(`"${payload.barangay}" edit saved locally in IndexedDB and queued for auto-sync.`);
      return;
    }

    // Show loading animation for 3 seconds while running Excel update in the background
    if (btnSubmit) btnSubmit.disabled = true;
    if (btnSpinner) btnSpinner.classList.remove('hidden');
    if (btnText) btnText.textContent = 'Saving...';

    const minDelayPromise = new Promise((resolve) => setTimeout(resolve, 3000));
    const apiPromise = Api.post('/api/v1/nip/population/update', payload)
      .then((res) => ({ ok: true, res }))
      .catch((err) => ({ ok: false, err }));

    // Optimistic UI: update local cache + table immediately
    applyCachedRowUpdate();
    Drawers.drafts?.clearFormDraft('nip-pop-edit-form');
    if (feedbackBox) feedbackBox.classList.add('hidden');

    // Close drawer right after the 3-second loading animation (non-blocking if backend takes longer)
    minDelayPromise.then(() => {
      if (btnSubmit) btnSubmit.disabled = false;
      if (btnSpinner) btnSpinner.classList.add('hidden');
      if (btnText) btnText.textContent = 'Update Excel Cells';
      this.hide();
    });

    // Fire completion toasts only after BOTH the 3-second animation AND the backend Excel edit finish
    Promise.all([minDelayPromise, apiPromise]).then(async ([, outcome]) => {
      try {
        if (!outcome.ok) throw outcome.err;
        const res = outcome.res;
        if (!res || !res.success) {
          throw new Error(res?.message || 'Failed to update Excel cells.');
        }

        applyCachedRowUpdate(res.data || {});
        Storage.nipResyncState?.setPending(sourceFile);
        Drawers.syncDownloadCardState();

        if (typeof Notifications.success === 'function') {
          Notifications.success(`Updated "${payload.barangay}" in ${sourceFile}.`);
        }
        Drawers.showStatusToast(true, 'Excel Updated', `Updated "${payload.barangay}" (Row ${excelRow}) in ${sourceFile}.`);
        Drawers.showResyncToast(sourceFile);
      } catch (err) {
        if (Drawers.offline?.isNetworkError(err)) {
          await Storage.offlineSync.enqueue({
            endpoint: '/api/v1/nip/population/update',
            method: 'POST',
            payload,
            label: `Edit "${payload.barangay}"`
          });
          Drawers.offline.showOfflineToast(`"${payload.barangay}" edit saved locally in IndexedDB and queued for auto-sync.`);
          return;
        }
        Drawers.showStatusToast(false, 'Update Failed', err.message || 'Could not update Excel cells.');
        if (typeof Notifications.error === 'function') {
          Notifications.error(err.message || 'Could not update Excel cells.');
        }
      }
    });
  }
  // END - handleEdit
}

// =============================================================================
// SUBCHILD CLASS 8: NipPopDeleteModal
// Handles deleting a Barangay row's raw data in MARAWI POP 2024 (#modal-nip-pop-delete)
// while preserving any detected Excel formulas
// =============================================================================
export class NipPopDeleteModal {
  constructor() {
    this.modalId = 'modal-nip-pop-delete';
    this.modalEl = null;
    this.activeRow = null;
  }

  // START - init: Binds modal close, cancel, backdrop click, and confirm delete button
  init() {
    this.modalEl = document.getElementById(this.modalId);
    if (!this.modalEl) return;

    document.getElementById('nip-pop-delete-close-btn')?.addEventListener('click', () => this.hide());
    document.getElementById('nip-pop-delete-cancel-btn')?.addEventListener('click', () => this.hide());
    document.getElementById('nip-btn-submit-pop-delete')?.addEventListener('click', () => this.handleDelete());

    this.modalEl.addEventListener('click', (e) => {
      if (e.target === this.modalEl) this.hide();
    });
  }
  // END - init

  // START - openForRow: Populates delete confirmation modal with target row details
  openForRow(row) {
    if (!row || !this.modalEl) return;
    this.activeRow = row;
    const excelRow = row.excel_row || (row.row_index + 4);

    document.getElementById('nip-pop-delete-row-index').value = row.row_index;
    document.getElementById('nip-pop-delete-excel-row').value = excelRow;
    document.getElementById('nip-pop-delete-nip-row').value = row.nip_row || '';

    const nameEl = document.getElementById('nip-pop-delete-barangay-name');
    if (nameEl) nameEl.textContent = row.barangay || 'this barangay';

    const badgeEl = document.getElementById('nip-pop-delete-row-badge');
    if (badgeEl) badgeEl.textContent = `Row ${excelRow}`;

    const feedbackBox = document.getElementById('nip-pop-delete-feedback');
    if (feedbackBox) feedbackBox.classList.add('hidden');

    this.modalEl.classList.remove('hidden');
    this.modalEl.setAttribute('aria-hidden', 'false');
  }
  // END - openForRow

  // START - hide: Closes the delete confirmation modal
  hide() {
    if (!this.modalEl) return;
    this.modalEl.classList.add('hidden');
    this.modalEl.setAttribute('aria-hidden', 'true');
  }
  // END - hide

  // START - handleDelete: Instantly removes row from table & updates cache, then persists Excel deletion in background before showing toasts
  async handleDelete() {
    const cachedPop = Storage.nipPopulationCache?.get?.();
    const sourceFile = cachedPop?.source_file || Storage.nipWorkbooks?.getActiveWorkbook?.() || 'MARAWI NIP DATA - UPGRADED.xlsx';

    const rowIndex = Number(document.getElementById('nip-pop-delete-row-index')?.value || 0);
    const excelRow = Number(document.getElementById('nip-pop-delete-excel-row')?.value || 0);
    const nipRowVal = document.getElementById('nip-pop-delete-nip-row')?.value;
    const barangayName = this.activeRow?.barangay || 'Barangay';

    const payload = {
      source_file: sourceFile,
      excel_row: excelRow,
      nip_row: nipRowVal ? Number(nipRowVal) : null,
      barangay: barangayName
    };

    // Snapshot cache for rollback if background Excel mutation fails
    const previousCacheSnapshot = cachedPop ? JSON.parse(JSON.stringify(cachedPop)) : null;

    // 1. Instant UI update: close modal, remove row from table & cache immediately
    this.hide();

    if (cachedPop && Array.isArray(cachedPop.rows)) {
      const deletedRow = cachedPop.rows.find((r) => r.row_index === rowIndex || r.excel_row === excelRow);
      cachedPop.rows = cachedPop.rows.filter((r) => r.row_index !== rowIndex && r.excel_row !== excelRow);
      cachedPop.rows.forEach((r, idx) => {
        r.row_index = idx + 1;
      });

      if (deletedRow && cachedPop.citySummary) {
        cachedPop.citySummary.totalPop = Math.max(0, (cachedPop.citySummary.totalPop || 0) - (deletedRow.total_pop || 0));
        cachedPop.citySummary.householdPop = Math.max(0, (cachedPop.citySummary.householdPop || 0) - (deletedRow.household_pop || 0));
        cachedPop.citySummary.households = Math.max(0, (cachedPop.citySummary.households || 0) - (deletedRow.households || 0));
      }
      Storage.nipPopulationCache?.save(cachedPop);
    }

    if (window.CHO?.Tables?.marawiPopulation) {
      window.CHO.Tables.marawiPopulation.loadFromStorage();
    }

    // 2. Run Excel mutation in background; only show success & resync toasts once saved
    try {
      const res = await Api.post('/api/v1/nip/population/delete', payload);
      if (!res || !res.success) {
        throw new Error(res?.message || 'Failed to delete row data from Excel.');
      }

      if (typeof Notifications.success === 'function') {
        Notifications.success(`Deleted "${barangayName}" data from ${sourceFile}.`);
      }

      Storage.nipResyncState?.setPending(sourceFile);
      Drawers.drafts?.clearFormDraft('nip-pop-edit-form');
      Drawers.syncDownloadCardState();

      const preservedCount = res.data?.preserved_formulas ?? 0;
      const formulaNote = preservedCount > 0 ? ` (${preservedCount} formula${preservedCount > 1 ? 's' : ''} preserved)` : '';
      Drawers.showStatusToast(true, 'Data Deleted', `Removed "${barangayName}" (Row ${excelRow})${formulaNote}.`);
      Drawers.showResyncToast(sourceFile);
    } catch (err) {
      // Rollback optimistic removal on failure
      if (previousCacheSnapshot) {
        Storage.nipPopulationCache?.save(previousCacheSnapshot);
        if (window.CHO?.Tables?.marawiPopulation) {
          window.CHO.Tables.marawiPopulation.loadFromStorage();
        }
      }
      Drawers.showStatusToast(false, 'Delete Failed', err.message || 'Could not delete row data from Excel.');
      if (typeof Notifications.error === 'function') {
        Notifications.error(err.message || 'Could not delete row data from Excel.');
      }
    }
  }
  // END - handleDelete
}

// =============================================================================
// SUBCHILD CLASS 8: TclInfantDrawer
// Handles Target Client List (0-12 Months) infant inspection drawer & pill tabs
// =============================================================================
export class TclInfantDrawer extends BaseDrawerController {
  constructor() {
    super('drawer-tcl-infant2');
  }

  // START - init: Initializes TCL infant drawer, table row click triggers, and pill tabs
  init() {
    super.init();
    if (!this.drawerEl) return;

    this.bindRowClicks();
    this.bindTabs();
  }
  // END - init

  // START - bindRowClicks: Opens #drawer-tcl-infant2 when clicking a table row (excluding action buttons)
  bindRowClicks() {
    const tbody = document.getElementById('cho-nip-pop-tbody2');
    if (!tbody || tbody.dataset.tclRowBound === '1') return;

    tbody.dataset.tclRowBound = '1';
    tbody.addEventListener('click', (e) => {
      if (e.target.closest('button, a, input, select')) return;
      const row = e.target.closest('tr[data-tcl-record]');
      if (!row) return;

      try {
        const record = JSON.parse(row.dataset.tclRecord || '{}');
        this.openWithRecord(record);
      } catch (err) {
        console.warn('[TclInfantDrawer] Failed to parse row record:', err);
      }
    });
  }
  // END - bindRowClicks

  // START - bindTabs: Attaches pill tab switching logic for Overview, Immunization I, and Immunization II
  bindTabs() {
    const tabBtns = this.drawerEl.querySelectorAll('[data-tcl-tab]');
    tabBtns.forEach((btn) => {
      if (btn.dataset.bound === '1') return;
      btn.dataset.bound = '1';
      btn.addEventListener('click', () => this.switchTab(btn.dataset.tclTab));
    });
  }
  // END - bindTabs

  // START - switchTab: Activates the selected pill tab and reveals its corresponding panel
  switchTab(tabKey) {
    const tabBtns = this.drawerEl.querySelectorAll('[data-tcl-tab]');
    const panels = {
      overview: document.getElementById('tcl-tab-panel-overview2'),
      imm1: document.getElementById('tcl-tab-panel-imm1-2'),
      imm2: document.getElementById('tcl-tab-panel-imm2-2')
    };

    tabBtns.forEach((btn) => {
      const isActive = btn.dataset.tclTab === tabKey;
      btn.setAttribute('aria-selected', String(isActive));
      btn.classList.toggle('bg-white', isActive);
      btn.classList.toggle('text-slate-900', isActive);
      btn.classList.toggle('font-bold', isActive);
      btn.classList.toggle('shadow-xs', isActive);
      btn.classList.toggle('text-slate-500', !isActive);
      btn.classList.toggle('font-semibold', !isActive);
    });

    Object.entries(panels).forEach(([key, panel]) => {
      if (panel) panel.classList.toggle('hidden', key !== tabKey);
    });
  }
  // END - switchTab

  // START - openWithRecord: Hydrates all drawer tabs with infant data and opens the drawer
  openWithRecord(record) {
    if (!record) return;

    const nameEl = document.getElementById('tcl-drawer-child-name2');
    const metaEl = document.getElementById('tcl-drawer-child-meta2');
    if (nameEl) nameEl.textContent = record.child || '--';
    if (metaEl) metaEl.textContent = record.barangay || '--';

    this.hydrateOverview(record);
    this.hydrateImmunization1(record.imm1 || {}, record.imm1_note);
    this.hydrateImmunization2(record.imm2 || {});

    this.switchTab('overview');
    this.show();
  }
  // END - openWithRecord

  // START - hydrateOverview: Populates the Overview tab demographics and overall dose progress
  hydrateOverview(record) {
    const map = {
      'tcl-overview-age2': record.age_full || record.age || '--',
      'tcl-overview-reg2': record.reg_date_full || record.reg_date_fmt || '--',
      'tcl-overview-dob2': record.dob_full || record.dob_fmt || '--'
    };
    Object.entries(map).forEach(([id, val]) => {
      const el = document.getElementById(id);
      if (el) el.textContent = val;
    });

    const isMale = record.sex === 'MALE';
    const sexSvg = isMale
      ? `<svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M19 5l-5.4 5.4" /><path d="M19 5h-5" /><path d="M19 5v5" /></svg>`
      : `<svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 9a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M12 14v7" /><path d="M9 18h6" /></svg>`;

    const sexIconEl = document.getElementById('tcl-overview-sex-icon2');
    if (sexIconEl) {
      sexIconEl.className = `inline-flex items-center ${isMale ? 'text-sky-600' : 'text-pink-500'}`;
      sexIconEl.innerHTML = sexSvg;
    }

    const sexEl = document.getElementById('tcl-overview-sex-badge2');
    if (sexEl) {
      const cls = isMale
        ? 'bg-sky-600 text-white border-sky-700'
        : 'bg-pink-500 text-white border-pink-600';
      sexEl.className = `inline-flex items-center gap-1.5 px-3 py-1 text-xs font-extrabold uppercase tracking-wide rounded-md border shadow-xs ${cls}`;
      sexEl.innerHTML = `${sexSvg}<span>${record.sex || '--'}</span>`;
    }

    const allDoses = [
      record.imm1?.bcg,
      record.imm1?.hepa,
      ...(record.imm2?.dpt || []),
      ...(record.imm2?.opv || []),
      ...(record.imm2?.pcv || []),
      ...(record.imm2?.ipv || []),
      ...(record.imm2?.mcv1 || []),
      ...(record.imm2?.mcv2 || [])
    ].filter(Boolean);

    const completed = allDoses.filter((d) => d.status === 'INJECTED').length;
    const total = allDoses.length || 15;
    const pct = Math.round((completed / total) * 100);

    const countEl = document.getElementById('tcl-overview-progress-count2');
    const barEl = document.getElementById('tcl-overview-progress-bar2');
    const notesEl = document.getElementById('tcl-overview-notes-text2');
    if (countEl) countEl.textContent = `${completed} / ${total} Doses (${pct}%)`;
    if (barEl) barEl.style.width = `${pct}%`;
    this.renderNotesBox(notesEl, record.overview_note);
  }
  // END - hydrateOverview

  // START - hydrateImmunization1: Populates the 2x2 BCG & Hepa B-BD grid and notes reader
  hydrateImmunization1(imm1, noteText = '') {
    const bcgBadge = document.getElementById('tcl-imm1-bcg-badge2');
    const bcgDate = document.getElementById('tcl-imm1-bcg-date2');
    const hepaBadge = document.getElementById('tcl-imm1-hepa-badge2');
    const hepaDate = document.getElementById('tcl-imm1-hepa-date2');
    const imm1NotesEl = document.getElementById('tcl-imm1-notes-text2');

    if (bcgBadge) bcgBadge.innerHTML = this.getBadgeMarkup(imm1.bcg?.status);
    if (bcgDate) bcgDate.textContent = imm1.bcg?.date || '—';
    if (hepaBadge) hepaBadge.innerHTML = this.getBadgeMarkup(imm1.hepa?.status);
    if (hepaDate) hepaDate.textContent = imm1.hepa?.date || '—';
    this.renderNotesBox(imm1NotesEl, noteText);
  }
  // END - hydrateImmunization1

  // START - renderNotesBox: Populates a read-only notes container with multi-line text or an empty state
  renderNotesBox(el, noteText = '') {
    if (!el) return;
    const trimmed = (noteText || '').trim();
    if (trimmed) {
      el.classList.remove('text-slate-400', 'italic');
      el.classList.add('text-slate-700');
      el.textContent = trimmed;
    } else {
      el.classList.remove('text-slate-700');
      el.classList.add('text-slate-400', 'italic');
      el.textContent = 'No clinical notes recorded for this section yet. Click the Edit action button on the table to add remarks.';
    }
  }
  // END - renderNotesBox

  // START - hydrateImmunization2: Populates the vaccine cards (DPT-HiB-HepB, OPV, PCV, IPV, MCV1, MCV2)
  hydrateImmunization2(imm2) {
    const targets = [
      { id: 'tcl-imm2-dpt-list2', doses: imm2.dpt || [] },
      { id: 'tcl-imm2-opv-list2', doses: imm2.opv || [] },
      { id: 'tcl-imm2-pcv-list2', doses: imm2.pcv || [] },
      { id: 'tcl-imm2-ipv-list2', doses: imm2.ipv || [] },
      { id: 'tcl-imm2-mcv1-list2', doses: imm2.mcv1 || [] },
      { id: 'tcl-imm2-mcv2-list2', doses: imm2.mcv2 || [] }
    ];

    targets.forEach(({ id, doses }) => {
      const container = document.getElementById(id);
      if (!container) return;
      container.innerHTML = doses.map((item) => this.renderDoseRow(item)).join('');
    });
  }
  // END - hydrateImmunization2

  // START - renderDoseRow: Builds a single dose row with dose label, status badge, and date
  renderDoseRow(item) {
    const dateText = item.date || '—';
    return `
      <div class="flex items-center justify-between gap-3 py-3 first:pt-1 last:pb-0">
        <div class="flex items-center gap-2.5 min-w-0">
          <span class="text-sm font-bold text-slate-700">${item.dose}</span>
          ${this.getBadgeMarkup(item.status)}
        </div>
        <div class="text-right shrink-0">
          <div class="text-xs font-bold uppercase tracking-wider text-slate-400 leading-none">Date</div>
          <div class="text-sm font-semibold text-slate-800 mt-0.5">${dateText}</div>
        </div>
      </div>
    `;
  }
  // END - renderDoseRow

  // START - getBadgeMarkup: Returns solid emerald (INJECTED) or solid rose (NOT YET) badge HTML
  getBadgeMarkup(status = 'NOT YET') {
    const isInjected = status === 'INJECTED';
    const cls = isInjected
      ? 'bg-emerald-600 text-white border-emerald-700'
      : 'bg-rose-600 text-white border-rose-700';
    return `<span class="inline-flex items-center px-3 py-0.5 text-xs font-extrabold tracking-wide rounded-full border shadow-xs ${cls}">${isInjected ? 'INJECTED' : 'NOT YET'}</span>`;
  }
  // END - getBadgeMarkup
}

// =============================================================================
// SUBCHILD CLASS 6B: NipPopAddDrawer2
// Handles adding a new Target Client List (0-12 Months) row (#drawer-nip-pop-add2)
// =============================================================================
const MARAWI_BARANGAYS = [
  'Ambolong', 'Amito Marantao', 'Angoyao', 'Bacolod Chico Proper', 'Banga', 'Bangco',
  'Banggolo Poblacion', 'Bangon', 'Basak Malutlut', 'Beyaba-Damag', 'Bito Buadi Itowa',
  'Bito Buadi Parba', 'Boganga', 'Boto Ambolong', 'Buadi Sacayo (East Basak)', 'Bubong Lumbac',
  'Bubonga Cadayonan', 'Bubonga Lilod Madaya', 'Bubonga Marawi', 'Bubonga Pagalamatan',
  'Bubonga Punod', 'Cabasaran', 'Cabingan', 'Cadayonan', 'Cadayonan I', 'Calocan East',
  'Calocan West', 'Daguduban', 'Dansalan', 'Datu Dalidigan', 'Datu Naga', 'Datu Sa Dansalan',
  'Dayawan', 'Dimaluna', 'Dulay', 'Dulay West', 'East Basak', 'Emie Punud', 'Fort',
  'Gadongan', 'Gadongan Mapantao', 'Guimba', 'Kapantaran', 'Kilala', 'Kormatan Matampay',
  'Lilod Madaya (Poblacion)', 'Lilod Saduc', 'Lomidong', 'Lumbac Marinaut',
  'Lumbaca Madaya (Poblacion)', 'Lumbaca Toros', 'Malimono', 'Marawi Poblacion',
  'Marinaut East', 'Marinaut West', 'Matampay', 'Mipaga Proper', 'Moncado Colony',
  'Moncado Kadingilan', 'Moriatao Loksadato', 'Navarro', 'Norhaya Village', 'Olawa Ambolong',
  'Pagalamatan Gambai', 'Pagayawan', 'Panggao Saduc', 'Pantaon', 'Papandayan',
  'Papandayan Caniogan', 'Paridi', 'Patani', 'Pindolonan', 'Poona Marantao', 'Pugaan',
  'Rapasun MSU', 'Raya Madaya I', 'Raya Madaya II', 'Raya Saduc', 'Rorogagus East',
  'Rorogagus Proper', 'Sabala Manao', 'Sabala Manao Proper', 'Saduc Proper', 'Saguiaran (Sagunsongan)',
  'Sangcay Dansalan', 'Somiorang', 'South Madaya Proper', 'Sugod Proper', 'Sultan Corobong',
  'Sultan Panoroganan', 'Tampilong', 'Timbangalan', 'Tolali', 'Tongantongan-Tuca Timbangalan',
  'Toros', 'Tuca'
];

function bindBarangayDropdown(inputId, toggleId, dropdownId) {
  const input = document.getElementById(inputId);
  const toggleBtn = document.getElementById(toggleId);
  const dropdown = document.getElementById(dropdownId);
  if (!input || !dropdown) return;

  const renderList = (query = '') => {
    const q = query.trim().toLowerCase();
    const matches = q
      ? MARAWI_BARANGAYS.filter((b) => b.toLowerCase().includes(q))
      : MARAWI_BARANGAYS;

    dropdown.innerHTML = matches.length
      ? matches.map((b) => `
          <button type="button" data-brgy-opt="${b}"
                  class="w-full text-left px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 flex items-center justify-between cursor-pointer">
            <span>${b}</span>
            <span class="text-xs text-slate-400">Marawi City</span>
          </button>
        `).join('')
      : `<div class="px-3.5 py-3 text-sm text-slate-400 text-center">No matching barangay found</div>`;

    dropdown.querySelectorAll('[data-brgy-opt]').forEach((btn) => {
      btn.addEventListener('click', () => {
        input.value = btn.dataset.brgyOpt || '';
        dropdown.classList.add('hidden');
      });
    });
  };

  input.addEventListener('focus', () => {
    renderList(input.value);
    dropdown.classList.remove('hidden');
  });

  input.addEventListener('input', () => {
    renderList(input.value);
    dropdown.classList.remove('hidden');
  });

  toggleBtn?.addEventListener('click', () => {
    const isHidden = dropdown.classList.contains('hidden');
    if (isHidden) {
      renderList('');
      dropdown.classList.remove('hidden');
      input.focus();
    } else {
      dropdown.classList.add('hidden');
    }
  });

  document.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target) && e.target !== input && !toggleBtn?.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
}

function bindSexDropdown(btnId, dropdownId, displayId, hiddenId) {
  const btn = document.getElementById(btnId);
  const dropdown = document.getElementById(dropdownId);
  const display = document.getElementById(displayId);
  const hiddenInput = document.getElementById(hiddenId);
  if (!btn || !dropdown || !display || !hiddenInput) return () => {};

  const getSexMarkup = (sex) => {
    if (String(sex).toLowerCase() === 'female') {
      return `<svg class="w-4 h-4 text-pink-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 9a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M12 14v7" /><path d="M9 18h6" /></svg><span>Female</span>`;
    }
    return `<svg class="w-4 h-4 text-sky-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M19 5l-5.4 5.4" /><path d="M19 5h-5" /><path d="M19 5v5" /></svg><span>Male</span>`;
  };

  const setSex = (sex) => {
    const normalized = String(sex).toLowerCase() === 'female' ? 'Female' : 'Male';
    hiddenInput.value = normalized;
    display.innerHTML = getSexMarkup(normalized);
    dropdown.classList.add('hidden');
    btn.setAttribute('aria-expanded', 'false');
  };

  btn.addEventListener('click', () => {
    const isHidden = dropdown.classList.contains('hidden');
    dropdown.classList.toggle('hidden', !isHidden);
    btn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
  });

  dropdown.querySelectorAll('[data-sex-value]').forEach((opt) => {
    opt.addEventListener('click', () => setSex(opt.dataset.sexValue || 'Male'));
  });

  document.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
      dropdown.classList.add('hidden');
      btn.setAttribute('aria-expanded', 'false');
    }
  });

  return setSex;
}

function bindVaccineDateSync(prefix) {
  const pairs = [
    ['vax-date', 'bcg'], ['hepa-date', 'hepa'],
    ['dpt1-date', 'dpt1'], ['dpt2-date', 'dpt2'], ['dpt3-date', 'dpt3'],
    ['opv1-date', 'opv1'], ['opv2-date', 'opv2'], ['opv3-date', 'opv3'],
    ['pcv1-date', 'pcv1'], ['pcv2-date', 'pcv2'], ['pcv3-date', 'pcv3'],
    ['ipv1-date', 'ipv1'], ['ipv2-date', 'ipv2'],
    ['mcv1-date', 'mcv1'], ['mcv2-date', 'mcv2']
  ];
  pairs.forEach(([dSuffix, sSuffix]) => {
    const dateEl = document.getElementById(`${prefix}-${dSuffix}`);
    const selEl = document.getElementById(`${prefix}-${sSuffix}`);
    if (!dateEl || !selEl) return;
    dateEl.addEventListener('change', () => {
      selEl.value = dateEl.value ? 'INJECTED' : 'NOT YET';
    });
  });
}

function buildTclPayloadFromForm(prefix, rowId) {
  const child = document.getElementById(`${prefix}-child`)?.value.trim() || '';
  const barangay = document.getElementById(`${prefix}-barangay`)?.value.trim() || '';
  const regDate = document.getElementById(`${prefix}-reg-date`)?.value || '';
  const dob = document.getElementById(`${prefix}-dob`)?.value || '';
  const sex = document.getElementById(`${prefix}-sex`)?.value || 'Male';
  const bcg = document.getElementById(`${prefix}-bcg`)?.value || 'NOT YET';
  const vaxDate = document.getElementById(`${prefix}-vax-date`)?.value || '';
  const hepa = document.getElementById(`${prefix}-hepa`)?.value || 'NOT YET';
  const hepaDate = document.getElementById(`${prefix}-hepa-date`)?.value || '';

  const fmtDate = (iso, opts = { month: 'short', day: 'numeric', year: 'numeric' }) => {
    if (!iso) return '';
    const d = new Date(`${iso}T00:00:00`);
    return Number.isNaN(d.getTime()) ? iso : d.toLocaleDateString('en-US', opts);
  };

  const doseEntry = (doseLabel, key) => {
    const status = document.getElementById(`${prefix}-${key}`)?.value || 'NOT YET';
    const iso = document.getElementById(`${prefix}-${key}-date`)?.value || '';
    return { dose: doseLabel, status, date: fmtDate(iso), iso };
  };

  const dobDate = new Date(`${dob}T00:00:00`);
  const diffDays = Number.isNaN(dobDate.getTime()) ? 0 : Math.max(0, Math.floor((new Date() - dobDate) / 86400000));
  const months = Math.floor(diffDays / 30);
  const ageShort = months >= 1 ? `${months} ${months === 1 ? 'mo' : 'mos'}` : `${diffDays} days`;
  const ageFull = months >= 1 ? `${months} ${months === 1 ? 'month' : 'months'} old` : `${diffDays} days old`;

  return {
    id: rowId,
    child,
    barangay,
    reg_date: regDate,
    reg_date_fmt: fmtDate(regDate),
    reg_date_full: fmtDate(regDate, { month: 'long', day: 'numeric', year: 'numeric' }),
    dob,
    dob_fmt: Number.isNaN(dobDate.getTime()) ? dob : `${dobDate.getMonth() + 1}/${dobDate.getDate()}/${dobDate.getFullYear()}`,
    dob_full: fmtDate(dob, { month: 'long', day: 'numeric', year: 'numeric' }),
    age: ageShort,
    age_full: ageFull,
    sex: sex.toUpperCase(),
    overview_note: '',
    imm1_note: '',
    imm1: {
      bcg: { status: bcg, date: fmtDate(vaxDate), iso: vaxDate },
      hepa: { status: hepa, date: fmtDate(hepaDate), iso: hepaDate }
    },
    imm2: {
      dpt: [doseEntry('1st Dose', 'dpt1'), doseEntry('2nd Dose', 'dpt2'), doseEntry('3rd Dose', 'dpt3')],
      opv: [doseEntry('1st Dose', 'opv1'), doseEntry('2nd Dose', 'opv2'), doseEntry('3rd Dose', 'opv3')],
      pcv: [doseEntry('1st Dose', 'pcv1'), doseEntry('2nd Dose', 'pcv2'), doseEntry('3rd Dose', 'pcv3')],
      ipv: [doseEntry('1st Dose', 'ipv1'), doseEntry('2nd Dose', 'ipv2')],
      mcv1: [doseEntry('1st Dose', 'mcv1')],
      mcv2: [doseEntry('1st Dose', 'mcv2')]
    }
  };
}

function renderTclRowCells(tr, record) {
  const id = record.id;
  const isMale = record.sex === 'MALE';
  const bcg = record.imm1?.bcg?.status || 'NOT YET';
  const vaxFmt = record.imm1?.bcg?.date || '';
  const vaxIso = record.imm1?.bcg?.iso || '';
  const isInjected = bcg === 'INJECTED';
  const bcgBadgeClass = isInjected
    ? 'bg-emerald-600 text-white border-emerald-700 shadow-xs'
    : 'bg-rose-600 text-white border-rose-700 shadow-xs';
  const sexBadgeClass = isMale
    ? 'bg-sky-600 text-white border-sky-700 shadow-xs'
    : 'bg-pink-500 text-white border-pink-600 shadow-xs';
  const sexSvg = isMale
    ? '<svg class="w-3.5 h-3.5 shrink-0 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M19 5l-5.4 5.4" /><path d="M19 5h-5" /><path d="M19 5v5" /></svg>'
    : '<svg class="w-3.5 h-3.5 shrink-0 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 9a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" /><path d="M12 14v7" /><path d="M9 18h6" /></svg>';

  const vaxCell = vaxIso
    ? `<span id="tcl-vax-date-${id}-2" data-tooltip-target="tcl-vax-tooltip-${id}-2" data-tooltip-placement="top" class="text-xs font-semibold text-slate-800 underline decoration-dotted decoration-slate-400 underline-offset-4 cursor-help">${vaxFmt}</span>
       <div id="tcl-vax-tooltip-${id}-2" role="tooltip" class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">${vaxFmt}<div class="tooltip-arrow" data-popper-arrow></div></div>`
    : `<span id="tcl-vax-date-${id}-2" class="text-xs font-medium text-slate-400">&mdash;</span>`;

  tr.className = 'group hover:bg-sky-100 hover:shadow-sm transition-all duration-150 cursor-pointer';
  tr.dataset.rowId = String(id);
  tr.dataset.barangay = record.barangay || '';
  tr.dataset.bcg = bcg;
  tr.dataset.sex = isMale ? 'male' : 'female';
  tr.dataset.tclRecord = JSON.stringify(record);

  tr.innerHTML = `
    <td class="px-4 py-3 font-mono text-xs font-bold text-slate-500 group-hover:text-slate-800 whitespace-nowrap">${id}</td>
    <td class="px-4 py-3 bg-slate-50/60 group-hover:bg-transparent font-semibold text-slate-800 whitespace-nowrap">${record.barangay}</td>
    <td class="px-4 py-3 whitespace-nowrap">
      <span id="tcl-reg-date-${id}-2" data-tooltip-target="tcl-reg-tooltip-${id}-2" data-tooltip-placement="top" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200 cursor-help">
        <span class="w-1.5 h-1.5 rounded-full bg-cho-blue-700"></span>${record.reg_date_fmt}
      </span>
      <div id="tcl-reg-tooltip-${id}-2" role="tooltip" class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">${record.reg_date_full}<div class="tooltip-arrow" data-popper-arrow></div></div>
    </td>
    <td class="px-4 py-3 bg-slate-50/60 group-hover:bg-transparent font-bold text-slate-900 whitespace-nowrap">${record.child}</td>
    <td class="px-4 py-3 font-mono text-xs text-slate-600 whitespace-nowrap">
      <span id="tcl-dob-text-${id}-2" data-tooltip-target="tcl-dob-tooltip-${id}-2" data-tooltip-placement="top" class="inline-flex items-center underline decoration-dotted decoration-slate-400 underline-offset-4 cursor-help">${record.dob_fmt}</span>
      <div id="tcl-dob-tooltip-${id}-2" role="tooltip" class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">${record.dob_full}<div class="tooltip-arrow" data-popper-arrow></div></div>
    </td>
    <td class="px-4 py-3 bg-slate-50/60 group-hover:bg-transparent text-xs font-semibold text-slate-700 whitespace-nowrap">
      <span id="tcl-age-text-${id}-2" data-tooltip-target="tcl-age-tooltip-${id}-2" data-tooltip-placement="top" class="inline-flex items-center underline decoration-dotted decoration-slate-400 underline-offset-4 cursor-help">${record.age}</span>
      <div id="tcl-age-tooltip-${id}-2" role="tooltip" class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">${record.age_full}<div class="tooltip-arrow" data-popper-arrow></div></div>
    </td>
    <td class="px-4 py-3 whitespace-nowrap">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wide rounded-md border ${sexBadgeClass}">${sexSvg}${record.sex}</span>
    </td>
    <td class="px-4 py-2.5 bg-slate-50/60 group-hover:bg-transparent text-center whitespace-nowrap">
      <div class="inline-flex items-center justify-center gap-3">
        <span id="tcl-bcg-badge-${id}-2" class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-extrabold tracking-wide rounded-full border ${bcgBadgeClass}">${bcg}</span>
        <div class="pl-3 border-l border-slate-200 text-center">
          <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none mb-1">Date of Vaccination</div>
          ${vaxCell}
        </div>
      </div>
    </td>
    <td class="px-4 py-3 text-center whitespace-nowrap">
      <div class="inline-flex items-center justify-center gap-1.5">
        <button type="button" id="tcl-btn-edit-${id}-2" data-tooltip-target="tcl-edit-tooltip-${id}-2" data-tooltip-placement="top" aria-label="Edit record" class="p-1.5 text-slate-500 hover:text-cho-blue-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        </button>
        <div id="tcl-edit-tooltip-${id}-2" role="tooltip" class="absolute z-30 invisible inline-block px-2.5 py-1 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">Edit Record<div class="tooltip-arrow" data-popper-arrow></div></div>
        <button type="button" id="tcl-btn-delete-${id}-2" data-tooltip-target="tcl-delete-tooltip-${id}-2" data-tooltip-placement="top" aria-label="Delete record" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
        <div id="tcl-delete-tooltip-${id}-2" role="tooltip" class="absolute z-30 invisible inline-block px-2.5 py-1 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">Delete Record<div class="tooltip-arrow" data-popper-arrow></div></div>
      </div>
    </td>
  `;
}

export class NipPopAddDrawer2 extends BaseDrawerController {
  // START - constructor
  constructor() {
    super('drawer-nip-pop-add2');
    this.resetSexDropdown = () => {};
  }
  // END - constructor

  // START - init: Binds searchable barangay dropdown, sex icon dropdown, vaccine sync, IndexedDB hydrate, and submit
  init() {
    super.init();
    bindBarangayDropdown('tcl-add-barangay', 'tcl-brgy-toggle', 'tcl-brgy-dropdown');
    this.resetSexDropdown = bindSexDropdown('tcl-sex-btn', 'tcl-sex-dropdown', 'tcl-sex-display', 'tcl-add-sex');
    bindVaccineDateSync('tcl-add');
    this.hydrateFromIndexedDb();

    const form = document.getElementById('nip-pop-add-form2');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleAdd();
      });
    }
  }
  // END - init

  // START - hydrateFromIndexedDb: Restores any locally saved TCL records from IndexedDB into the table
  async hydrateFromIndexedDb() {
    const tbody = document.getElementById('cho-nip-pop-tbody2');
    if (!tbody || !Storage.offlineSync) return;
    try {
      const saved = await Storage.offlineSync.getAllTclRecords();
      if (!Array.isArray(saved) || saved.length === 0) return;

      saved.forEach((record) => {
        const existing = tbody.querySelector(`tr[data-row-id="${record.id}"]`);
        if (existing) {
          renderTclRowCells(existing, record);
        } else {
          const tr = document.createElement('tr');
          renderTclRowCells(tr, record);
          tbody.appendChild(tr);
        }
      });
      window.CHO_TCL_REFRESH_TABLE2?.();
      initTooltips();
    } catch (e) {
      console.warn('[NipPopAddDrawer2] IndexedDB hydrate skipped:', e);
    }
  }
  // END - hydrateFromIndexedDb

  // START - handleAdd: Appends a new child entry with At-Birth & Routine Infant Doses to the NIP2 TCL table & IndexedDB
  async handleAdd() {
    const tbody = document.getElementById('cho-nip-pop-tbody2');
    if (!tbody) return;

    const maxId = Array.from(tbody.querySelectorAll('tr')).reduce((max, r) => Math.max(max, Number(r.dataset.rowId || 0)), 0);
    const record = buildTclPayloadFromForm('tcl-add', maxId + 1);
    if (!record.child || !record.barangay || !record.reg_date || !record.dob) return;

    const tr = document.createElement('tr');
    renderTclRowCells(tr, record);
    tbody.appendChild(tr);

    window.CHO_TCL_REFRESH_TABLE2?.();
    initTooltips();

    await Storage.offlineSync?.saveTclRecord(record).catch(() => {});
    if (!Drawers.offline?.isOnline) {
      await Storage.offlineSync?.enqueue({
        endpoint: '/api/v1/nip2/tcl/add',
        method: 'POST',
        payload: record,
        label: `Add "${record.child}"`
      }).catch(() => {});
    }

    document.getElementById('nip-pop-add-form2')?.reset();
    Drawers.drafts?.clearFormDraft('nip-pop-add-form2');
    this.resetSexDropdown('Male');
    this.hide();
    Drawers.drafts?.clearFormDraft('nip-pop-edit-form2');
    if (!Drawers.offline?.isOnline) {
      Drawers.offline.showOfflineToast(`"${record.child}" saved locally in IndexedDB and queued for auto-sync.`);
    } else {
      Drawers.showStatusToast(true, 'Child Record Added', `"${record.child}" (${record.barangay}) has been added to the Target Client List.`);
    }
  }
  // END - handleAdd
}

// =============================================================================
// SUBCHILD CLASS 6C: NipPopEditDrawer2
// Handles editing & deleting Target Client List (0-12 Months) rows (#drawer-nip-pop-edit2)
// =============================================================================
export class NipPopEditDrawer2 extends BaseDrawerController {
  // START - constructor
  constructor() {
    super('drawer-nip-pop-edit2');
    this.activeRowEl = null;
    this.setSexDropdown = () => {};
  }
  // END - constructor

  // START - init: Binds delegated edit/delete row actions, dropdowns, vaccine sync, and submit
  init() {
    super.init();
    bindBarangayDropdown('tcl-edit-barangay', 'tcl-edit-brgy-toggle', 'tcl-edit-brgy-dropdown');
    this.setSexDropdown = bindSexDropdown('tcl-edit-sex-btn', 'tcl-edit-sex-dropdown', 'tcl-edit-sex-display', 'tcl-edit-sex');
    bindVaccineDateSync('tcl-edit');

    const tbody = document.getElementById('cho-nip-pop-tbody2');
    if (tbody && tbody.dataset.tclEditBound !== '1') {
      tbody.dataset.tclEditBound = '1';
      tbody.addEventListener('click', (e) => {
        const editBtn = e.target.closest('[id^="tcl-btn-edit-"]');
        if (editBtn) {
          e.stopPropagation();
          const tr = editBtn.closest('tr');
          if (tr) this.openForRow(tr);
          return;
        }
        const delBtn = e.target.closest('[id^="tcl-btn-delete-"]');
        if (delBtn) {
          e.stopPropagation();
          const tr = delBtn.closest('tr');
          if (tr) {
            const parsed = JSON.parse(tr.dataset.tclRecord || '{}');
            const name = parsed.child || 'Record';
            const rowId = parsed.id || tr.dataset.rowId;
            tr.remove();
            window.CHO_TCL_REFRESH_TABLE2?.();
            Storage.offlineSync?.deleteTclRecord(rowId).catch(() => {});
            Drawers.showStatusToast(true, 'Record Removed', `"${name}" has been removed from the Target Client List.`);
          }
        }
      });
    }

    const form = document.getElementById('nip-pop-edit-form2');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleEdit();
      });
    }
  }
  // END - init

  // START - openForRow: Populates edit drawer fields from the clicked table row
  openForRow(tr) {
    this.activeRowEl = tr;
    const record = JSON.parse(tr.dataset.tclRecord || '{}');
    const setVal = (id, val) => {
      const el = document.getElementById(id);
      if (el) el.value = val ?? '';
    };

    setVal('tcl-edit-row-id', record.id || tr.dataset.rowId || '');
    const badge = document.getElementById('nip-pop-edit-row-badge2');
    if (badge) badge.textContent = `Record #${record.id || tr.dataset.rowId || '--'}`;

    setVal('tcl-edit-child', record.child || '');
    setVal('tcl-edit-barangay', record.barangay || '');
    setVal('tcl-edit-reg-date', record.reg_date || '');
    setVal('tcl-edit-dob', record.dob || '');
    this.setSexDropdown(record.sex || 'Male');

    setVal('tcl-edit-bcg', record.imm1?.bcg?.status || 'NOT YET');
    setVal('tcl-edit-vax-date', record.imm1?.bcg?.iso || '');
    setVal('tcl-edit-hepa', record.imm1?.hepa?.status || 'NOT YET');
    setVal('tcl-edit-hepa-date', record.imm1?.hepa?.iso || '');

    const setDose = (key, arr, idx) => {
      const item = arr?.[idx] || {};
      setVal(`tcl-edit-${key}`, item.status || 'NOT YET');
      setVal(`tcl-edit-${key}-date`, item.iso || '');
    };
    setDose('dpt1', record.imm2?.dpt, 0);
    setDose('dpt2', record.imm2?.dpt, 1);
    setDose('dpt3', record.imm2?.dpt, 2);
    setDose('opv1', record.imm2?.opv, 0);
    setDose('opv2', record.imm2?.opv, 1);
    setDose('opv3', record.imm2?.opv, 2);
    setDose('pcv1', record.imm2?.pcv, 0);
    setDose('pcv2', record.imm2?.pcv, 1);
    setDose('pcv3', record.imm2?.pcv, 2);
    setDose('ipv1', record.imm2?.ipv, 0);
    setDose('ipv2', record.imm2?.ipv, 1);
    setDose('mcv1', record.imm2?.mcv1, 0);
    setDose('mcv2', record.imm2?.mcv2, 0);

    this.show();
  }
  // END - openForRow

  // START - handleEdit: Updates the existing row in place, persists to IndexedDB, and refreshes tooltips
  async handleEdit() {
    if (!this.activeRowEl) return;
    const rowId = Number(document.getElementById('tcl-edit-row-id')?.value || this.activeRowEl.dataset.rowId || 0);
    const existing = JSON.parse(this.activeRowEl.dataset.tclRecord || '{}');
    const updated = buildTclPayloadFromForm('tcl-edit', rowId);
    if (!updated.child || !updated.barangay || !updated.reg_date || !updated.dob) return;

    updated.overview_note = existing.overview_note || '';
    updated.imm1_note = existing.imm1_note || '';

    renderTclRowCells(this.activeRowEl, updated);
    window.CHO_TCL_REFRESH_TABLE2?.();
    initTooltips();

    await Storage.offlineSync?.saveTclRecord(updated).catch(() => {});
    if (!Drawers.offline?.isOnline) {
      await Storage.offlineSync?.enqueue({
        endpoint: '/api/v1/nip2/tcl/edit',
        method: 'POST',
        payload: updated,
        label: `Edit "${updated.child}"`
      }).catch(() => {});
    }

    this.hide();
    Drawers.drafts?.clearFormDraft('nip-pop-edit-form2');
    if (!Drawers.offline?.isOnline) {
      Drawers.offline.showOfflineToast(`"${updated.child}" changes saved locally in IndexedDB and queued for auto-sync.`);
    } else {
      Drawers.showStatusToast(true, 'Child Record Updated', `"${updated.child}" has been updated.`);
    }
  }
  // END - handleEdit
}

// =============================================================================
// SUBCHILD CLASS 6D: OfflineSyncMonitor
// Heartbeat connectivity monitor + IndexedDB pending_sync auto-flush & toasts
// =============================================================================
export class OfflineSyncMonitor {
  // START - constructor
  constructor() {
    this.isOnline = typeof navigator !== 'undefined' ? navigator.onLine : true;
    this.isSlow = false;
    this.isFlushing = false;
    this.heartbeatTimer = null;
  }
  // END - constructor

  // START - init: Attaches online/offline listeners and starts lightweight heartbeat
  init() {
    window.addEventListener('online', () => this.checkHeartbeat());
    window.addEventListener('offline', () => this.setOfflineState('No internet connection detected.'));

    this.checkHeartbeat();
    this.heartbeatTimer = window.setInterval(() => this.checkHeartbeat(), 15000);
  }
  // END - init

  // START - isNetworkError: Detects dropped Wi-Fi, offline mode, or aborted fetch errors
  isNetworkError(err) {
    if (!this.isOnline || !navigator.onLine) return true;
    const msg = String(err?.message || '');
    return msg === 'OFFLINE_MODE' || err?.name === 'TypeError' || msg.includes('Failed to fetch') || msg.includes('NetworkError');
  }
  // END - isNetworkError

  // START - checkHeartbeat: Pings real internet connectivity + local API to detect offline/slow connection even on localhost
  async checkHeartbeat() {
    if (!navigator.onLine) {
      this.setOfflineState('Wi-Fi or internet connection dropped.');
      return;
    }

    const ctrl = new AbortController();
    const timeoutId = window.setTimeout(() => ctrl.abort(), 3500);
    const startedAt = performance.now();

    try {
      await fetch(`https://www.gstatic.com/generate_204?_hb=${Date.now()}`, {
        method: 'GET',
        mode: 'no-cors',
        cache: 'no-store',
        signal: ctrl.signal
      });
      window.clearTimeout(timeoutId);

      const elapsed = performance.now() - startedAt;
      const wasOffline = !this.isOnline;
      this.isOnline = true;
      this.isSlow = elapsed > 2000;
      this.hideOfflineToast();

      if (wasOffline) {
        await this.flushPendingSync(true);
      } else {
        const pendingCount = await Storage.offlineSync?.countPending?.().catch(() => 0);
        if (pendingCount > 0) {
          await this.flushPendingSync(false);
        }
      }
    } catch {
      window.clearTimeout(timeoutId);
      this.setOfflineState('No internet connection detected. Switched to Offline Mode.');
    }
  }
  // END - checkHeartbeat

  // START - setOfflineState: Marks client offline and displays persistent Flowbite offline toast
  setOfflineState(reason) {
    const changed = this.isOnline;
    this.isOnline = false;
    if (changed || !document.getElementById('cho-nip-offline-toast')) {
      this.showOfflineToast(reason);
    }
  }
  // END - setOfflineState

  // START - flushPendingSync: Auto-pushes queued pending_sync mutations when connection stabilizes
  async flushPendingSync(showReconnectToast = true) {
    if (this.isFlushing || !this.isOnline || !Storage.offlineSync) return;
    this.isFlushing = true;

    try {
      const pending = await Storage.offlineSync.getPending();
      if (!Array.isArray(pending) || pending.length === 0) {
        if (showReconnectToast) {
          Drawers.showStatusToast(true, 'Back Online', 'Internet connection is stable.');
        }
        return;
      }

      let synced = 0;
      for (const item of pending) {
        try {
          const res = await Api.request(item.endpoint, {
            method: item.method || 'POST',
            body: item.payload
          });
          if (res && res.success) {
            await Storage.offlineSync.removePending(item.id);
            synced++;
          }
        } catch {
          break;
        }
      }

      if (synced > 0) {
        Drawers.showStatusToast(true, 'Offline Queue Synced', `Auto-pushed ${synced} queued record(s) to the server.`);
      }
    } finally {
      this.isFlushing = false;
    }
  }
  // END - flushPendingSync

  // START - showOfflineToast: Displays bottom-center red-rose banner OR trimmed sidebar banner based on 5-day cookie preference
  showOfflineToast(message = 'You are in Offline Mode. Changes are saved locally to IndexedDB.') {
    const posPref = Storage.cookies?.get?.('cho_offline_banner_pos');

    if (posPref === 'sidebar') {
      // Refresh 5-day inactivity cookie on active visit
      Storage.cookies?.set?.('cho_offline_banner_pos', 'sidebar', 5);
      const sidebarBanner = document.getElementById('cho-sidebar-offline-banner');
      if (sidebarBanner) {
        const msgEl = document.getElementById('cho-sidebar-offline-msg');
        const tipMsgEl = document.getElementById('cho-sidebar-offline-tooltip-msg');
        if (msgEl) msgEl.textContent = message;
        if (tipMsgEl) tipMsgEl.textContent = `Offline Mode: ${message}`;
        if (sidebarBanner.classList.contains('hidden')) {
          AppAnimations?.banner?.fadeIn ? AppAnimations.banner.fadeIn(sidebarBanner) : sidebarBanner.classList.remove('hidden');
          initTooltips();
        }
        const closeBtn = document.getElementById('cho-sidebar-offline-close');
        if (closeBtn && closeBtn.dataset.bound !== '1') {
          closeBtn.dataset.bound = '1';
          closeBtn.addEventListener('click', () => {
            Storage.cookies?.remove?.('cho_offline_banner_pos');
            if (AppAnimations?.banner?.dissolveOut) {
              AppAnimations.banner.dissolveOut(sidebarBanner, () => {
                sidebarBanner.classList.add('hidden', 'pointer-events-auto');
                sidebarBanner.classList.remove('opacity-0', 'translate-y-2', 'scale-95', 'pointer-events-none');
              });
            } else {
              sidebarBanner.classList.add('hidden');
            }
          });
        }
        return;
      }
    }

    const existing = document.getElementById('cho-nip-offline-toast');
    if (existing) {
      const msgEl = existing.querySelector('[data-offline-msg]');
      if (msgEl) msgEl.textContent = message;
      return;
    }

    const toast = document.createElement('div');
    toast.id = 'cho-nip-offline-toast';
    toast.setAttribute('role', 'banner');
    toast.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-4xl px-4 py-2 text-white bg-rose-600 border-2 border-rose-700 rounded-xl shadow-lg opacity-60 hover:opacity-100 transition-all duration-200';
    toast.innerHTML = `
      <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 min-w-0">
          <div class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-white bg-rose-700/80 border border-rose-500 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
            </svg>
          </div>
          <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2 min-w-0 text-xs sm:text-sm">
            <span class="font-bold text-white shrink-0">Offline Mode:</span>
            <span data-offline-msg class="text-rose-50 truncate">${message}</span>
          </div>
        </div>
        <button type="button" id="cho-nip-offline-toast-close" aria-label="Close" class="shrink-0 text-rose-100 hover:text-white hover:bg-rose-700/70 rounded-lg p-1 transition-colors cursor-pointer">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
        </button>
      </div>
    `;
    document.body.appendChild(toast);
    document.getElementById('cho-nip-offline-toast-close')?.addEventListener('click', () => {
      Storage.cookies?.set?.('cho_offline_banner_pos', 'sidebar', 5);
      if (AppAnimations?.banner?.dissolveOut) {
        AppAnimations.banner.dissolveOut(toast, () => toast.remove());
      } else {
        toast.remove();
      }
    });
  }
  // END - showOfflineToast

  // START - hideOfflineToast: Dismisses both bottom-center and sidebar offline banners once connection stabilizes
  hideOfflineToast() {
    document.getElementById('cho-nip-offline-toast')?.remove();
    document.getElementById('cho-sidebar-offline-banner')?.classList.add('hidden');
  }
  // END - hideOfflineToast
}

// =============================================================================
// SUBCHILD CLASS 6E: DrawerDraftCache
// Temporary 10-minute sessionStorage draft cache for all drawer forms.
// Survives page refresh & navigation; auto-destroys on close, save, tab close, or logout.
// =============================================================================
export class DrawerDraftCache {
  // START - constructor
  constructor() {
    this.PREFIX = 'cho_drawer_draft_';
    this.TTL_MS = 10 * 60 * 1000; // 10 minutes (within 5–15 min window)
    this.FORM_IDS = [
      'nip-pop-add-form',
      'nip-pop-edit-form',
      'nip-pop-add-form2',
      'nip-pop-edit-form2'
    ];
  }
  // END - constructor

  // START - init: Restores non-expired drafts, binds input listeners, and hooks logout cleanup
  init() {
    this.FORM_IDS.forEach((formId) => {
      const form = document.getElementById(formId);
      if (!form) return;

      this.restoreFormDraft(form);

      const saveHandler = () => this.saveFormDraft(form);
      form.addEventListener('input', saveHandler);
      form.addEventListener('change', saveHandler);
      form.addEventListener('click', (e) => {
        if (e.target.closest('[data-brgy-opt], [data-sex-value]')) {
          setTimeout(saveHandler, 20);
        }
      });
    });

    document.addEventListener('click', (e) => {
      if (e.target.closest('[data-cho-logout], #cho-btn-logout, #cho-logout-btn, a[href*="logout"]')) {
        this.clearAllDrafts();
      }
    });
  }
  // END - init

  // START - saveFormDraft: Serializes drawer form fields into sessionStorage with 10-min expiry
  saveFormDraft(form) {
    if (!form || !form.id) return;
    const fields = {};
    form.querySelectorAll('input, select, textarea').forEach((el) => {
      if (!el.id || el.type === 'file') return;
      fields[el.id] = el.type === 'checkbox' ? el.checked : el.value;
    });
    try {
      sessionStorage.setItem(`${this.PREFIX}${form.id}`, JSON.stringify({
        expiresAt: Date.now() + this.TTL_MS,
        fields
      }));
    } catch {
      // Ignore storage quota errors
    }
  }
  // END - saveFormDraft

  // START - restoreFormDraft: Repopulates drawer form fields if draft exists and has not expired
  restoreFormDraft(form) {
    if (!form || !form.id) return;
    const key = `${this.PREFIX}${form.id}`;
    try {
      const raw = sessionStorage.getItem(key);
      if (!raw) return;
      const parsed = JSON.parse(raw);
      if (!parsed || Date.now() > Number(parsed.expiresAt || 0)) {
        sessionStorage.removeItem(key);
        return;
      }

      Object.entries(parsed.fields || {}).forEach(([id, val]) => {
        const el = document.getElementById(id);
        if (!el) return;
        if (el.type === 'checkbox') el.checked = Boolean(val);
        else el.value = val;
      });

      if (form.id === 'nip-pop-add-form2' && parsed.fields?.['tcl-add-sex']) {
        Drawers.popAdd2?.resetSexDropdown?.(parsed.fields['tcl-add-sex']);
      }
      if (form.id === 'nip-pop-edit-form2' && parsed.fields?.['tcl-edit-sex']) {
        Drawers.popEdit2?.setSexDropdown?.(parsed.fields['tcl-edit-sex']);
      }
    } catch {
      sessionStorage.removeItem(key);
    }
  }
  // END - restoreFormDraft

  // START - clearFormDraft: Immediately removes a specific form's temporary draft
  clearFormDraft(formId) {
    if (!formId) return;
    try {
      sessionStorage.removeItem(`${this.PREFIX}${formId}`);
    } catch {}
  }
  // END - clearFormDraft

  // START - clearDrawerDrafts: Clears any form drafts inside a given drawer element
  clearDrawerDrafts(drawerId) {
    const drawer = document.getElementById(drawerId);
    if (!drawer) return;
    drawer.querySelectorAll('form[id]').forEach((f) => this.clearFormDraft(f.id));
  }
  // END - clearDrawerDrafts

  // START - clearAllDrafts: Purges all drawer drafts (used on logout)
  clearAllDrafts() {
    this.FORM_IDS.forEach((id) => this.clearFormDraft(id));
  }
  // END - clearAllDrafts
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
  popAdd: new NipPopAddDrawer(),
  popAdd2: new NipPopAddDrawer2(),
  popEdit: new NipPopEditDrawer(),
  popEdit2: new NipPopEditDrawer2(),
  popDelete: new NipPopDeleteModal(),
  tclInfant: new TclInfantDrawer(),
  gate: new NipWorkspaceGate(),
  popResolver: new NipPopWorksheetResolver(),
  offline: new OfflineSyncMonitor(),
  drafts: new DrawerDraftCache(),

  init() {
    this.offline.init();
    this.gate.init();
    this.load.init();
    this.generate.init();
    this.reset.init();
    this.upload.init();
    this.popAdd.init();
    this.popAdd2.init();
    this.popEdit.init();
    this.popEdit2.init();
    this.drafts.init();
    this.popDelete.init();
    this.tclInfant.init();

    // Show global NIP resync banner & enforce download card disabled-state if resync is pending
    const isNipPage = window.location.pathname.toLowerCase().includes('/nip/');
    const pendingResync = Storage.nipResyncState?.getPending?.();
    if (isNipPage && pendingResync) {
      this.showResyncToast(pendingResync.source_file);
    }
    this.syncDownloadCardState();
  },

  // START - syncDownloadCardState: Disables #cho-nip-action-download-card while a resync is pending
  syncDownloadCardState() {
    const downloadCard = document.getElementById('cho-nip-action-download-card');
    if (!downloadCard) return;

    const pending = Storage.nipResyncState?.getPending?.();
    if (pending) {
      downloadCard.setAttribute('aria-disabled', 'true');
      downloadCard.setAttribute('title', 'Please click Resync & Refresh in the top banner first.');
      downloadCard.classList.add('opacity-50', 'grayscale-[35%]', 'cursor-not-allowed', 'pointer-events-none');
    } else {
      downloadCard.removeAttribute('aria-disabled');
      downloadCard.removeAttribute('title');
      downloadCard.classList.remove('opacity-50', 'grayscale-[35%]', 'cursor-not-allowed', 'pointer-events-none');
    }
  },
  // END - syncDownloadCardState

  // START - showStatusToast: Bottom-right auto-hiding Flowbite toast (if success -> emerald, else -> rose error)
  showStatusToast(isSuccess, title, message) {
    const existing = document.getElementById('cho-nip-status-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'cho-nip-status-toast';
    toast.setAttribute('role', 'alert');

    if (isSuccess) {
      toast.className = 'fixed bottom-5 right-5 z-50 flex items-center w-full max-w-md p-4 text-slate-700 bg-white border border-emerald-200 rounded-xl shadow-lg transition-all';
      toast.innerHTML = `
        <div class="inline-flex items-center justify-center shrink-0 w-9 h-9 text-emerald-600 bg-emerald-50 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <div class="ms-3 text-sm flex-1 min-w-0">
          <p class="font-bold text-slate-900">${title}</p>
          <p class="text-slate-600 mt-0.5 text-xs sm:text-sm">${message}</p>
        </div>
        <button type="button" id="cho-nip-status-toast-close" aria-label="Close" class="ms-2 text-slate-400 hover:text-slate-700 rounded-lg p-1.5 cursor-pointer">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
        </button>
      `;
    } else {
      toast.className = 'fixed bottom-5 right-5 z-50 flex items-center w-full max-w-md p-4 text-slate-700 bg-white border border-rose-200 rounded-xl shadow-lg transition-all';
      toast.innerHTML = `
        <div class="inline-flex items-center justify-center shrink-0 w-9 h-9 text-rose-600 bg-rose-50 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </div>
        <div class="ms-3 text-sm flex-1 min-w-0">
          <p class="font-bold text-rose-700">${title}</p>
          <p class="text-slate-600 mt-0.5 text-xs sm:text-sm break-words">${message}</p>
        </div>
        <button type="button" id="cho-nip-status-toast-close" aria-label="Close" class="ms-2 text-slate-400 hover:text-slate-700 rounded-lg p-1.5 cursor-pointer">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
        </button>
      `;
    }

    document.body.appendChild(toast);
    document.getElementById('cho-nip-status-toast-close')?.addEventListener('click', () => toast.remove());

    setTimeout(() => {
      if (toast.isConnected) toast.remove();
    }, isSuccess ? 4000 : 6000);
  },
  // END - showStatusToast

  // START - showResyncToast: Wided top-center news banner globally on NIP pages with Resync & Refresh + emerald Save .xlsx buttons
  showResyncToast(sourceFile) {
    if (!window.location.pathname.toLowerCase().includes('/nip/')) return;
    const existing = document.getElementById('cho-nip-resync-toast');
    if (existing) existing.remove();

    const downloadHref = Api.resolveUrl(`/api/v1/nip/download?file=${encodeURIComponent(sourceFile)}&_t=${Date.now()}`);
    const toast = document.createElement('div');
    toast.id = 'cho-nip-resync-toast';
    toast.setAttribute('role', 'banner');
    toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-4xl px-4 py-3 text-slate-700 bg-white border border-slate-200 rounded-xl shadow-xl transition-all';
    toast.innerHTML = `
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
          <div class="inline-flex items-center justify-center shrink-0 w-9 h-9 text-cho-blue-900 bg-blue-50 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
          </div>
          <div class="min-w-0">
            <p class="text-xs font-bold text-slate-900">Excel Data Updated</p>
            <p class="text-[11px] text-slate-500 truncate">
              Changes saved to <span class="font-semibold text-slate-700">${sourceFile}</span>. Click Resync to refresh your view &amp; cache.
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
          <button type="button" id="cho-nip-toast-resync-btn"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 rounded-lg transition-colors cursor-pointer">
            Resync &amp; Refresh
          </button>
          <a id="cho-nip-toast-save-btn"
             href="${downloadHref}"
             class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">
            Save .xlsx
          </a>
          <button type="button" id="cho-nip-toast-close-btn" aria-label="Close"
                  class="text-slate-400 hover:text-slate-700 rounded-lg p-1.5 cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
          </button>
        </div>
      </div>
    `;

    document.body.appendChild(toast);

    document.getElementById('cho-nip-toast-close-btn')?.addEventListener('click', () => {
      toast.remove();
    });

    document.getElementById('cho-nip-toast-save-btn')?.addEventListener('click', async (e) => {
      const link = e.currentTarget;
      const freshHref = Api.resolveUrl(`/api/v1/nip/download?file=${encodeURIComponent(sourceFile)}&_t=${Date.now()}`);
      link.setAttribute('href', freshHref);
      Storage.nipResyncState?.clear();
      Drawers.syncDownloadCardState();
      try {
        const res = await fetch(freshHref, {
          cache: 'no-store'
        });
        if (res.ok) {
          const blob = await res.blob();
          const freshFile = new File([blob], sourceFile, { type: blob.type });
          await Drawers.load.parseWorkbookDashboardStats(freshFile);
        }
      } catch (err) {
        console.warn('[ResyncToast] Save .xlsx resync error:', err);
      }
      setTimeout(() => {
        window.location.reload();
      }, 400);
    });

    document.getElementById('cho-nip-toast-resync-btn')?.addEventListener('click', async (e) => {
      const btn = e.currentTarget;
      btn.disabled = true;
      btn.textContent = 'Resyncing...';
      try {
        const res = await fetch(Api.resolveUrl(`/api/v1/nip/download?file=${encodeURIComponent(sourceFile)}&_t=${Date.now()}`), {
          cache: 'no-store'
        });
        if (res.ok) {
          const blob = await res.blob();
          const freshFile = new File([blob], sourceFile, { type: blob.type });
          await Drawers.load.parseWorkbookDashboardStats(freshFile);
        }
        Storage.nipResyncState?.clear();
        Drawers.syncDownloadCardState();
      } catch (err) {
        console.warn('[ResyncToast] Failed to re-fetch workbook:', err);
      }
      window.location.reload();
    });
  }
  // END - showResyncToast
};

export const Drawer = Drawers;
