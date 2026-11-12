<?php
/**
 * CHO MARAWI PORTAL - DASHBOARD
 * Location: frontend/pages/dashboard/index.php
 */

declare(strict_types=1);

require dirname(dirname(dirname(__DIR__))) . '/backend/bootstrap.php';

$pageTitle = 'Dashboard | City Health Office - Marawi City';

require BASE_PATH . '/frontend/layouts/head.php';
require BASE_PATH . '/frontend/layouts/sidebar.php';
?>

<div id="cho-dashboard-main-container" class="p-4 sm:ml-72 transition-all space-y-7">

    <!-- =========================================================================
         BANNER HERO: ROUNDED CONTAINER WITH BACKGROUND IMAGE & BRAND OVERLAY
         ========================================================================= -->
    <div id="cho-dashboard-welcome-banner" class="relative overflow-hidden rounded-2xl border border-slate-200/80 shadow-xs">
        <!-- Background Imagery (Restored to previous center view) -->
        <div id="cho-dashboard-banner-bg-wrapper" class="absolute inset-0 w-full h-full">
            <img id="cho-dashboard-banner-bg-img" src="<?= asset('images/landing/cho.webp') ?>" alt="City Health Office Facility" class="w-full h-full object-cover object-center" />
        </div>

        <!-- Brand Color Overlays: CHO Navy gradient with soft blur wash for high contrast & elegance -->
        <div id="cho-dashboard-banner-overlay-gradient" class="absolute inset-0 bg-gradient-to-r from-cho-blue-950/95 via-cho-blue-900/85 to-cho-blue-800/70 backdrop-blur-[1px]"></div>
        <div id="cho-dashboard-banner-overlay-vignette" class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

        <!-- Banner Content (Balanced medium height) -->
        <div id="cho-dashboard-banner-content" class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-6 px-6 sm:py-7 sm:px-8 text-white">
            <div id="cho-dashboard-banner-text-group" class="space-y-1.5 min-w-0">
                <div id="cho-dashboard-banner-greeting" class="flex items-center gap-2">
                    <span id="cho-dashboard-banner-greeting-text" class="inline-flex items-center text-xs sm:text-sm font-medium text-cho-gold-300">
                        Welcome back, CHO Health Team 👋
                    </span>
                </div>
                <h1 id="cho-dashboard-banner-heading" class="text-xl sm:text-2xl lg:text-3xl font-extrabold font-heading tracking-tight text-white leading-tight whitespace-nowrap">
                    How can we help make your workday easier today?
                </h1>
            </div>

            <!-- Start Tour Action Button (highlight animation planned) -->
            <div id="cho-dashboard-banner-action-wrap" class="shrink-0 flex items-center">
                <!-- START TOUR BUTTON: Target for future step-by-step interactive onboarding highlighting animation -->
                <button type="button" id="btn-start-tour" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold text-cho-blue-950 bg-white hover:bg-slate-100 active:scale-95 rounded-xl shadow-sm hover:shadow-md transition-all cursor-pointer group">
                    <svg id="cho-dashboard-tour-icon" class="w-4 h-4 text-cho-blue-800 group-hover:rotate-45 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="cho-dashboard-tour-btn-text">Start Tour</span>
                </button>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         QUICK ACCESS TOOLS SECTION: Direct Fast Actions (Header trimmed per request)
         ========================================================================= -->
    <div id="cho-quick-access-section">
        <!-- Grid of Transparent Clickable Action Tiles with Image/Icon on Left -->
        <div id="cho-quick-access-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">

            <!-- Tool 1: Export Master NIP Excel Sheet -->
            <a href="<?= url('/api/v1/nip/download?file=MARAWI+NIP+DATA+-+UPGRADED.xlsx') ?>" id="qa-card-export" class="group flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-transparent hover:bg-white border border-transparent hover:border-slate-200/90 hover:shadow-xs transition-all duration-200">
                <img id="qa-icon-export" src="<?= asset('images/icons/export.svg') ?>" alt="Export Icon" class="w-11 h-11 shrink-0 rounded-xl opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all" />
                <div id="qa-text-export" class="min-w-0 flex-1">
                    <div id="qa-title-export" class="text-sm font-bold text-slate-800 group-hover:text-cho-blue-700 transition-colors truncate">
                        Export Master NIP Workbook
                    </div>
                    <div id="qa-desc-export" class="text-xs text-slate-500 truncate">
                        Direct download formula-intact .xlsx file
                    </div>
                </div>
            </a>

            <!-- Tool 2: Upload Updated Workbook -->
            <a href="#" id="qa-card-upload" class="group flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-transparent hover:bg-white border border-transparent hover:border-slate-200/90 hover:shadow-xs transition-all duration-200">
                <img id="qa-icon-upload" src="<?= asset('images/icons/upload.svg') ?>" alt="Upload Icon" class="w-11 h-11 shrink-0 rounded-xl opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all" />
                <div id="qa-text-upload" class="min-w-0 flex-1">
                    <div id="qa-title-upload" class="text-sm font-bold text-slate-800 group-hover:text-cho-blue-700 transition-colors truncate">
                        Upload Excel Data Sheet
                    </div>
                    <div id="qa-desc-upload" class="text-xs text-slate-500 truncate">
                        Sync raw inputs from field health centers
                    </div>
                </div>
            </a>

            <!-- Tool 3: Clean Period / Year Generator -->
            <a href="#" id="qa-card-generate" class="group flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-transparent hover:bg-white border border-transparent hover:border-slate-200/90 hover:shadow-xs transition-all duration-200">
                <img id="qa-icon-generate" src="<?= asset('images/icons/generate.svg') ?>" alt="Generate Icon" class="w-11 h-11 shrink-0 rounded-xl opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all" />
                <div id="qa-text-generate" class="min-w-0 flex-1">
                    <div id="qa-title-generate" class="text-sm font-bold text-slate-800 group-hover:text-cho-blue-700 transition-colors truncate">
                        Generate Blank Year Sheet
                    </div>
                    <div id="qa-desc-generate" class="text-xs text-slate-500 truncate">
                        Zero 26k cells with 8,500+ formulas intact
                    </div>
                </div>
            </a>

            <!-- Tool 4: Reset Quarter Inputs -->
            <a href="#" id="qa-card-reset" class="group flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-transparent hover:bg-white border border-transparent hover:border-slate-200/90 hover:shadow-xs transition-all duration-200">
                <img id="qa-icon-reset" src="<?= asset('images/icons/reset.svg') ?>" alt="Reset Quarter Icon" class="w-11 h-11 shrink-0 rounded-xl opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all" />
                <div id="qa-text-reset" class="min-w-0 flex-1">
                    <div id="qa-title-reset" class="text-sm font-bold text-slate-800 group-hover:text-cho-blue-700 transition-colors truncate">
                        Quarter Input Reset
                    </div>
                    <div id="qa-desc-reset" class="text-xs text-slate-500 truncate">
                        Clear specific Q1-Q4 raw entries safely
                    </div>
                </div>
            </a>

            <!-- Tool 5: Vaccine Stock-Out Alert -->
            <a href="#" id="qa-card-stock-alert" class="group flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-transparent hover:bg-white border border-transparent hover:border-slate-200/90 hover:shadow-xs transition-all duration-200">
                <img id="qa-icon-stock-alert" src="<?= asset('images/icons/stock-alert.svg') ?>" alt="Stock Out Icon" class="w-11 h-11 shrink-0 rounded-xl opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all" />
                <div id="qa-text-stock-alert" class="min-w-0 flex-1">
                    <div id="qa-title-stock-alert" class="text-sm font-bold text-slate-800 group-hover:text-cho-blue-700 transition-colors truncate">
                        Stock-Out & Expiry Log
                    </div>
                    <div id="qa-desc-stock-alert" class="text-xs text-slate-500 truncate">
                        Track BCG, PCV & cold chain shortages
                    </div>
                </div>
            </a>

            <!-- Tool 6: 74 Barangay Population Sync -->
            <a href="#" id="qa-card-census" class="group flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl bg-transparent hover:bg-white border border-transparent hover:border-slate-200/90 hover:shadow-xs transition-all duration-200">
                <img id="qa-icon-census" src="<?= asset('images/icons/census.svg') ?>" alt="Census Icon" class="w-11 h-11 shrink-0 rounded-xl opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all" />
                <div id="qa-text-census" class="min-w-0 flex-1">
                    <div id="qa-title-census" class="text-sm font-bold text-slate-800 group-hover:text-cho-blue-700 transition-colors truncate">
                        Barangay Population Master
                    </div>
                    <div id="qa-desc-census" class="text-xs text-slate-500 truncate">
                        176,029 census & eligible target baselines
                    </div>
                </div>
            </a>

        </div>
    </div>

    <!-- =========================================================================
         CHO MARAWI SYSTEMS SECTION: Featured Sub-Systems (Design based on image)
         ========================================================================= -->
    <div id="cho-systems-section" class="space-y-4 pt-1">
        <!-- All-Caps Header -->
        <div id="cho-systems-header" class="flex items-center justify-between px-1">
            <h2 id="cho-systems-heading" class="text-base sm:text-lg font-bold font-heading tracking-wide uppercase text-slate-900">
                CHO MARAWI SYSTEMS
            </h2>
        </div>

        <!-- 3-Column Responsive Grid matching Image Design -->
        <div id="cho-systems-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- Card 1: NIP Program Data Monitoring -->
            <a href="#" id="cho-system-card-nip" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-nip" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-nip" class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-nip" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            NIP Program System
                        </h3>
                        <p id="cho-system-desc-nip" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            National Immunization Program analytics, 18 vaccine indicators, and 74 barangays.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-nip" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-nip" src="<?= asset('images/systems/nip-preview.svg') ?>" alt="NIP Dashboard Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 2: CHO Marawi Finance -->
            <a href="#" id="cho-system-card-finance" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-finance" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-finance" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-finance" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            CHO Marawi Finance
                        </h3>
                        <p id="cho-system-desc-finance" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Disbursement vouchers, medical procurement, LGU payroll audits, and resource allocation.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-finance" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-finance" src="<?= asset('images/systems/finance-preview.svg') ?>" alt="Finance System Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 3: Maternal, Child Health & Nutrition (MCHN) -->
            <a href="#" id="cho-system-card-maternal" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-maternal" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-maternal" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-maternal" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            Maternal & Child Health
                        </h3>
                        <p id="cho-system-desc-maternal" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Antenatal checkups, high-risk pregnancy monitoring, infant birth planning, and nutrition.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-maternal" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-maternal" src="<?= asset('images/systems/maternal-preview.svg') ?>" alt="Maternal Health Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 4: Clinical Consultations & Outpatient Services -->
            <a href="#" id="cho-system-card-clinical" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-clinical" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-clinical" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-clinical" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            Clinical & Outpatient EHR
                        </h3>
                        <p id="cho-system-desc-clinical" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Primary healthcare consultations, electronic records, lab diagnostics, and APMC referrals.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-clinical" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-clinical" src="<?= asset('images/systems/clinical-preview.svg') ?>" alt="Clinical Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 5: Disease Surveillance & Outbreak Response -->
            <a href="#" id="cho-system-card-surveillance" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-surveillance" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-surveillance" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-surveillance" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            Epidemiology & Surveillance
                        </h3>
                        <p id="cho-system-desc-surveillance" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Real-time disease tracking, Dengue cluster maps, and reporting to MOH-BARMM.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-surveillance" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-surveillance" src="<?= asset('images/systems/surveillance-preview.svg') ?>" alt="Surveillance Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 6: TB-DOTS & Infectious Diseases -->
            <a href="#" id="cho-system-card-tb" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-tb" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-tb" class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-tb" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            TB-DOTS & Treatment Center
                        </h3>
                        <p id="cho-system-desc-tb" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Directly Observed Treatment, GeneXpert sputum testing, and medication adherence logging.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-tb" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-tb" src="<?= asset('images/systems/tb-preview.svg') ?>" alt="TB DOTS Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 7: Environmental Health & Sanitation -->
            <a href="#" id="cho-system-card-sanitation" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-sanitation" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-sanitation" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-sanitation" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            Environmental Sanitation
                        </h3>
                        <p id="cho-system-desc-sanitation" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Sanitary permits, food establishment inspections, and drinking water safety tests.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-sanitation" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-sanitation" src="<?= asset('images/systems/sanitation-preview.svg') ?>" alt="Sanitation Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 8: Animal Bite Treatment Center (ABTC) -->
            <a href="#" id="cho-system-card-abtc" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-abtc" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-abtc" class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-abtc" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            Animal Bite Center (ABTC)
                        </h3>
                        <p id="cho-system-desc-abtc" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Post-exposure rabies prophylaxis, wound care logging, and vaccine course scheduling.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-abtc" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-abtc" src="<?= asset('images/systems/abtc-preview.svg') ?>" alt="ABTC Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

            <!-- Card 9: Dental Health & Oral Services -->
            <a href="#" id="cho-system-card-dental" class="group relative flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-slate-50/80 border border-slate-200/90 hover:border-slate-300 rounded-2xl shadow-xs hover:shadow-md transition-all duration-200">
                <div id="cho-system-left-dental" class="flex-1 pr-3 space-y-2">
                    <div id="cho-system-icon-dental" class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cho-system-title-dental" class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-cho-blue-700 transition-colors">
                            Dental Health Services
                        </h3>
                        <p id="cho-system-desc-dental" class="text-xs text-slate-500 leading-relaxed mt-1 line-clamp-2">
                            Oral health examinations, routine tooth extractions, cleanings, and school dental outreach.
                        </p>
                    </div>
                </div>
                <!-- Right Side Graphic/Dashboard Preview -->
                <div id="cho-system-img-wrap-dental" class="shrink-0 w-24 h-20 sm:w-28 sm:h-22 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex items-center justify-center">
                    <img id="cho-system-img-dental" src="<?= asset('images/systems/dental-preview.svg') ?>" alt="Dental Services Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                </div>
            </a>

        </div>

        </div>
    </div>

</div>

<?php
require BASE_PATH . '/frontend/layouts/footer.php';
