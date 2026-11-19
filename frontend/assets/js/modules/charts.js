/**
 * CHO MARAWI PORTAL - CHARTS MODULE
 * Integrates Flowbite Charts & ApexCharts for Public Health Intelligence:
 * - Outpatient & Maternal Care Area Chart
 * - Barangay Health Services Column/Bar Chart
 * - Dynamic theme adaptation (Light / Dark mode colors)
 */

import ApexCharts from 'apexcharts';

export const Charts = {
  instances: {},

  init() {
    this.initOutpatientAreaChart();
    this.initBarangayBarChart();
    this.initServicesDonutChart();

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
        categories: ['Sagonsongan', 'Dulay', 'Kilala', 'Marawi Central', 'Basak Malutlut', 'Matampay', 'Banggolo'],
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

  // START - renderNipAntigenChart: Renders 18 antigens dumbbell / lollipop chart (ANNUAL!A11:F28)
  renderNipAntigenChart(antigens) {
    const el = document.getElementById('cho-nip-antigens-chart');
    if (!el || typeof ApexCharts === 'undefined') return;

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

    if (this.instances.nipAntigens) {
      this.instances.nipAntigens.destroy();
    }
    this.instances.nipAntigens = new ApexCharts(el, options);
    this.instances.nipAntigens.render();

    // Ensure chart stays responsive to container width changes
    if (typeof ResizeObserver !== 'undefined') {
      if (el._choResizeObserver) el._choResizeObserver.disconnect();
      let lastWidth = el.clientWidth;
      el._choResizeObserver = new ResizeObserver((entries) => {
        const w = Math.round(entries[0]?.contentRect?.width || 0);
        if (w > 0 && w !== lastWidth && this.instances.nipAntigens) {
          lastWidth = w;
          this.instances.nipAntigens.updateOptions({ chart: { width: '100%' } }, false, false);
        }
      });
      el._choResizeObserver.observe(el);
    }
  },
  // END - renderNipAntigenChart

  // START - renderNipCatchmentChart: Renders FIC quota and share per Catchment
  renderNipCatchmentChart(catchments, totalFicAccomplished = 0) {
    const el = document.getElementById('cho-nip-catchments-chart');
    const legendEl = document.getElementById('cho-nip-catchments-legend');
    const summaryEl = document.getElementById('cho-nip-catchments-summary');
    if (!el || typeof ApexCharts === 'undefined') return;

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

    if (this.instances.nipCatchments) {
      this.instances.nipCatchments.destroy();
    }
    this.instances.nipCatchments = new ApexCharts(el, options);
    this.instances.nipCatchments.render();

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
  }
};
