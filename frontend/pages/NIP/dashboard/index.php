<?php
/**
 * CHO MARAWI PORTAL - NIP PROGRAM DASHBOARD
 * Location: frontend/pages/NIP/dashboard/index.php
 */

declare(strict_types=1);

require dirname(dirname(dirname(dirname(__DIR__)))) . '/backend/bootstrap.php';

$pageTitle = 'NIP Program Dashboard | City Health Office - Marawi City';

require BASE_PATH . '/frontend/layouts/head.php';
require BASE_PATH . '/frontend/layouts/sidebar.php';
?>

<div id="cho-nip-dashboard-main-container" class="p-4 sm:ml-72 transition-all space-y-6">

    <!-- =========================================================================
         PRESERVED BUTTON FUNCTIONALITY FROM PREVIOUS HEADER:
         <a href="<?= url('/api/v1/nip/download?file=MARAWI+NIP+DATA+-+UPGRADED.xlsx') ?>" 
            id="cho-nip-btn-download-preserved" 
            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-xs transition-all">
             <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
             </svg>
             Download Master (.xlsx)
         </a>
         Note: This exact direct-download action is also fully integrated into Quick Access Action #4 below.
         ========================================================================= -->

    <!-- TOP HEADER: Flat Text Elements Without Any Wrapping Div -->
    <h1 id="cho-nip-dashboard-title" class="text-2xl sm:text-3xl font-black font-heading tracking-tight text-cho-blue-900 dark:text-cho-blue-400">
        NIP Dashboard
    </h1>
    <p id="cho-nip-dashboard-subtitle" class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium -mt-4">
        18 Antigen Indicators & 74 Barangays Monitoring
    </p>

    <!-- =========================================================================
         QUICK STAT CARDS: INFLUENCED BY IMAGE 1
         - Zero border radius (rounded-none)
         - Every single div carries an id
         - Font Money integrated for larger numerical values
         - Horizontal separator line with bottom metadata
         - Slightly tilted SVG icon as watermark background (Barangays, Infants, Childrens, Coverage)
         ========================================================================= -->
    <div id="cho-nip-stats-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Barangays Population (Blue Theme with Integrated Background Photo) -->
        <div id="cho-stat-card-barangays" class="group relative overflow-hidden rounded-none p-5 bg-[#005bf8] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <!-- Background Photo from assets/images/nip/nip1.jpeg: Resting color/opacity returned, Enhanced on hover -->
            <img id="cho-stat-bg-img-barangays" 
                 src="<?= asset('images/nip/nip1.jpeg') ?>" 
                 alt="Barangays Health Workers" 
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />

            <!-- Dark gradient protective vignette on hover for maximum text contrast -->
            <div id="cho-stat-overlay-barangays" class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>

            <!-- Background Slightly Tilted SVG Watermark: Location Pin (Overlaps on front of image) -->
            <svg id="cho-stat-bg-icon-barangays" class="absolute right-2 bottom-1.5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out z-[5]" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M11.906 1.994a8.002 8.002 0 0 1 8.09 8.421 7.996 7.996 0 0 1-1.297 3.957.996.996 0 0 1-.133.204l-.108.129c-.178.243-.37.477-.573.699l-5.112 6.224a1 1 0 0 1-1.545 0L5.982 15.26l-.002-.002a18.146 18.146 0 0 1-.309-.38l-.133-.163a.999.999 0 0 1-.13-.202 7.995 7.995 0 0 1 6.498-12.518ZM15 9.997a3 3 0 1 1-5.999 0 3 3 0 0 1 5.999 0Z" clip-rule="evenodd"/>
            </svg>

            <!-- Card Content -->
            <div id="cho-stat-content-barangays" class="relative z-10">
                <!-- Top Row: Label & Index -->
                <div id="cho-stat-header-barangays" class="flex items-center justify-between">
                    <span id="cho-stat-label-barangays" class="text-[11px] font-bold uppercase tracking-wider text-white/90">
                        Total Population
                    </span>
                    <span id="cho-stat-idx-barangays" class="text-xs font-black tracking-widest text-white/60">
                        01
                    </span>
                </div>

                <!-- Big Value with Money Font -->
                <div id="cho-stat-val-barangays" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    176,029
                </div>

                <!-- Horizontal Divider Line -->
                <div id="cho-stat-divider-barangays" class="w-full border-t border-white/20 my-2.5"></div>

                <!-- Bottom Row: Subtitle & Status Badge -->
                <div id="cho-stat-footer-barangays" class="flex items-center justify-between text-xs text-white/90">
                    <span id="cho-stat-sub-barangays" class="text-[11px] font-medium text-white/80">
                        74 Master Barangays
                    </span>
                    <span id="cho-stat-pill-barangays" class="inline-flex items-center gap-1 text-[11px] font-semibold text-white">
                        <span>↑</span> Active Cohort
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: Eligible Infants (Orange/Amber Theme with Integrated Background Photo) -->
        <div id="cho-stat-card-infants" class="group relative overflow-hidden rounded-none p-5 bg-[#e66a00] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <!-- Background Photo from assets/images/nip/nip2.jpeg: Resting color/opacity returned, Enhanced on hover -->
            <img id="cho-stat-bg-img-infants" 
                 src="<?= asset('images/nip/nip2.jpeg') ?>" 
                 alt="Infants Immunization" 
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />

            <!-- Dark gradient protective vignette on hover for maximum text contrast -->
            <div id="cho-stat-overlay-infants" class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>

            <!-- Background Slightly Tilted SVG Watermark: Family / Children Group -->
            <svg id="cho-stat-bg-icon-infants" class="absolute right-2 bottom-1.5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
            </svg>

            <!-- Card Content -->
            <div id="cho-stat-content-infants" class="relative z-10">
                <!-- Top Row: Label & Index -->
                <div id="cho-stat-header-infants" class="flex items-center justify-between">
                    <span id="cho-stat-label-infants" class="text-[11px] font-bold uppercase tracking-wider text-white/90">
                        Annual Target
                    </span>
                    <span id="cho-stat-idx-infants" class="text-xs font-black tracking-widest text-white/60">
                        02
                    </span>
                </div>

                <!-- Big Value with Money Font -->
                <div id="cho-stat-val-infants" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    4,965
                </div>

                <!-- Horizontal Divider Line -->
                <div id="cho-stat-divider-infants" class="w-full border-t border-white/20 my-2.5"></div>

                <!-- Bottom Row: Subtitle & Status Badge -->
                <div id="cho-stat-footer-infants" class="flex items-center justify-between text-xs text-white/90">
                    <span id="cho-stat-sub-infants" class="text-[11px] font-medium text-white/80">
                        Eligible 0-11mo Infants
                    </span>
                    <span id="cho-stat-pill-infants" class="inline-flex items-center gap-1 text-[11px] font-semibold text-white">
                        <span>🎯</span> Target Baseline
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 3: Childrens Immunized / FIC (Deep Teal/Emerald Theme with Integrated Background Photo) -->
        <div id="cho-stat-card-childrens" class="group relative overflow-hidden rounded-none p-5 bg-[#007a64] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <!-- Background Photo from assets/images/nip/nip3.jpg: Resting color/opacity returned, Enhanced on hover -->
            <img id="cho-stat-bg-img-childrens" 
                 src="<?= asset('images/nip/nip3.jpg') ?>" 
                 alt="Childrens Immunization" 
                 class="absolute inset-0 w-full h-full object-cover object-[center_20%] opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />

            <!-- Dark gradient protective vignette on hover for maximum text contrast -->
            <div id="cho-stat-overlay-childrens" class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>

            <!-- Background Slightly Tilted SVG Watermark: Heart Shield (Adjusted to move higher up to the top) -->
            <svg id="cho-stat-bg-icon-childrens" class="absolute right-2 bottom-5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out" fill="currentColor" viewBox="0 0 24 24">
                <path d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z"/>
            </svg>

            <!-- Card Content -->
            <div id="cho-stat-content-childrens" class="relative z-10">
                <!-- Top Row: Label & Index -->
                <div id="cho-stat-header-childrens" class="flex items-center justify-between">
                    <span id="cho-stat-label-childrens" class="text-[11px] font-bold uppercase tracking-wider text-white/90">
                        FIC Accomplished
                    </span>
                    <span id="cho-stat-idx-childrens" class="text-xs font-black tracking-widest text-white/60">
                        03
                    </span>
                </div>

                <!-- Big Value with Money Font -->
                <div id="cho-stat-val-childrens" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    3,661
                </div>

                <!-- Horizontal Divider Line -->
                <div id="cho-stat-divider-childrens" class="w-full border-t border-white/20 my-2.5"></div>

                <!-- Bottom Row: Subtitle & Status Badge -->
                <div id="cho-stat-footer-childrens" class="flex items-center justify-between text-xs text-white/90">
                    <span id="cho-stat-sub-childrens" class="text-[11px] font-medium text-white/80">
                        Fully Immunized Children
                    </span>
                    <span id="cho-stat-pill-childrens" class="inline-flex items-center gap-1 text-[11px] font-semibold text-white">
                        <span>✓</span> Verified FIC
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 4: FIC Coverage Rate (Crimson/Red Theme with Integrated Background Photo) -->
        <div id="cho-stat-card-coverage" class="group relative overflow-hidden rounded-none p-5 bg-[#c00030] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <!-- Background Photo from assets/images/nip/nip4.jpg: Resting color/opacity returned, Enhanced on hover -->
            <img id="cho-stat-bg-img-coverage" 
                 src="<?= asset('images/nip/nip4.jpg') ?>" 
                 alt="Coverage Benchmark" 
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />

            <!-- Dark gradient protective vignette on hover for maximum text contrast -->
            <div id="cho-stat-overlay-coverage" class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>

            <!-- Background Slightly Tilted SVG Watermark: Orbit Target -->
            <svg id="cho-stat-bg-icon-coverage" class="absolute right-2 bottom-1.5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-width="2" d="M8.737 8.737a21.49 21.49 0 0 1 3.308-2.724m0 0c3.063-2.026 5.99-2.641 7.331-1.3 1.827 1.828.026 6.591-4.023 10.64-4.049 4.049-8.812 5.85-10.64 4.023-1.33-1.33-.736-4.218 1.249-7.253m6.083-6.11c-3.063-2.026-5.99-2.641-7.331-1.3-1.827 1.828-.026 6.591 4.023 10.64m3.308-9.34a21.497 21.497 0 0 1 3.308 2.724m2.775 3.386c1.985 3.035 2.579 5.923 1.248 7.253-1.336 1.337-4.245.732-7.295-1.275M14 12a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
            </svg>

            <!-- Card Content -->
            <div id="cho-stat-content-coverage" class="relative z-10">
                <!-- Top Row: Label & Index -->
                <div id="cho-stat-header-coverage" class="flex items-center justify-between">
                    <span id="cho-stat-label-coverage" class="text-[11px] font-bold uppercase tracking-wider text-white/90">
                        FIC Coverage
                    </span>
                    <span id="cho-stat-idx-coverage" class="text-xs font-black tracking-widest text-white/60">
                        04
                    </span>
                </div>

                <!-- Big Value with Money Font -->
                <div id="cho-stat-val-coverage" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    73.7%
                </div>

                <!-- Horizontal Divider Line -->
                <div id="cho-stat-divider-coverage" class="w-full border-t border-white/20 my-2.5"></div>

                <!-- Bottom Row: Subtitle & Status Badge -->
                <div id="cho-stat-footer-coverage" class="flex items-center justify-between text-xs text-white/90">
                    <span id="cho-stat-sub-coverage" class="text-[11px] font-medium text-white/80">
                        Benchmark: 95.0%
                    </span>
                    <span id="cho-stat-pill-coverage" class="inline-flex items-center gap-1 text-[11px] font-semibold text-white">
                        <span>📊</span> Gap: 21.3%
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- =========================================================================
         NIP QUICK ACCESS INTERACTION GRID: COPIED FROM CHO-SYSTEMS-GRID
         Decreased height for tight, professional action workflows
         ========================================================================= -->
    <div id="cho-nip-systems-section" class="space-y-3 pt-2">
        <!-- Section Header -->
        <div id="cho-nip-systems-header" class="flex items-center justify-between px-1">
            <h2 id="cho-nip-systems-heading" class="text-xs sm:text-sm font-extrabold font-heading tracking-wide uppercase text-cho-blue-900 dark:text-cho-blue-400">
                NIP Program Data Management & Excel Actions
            </h2>
            <span id="cho-nip-systems-sub" class="text-xs text-slate-400">
                Connected to <code class="text-cho-blue-700 dark:text-cho-blue-400 font-mono">d:/laragon/www/SHEETS</code>
            </span>
        </div>

        <!-- 3-Column Responsive Grid matching cho-systems-grid with Decreased Height -->
        <div id="cho-systems-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">

            <!-- Card 1: Load / Switch Excel Workbook (Opens Dedicated Right Drawer #drawer-nip-load) -->
            <button type="button" 
                    id="cho-nip-action-load-card" 
                    data-cho-drawer-target="drawer-nip-load" 
                    data-drawer-placement="right" 
                    aria-controls="drawer-nip-load" 
                    class="group relative flex items-center justify-between p-3.5 sm:p-4 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200 text-left w-full cursor-pointer">
                <div id="cho-nip-action-left-load" class="flex-1 pr-3 space-y-1.5 min-w-0">
                    <div id="cho-nip-action-icon-load" class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h2"/>
                        </svg>
                    </div>
                    <div id="cho-nip-action-text-load" class="min-w-0">
                        <h3 id="cho-nip-action-title-load" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors truncate">
                            Load Active Excel Sheet
                        </h3>
                        <p id="cho-nip-action-desc-load" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-1">
                            Switch workbook or inspect server repo
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic / Preview Thumbnail (Decreased Height) -->
                <div id="cho-nip-action-img-wrap-load" class="shrink-0 w-24 h-16 sm:w-28 sm:h-18 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-1.5">
                    <img id="cho-nip-action-img-load" src="<?= asset('images/systems/nip-preview.svg') ?>" alt="Load Excel Sheet" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </button>

            <!-- Card 2: Generate Blank Year Sheet (Opens Dedicated Right Drawer #drawer-nip-generate) -->
            <button type="button" 
                    id="cho-nip-action-generate-card" 
                    data-cho-drawer-target="drawer-nip-generate" 
                    data-drawer-placement="right" 
                    aria-controls="drawer-nip-generate" 
                    class="group relative flex items-center justify-between p-3.5 sm:p-4 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200 text-left w-full cursor-pointer">
                <div id="cho-nip-action-left-generate" class="flex-1 pr-3 space-y-1.5 min-w-0">
                    <div id="cho-nip-action-icon-generate" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div id="cho-nip-action-text-generate" class="min-w-0">
                        <h3 id="cho-nip-action-title-generate" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors truncate">
                            Create Blank Year Sheet
                        </h3>
                        <p id="cho-nip-action-desc-generate" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-1">
                            Zero 26k cells with 8,500+ formulas intact
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic / Preview Thumbnail (Decreased Height) -->
                <div id="cho-nip-action-img-wrap-generate" class="shrink-0 w-24 h-16 sm:w-28 sm:h-18 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-2">
                    <img id="cho-nip-action-img-generate" src="<?= asset('images/icons/generate.svg') ?>" alt="Generate Sheet" class="w-full h-full object-contain group-hover:scale-105 transition-transform" />
                </div>
            </button>

            <!-- Card 3: Reset Quarter Raw Inputs (Opens Dedicated Right Drawer #drawer-nip-reset) -->
            <button type="button" 
                    id="cho-nip-action-reset-card" 
                    data-cho-drawer-target="drawer-nip-reset" 
                    data-drawer-placement="right" 
                    aria-controls="drawer-nip-reset" 
                    class="group relative flex items-center justify-between p-3.5 sm:p-4 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200 text-left w-full cursor-pointer">
                <div id="cho-nip-action-left-reset" class="flex-1 pr-3 space-y-1.5 min-w-0">
                    <div id="cho-nip-action-icon-reset" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div id="cho-nip-action-text-reset" class="min-w-0">
                        <h3 id="cho-nip-action-title-reset" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors truncate">
                            Quarter Input Reset Tool
                        </h3>
                        <p id="cho-nip-action-desc-reset" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-1">
                            Clear Q1–Q4 raw entries safely by date
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic / Preview Thumbnail (Decreased Height) -->
                <div id="cho-nip-action-img-wrap-reset" class="shrink-0 w-24 h-16 sm:w-28 sm:h-18 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-2">
                    <img id="cho-nip-action-img-reset" src="<?= asset('images/icons/reset.svg') ?>" alt="Reset Quarter" class="w-full h-full object-contain group-hover:scale-105 transition-transform" />
                </div>
            </button>

            <!-- Card 4: Direct Export Master Workbook (Formula Intact Download) -->
            <a href="<?= url('/api/v1/nip/download?file=MARAWI+NIP+DATA+-+UPGRADED.xlsx') ?>" 
               id="cho-nip-action-download-card" 
               class="group relative flex items-center justify-between p-3.5 sm:p-4 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-nip-action-left-download" class="flex-1 pr-3 space-y-1.5 min-w-0">
                    <div id="cho-nip-action-icon-download" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div id="cho-nip-action-text-download" class="min-w-0">
                        <h3 id="cho-nip-action-title-download" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors truncate">
                            Export Master Workbook
                        </h3>
                        <p id="cho-nip-action-desc-download" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-1">
                            Direct download formula-intact .xlsx file
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic / Preview Thumbnail (Decreased Height) -->
                <div id="cho-nip-action-img-wrap-download" class="shrink-0 w-24 h-16 sm:w-28 sm:h-18 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-2">
                    <img id="cho-nip-action-img-download" src="<?= asset('images/icons/export.svg') ?>" alt="Export Master" class="w-full h-full object-contain group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 5: Upload Updated Field Sheet (Opens Dedicated Right Drawer #drawer-nip-upload) -->
            <button type="button" 
                    id="cho-nip-action-upload-card" 
                    data-cho-drawer-target="drawer-nip-upload" 
                    data-drawer-placement="right" 
                    aria-controls="drawer-nip-upload" 
                    class="group relative flex items-center justify-between p-3.5 sm:p-4 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200 text-left w-full cursor-pointer">
                <div id="cho-nip-action-left-upload" class="flex-1 pr-3 space-y-1.5 min-w-0">
                    <div id="cho-nip-action-icon-upload" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div id="cho-nip-action-text-upload" class="min-w-0">
                        <h3 id="cho-nip-action-title-upload" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors truncate">
                            Upload Updated Workbook
                        </h3>
                        <p id="cho-nip-action-desc-upload" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-1">
                            Sync raw inputs from health stations
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic / Preview Thumbnail (Decreased Height) -->
                <div id="cho-nip-action-img-wrap-upload" class="shrink-0 w-24 h-16 sm:w-28 sm:h-18 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-2">
                    <img id="cho-nip-action-img-upload" src="<?= asset('images/icons/upload.svg') ?>" alt="Upload Workbook" class="w-full h-full object-contain group-hover:scale-105 transition-transform" />
                </div>
            </button>

            <!-- Card 6: 74 Barangay Population Baseline Master -->
            <div id="cho-nip-action-census-card" 
                 class="group relative flex items-center justify-between p-3.5 sm:p-4 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs transition-all duration-200">
                <div id="cho-nip-action-left-census" class="flex-1 pr-3 space-y-1.5 min-w-0">
                    <div id="cho-nip-action-icon-census" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div id="cho-nip-action-text-census" class="min-w-0">
                        <h3 id="cho-nip-action-title-census" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors truncate">
                            74 Barangay Cohorts
                        </h3>
                        <p id="cho-nip-action-desc-census" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-1">
                            4 CHO Catchments & Target Matrix
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic / Preview Thumbnail (Decreased Height) -->
                <div id="cho-nip-action-img-wrap-census" class="shrink-0 w-24 h-16 sm:w-28 sm:h-18 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center p-2">
                    <img id="cho-nip-action-img-census" src="<?= asset('images/icons/census.svg') ?>" alt="Barangay Master" class="w-full h-full object-contain group-hover:scale-105 transition-transform" />
                </div>
            </div>

        </div>
    </div>

    <!-- =========================================================================
         ANALYTICS CANVAS & WORKSPACE PREVIEW
         Default: Empty Placeholder Canvas (#cho-nip-canvas-card)
         When Excel Loaded: Swapped with Dynamic Interactive Charts Grid (#cho-nip-charts-container)
         ========================================================================= -->
    <!-- Placeholder Canvas (Shown by default when no sheet is loaded or waiting) -->
    <div id="cho-nip-canvas-card" class="min-h-[44vh] flex flex-col items-center justify-center p-8 bg-slate-50/70 border border-slate-200/90 rounded-xl shadow-xs text-center space-y-3">
        <div id="cho-nip-canvas-icon-wrap" class="w-12 h-12 rounded-xl bg-white text-slate-600 border border-slate-200/80 shadow-xs flex items-center justify-center">
            <svg id="cho-nip-canvas-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div id="cho-nip-canvas-heading-wrap">
            <h2 id="cho-nip-canvas-heading" class="text-base sm:text-lg font-bold text-slate-800">
                NIP Specialized Analytics & Antigen Coverage Canvas
            </h2>
            <p id="cho-nip-canvas-text" class="text-xs sm:text-sm text-slate-500 max-w-lg mt-1">
                Connected to <code class="px-1.5 py-0.5 bg-white text-slate-700 border border-slate-200 rounded font-mono text-xs">d:/laragon/www/SHEETS</code>. Load or drop an Excel workbook using <strong class="text-slate-700 font-semibold">Load Active Sheet</strong> to inspect 18 antigens, quarter trends, and catchment quotas.
            </p>
        </div>
        <button type="button" 
                data-cho-drawer-target="drawer-nip-load" 
                class="mt-2 inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-cho-blue-900 bg-white hover:bg-slate-50 border border-slate-200/90 rounded-lg shadow-xs transition-colors cursor-pointer">
            <svg class="w-4 h-4 text-cho-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span>Scan & Load Excel Sheet</span>
        </button>
    </div>

    <!-- Hidden Charts Container (Revealed dynamically by charts.js when workbook data is scanned & loaded) -->
    <div id="cho-nip-charts-container" class="hidden space-y-4">
        <!-- Workbook Active Banner & File Info -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-white border border-slate-200/90 rounded-xl shadow-xs gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">
                            Active Sheet Live
                        </span>
                        <h4 id="cho-nip-active-filename" class="text-sm font-bold text-slate-800 truncate max-w-[280px] sm:max-w-md">
                            MARAWI NIP DATA - UPGRADED.xlsx
                        </h4>
                    </div>
                    <p id="cho-nip-active-sheet-meta" class="text-xs text-slate-500 mt-0.5">
                        Year 2026 &bull; Quarter Q1 &bull; 18 Antigens Monitored &bull; 74 Master Barangays
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 self-end sm:self-auto">
                <button type="button" 
                        data-cho-drawer-target="drawer-nip-load" 
                        class="text-xs px-3 py-1.5 font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-300 rounded-lg transition-colors cursor-pointer">
                    Change Sheet
                </button>
            </div>
        </div>

        <!-- 2-Column Responsive Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Chart 1: 18 Antigens Coverage Performance (Bar/Column Chart) -->
            <div class="lg:col-span-8 bg-white border border-slate-200/90 rounded-xl p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-800">
                            18 Antigens Routine Immunization Performance
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Target vs Accomplished vs Coverage % by Vaccine Type
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 bg-slate-100 text-slate-700 rounded-lg">
                        Coverage Benchmark: 95%
                    </span>
                </div>
                <div id="cho-nip-antigens-chart" class="w-full min-h-[340px]"></div>
            </div>

            <!-- Chart 2: Catchment Area Quotas / FIC Contribution (Donut Chart) -->
            <div class="lg:col-span-4 bg-white border border-slate-200/90 rounded-xl p-5 shadow-xs space-y-3 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-800">
                                CHO Catchment Shares
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                FIC distribution across 4 City Catchments
                            </p>
                        </div>
                    </div>
                    <div id="cho-nip-catchments-chart" class="w-full min-h-[280px] flex items-center justify-center"></div>
                </div>

                <!-- Quick Catchment Quota Summary Table -->
                <div id="cho-nip-catchments-summary" class="border-t border-slate-100 pt-3 text-xs space-y-1.5">
                    <!-- Populated dynamically by charts.js -->
                </div>
            </div>
        </div>
    </div>

</div>

<!-- =============================================================================
     SEPARATED FLOWBITE RIGHT DRAWERS: NIP EXCEL ACTIONS
     Follows Flowbite specifications:
     - Independent drawer per action
     - High z-index (z-50) so sidebar remains strictly behind
     - Medium button sizes & medium form text inputs
     - Widened drawer width (w-full sm:w-[480px] md:w-[520px])
     - Subchild OOP handlers registered in drawer.js
     - Clean light-mode slate styling (no dark / primary color clashes)
     ============================================================================= -->

<!-- DRAWER 1: LOAD ACTIVE EXCEL WORKBOOK -->
<div id="drawer-nip-load" 
     data-cho-nip-drawer 
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 w-full sm:w-[480px] md:w-[520px] max-w-full shadow-2xl flex flex-col justify-between" 
     tabindex="-1" 
     aria-labelledby="drawer-nip-load-label" 
     aria-hidden="true">

    <div id="drawer-nip-load-top-section" class="space-y-4">
        <!-- Drawer Header -->
        <div id="drawer-nip-load-header" class="border-b border-slate-200 pb-3 flex items-center justify-between">
            <div id="drawer-nip-load-title-group" class="flex items-center gap-2">
                <div id="drawer-nip-load-icon-wrap" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h2"/>
                    </svg>
                </div>
                <h5 id="drawer-nip-load-label" class="text-base font-bold text-slate-900">
                    Load Active Sheet
                </h5>
            </div>

            <!-- Close Drawer Button -->
            <button type="button" 
                    id="drawer-nip-load-close-btn" 
                    data-cho-drawer-hide="drawer-nip-load" 
                    aria-controls="drawer-nip-load" 
                    class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Select an Excel workbook from the server repository (<code class="px-1 py-0.5 bg-slate-100 rounded font-mono text-[11px]">d:/laragon/www/SHEETS</code>) or upload a new sheet below to bind with the dashboard charts and coverage counters.
        </p>

        <!-- Form: Load Workbook -->
        <form id="nip-drawer-load-form" class="space-y-4">
            <div id="nip-drawer-load-field" class="space-y-2">
                <label for="nip-select-active-file" class="block text-xs font-semibold text-slate-700">
                    Discovered Workbooks (Auto-Detected)
                </label>
                <!-- Dynamic Radio List from API -->
                <div id="nip-drawer-load-files-list" class="space-y-2 max-h-52 overflow-y-auto pr-1"></div>

                <!-- Flowbite Wided Dropdown Component -->
                <div class="pt-1">
                    <label class="block text-[11px] font-medium text-slate-500 mb-1">
                        Or select active file from dropdown:
                    </label>
                    <div class="relative w-full">
                        <button id="nip-dropdown-btn-active-file" 
                                data-dropdown-toggle="nip-dropdown-menu-active-file" 
                                type="button" 
                                class="cursor-pointer w-full flex items-center justify-between text-slate-800 bg-slate-50 border border-slate-300 hover:bg-slate-100 font-medium rounded-lg text-xs sm:text-sm px-3.5 py-2.5 focus:outline-none focus:ring-4 focus:ring-slate-200 shadow-xs text-left transition-colors">
                            <span id="nip-dropdown-label-active-file" class="truncate">MARAWI NIP DATA - UPGRADED.xlsx (Master Active)</span>
                            <svg class="w-4 h-4 ms-2 shrink-0 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Menu (Wided) -->
                        <div id="nip-dropdown-menu-active-file" class="z-50 hidden bg-white border border-slate-200 rounded-xl shadow-lg w-full max-h-56 overflow-y-auto">
                            <ul id="nip-dropdown-list-active-file" class="p-2 text-xs sm:text-sm text-slate-700 font-medium space-y-1" aria-labelledby="nip-dropdown-btn-active-file">
                                <li>
                                    <button type="button" data-val="MARAWI NIP DATA - UPGRADED.xlsx" class="nip-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors">
                                        MARAWI NIP DATA - UPGRADED.xlsx (Master Active)
                                    </button>
                                </li>
                                <li>
                                    <button type="button" data-val="MARAWI NIP DATA - 2026.xlsx" class="nip-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors">
                                        MARAWI NIP DATA - 2026.xlsx (Blank Template)
                                    </button>
                                </li>
                                <li>
                                    <button type="button" data-val="MARAWI NIP DATA - 2025.xlsx" class="nip-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors">
                                        MARAWI NIP DATA - 2025.xlsx (Archive)
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Hidden Select Element for form serialization & programmatic binding -->
                    <select id="nip-select-active-file" 
                            name="filename" 
                            class="hidden cursor-pointer">
                        <option value="MARAWI NIP DATA - UPGRADED.xlsx" selected>MARAWI NIP DATA - UPGRADED.xlsx (Master Active)</option>
                        <option value="MARAWI NIP DATA - 2026.xlsx">MARAWI NIP DATA - 2026.xlsx (Blank Template)</option>
                        <option value="MARAWI NIP DATA - 2025.xlsx">MARAWI NIP DATA - 2025.xlsx (Archive)</option>
                    </select>
                </div>
            </div>

            <!-- Divider with Label -->
            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="shrink-0 mx-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Or Upload New Sheet</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Flowbite Medium Dropzone File Upload -->
            <div id="nip-load-dropzone-container" class="flex flex-col items-center justify-center w-full">
                <label for="nip-load-dropzone-file" class="flex flex-col items-center justify-center w-full h-36 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-100/80 hover:border-slate-400 transition-colors">
                    <div class="flex flex-col items-center justify-center text-slate-500 pt-3 pb-3 px-4 text-center">
                        <svg class="w-7 h-7 mb-2 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2"/>
                        </svg>
                        <p class="mb-1 text-xs text-slate-700"><span class="font-bold">Click to upload</span> or drag and drop</p>
                        <p class="text-[11px] text-slate-400">Excel files: .xlsx, .xlsm (Max 25MB)</p>
                        <div id="nip-load-dropzone-filename" class="text-xs font-semibold text-cho-blue-900 mt-1 hidden truncate max-w-xs"></div>
                    </div>
                    <input id="nip-load-dropzone-file" name="dropzone_workbook" type="file" accept=".xlsx,.xlsm" class="hidden" />
                </label>
            </div>

            <!-- Feedback Display Box -->
            <div id="nip-load-feedback" class="hidden"></div>

            <!-- Submit Button (Medium Size with cho-nip-dashboard-title text color bg-cho-blue-900) -->
            <button type="submit" 
                    id="nip-btn-submit-load" 
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 active:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer">
                Switch Active Sheet
            </button>
        </form>
    </div>

    <!-- Drawer Footer Note -->
    <div id="drawer-nip-load-footer" class="border-t border-slate-200 pt-3 text-[11px] text-slate-400 text-center">
        City Health Office Marawi &bull; NIP Data Engine v2.0
    </div>

</div>

<!-- DRAWER 2: GENERATE BLANK YEAR SHEET -->
<div id="drawer-nip-generate" 
     data-cho-nip-drawer 
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 w-full sm:w-[480px] md:w-[520px] max-w-full shadow-2xl flex flex-col justify-between" 
     tabindex="-1" 
     aria-labelledby="drawer-nip-generate-label" 
     aria-hidden="true">

    <div id="drawer-nip-generate-top-section" class="space-y-4">
        <!-- Drawer Header -->
        <div id="drawer-nip-generate-header" class="border-b border-slate-200 pb-3 flex items-center justify-between">
            <div id="drawer-nip-generate-title-group" class="flex items-center gap-2">
                <div id="drawer-nip-generate-icon-wrap" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h5 id="drawer-nip-generate-label" class="text-base font-bold text-slate-900">
                    Create Blank Year Sheet
                </h5>
            </div>

            <!-- Close Drawer Button -->
            <button type="button" 
                    id="drawer-nip-generate-close-btn" 
                    data-cho-drawer-hide="drawer-nip-generate" 
                    aria-controls="drawer-nip-generate" 
                    class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Spawns a pristine annual blank copy from master template. Preserves all 8,500+ formulas, catchment lookups, and cell formatting with raw counts safely reset to 0.
        </p>

        <!-- Form: Generate Year -->
        <form id="nip-drawer-generate-form" class="space-y-4">
            <div id="nip-drawer-generate-year-field" class="space-y-1.5">
                <label for="nip-input-generate-year" class="block text-xs font-semibold text-slate-700">
                    Target Fiscal Year
                </label>
                <input type="number" 
                       id="nip-input-generate-year" 
                       name="year" 
                       value="<?= date('Y') + 1 ?>" 
                       min="2020" 
                       max="2035" 
                       required 
                       class="cho-input-field w-full text-xs sm:text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded-lg p-2.5 focus:ring-slate-500 focus:border-slate-500" />
            </div>

            <div id="nip-drawer-generate-options" class="p-3 bg-slate-50 rounded-xl space-y-2 border border-slate-200 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="nip-gen-chk-formulas" checked disabled class="rounded text-slate-900">
                    <label for="nip-gen-chk-formulas" class="font-medium">Preserve all 8,500+ formulas</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="nip-gen-chk-barangays" checked disabled class="rounded text-slate-900">
                    <label for="nip-gen-chk-barangays" class="font-medium">Retain 74 Barangay Population Baselines</label>
                </div>
            </div>

            <!-- Feedback Display Box -->
            <div id="nip-generate-feedback" class="hidden"></div>

            <!-- Submit Button (Medium Size with cho-nip-dashboard-title text color bg-cho-blue-900) -->
            <button type="submit" 
                    id="nip-btn-submit-generate" 
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 active:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer">
                Generate Pristine Workbook
            </button>
        </form>
    </div>

    <!-- Drawer Footer Note -->
    <div id="drawer-nip-generate-footer" class="border-t border-slate-200 pt-3 text-[11px] text-slate-400 text-center">
        City Health Office Marawi &bull; NIP Data Engine v2.0
    </div>

</div>

<!-- DRAWER 3: RESET QUARTER RAW INPUTS -->
<div id="drawer-nip-reset" 
     data-cho-nip-drawer 
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 w-full sm:w-[480px] md:w-[520px] max-w-full shadow-2xl flex flex-col justify-between" 
     tabindex="-1" 
     aria-labelledby="drawer-nip-reset-label" 
     aria-hidden="true">

    <div id="drawer-nip-reset-top-section" class="space-y-4">
        <!-- Drawer Header -->
        <div id="drawer-nip-reset-header" class="border-b border-slate-200 pb-3 flex items-center justify-between">
            <div id="drawer-nip-reset-title-group" class="flex items-center gap-2">
                <div id="drawer-nip-reset-icon-wrap" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <h5 id="drawer-nip-reset-label" class="text-base font-bold text-slate-900">
                    Reset Quarter Inputs
                </h5>
            </div>

            <!-- Close Drawer Button -->
            <button type="button" 
                    id="drawer-nip-reset-close-btn" 
                    data-cho-drawer-hide="drawer-nip-reset" 
                    aria-controls="drawer-nip-reset" 
                    class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Zero raw cell tallies for a chosen quarter or date range across all 74 barangays. Calculated columns (FIC, Coverage %, Cumulative Totals) recalculate automatically.
        </p>

        <!-- Form: Reset Quarter Inputs -->
        <form id="nip-drawer-reset-form" class="space-y-4">
            <!-- Flowbite Wided Dropdown Component for Target Quarter -->
            <div id="nip-drawer-reset-field" class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">
                    Select Target Quarter
                </label>
                <div class="relative w-full">
                    <button id="nip-dropdown-btn-reset-period" 
                            data-dropdown-toggle="nip-dropdown-menu-reset-period" 
                            type="button" 
                            class="cursor-pointer w-full flex items-center justify-between text-slate-800 bg-slate-50 border border-slate-300 hover:bg-slate-100 font-medium rounded-lg text-xs sm:text-sm px-3.5 py-2.5 focus:outline-none focus:ring-4 focus:ring-slate-200 shadow-xs text-left transition-colors">
                        <span id="nip-dropdown-label-reset-period" class="truncate">Q1 (January – March)</span>
                        <svg class="w-4 h-4 ms-2 shrink-0 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                    </button>

                    <!-- Dropdown Menu (Wided) -->
                    <div id="nip-dropdown-menu-reset-period" class="z-50 hidden bg-white border border-slate-200 rounded-xl shadow-lg w-full">
                        <ul id="nip-dropdown-list-reset-period" class="p-2 text-xs sm:text-sm text-slate-700 font-medium space-y-1" aria-labelledby="nip-dropdown-btn-reset-period">
                            <li>
                                <button type="button" data-val="Q1" class="nip-period-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors">
                                    Q1 (January – March)
                                </button>
                            </li>
                            <li>
                                <button type="button" data-val="Q2" class="nip-period-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors">
                                    Q2 (April – June)
                                </button>
                            </li>
                            <li>
                                <button type="button" data-val="Q3" class="nip-period-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors">
                                    Q3 (July – September)
                                </button>
                            </li>
                            <li>
                                <button type="button" data-val="Q4" class="nip-period-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-slate-800 rounded-lg text-left cursor-pointer transition-colors">
                                    Q4 (October – December)
                                </button>
                            </li>
                            <li>
                                <button type="button" data-val="ALL" class="nip-period-dropdown-item inline-flex items-center w-full p-2 hover:bg-slate-100 text-rose-700 font-semibold rounded-lg text-left cursor-pointer transition-colors">
                                    Entire Year (Full Reset)
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Hidden Select for form serialization & value reading -->
                <select id="nip-select-reset-period" 
                        name="period" 
                        class="hidden cursor-pointer">
                    <option value="Q1" selected>Q1 (January – March)</option>
                    <option value="Q2">Q2 (April – June)</option>
                    <option value="Q3">Q3 (July – September)</option>
                    <option value="Q4">Q4 (October – December)</option>
                    <option value="ALL">Entire Year (Full Reset)</option>
                </select>
            </div>

            <!-- Flowbite Datepicker with Calendar Icon -->
            <div id="nip-drawer-reset-date-field" class="space-y-1.5">
                <label for="nip-input-reset-effective-date" class="block text-xs font-semibold text-slate-700">
                    Effective Action Date
                </label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input datepicker 
                           datepicker-autohide 
                           datepicker-format="yyyy-mm-dd" 
                           type="text" 
                           id="nip-input-reset-effective-date" 
                           name="effective_date" 
                           value="<?= date('Y-m-d') ?>" 
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-xs sm:text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full ps-10 p-2.5 shadow-xs transition-colors cursor-pointer" 
                           placeholder="Select date" />
                </div>
            </div>

            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                <span class="font-bold">⚠️ Warning:</span> This operation will clear raw immunization input tallies for the selected period. An automatic timestamped backup is generated prior to modification.
            </div>

            <!-- Feedback Display Box -->
            <div id="nip-reset-feedback" class="hidden"></div>

            <!-- Submit Button (Medium Size with cho-nip-dashboard-title text color bg-cho-blue-900) -->
            <button type="submit" 
                    id="nip-btn-submit-reset" 
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 active:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer">
                Confirm & Reset Quarter Raw Inputs
            </button>
        </form>
    </div>

    <!-- Drawer Footer Note -->
    <div id="drawer-nip-reset-footer" class="border-t border-slate-200 pt-3 text-[11px] text-slate-400 text-center">
        City Health Office Marawi &bull; NIP Data Engine v2.0
    </div>

</div>

<!-- DRAWER 4: UPLOAD UPDATED EXCEL WORKBOOK -->
<div id="drawer-nip-upload" 
     data-cho-nip-drawer 
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 w-full sm:w-[480px] md:w-[520px] max-w-full shadow-2xl flex flex-col justify-between" 
     tabindex="-1" 
     aria-labelledby="drawer-nip-upload-label" 
     aria-hidden="true">

    <div id="drawer-nip-upload-top-section" class="space-y-4">
        <!-- Drawer Header -->
        <div id="drawer-nip-upload-header" class="border-b border-slate-200 pb-3 flex items-center justify-between">
            <div id="drawer-nip-upload-title-group" class="flex items-center gap-2">
                <div id="drawer-nip-upload-icon-wrap" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <h5 id="drawer-nip-upload-label" class="text-base font-bold text-slate-900">
                    Upload Field Sheet
                </h5>
            </div>

            <!-- Close Drawer Button -->
            <button type="button" 
                    id="drawer-nip-upload-close-btn" 
                    data-cho-drawer-hide="drawer-nip-upload" 
                    aria-controls="drawer-nip-upload" 
                    class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Upload field-compiled quarterly data from rural health stations. The system verifies header schemas, validates cell ranges, and synchronizes real-time metrics.
        </p>

        <!-- Form: Upload Field Workbook with Flowbite File Input styling -->
        <form id="nip-drawer-upload-form" class="space-y-4" enctype="multipart/form-data">
            <div id="nip-drawer-upload-field" class="space-y-2">
                <label for="nip-upload-file-input" class="block text-xs sm:text-sm font-semibold text-slate-700">
                    Upload file
                </label>
                <input type="file" 
                       id="nip-upload-file-input" 
                       name="workbook" 
                       accept=".xlsx,.xlsm" 
                       required 
                       class="cursor-pointer bg-slate-50 border border-slate-300 text-slate-800 text-xs sm:text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full shadow-xs file:mr-4 file:py-2.5 file:ps-5 file:pe-4 file:rounded-s-md file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300 transition-colors" />
                <p class="text-[11px] text-slate-500">Supported formats: Microsoft Excel OpenXML (.xlsx, .xlsm), Max size: 25MB.</p>
            </div>

            <!-- Feedback Display Box -->
            <div id="nip-upload-feedback" class="hidden"></div>

            <!-- Submit Button (Medium Size with cho-nip-dashboard-title text color bg-cho-blue-900) -->
            <button type="submit" 
                    id="nip-btn-submit-upload" 
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 active:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer">
                Upload Excel Workbook
            </button>
        </form>
    </div>

    <!-- Drawer Footer Note -->
    <div id="drawer-nip-upload-footer" class="border-t border-slate-200 pt-3 text-[11px] text-slate-400 text-center">
        City Health Office Marawi &bull; NIP Data Engine v2.0
    </div>

</div>

<?php
require BASE_PATH . '/frontend/layouts/footer.php';
