/**
 * CHO MARAWI PORTAL - CHARTS MODULE
 * Integrates Flowbite Charts & ApexCharts for Public Health Intelligence:
 * - Outpatient & Maternal Care Area Chart
 * - Barangay Health Services Column/Bar Chart
 * - Dynamic theme adaptation (Light / Dark mode colors)
 */

import { Api } from './api.js';

let ApexCharts = null;

export const Charts = {
  instances: {},
  lastNipData: null,
  lastNip2Data: null,
  _apexPromise: null,

  async loadApexCharts() {
    if (typeof ApexCharts === 'function') return ApexCharts;
    if (!this._apexPromise) {
      this._apexPromise = import('apexcharts').then((m) => {
        const resolved = m?.default?.default || m?.default || m;
        ApexCharts = typeof resolved === 'function' ? resolved : (window.ApexCharts || resolved);
        return ApexCharts;
      });
    }
    return this._apexPromise;
  },

  async init() {
    const hasCharts = [
      'cho-chart-outpatient-area',
      'cho-chart-barangay-bar',
      'cho-chart-services-donut',
      'cho-nip-antigens-chart',
      'cho-nip-catchments-chart',
      'cho-nip-antigens-chart2',
      'cho-nip-catchments-chart2'
    ].some((id) => document.getElementById(id));

    if (!hasCharts) return;

    await this.loadApexCharts();
    this.initOutpatientAreaChart();
    this.initBarangayBarChart();
    this.initServicesDonutChart();
    this.initNip2DashboardCharts();

    // Listen for theme changes to refresh chart color scheme
    const observer = new MutationObserver(() => {
      this.refreshChartThemes();
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'data-theme'] });
  },

  isDark() {
    return document.documentElement.classList.contains('dark');
  },

  initOutpatientAreaChart() {
    const el = document.getElementById('cho-chart-outpatient-area');
    if (!el || typeof ApexCharts === 'undefined') return;

    const isDark = this.isDark();
    const options = {
      chart: {
        height: '320px',
        maxWidth: '100%',
        type: 'area',
        fontFamily: 'Poppins, Averta, sans-serif',
        toolbar: { show: false },
        background: 'transparent'
      },
      tooltip: {
        enabled: true,
        theme: isDark ? 'dark' : 'light',
        x: { show: true }
      },
      fill: {
        type: 'gradient',
        gradient: {
          opacityFrom: 0.55,
          opacityTo: 0.05,
          shade: isDark ? '#1e293b' : '#0f3b82',
          gradientToColors: ['#fab915', '#0f3b82']
        }
      },
      dataLabels: { enabled: false },
      stroke: { width: 3, curve: 'smooth' },
      grid: {
        show: true,
        strokeDashArray: 4,
        padding: { left: 2, right: 2, top: 0 },
        borderColor: isDark ? '#334155' : '#e2e8f0'
      },
      series: [
        {
          name: 'Primary Outpatient Consultations',
          data: [420, 510, 480, 620, 710, 680, 750],
          color: '#0f3b82'
        },
        {
          name: 'Maternal & Child Care (MCHN)',
          data: [210, 240, 290, 310, 380, 340, 390],
          color: '#fab915'
        }
      ],
      xaxis: {
        categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        labels: {
          style: {
            colors: isDark ? '#94a3b8' : '#64748b',
            fontSize: '12px'
          }
        },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: {
        labels: {
          style: {
            colors: isDark ? '#94a3b8' : '#64748b',
            fontSize: '12px'
          }
        }
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        labels: {
          colors: isDark ? '#e2e8f0' : '#1e293b'
        }
      }
    };

    if (this.instances.outpatient) {
      this.instances.outpatient.destroy();
    }
    this.instances.outpatient = new ApexCharts(el, options);
    this.instances.outpatient.render();
  },

  initBarangayBarChart() {
    const el = document.getElementById('cho-chart-barangay-bar');
    if (!el || typeof ApexCharts === 'undefined') return;

    const isDark = this.isDark();
    const options = {
      series: [
        {
          name: 'Routine Immunizations (EPI)',
          data: [142, 98, 115, 160, 134, 88, 120]
        },
        {
          name: 'TB-DOTS Consultations',
          data: [35, 24, 40, 52, 28, 19, 31]
        }
      ],
      chart: {
        type: 'bar',
        height: 320,
        fontFamily: 'Poppins, Averta, sans-serif',
        toolbar: { show: false },
        background: 'transparent'
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          borderRadius: 4
        }
      },
      dataLabels: { enabled: false },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      xaxis: {
        categories: ['Tuca', 'Dulay', 'Kilala', 'Marawi Central', 'Basak Malutlut', 'Matampay', 'Banggolo'],
        labels: {
          style: {
            colors: isDark ? '#94a3b8' : '#64748b',
            fontSize: '11px'
          }
        }
      },
      yaxis: {
        title: {
          text: 'Number of Patients Screened',
          style: { color: isDark ? '#94a3b8' : '#64748b' }
        },
        labels: {
          style: { colors: isDark ? '#94a3b8' : '#64748b' }
        }
      },
      colors: ['#00843d', '#0f3b82'],
      fill: { opacity: 1 },
      grid: {
        borderColor: isDark ? '#334155' : '#e2e8f0',
        strokeDashArray: 4
      },
      tooltip: {
        theme: isDark ? 'dark' : 'light'
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        labels: {
          colors: isDark ? '#e2e8f0' : '#1e293b'
        }
      }
    };

    if (this.instances.barangay) {
      this.instances.barangay.destroy();
    }
    this.instances.barangay = new ApexCharts(el, options);
    this.instances.barangay.render();
  },

  initServicesDonutChart() {
    const el = document.getElementById('cho-chart-services-donut');
    if (!el || typeof ApexCharts === 'undefined') return;

    const isDark = this.isDark();
    const options = {
      series: [42, 26, 18, 14],
      labels: ['Outpatient Clinic', 'Maternal & Child (MCHN)', 'Communicable / TB', 'Environmental Health'],
      colors: ['#0f3b82', '#fab915', '#00843d', '#6366f1'],
      chart: {
        type: 'donut',
        height: 260,
        fontFamily: 'Poppins, Averta, sans-serif',
        background: 'transparent'
      },
      stroke: { colors: [isDark ? '#1e293b' : '#ffffff'], width: 2 },
      plotOptions: {
        pie: {
          donut: {
            size: '72%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total Cases',
                fontSize: '13px',
                fontWeight: 600,
                color: isDark ? '#94a3b8' : '#64748b',
                formatter: () => '1,420'
              }
            }
          }
        }
      },
      dataLabels: { enabled: false },
      legend: {
        position: 'bottom',
        labels: {
          colors: isDark ? '#cbd5e1' : '#334155'
        }
      },
      tooltip: {
        theme: isDark ? 'dark' : 'light'
      }
    };

    if (this.instances.donut) {
      this.instances.donut.destroy();
    }
    this.instances.donut = new ApexCharts(el, options);
    this.instances.donut.render();
  },

  // START - initNipDashboardCharts: Scans, mounts, and updates NIP Excel-based dynamic charts
  initNipDashboardCharts(kpiData) {
    if (!kpiData) return;
    this.lastNipData = kpiData;

    const canvasPlaceholder = document.getElementById('cho-nip-canvas-card');
    const chartsSkeleton = document.getElementById('cho-nip-charts-skeleton');
    const chartsContainer = document.getElementById('cho-nip-charts-container');
    const activeFilenameEl = document.getElementById('cho-nip-active-filename');
    const activeMetaEl = document.getElementById('cho-nip-active-sheet-meta');

    if (activeFilenameEl && kpiData.source_file) {
      activeFilenameEl.textContent = kpiData.source_file;
    }
    if (activeMetaEl) {
      activeMetaEl.textContent = `Year ${kpiData.active_year || 2026} • Quarter ${kpiData.active_quarter || 'Q1'} • ${kpiData.antigens?.length || 18} Antigens Monitored • 74 Master Barangays`;
    }

    // Show Flowbite bar & donut chart skeleton briefly, then reveal live ApexCharts
    if (canvasPlaceholder) canvasPlaceholder.classList.add('hidden');
    if (chartsSkeleton && chartsContainer) {
      chartsContainer.classList.add('hidden');
      chartsSkeleton.classList.remove('hidden');
      setTimeout(() => {
        chartsSkeleton.classList.add('hidden');
        chartsContainer.classList.remove('hidden');
        requestAnimationFrame(() => {
          this.renderNipAntigenChart(kpiData.antigens || []);
          this.renderNipCatchmentChart(kpiData.catchments || [], kpiData.stats?.ficAccomplished || 0);
        });
      }, 240);
      return;
    }

    if (chartsContainer) chartsContainer.classList.remove('hidden');
    requestAnimationFrame(() => {
      this.renderNipAntigenChart(kpiData.antigens || []);
      this.renderNipCatchmentChart(kpiData.catchments || [], kpiData.stats?.ficAccomplished || 0);
    });
  },
  // END - initNipDashboardCharts

  // START - initNip2DashboardCharts: Database-driven NIP2 charts & expandable stats grid
  async initNip2DashboardCharts() {
    const chartsContainer = document.getElementById('cho-nip-charts-container2');
    const antigensEl = document.getElementById('cho-nip-antigens-chart2');
    if (!chartsContainer && !antigensEl) return;

    this.bindNip2SeeMoreToggle();
    this.bindNip2PeriodFilter();
    this.initNip2BarangayCalculator();

    const defaultDataset = this.getNip2DatabaseFallback();
    let activeData = defaultDataset;

    try {
      const res = await fetch(Api.resolveUrl('/api/v1/nip/kpis'), {
        headers: { Accept: 'application/json' }
      });
      if (res.ok) {
        const json = await res.json();
        const apiData = json?.data;
        if (apiData && (apiData.antigens?.length > 0 || apiData.catchments?.length > 0)) {
          activeData = {
            ...defaultDataset,
            ...apiData,
            antigens: apiData.antigens?.length ? apiData.antigens : defaultDataset.antigens,
            catchments: apiData.catchments?.length ? apiData.catchments : defaultDataset.catchments
          };
        } else if (apiData) {
          activeData = {
            ...defaultDataset,
            total_population: apiData.total_population || defaultDataset.total_population,
            annual_target: apiData.annual_target || defaultDataset.annual_target,
            fic_accomplished: apiData.fic_accomplished || defaultDataset.fic_accomplished,
            fic_coverage_pct: apiData.fic_coverage_pct ?? defaultDataset.fic_coverage_pct
          };
        }
      }
    } catch {
      // Gracefully fall back to database baseline dataset when API is unreachable
    }

    this.lastNip2Data = activeData;
    this.applyNip2ChartPeriod();

    const canvasPlaceholder = document.getElementById('cho-nip-canvas-card2');
    const chartsSkeleton = document.getElementById('cho-nip-charts-skeleton2');
    if (canvasPlaceholder) canvasPlaceholder.classList.add('hidden');

    if (chartsSkeleton && chartsContainer) {
      chartsContainer.classList.add('hidden');
      chartsSkeleton.classList.remove('hidden');
      setTimeout(() => {
        chartsSkeleton.classList.add('hidden');
        chartsContainer.classList.remove('hidden');
        requestAnimationFrame(() => {
          this.applyNip2ChartPeriod();
        });
      }, 240);
      return;
    }

    if (chartsContainer) chartsContainer.classList.remove('hidden');
    requestAnimationFrame(() => {
      this.applyNip2ChartPeriod();
    });
  },
  // END - initNip2DashboardCharts

  // START - bindNip2SeeMoreToggle: Expandable duplicated stats grid toggle for NIP2
  bindNip2SeeMoreToggle() {
    const toggleBtn = document.getElementById('cho-nip-stats-see-more2');
    const expandable = document.getElementById('cho-nip-stats-expandable2');
    const labelEl = document.getElementById('cho-nip-stats-see-more-text2');
    const iconEl = document.getElementById('cho-nip-stats-see-more-icon2');
    if (!toggleBtn || !expandable || toggleBtn.dataset.bound === '1') return;

    toggleBtn.dataset.bound = '1';
    toggleBtn.addEventListener('click', () => {
      const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
      const nextState = !isExpanded;
      toggleBtn.setAttribute('aria-expanded', String(nextState));

      expandable.classList.toggle('grid-rows-[1fr]', nextState);
      expandable.classList.toggle('opacity-100', nextState);
      expandable.classList.toggle('grid-rows-[0fr]', !nextState);
      expandable.classList.toggle('opacity-0', !nextState);

      if (labelEl) labelEl.textContent = nextState ? 'SEE LESS' : 'SEE MORE';
      if (iconEl) iconEl.classList.toggle('rotate-180', nextState);
      const searchWrap = document.getElementById('cho-nip2-brgy-search-wrap');
      if (searchWrap) searchWrap.classList.toggle('hidden', !nextState);
    });
  },
  // END - bindNip2SeeMoreToggle

  // START - bindNip2PeriodFilter: Global Yearly/Monthly filter affecting KPIs, expandable cards, and charts
  bindNip2PeriodFilter() {
    this.nip2Period = { year: 'all', month: 'all' };
    const dropdownEl = document.getElementById('cho-nip2-period-dropdown');
    const labelEl = document.getElementById('cho-nip2-period-label');
    const yearEl = document.getElementById('cho-nip2-period-year');
    const monthEl = document.getElementById('cho-nip2-period-month');
    const overallBtn = document.getElementById('cho-nip2-period-overall');
    const applyBtn = document.getElementById('cho-nip2-period-apply');
    const resetBtn = document.getElementById('cho-nip2-period-reset');
    if (!yearEl || !monthEl) return;

    const monthNames = {
      '01': 'January', '02': 'February', '03': 'March', '04': 'April',
      '05': 'May', '06': 'June', '07': 'July', '08': 'August',
      '09': 'September', '10': 'October', '11': 'November', '12': 'December'
    };

    const syncLabel = () => {
      if (!labelEl) return;
      const { year, month } = this.nip2Period;
      if (year === 'all' && month === 'all') {
        labelEl.textContent = 'All Years (Overall)';
      } else if (month === 'all') {
        labelEl.textContent = `Year ${year} (Annually)`;
      } else {
        labelEl.textContent = `${monthNames[month] || month} ${year === 'all' ? '2026' : year}`;
      }
    };

    const applyScope = () => {
      const year = yearEl.value || 'all';
      const month = monthEl.value || 'all';
      this.nip2Period = {
        year: month !== 'all' && year === 'all' ? '2026' : year,
        month
      };
      if (month !== 'all' && year === 'all') yearEl.value = '2026';

      syncLabel();
      dropdownEl?.classList.add('hidden');
      this.refreshNip2BarangayCalculator?.();
      this.applyNip2ChartPeriod?.();
    };

    dropdownEl?.addEventListener('click', (e) => e.stopPropagation());
    applyBtn?.addEventListener('click', applyScope);

    const resetPeriod = () => {
      yearEl.value = 'all';
      monthEl.value = 'all';
      this.nip2Period = { year: 'all', month: 'all' };
      syncLabel();
      dropdownEl?.classList.add('hidden');
      this.refreshNip2BarangayCalculator?.();
      this.applyNip2ChartPeriod?.();
    };

    overallBtn?.addEventListener('click', resetPeriod);
    resetBtn?.addEventListener('click', resetPeriod);
  },

  applyNip2ChartPeriod() {
    const base = this.lastNip2Data || this.getNip2DatabaseFallback();
    const { year, month } = this.nip2Period || { year: 'all', month: 'all' };
    const tclData = this.buildNip2DatasetFromTcl(year, month);

    const merged = {
      ...base,
      annual_target: tclData.annual_target,
      fic_accomplished: tclData.fic_accomplished,
      fic_coverage_pct: tclData.fic_coverage_pct,
      antigens: tclData.antigens,
      catchments: tclData.catchments
    };

    this.applyNip2StatCards(merged);
    this.renderNipAntigenChart(merged.antigens, 'cho-nip-antigens-chart2', 'nipAntigens2');
    this.renderNipCatchmentChart(merged.catchments, merged.fic_accomplished, '2');
  },

  // START - buildNip2DatasetFromTcl: Computes live antigens & catchment charts directly from NIP2/newborns TCL records
  buildNip2DatasetFromTcl(year = 'all', month = 'all') {
    let records = [];
    try {
      const raw = localStorage.getItem('cho_nip2_tcl_records');
      if (raw) {
        const parsed = JSON.parse(raw);
        if (Array.isArray(parsed) && parsed.length > 0) records = parsed;
      }
    } catch {}
    if (!records.length && typeof this.getDefaultTclMockRecords === 'function') {
      records = this.getDefaultTclMockRecords();
    }

    const matchesPeriod = (iso = '') => {
      if (year === 'all' && month === 'all') return true;
      if (!iso) return false;
      const prefix = month === 'all' ? `${year}-` : `${year}-${month}-`;
      return String(iso).startsWith(prefix);
    };

    const scoped = records.filter((r) => matchesPeriod(r.reg_date || r.dob || ''));
    const target = scoped.length;

    const specs = [
      { name: 'BCG', get: (r) => r.imm1?.bcg },
      { name: 'Hepa - B', get: (r) => r.imm1?.hepa },
      { name: 'DPT 1', get: (r) => r.imm2?.dpt?.[0] },
      { name: 'DPT 2', get: (r) => r.imm2?.dpt?.[1] },
      { name: 'DPT 3', get: (r) => r.imm2?.dpt?.[2] },
      { name: 'OPV 1', get: (r) => r.imm2?.opv?.[0] },
      { name: 'OPV 2', get: (r) => r.imm2?.opv?.[1] },
      { name: 'OPV 3', get: (r) => r.imm2?.opv?.[2] },
      { name: 'PCV 1', get: (r) => r.imm2?.pcv?.[0] },
      { name: 'PCV 2', get: (r) => r.imm2?.pcv?.[1] },
      { name: 'PCV 3', get: (r) => r.imm2?.pcv?.[2] },
      { name: 'IPV 1', get: (r) => r.imm2?.ipv?.[0] },
      { name: 'IPV 2', get: (r) => r.imm2?.ipv?.[1] },
      { name: 'MCV1 (9 mos)', get: (r) => r.imm2?.mcv1?.[0] },
      { name: 'MCV2 (12 mos)', get: (r) => r.imm2?.mcv2?.[0] }
    ];

    const antigens = specs.map((s) => {
      let male = 0;
      let female = 0;
      scoped.forEach((r) => {
        const d = s.get(r);
        if (d?.status === 'INJECTED' && matchesPeriod(d.iso || r.reg_date || '')) {
          if (String(r.sex || '').toUpperCase() === 'MALE') male += 1;
          else female += 1;
        }
      });
      return { name: s.name, target, total: male + female, male, female };
    });

    let ficAccomplished = 0;
    let ficMale = 0;
    let ficFemale = 0;
    const brgyCounts = new Map();

    scoped.forEach((r) => {
      const brgy = (r.barangay || 'Unknown').trim();
      brgyCounts.set(brgy, (brgyCounts.get(brgy) || 0) + 1);

      const allDoses = [
        r.imm1?.bcg, r.imm1?.hepa,
        ...(r.imm2?.dpt || []), ...(r.imm2?.opv || []),
        ...(r.imm2?.pcv || []), ...(r.imm2?.ipv || []),
        ...(r.imm2?.mcv1 || []), ...(r.imm2?.mcv2 || [])
      ].filter(Boolean);

      if (allDoses.length > 0 && allDoses.every((d) => d.status === 'INJECTED')) {
        ficAccomplished += 1;
        if (String(r.sex || '').toUpperCase() === 'MALE') ficMale += 1;
        else ficFemale += 1;
      }
    });

    antigens.push({ name: 'Fully Immunized (100%)', target, total: ficAccomplished, male: ficMale, female: ficFemale });

    const catchments = Array.from(brgyCounts.entries())
      .sort((a, b) => b[1] - a[1])
      .slice(0, 8)
      .map(([name, cnt]) => ({ name, barangays: 1, fic_total: cnt }));

    return {
      annual_target: target,
      fic_accomplished: ficAccomplished,
      fic_coverage_pct: target > 0 ? (ficAccomplished / target) * 100 : 0,
      antigens,
      catchments
    };
  },
  // END - buildNip2DatasetFromTcl
  // END - bindNip2PeriodFilter

  // START - getDefaultTclMockRecords: Baseline TCL fallback when NIP2/newborns has not yet been visited
  getDefaultTclMockRecords() {
    return [
      { id: 1, barangay: 'Tuca', reg_date: '2026-10-04', sex: 'MALE', imm1: { bcg: { status: 'INJECTED', iso: '2026-10-04' }, hepa: { status: 'INJECTED', iso: '2026-09-18' } }, imm2: { dpt: [{ status: 'INJECTED', iso: '2026-10-30' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], opv: [{ status: 'INJECTED', iso: '2026-10-30' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], pcv: [{ status: 'INJECTED', iso: '2026-10-30' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], ipv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 2, barangay: 'Basak Malutlut', reg_date: '2026-10-09', sex: 'FEMALE', imm1: { bcg: { status: 'INJECTED', iso: '2026-10-09' }, hepa: { status: 'INJECTED', iso: '2026-09-25' } }, imm2: { dpt: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], opv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], pcv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], ipv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 3, barangay: 'Banggolo', reg_date: '2026-10-14', sex: 'MALE', imm1: { bcg: { status: 'NOT YET', iso: '' }, hepa: { status: 'NOT YET', iso: '' } }, imm2: { dpt: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], opv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], pcv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], ipv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 4, barangay: 'Marawi Central', reg_date: '2026-10-19', sex: 'FEMALE', imm1: { bcg: { status: 'INJECTED', iso: '2026-10-19' }, hepa: { status: 'NOT YET', iso: '' } }, imm2: { dpt: [{ status: 'INJECTED', iso: '2026-09-23' }, { status: 'INJECTED', iso: '2026-10-21' }, { status: 'NOT YET', iso: '' }], opv: [{ status: 'INJECTED', iso: '2026-09-23' }, { status: 'INJECTED', iso: '2026-10-21' }, { status: 'NOT YET', iso: '' }], pcv: [{ status: 'INJECTED', iso: '2026-09-23' }, { status: 'INJECTED', iso: '2026-10-21' }, { status: 'NOT YET', iso: '' }], ipv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 5, barangay: 'Dulay', reg_date: '2026-10-22', sex: 'MALE', imm1: { bcg: { status: 'INJECTED', iso: '2026-10-22' }, hepa: { status: 'INJECTED', iso: '2026-07-05' } }, imm2: { dpt: [{ status: 'INJECTED', iso: '2026-08-16' }, { status: 'INJECTED', iso: '2026-09-13' }, { status: 'INJECTED', iso: '2026-10-11' }], opv: [{ status: 'INJECTED', iso: '2026-08-16' }, { status: 'INJECTED', iso: '2026-09-13' }, { status: 'INJECTED', iso: '2026-10-11' }], pcv: [{ status: 'INJECTED', iso: '2026-08-16' }, { status: 'INJECTED', iso: '2026-09-13' }, { status: 'INJECTED', iso: '2026-10-11' }], ipv: [{ status: 'INJECTED', iso: '2026-10-11' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 6, barangay: 'Kilala', reg_date: '2026-10-27', sex: 'FEMALE', imm1: { bcg: { status: 'NOT YET', iso: '' }, hepa: { status: 'INJECTED', iso: '2026-10-15' } }, imm2: { dpt: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], opv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], pcv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], ipv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 7, barangay: 'Matampay', reg_date: '2026-11-02', sex: 'MALE', imm1: { bcg: { status: 'INJECTED', iso: '2026-11-02' }, hepa: { status: 'INJECTED', iso: '2026-05-20' } }, imm2: { dpt: [{ status: 'INJECTED', iso: '2026-07-01' }, { status: 'INJECTED', iso: '2026-07-29' }, { status: 'INJECTED', iso: '2026-08-26' }], opv: [{ status: 'INJECTED', iso: '2026-07-01' }, { status: 'INJECTED', iso: '2026-07-29' }, { status: 'INJECTED', iso: '2026-08-26' }], pcv: [{ status: 'INJECTED', iso: '2026-07-01' }, { status: 'INJECTED', iso: '2026-07-29' }, { status: 'INJECTED', iso: '2026-08-26' }], ipv: [{ status: 'INJECTED', iso: '2026-08-26' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 8, barangay: 'Lilod Madaya', reg_date: '2026-11-06', sex: 'FEMALE', imm1: { bcg: { status: 'INJECTED', iso: '2026-11-06' }, hepa: { status: 'INJECTED', iso: '2026-03-11' } }, imm2: { dpt: [{ status: 'INJECTED', iso: '2026-04-22' }, { status: 'INJECTED', iso: '2026-05-20' }, { status: 'INJECTED', iso: '2026-06-17' }], opv: [{ status: 'INJECTED', iso: '2026-04-22' }, { status: 'INJECTED', iso: '2026-05-20' }, { status: 'INJECTED', iso: '2026-06-17' }], pcv: [{ status: 'INJECTED', iso: '2026-04-22' }, { status: 'INJECTED', iso: '2026-05-20' }, { status: 'INJECTED', iso: '2026-06-17' }], ipv: [{ status: 'INJECTED', iso: '2026-06-17' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 9, barangay: 'Marinaut', reg_date: '2026-11-11', sex: 'MALE', imm1: { bcg: { status: 'NOT YET', iso: '' }, hepa: { status: 'NOT YET', iso: '' } }, imm2: { dpt: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], opv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], pcv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], ipv: [{ status: 'NOT YET', iso: '' }, { status: 'NOT YET', iso: '' }], mcv1: [{ status: 'NOT YET', iso: '' }], mcv2: [{ status: 'NOT YET', iso: '' }] } },
      { id: 10, barangay: 'Poblacion', reg_date: '2026-11-15', sex: 'FEMALE', imm1: { bcg: { status: 'INJECTED', iso: '2025-11-20' }, hepa: { status: 'INJECTED', iso: '2025-11-15' } }, imm2: { dpt: [{ status: 'INJECTED', iso: '2025-12-27' }, { status: 'INJECTED', iso: '2026-01-24' }, { status: 'INJECTED', iso: '2026-02-21' }], opv: [{ status: 'INJECTED', iso: '2025-12-27' }, { status: 'INJECTED', iso: '2026-01-24' }, { status: 'INJECTED', iso: '2026-02-21' }], pcv: [{ status: 'INJECTED', iso: '2025-12-27' }, { status: 'INJECTED', iso: '2026-01-24' }, { status: 'INJECTED', iso: '2026-02-21' }], ipv: [{ status: 'INJECTED', iso: '2026-02-21' }, { status: 'INJECTED', iso: '2026-08-15' }], mcv1: [{ status: 'INJECTED', iso: '2026-08-15' }], mcv2: [{ status: 'INJECTED', iso: '2026-11-15' }] } }
    ];
  },
  // END - getDefaultTclMockRecords

  // START - initNip2BarangayCalculator: Live barangay search dropdown & annual immunization totals synced with NIP2/newborns
  initNip2BarangayCalculator() {
    const searchEl = document.getElementById('cho-nip2-brgy-search');
    const searchBtnEl = document.getElementById('cho-nip2-brgy-search-btn');
    const clearEl = document.getElementById('cho-nip2-brgy-search-clear');
    const toggleDropdownEl = document.getElementById('cho-nip2-brgy-toggle');
    const dropdownEl = document.getElementById('cho-nip2-brgy-dropdown');
    if (!searchEl) return;

    const loadRecords = () => {
      try {
        const raw = localStorage.getItem('cho_nip2_tcl_records');
        if (raw) {
          const parsed = JSON.parse(raw);
          if (Array.isArray(parsed) && parsed.length > 0) return parsed;
        }
      } catch {}
      return this.getDefaultTclMockRecords();
    };

    const matchesPeriod = (iso = '') => {
      const { year, month } = this.nip2Period || { year: 'all', month: 'all' };
      if (year === 'all' && month === 'all') return true;
      if (!iso) return false;
      const prefix = month === 'all' ? `${year}-` : `${year}-${month}-`;
      return String(iso).startsWith(prefix);
    };

    const countInjected = (arr) => (
      Array.isArray(arr)
        ? arr.filter((d) => d?.status === 'INJECTED' && matchesPeriod(d.iso)).length
        : 0
    );

    const calculateAndRender = () => {
      const q = searchEl.value.trim().toLowerCase();
      if (clearEl) clearEl.classList.toggle('hidden', q.length === 0);

      const records = loadRecords();
      const scoped = records.filter((r) => {
        const matchBrgy = !q || (r.barangay || '').toLowerCase().includes(q);
        const matchTime = matchesPeriod(r.reg_date || r.dob || '');
        return matchBrgy && matchTime;
      });

      let fullyCompleted = 0;
      let male = 0;
      let female = 0;
      let bcg = 0;
      let hepa = 0;
      let dpt = 0;
      let opv = 0;
      let pcv = 0;
      let ipv = 0;
      let mcv1 = 0;
      let mcv2 = 0;

      scoped.forEach((rec) => {
        const sex = String(rec.sex || '').toUpperCase();
        if (sex === 'MALE') male += 1;
        else if (sex === 'FEMALE') female += 1;

        if (rec.imm1?.bcg?.status === 'INJECTED' && matchesPeriod(rec.imm1?.bcg?.iso)) bcg += 1;
        if (rec.imm1?.hepa?.status === 'INJECTED' && matchesPeriod(rec.imm1?.hepa?.iso)) hepa += 1;
        dpt += countInjected(rec.imm2?.dpt);
        opv += countInjected(rec.imm2?.opv);
        pcv += countInjected(rec.imm2?.pcv);
        ipv += countInjected(rec.imm2?.ipv);
        mcv1 += countInjected(rec.imm2?.mcv1);
        mcv2 += countInjected(rec.imm2?.mcv2);

        const allDoses = [
          rec.imm1?.bcg,
          rec.imm1?.hepa,
          ...(rec.imm2?.dpt || []),
          ...(rec.imm2?.opv || []),
          ...(rec.imm2?.pcv || []),
          ...(rec.imm2?.ipv || []),
          ...(rec.imm2?.mcv1 || []),
          ...(rec.imm2?.mcv2 || [])
        ].filter(Boolean);
        if (allDoses.length > 0 && allDoses.every((d) => d.status === 'INJECTED')) {
          fullyCompleted += 1;
        }
      });

      const notYetCompleted = scoped.length - fullyCompleted;
      const setVal = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = Number(val).toLocaleString();
      };

      setVal('cho-stat-val-barangays-extra2', fullyCompleted);
      setVal('cho-stat-val-infants-extra2', male);
      setVal('cho-stat-val-childrens-extra2', female);
      setVal('cho-stat-val-coverage-extra2', notYetCompleted);

      setVal('cho-nip2-calc-bcg', bcg);
      setVal('cho-nip2-calc-hepa', hepa);
      setVal('cho-nip2-calc-dpt', dpt);
      setVal('cho-nip2-calc-opv', opv);
      setVal('cho-nip2-calc-pcv', pcv);
      setVal('cho-nip2-calc-ipv', ipv);
      setVal('cho-nip2-calc-mcv1', mcv1);
      setVal('cho-nip2-calc-mcv2', mcv2);
    };

    const renderBarangayDropdown = (query = '') => {
      if (!dropdownEl) return;
      const records = loadRecords();
      const counts = new Map();
      records.forEach((r) => {
        const b = (r.barangay || '').trim();
        if (b) counts.set(b, (counts.get(b) || 0) + 1);
      });

      const q = query.trim().toLowerCase();
      const matches = Array.from(counts.entries())
        .sort((a, b) => a[0].localeCompare(b[0]))
        .filter(([name]) => !q || name.toLowerCase().includes(q));

      dropdownEl.innerHTML = matches.length
        ? matches.map(([name, cnt]) => `
            <button type="button" data-brgy-calc-opt="${name.replace(/"/g, '&quot;')}"
                    class="w-full text-left px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 flex items-center justify-between cursor-pointer">
              <span>${name}</span>
              <span class="text-xs text-slate-400">${cnt} ${cnt === 1 ? 'infant' : 'infants'} • Marawi City</span>
            </button>
          `).join('')
        : `<div class="px-3.5 py-3 text-sm text-slate-400 text-center">No matching barangay found</div>`;

      dropdownEl.querySelectorAll('[data-brgy-calc-opt]').forEach((btn) => {
        btn.addEventListener('click', () => {
          searchEl.value = btn.dataset.brgyCalcOpt || '';
          dropdownEl.classList.add('hidden');
          calculateAndRender();
        });
      });
    };

    searchEl.addEventListener('focus', () => {
      renderBarangayDropdown(searchEl.value);
      dropdownEl?.classList.remove('hidden');
    });

    searchEl.addEventListener('input', () => {
      if (clearEl) clearEl.classList.toggle('hidden', searchEl.value.trim().length === 0);
      renderBarangayDropdown(searchEl.value);
      dropdownEl?.classList.remove('hidden');
    });

    toggleDropdownEl?.addEventListener('click', () => {
      if (!dropdownEl) return;
      const isHidden = dropdownEl.classList.contains('hidden');
      if (isHidden) {
        renderBarangayDropdown('');
        dropdownEl.classList.remove('hidden');
        searchEl.focus();
      } else {
        dropdownEl.classList.add('hidden');
      }
    });

    document.addEventListener('click', (e) => {
      if (dropdownEl && !dropdownEl.contains(e.target) && e.target !== searchEl && !toggleDropdownEl?.contains(e.target)) {
        dropdownEl.classList.add('hidden');
      }
    });

    const triggerSearchWithSpinner = () => {
      dropdownEl?.classList.add('hidden');
      const typer = window.CHO?.Animations?.placeholderTyper;
      if (typer && typeof typer.runSearchSpinner === 'function') {
        typer.runSearchSpinner(searchBtnEl, () => calculateAndRender());
      } else {
        calculateAndRender();
      }
    };

    searchEl.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        triggerSearchWithSpinner();
      }
    });

    searchBtnEl?.addEventListener('click', triggerSearchWithSpinner);

    clearEl?.addEventListener('click', () => {
      searchEl.value = '';
      renderBarangayDropdown('');
      calculateAndRender();
      searchEl.focus();
    });

    window.addEventListener('storage', (e) => {
      if (e.key === 'cho_nip2_tcl_records' || e.key === 'cho_nip2_global_tcl_stats') {
        calculateAndRender();
        this.applyNip2ChartPeriod?.();
      }
    });

    this.refreshNip2BarangayCalculator = calculateAndRender;
    calculateAndRender();
  },
  // END - initNip2BarangayCalculator

  // START - applyNip2StatCards: Hydrates NIP2 top KPI cards from database payload
  applyNip2StatCards(data) {
    const popEl = document.getElementById('cho-stat-val-barangays2');
    const targetEl = document.getElementById('cho-stat-val-infants2');
    const ficEl = document.getElementById('cho-stat-val-childrens2');
    const covEl = document.getElementById('cho-stat-val-coverage2');
    const covPill = document.getElementById('cho-stat-pill-coverage2');

    if (popEl) popEl.textContent = Number(data.total_population || 0).toLocaleString();
    if (targetEl) targetEl.textContent = Number(data.annual_target || 0).toLocaleString();
    if (ficEl) ficEl.textContent = Number(data.fic_accomplished || 0).toLocaleString();
    if (covEl) covEl.textContent = `${Number(data.fic_coverage_pct || 0).toFixed(1)}%`;
    if (covPill) {
      const gap = Math.max(0, 95.0 - Number(data.fic_coverage_pct || 0)).toFixed(1);
      covPill.innerHTML = `<span>📊</span> Gap: ${gap}%`;
    }
  },
  // END - applyNip2StatCards

  // START - getNip2DatabaseFallback: Baseline Marawi NIP dataset for immediate DB chart rendering
  getNip2DatabaseFallback() {
    return {
      total_population: 176029,
      annual_target: 4965,
      fic_accomplished: 3661,
      fic_coverage_pct: 73.7,
      antigens: [
        { name: 'BCG', target: 4965, total: 4210, male: 2150, female: 2060 },
        { name: 'HepB (Within 24h)', target: 4965, total: 3980, male: 2020, female: 1960 },
        { name: 'Penta 1', target: 4965, total: 4120, male: 2100, female: 2020 },
        { name: 'Penta 2', target: 4965, total: 3940, male: 2005, female: 1935 },
        { name: 'Penta 3', target: 4965, total: 3810, male: 1940, female: 1870 },
        { name: 'OPV 1', target: 4965, total: 4105, male: 2090, female: 2015 },
        { name: 'OPV 2', target: 4965, total: 3925, male: 1998, female: 1927 },
        { name: 'OPV 3', target: 4965, total: 3790, male: 1930, female: 1860 },
        { name: 'IPV 1', target: 4965, total: 3850, male: 1960, female: 1890 },
        { name: 'IPV 2', target: 4965, total: 3510, male: 1785, female: 1725 },
        { name: 'PCV 1', target: 4965, total: 4040, male: 2055, female: 1985 },
        { name: 'PCV 2', target: 4965, total: 3880, male: 1975, female: 1905 },
        { name: 'PCV 3', target: 4965, total: 3720, male: 1890, female: 1830 },
        { name: 'MCV 1 (AMV)', target: 4965, total: 3760, male: 1915, female: 1845 },
        { name: 'MCV 2 (MMR)', target: 4965, total: 3661, male: 1865, female: 1796 },
        { name: 'FIC (Fully Immunized)', target: 4965, total: 3661, male: 1865, female: 1796 },
        { name: 'CIC (Completely Immunized)', target: 4965, total: 3420, male: 1740, female: 1680 },
        { name: 'Td2+ (Pregnant)', target: 4965, total: 3590, male: 0, female: 3590 }
      ],
      catchments: [
        { name: 'Catchment 1 (Poblacion)', barangays: 10, fic_total: 620 },
        { name: 'Catchment 2 (Banggolo)', barangays: 10, fic_total: 540 },
        { name: 'Catchment 3 (Tuca)', barangays: 9, fic_total: 495 },
        { name: 'Catchment 4 (Basak Malutlut)', barangays: 9, fic_total: 460 },
        { name: 'Catchment 5 (Marawi Central)', barangays: 9, fic_total: 430 },
        { name: 'Catchment 6 (Matampay)', barangays: 9, fic_total: 405 },
        { name: 'Catchment 7 (Dulay)', barangays: 9, fic_total: 371 },
        { name: 'Catchment 8 (Kilala)', barangays: 9, fic_total: 340 }
      ]
    };
  },
  // END - getNip2DatabaseFallback

  // START - renderNipAntigenChart: Renders 18 antigens dumbbell / lollipop chart (ANNUAL!A11:F28)
  async renderNipAntigenChart(antigens, targetId = 'cho-nip-antigens-chart', instanceKey = 'nipAntigens') {
    const el = document.getElementById(targetId);
    if (!el) return;
    if (typeof ApexCharts !== 'function') {
      await this.loadApexCharts();
    }
    if (typeof ApexCharts !== 'function') return;

    const validAntigens = antigens.filter((a) => a.name && a.name.trim() !== '');
    const seriesData = validAntigens.map((a) => {
      const label = a.name
        .replace(/\u00a0/g, ' ')
        .replace(/\s+/g, ' ')
        .replace(/^\d+\.\s*/, '')
        .replace(/\s*-\s*Total$/i, '')
        .trim();
      const target = Number(a.target || 0);
      const accomplished = Number(a.total || 0);
      return {
        x: label,
        y: [accomplished, target],
        meta: {
          name: label,
          target,
          accomplished,
          male: Number(a.male || 0),
          female: Number(a.female || 0)
        }
      };
    });

    const isDark = this.isDark();
    const options = {
      series: [
        {
          name: 'Accomplished vs Target',
          data: seriesData
        }
      ],
      chart: {
        type: 'rangeBar',
        width: '100%',
        height: 640,
        redrawOnParentResize: true,
        redrawOnWindowResize: true,
        fontFamily: 'Poppins, Averta, sans-serif',
        toolbar: { show: false },
        zoom: { enabled: false },
        background: 'transparent'
      },
      plotOptions: {
        bar: {
          isDumbbell: true,
          horizontal: true,
          columnWidth: 4,
          dumbbellColors: [['#005bf8', '#94a3b8']]
        }
      },
      colors: ['#cbd5e1'],
      fill: {
        type: 'solid',
        opacity: 0.75
      },
      dataLabels: { enabled: false },
      xaxis: {
        type: 'numeric',
        labels: {
          formatter: (val) => Math.round(Number(val)).toLocaleString(),
          style: {
            colors: isDark ? '#94a3b8' : '#64748b',
            fontSize: '11px',
            fontWeight: 500
          }
        },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: {
        labels: {
          maxWidth: 165,
          style: {
            colors: isDark ? '#cbd5e1' : '#334155',
            fontSize: '11px',
            fontWeight: 600
          }
        }
      },
      grid: {
        borderColor: isDark ? '#334155' : '#f1f5f9',
        strokeDashArray: 4,
        xaxis: { lines: { show: true } },
        yaxis: { lines: { show: false } }
      },
      legend: {
        show: true,
        showForSingleSeries: true,
        position: 'top',
        horizontalAlign: 'right',
        customLegendItems: ['Immunized Accomplished', 'Eligible Target'],
        markers: {
          fillColors: ['#005bf8', '#94a3b8']
        },
        labels: {
          colors: isDark ? '#cbd5e1' : '#334155'
        }
      },
      tooltip: {
        theme: isDark ? 'dark' : 'light',
        custom: ({ seriesIndex, dataPointIndex, w }) => {
          const point = w.config.series[seriesIndex]?.data[dataPointIndex];
          if (!point || !point.meta) return '';
          const { name, target, accomplished, male, female } = point.meta;
          const pct = target > 0 ? ((accomplished / target) * 100).toFixed(1) : '0.0';
          const genderRow = (male > 0 || female > 0)
            ? `<div class="text-[11px] text-slate-500 mt-1">Male: <strong>${male.toLocaleString()}</strong> &bull; Female: <strong>${female.toLocaleString()}</strong></div>`
            : '';
          return `
            <div class="px-3 py-2 text-xs bg-white text-slate-800 border border-slate-200 rounded-lg shadow-md">
              <div class="font-bold text-slate-900 mb-1">${name}</div>
              <div class="flex items-center justify-between gap-4">
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[#005bf8]"></span>Accomplished</span>
                <strong class="font-mono text-[#005bf8]">${accomplished.toLocaleString()}</strong>
              </div>
              <div class="flex items-center justify-between gap-4 mt-0.5">
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[#94a3b8]"></span>Target</span>
                <strong class="font-mono text-slate-700">${target.toLocaleString()}</strong>
              </div>
              <div class="flex items-center justify-between gap-4 mt-1 pt-1 border-t border-slate-100">
                <span class="text-slate-500">Coverage</span>
                <strong class="font-mono text-emerald-600">${pct}%</strong>
              </div>
              ${genderRow}
            </div>
          `;
        }
      },
      responsive: [
        {
          breakpoint: 640,
          options: {
            chart: { height: 500 },
            yaxis: {
              labels: {
                maxWidth: 110,
                style: { fontSize: '10px' }
              }
            },
            legend: {
              position: 'bottom',
              horizontalAlign: 'center'
            }
          }
        }
      ]
    };

    if (this.instances[instanceKey]) {
      this.instances[instanceKey].destroy();
    }
    this.instances[instanceKey] = new ApexCharts(el, options);
    this.instances[instanceKey].render();

    // Ensure chart stays responsive to container width changes
    if (typeof ResizeObserver !== 'undefined') {
      if (el._choResizeObserver) el._choResizeObserver.disconnect();
      let lastWidth = el.clientWidth;
      el._choResizeObserver = new ResizeObserver((entries) => {
        const w = Math.round(entries[0]?.contentRect?.width || 0);
        if (w > 0 && w !== lastWidth && this.instances[instanceKey]) {
          lastWidth = w;
          this.instances[instanceKey].updateOptions({ chart: { width: '100%' } }, false, false);
        }
      });
      el._choResizeObserver.observe(el);
    }
  },
  // END - renderNipAntigenChart

  // START - renderNipCatchmentChart: Renders FIC quota and share per Catchment
  async renderNipCatchmentChart(catchments, totalFicAccomplished = 0, suffix = '') {
    const el = document.getElementById(`cho-nip-catchments-chart${suffix}`);
    const legendEl = document.getElementById(`cho-nip-catchments-legend${suffix}`);
    const summaryEl = document.getElementById(`cho-nip-catchments-summary${suffix}`);
    const instanceKey = `nipCatchments${suffix}`;
    if (!el) return;
    if (typeof ApexCharts !== 'function') {
      await this.loadApexCharts();
    }
    if (typeof ApexCharts !== 'function') return;

    // Filter valid catchments with positive barangays or FIC
    const valid = catchments.filter(c => c.fic_total > 0 || c.barangays > 0);
    const labels = valid.map(c => c.name);
    const series = valid.map(c => c.fic_total);
    const palette = ['#005bf8', '#e66a00', '#007a64', '#c00030', '#6366f1', '#06b6d4', '#f59e0b', '#10b981'];

    const isDark = this.isDark();
    const options = {
      series: series.length > 0 ? series : [1],
      labels: labels.length > 0 ? labels : ['No Data'],
      colors: palette,
      chart: {
        type: 'donut',
        height: 300,
        fontFamily: 'Poppins, Averta, sans-serif',
        background: 'transparent'
      },
      stroke: { colors: [isDark ? '#1e293b' : '#ffffff'], width: 2 },
      plotOptions: {
        pie: {
          donut: {
            size: '68%',
            labels: {
              show: true,
              name: {
                fontSize: '13px',
                fontWeight: 600
              },
              value: {
                fontSize: '24px',
                fontWeight: 800
              },
              total: {
                show: true,
                label: 'Total FIC',
                fontSize: '13px',
                fontWeight: 600,
                color: isDark ? '#94a3b8' : '#64748b',
                formatter: (w) => {
                  const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                  return sum.toLocaleString();
                }
              }
            }
          }
        }
      },
      dataLabels: { enabled: false },
      legend: { show: false },
      tooltip: {
        theme: isDark ? 'dark' : 'light',
        y: {
          formatter: (val) => `${Number(val).toLocaleString()} FIC`
        }
      }
    };

    if (this.instances[instanceKey]) {
      this.instances[instanceKey].destroy();
    }
    this.instances[instanceKey] = new ApexCharts(el, options);
    this.instances[instanceKey].render();

    // Render 2-column Tailwind legend
    if (legendEl) {
      const displayLabels = labels.length > 0 ? labels : ['No Data'];
      legendEl.innerHTML = displayLabels.map((label, idx) => {
        const color = palette[idx % palette.length];
        return `
          <div class="flex items-center gap-2 min-w-0">
            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: ${color};"></span>
            <span class="truncate text-slate-700 font-medium">${label}</span>
          </div>
        `;
      }).join('');
    }

    // Render all Catchment summary rows to occupy full card height cleanly
    if (summaryEl && valid.length > 0) {
      summaryEl.innerHTML = valid.map(c => {
        const sharePct = c.share_pct ?? (
          totalFicAccomplished > 0
            ? ((Number(c.fic_total) / totalFicAccomplished) * 100).toFixed(1)
            : '0.0'
        );
        return `
        <div class="flex items-center justify-between gap-2 py-1 text-slate-600">
          <span class="font-medium text-slate-800 truncate">${c.name} <span class="text-rose-600">(${c.barangays} Brgys)</span></span>
          <span class="font-bold text-emerald-600 whitespace-nowrap">${Number(c.fic_total).toLocaleString()} FIC <span class="text-slate-400 font-normal">(${sharePct}%)</span></span>
        </div>
      `;
      }).join('');
    }
  },
  // END - renderNipCatchmentChart

  refreshChartThemes() {
    this.initOutpatientAreaChart();
    this.initBarangayBarChart();
    this.initServicesDonutChart();
    if (this.lastNipData && document.getElementById('cho-nip-antigens-chart')) {
      this.renderNipAntigenChart(this.lastNipData.antigens || []);
      this.renderNipCatchmentChart(this.lastNipData.catchments || [], this.lastNipData.stats?.ficAccomplished || 0);
    }
    if (this.lastNip2Data && document.getElementById('cho-nip-antigens-chart2')) {
      this.renderNipAntigenChart(this.lastNip2Data.antigens || [], 'cho-nip-antigens-chart2', 'nipAntigens2');
      this.renderNipCatchmentChart(this.lastNip2Data.catchments || [], this.lastNip2Data.fic_accomplished || 0, '2');
    }
  }
};
