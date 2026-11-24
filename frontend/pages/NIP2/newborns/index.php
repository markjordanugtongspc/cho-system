<?php
/**
 * CHO MARAWI PORTAL - MARAWI POPULATION MASTER TABLE
 * Location: frontend/pages/NIP2/population/index.php
 */

declare(strict_types=1);

require dirname(dirname(dirname(dirname(__DIR__)))) . '/backend/bootstrap.php';

$pageTitle = 'Target Client List (0-12 Months) | City Health Office - Marawi City';

require BASE_PATH . '/frontend/layouts/head.php';
require BASE_PATH . '/frontend/layouts/sidebar.php';
?>

<div id="cho-nip-population-main-container2" class="p-4 sm:ml-72 transition-all space-y-6">

    <!-- TOP HEADER -->
    <div id="cho-nip-population-header2" class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
        <div>
            <h1 id="cho-nip-population-title2" class="text-lg sm:text-2xl font-black font-heading tracking-tight text-cho-blue-900">
                TARGET CLIENT LIST FOR CHILD HEALTH CARE NEWBORN AND INFANTS AGE 0-12 MONTHS OLD
            </h1>
            <p id="cho-nip-population-subtitle2" class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                Child Health Care &bull; Newborn &amp; Infants (0&ndash;12 Months) Client Registry
            </p>
        </div>
        <div id="cho-nip-pop-badge-wrap2" class="flex items-center gap-2">
            <button type="button"
                    id="cho-nip-pop-btn-add2"
                    data-cho-drawer-target="drawer-nip-pop-add2"
                    data-drawer-placement="right"
                    aria-controls="drawer-nip-pop-add2"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 active:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Barangay Entry</span>
            </button>
        </div>
    </div>

    <!-- COMPACT SUMMARY STATS (Copied from NIP Dashboard #cho-nip-stats-grid) -->
    <div id="cho-nip-pop-stats-grid2" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Barangay Records (Blue Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-records2" class="group relative overflow-hidden rounded-none p-5 bg-[#005bf8] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip1.webp') ?>"
                 alt="Barangays Population"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-1.5 w-24 h-24 text-slate-300/60 group-hover:text-white/75 pointer-events-none transform -rotate-6 group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 ease-out z-[5]" fill="currentColor" viewBox="0 0 511.117 511.117" aria-hidden="true">
                <g transform="translate(1 1)">
                    <path d="M507.469,64.324L444.793,1.648c-3.531-3.531-8.828-3.531-12.359,0c-3.531,3.531-3.531,8.828,0,12.359l6.179,6.179l-56.938,56.938l-31.338-31.338c-3.531-3.531-8.828-3.531-12.359,0l-24.717,24.717c-1.766,1.766-2.648,3.531-2.648,6.179c0,2.648,0.883,4.414,2.648,6.179l6.647,6.595l-49.902,49.902l-118.29,118.29c-18.354,19.152-20.112,48.402-5.282,68.841L127,345.924c-0.934,0.934-1.613,1.872-2.053,2.936l-24.43,24.43c-7.685,7.685-9.282,18.685-4.803,27.754L1.648,495.11c-3.531,3.531-3.531,8.828,0,12.359c1.766,1.766,3.531,2.648,6.179,2.648c2.648,0,4.414-0.883,6.179-2.648l93.421-93.421c3.938,2.707,8.664,4.262,13.393,4.262c7.062,0,12.359-2.648,16.772-7.945l26.483-26.483c0.934-0.934,1.613-1.872,2.053-2.936l17.283-17.283c8.671,6.407,19.113,9.626,30.098,9.626c14.124,0,27.366-5.297,37.959-15.89l118.29-118.29l50.293-50.293l6.203,6.155c1.766,1.766,3.531,2.648,6.179,2.648c1.766,0,4.414-0.883,5.297-2.648l24.717-24.717c1.766-1.766,2.648-3.531,2.648-6.179c0-2.648-0.883-4.414-2.648-6.179l-31.338-31.338l56.938-56.938l7.062,7.062c1.766,1.766,3.531,2.648,6.179,2.648c1.766,0,4.414-0.883,6.179-2.648C511,73.152,511,67.855,507.469,64.324z M288.545,145.538l44.138-43.255l12.8,12.8l-43.697,43.697L288.545,145.538z M313.262,170.255l43.697-43.697l24.717,24.717l-43.697,43.697L313.262,170.255z M157.897,338.862l12.359,12.359l-12.359,12.359l-12.359-12.359L157.897,338.862z M125.234,398.007c-1.766,2.648-6.179,2.648-8.828,0l-3.531-3.531c-2.648-1.766-2.648-6.179,0-8.828l20.303-20.303l12.359,12.359L125.234,398.007z M238.228,344.159c-6.179,7.062-15.007,10.593-24.717,10.593c-8.828,0-17.655-3.531-24.717-10.593l-24.717-24.717c-14.124-13.241-14.124-35.31,0-49.434l24.717-24.717l24.717,24.717c1.766,1.766,3.531,2.648,6.179,2.648c2.648,0,5.297-0.883,6.179-2.648c3.531-3.531,3.531-8.828,0-12.359l-24.717-24.717l18.979-18.979l25.159,25.159c1.766,1.766,3.531,2.648,6.179,2.648c1.766,0,4.414-0.883,6.179-2.648c3.531-3.531,3.531-8.828,0-12.359l-25.159-25.159l18.538-18.538l25.159,25.159c1.766,1.766,3.531,2.648,6.179,2.648c2.648,0,4.414-0.883,6.179-2.648c3.531-3.531,3.531-8.828,0-12.359l-25.159-25.159l12.8-12.8l19.421,19.421l37.076,37.076l17.655,17.655L238.228,344.159z M363.579,220.572l-12.359-12.359l43.697-43.697l12.8,12.8L363.579,220.572z M432.434,176.435l-6.178-6.178c0,0,0-0.001-0.001-0.001l-24.716-24.716c0,0-0.001-0.001-0.001-0.001l-37.076-37.076c0,0-0.001,0-0.001-0.001l-31.778-31.779l12.359-12.359l30.897,30.897l37.959,37.959l30.897,30.897L432.434,176.435z M419.193,113.759l-24.717-24.717l56.497-55.614l24.717,24.717L419.193,113.759z"/>
                </g>
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90">Fully Completed</span>
                    <span class="text-xs font-black tracking-widest text-white/60">01</span>
                </div>
                <div id="cho-nip-pop-stat-records-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-20"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">100% All Doses Injected</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>✓</span> Fully Immunized</span>
                </div>
            </div>
        </div>

        <!-- Card 2: City Total Pop 2024 - C4 (Orange Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-total2" class="group relative overflow-hidden rounded-none p-5 bg-[#e66a00] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip2.webp') ?>"
                 alt="City Total Population"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-1.5 w-24 h-24 text-slate-300/60 group-hover:text-white/75 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 ease-out" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                <path d="M19 5l-5.4 5.4" />
                <path d="M19 5h-5" />
                <path d="M19 5v5" />
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90">Total Male Infants</span>
                    <span class="text-xs font-black tracking-widest text-white/60">02</span>
                </div>
                <div id="cho-nip-pop-stat-total-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-28"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">Male Gender Total</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>♂</span> Male Cohort</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Household Population Estimates 2024 - D4 (Emerald Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-households2" class="group relative overflow-hidden rounded-none p-5 bg-[#007a64] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip3.webp') ?>"
                 alt="Household Population Estimates"
                 class="absolute inset-0 w-full h-full object-cover object-[center_20%] opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-2 w-24 h-24 text-slate-300/60 group-hover:text-white/75 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 ease-out" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M7 9a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                <path d="M12 14v7" />
                <path d="M9 18h6" />
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90 truncate pr-2">Total Female Infants</span>
                    <span class="text-xs font-black tracking-widest text-white/60 shrink-0">03</span>
                </div>
                <div id="cho-nip-pop-stat-households-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-28"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">Female Gender Total</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>♀</span> Female Cohort</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Number of Households Estimates 2024 - E4 (Crimson Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-target2" class="group relative overflow-hidden rounded-none p-5 bg-[#c00030] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip4.webp') ?>"
                 alt="Number of Households Estimates 2024"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-1.5 w-24 h-24 text-slate-300/60 group-hover:text-white/75 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-all duration-500 ease-out" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M20 9L9.50038 19.5C8.11966 20.8807 5.88109 20.8807 4.50038 19.5C3.11967 18.1193 3.11967 15.8807 4.50038 14.5L15 4M14.0004 3L21.0004 10M7 12H17" />
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90 truncate pr-2">Not Yet Completed</span>
                    <span class="text-xs font-black tracking-widest text-white/60 shrink-0">04</span>
                </div>
                <div id="cho-nip-pop-stat-target-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-24"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">Pending ≥1 Dose</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>⏳</span> Needs Follow-up</span>
                </div>
            </div>
        </div>

    </div>

    <!-- FLOWBITE TABLE CARD: Striped Columns + Hover State + Sortable Head + Wided Search + Filter + Infinite Scroll -->
    <div id="cho-nip-population-table-card2"
         data-sheets-base-url="<?= e(url('/SHEETS/')) ?>"
         class="relative bg-white border border-slate-200/90 rounded-xl shadow-xs overflow-visible">

        <!-- Table Toolbar: Wided Search + Table Filter + Database Status -->
        <div id="cho-nip-pop-toolbar2" class="p-4 border-b border-slate-200/90 bg-white rounded-t-xl flex flex-col lg:flex-row lg:items-center gap-3">
            <!-- Wided Table Search -->
            <div class="relative flex-1 min-w-0">
                <label for="cho-nip-pop-search2" class="sr-only">Search Target Client List</label>
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text"
                       id="cho-nip-pop-search2"
                       class="block w-full ps-10 pe-9 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-300 rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 placeholder-slate-400"
                       placeholder="Search child name, barangay address, or registration date..." />
                <button type="button"
                        id="cho-nip-pop-search-clear2"
                        class="hidden absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400 hover:text-slate-600 cursor-pointer"
                        title="Clear search">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Table Filter, Order & Database Source -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
                <!-- Flowbite Multi-Level Dropdown Filter -->
                <div class="relative flex items-center">
                    <button id="cho-nip-pop-filter2"
                            data-dropdown-toggle="cho-nip-pop-filter-dropdown2"
                            data-dropdown-placement="bottom-start"
                            type="button"
                            aria-label="Filter Records"
                            class="inline-flex items-center justify-between gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-800 text-xs sm:text-sm font-medium rounded-lg focus:ring-2 focus:ring-cho-blue-600 focus:border-cho-blue-600 w-full sm:w-52 px-3 py-2.5 cursor-pointer transition-colors">
                        <span class="inline-flex items-center gap-2 min-w-0">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span id="cho-nip-pop-filter-label2" class="truncate">All Infants</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Level 1 Dropdown Menu -->
                    <div id="cho-nip-pop-filter-dropdown2"
                         class="z-40 hidden bg-white border border-slate-200 rounded-xl shadow-xl w-52">
                        <ul class="p-2 text-sm text-slate-700 font-medium space-y-0.5" aria-labelledby="cho-nip-pop-filter2">
                            <li>
                                <button type="button" data-filter-value="all" data-filter-label="All Infants"
                                        class="cho-filter-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                    All Infants
                                </button>
                            </li>
                            <li>
                                <button id="cho-nip-pop-brgy-dropdown-btn2"
                                        data-dropdown-toggle="cho-nip-pop-brgy-dropdown2"
                                        data-dropdown-placement="right-start"
                                        type="button"
                                        class="inline-flex items-center justify-between w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                    <span>Barangay</span>
                                    <svg class="h-4 w-4 ms-auto text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Level 2 Submenu: Searchable Barangay Filter -->
                                <div id="cho-nip-pop-brgy-dropdown2"
                                     class="z-50 hidden bg-white border border-slate-200 rounded-xl shadow-xl w-64">
                                    <div class="bg-slate-50 border-b border-slate-200 p-2 rounded-t-xl">
                                        <label for="cho-nip-pop-brgy-search2" class="sr-only">Search Barangay</label>
                                        <input type="text"
                                               id="cho-nip-pop-brgy-search2"
                                               class="bg-white border border-slate-300 text-slate-900 text-xs sm:text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-2.5 py-1.5 placeholder-slate-400"
                                               placeholder="Search barangay..." />
                                    </div>
                                    <ul id="cho-nip-pop-brgy-list2"
                                        class="h-48 p-2 text-xs sm:text-sm text-slate-700 font-medium overflow-y-auto space-y-0.5"
                                        aria-labelledby="cho-nip-pop-brgy-dropdown-btn2">
                                    </ul>
                                    <div class="bg-slate-50 border-t border-slate-200 p-2 rounded-b-xl">
                                        <button type="button"
                                                id="cho-nip-pop-brgy-clear2"
                                                class="w-full inline-flex items-center justify-center text-white bg-cho-blue-900 hover:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 shadow-xs font-semibold rounded-lg text-xs px-3 py-1.5 cursor-pointer transition-colors">
                                            Show All Barangays (Global)
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <button id="cho-nip-pop-gender-dropdown-btn2"
                                        data-dropdown-toggle="cho-nip-pop-gender-dropdown2"
                                        data-dropdown-placement="right-start"
                                        type="button"
                                        class="inline-flex items-center justify-between w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                    <span>Genders</span>
                                    <svg class="h-4 w-4 ms-auto text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Level 2 Submenu: Genders (No Search) -->
                                <div id="cho-nip-pop-gender-dropdown2"
                                     class="z-50 hidden bg-white border border-slate-200 rounded-xl shadow-xl w-44">
                                    <ul class="p-2 text-sm text-slate-700 font-medium space-y-0.5" aria-labelledby="cho-nip-pop-gender-dropdown-btn2">
                                        <li>
                                            <button type="button" data-filter-value="male" data-filter-label="Genders: Male"
                                                    class="cho-filter-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                                Male
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" data-filter-value="female" data-filter-label="Genders: Female"
                                                    class="cho-filter-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                                Female
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <button id="cho-nip-pop-imm-dropdown-btn2"
                                        data-dropdown-toggle="cho-nip-pop-imm-dropdown2"
                                        data-dropdown-placement="right-start"
                                        type="button"
                                        class="inline-flex items-center justify-between w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                    <span>Immunizations</span>
                                    <svg class="h-4 w-4 ms-auto text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Level 2 Submenu: Immunizations Status -->
                                <div id="cho-nip-pop-imm-dropdown2"
                                     class="z-50 hidden bg-white border border-slate-200 rounded-xl shadow-xl w-48">
                                    <ul class="p-2 text-sm text-slate-700 font-medium space-y-0.5" aria-labelledby="cho-nip-pop-imm-dropdown-btn2">
                                        <li>
                                            <button type="button" data-filter-value="all" data-filter-label="Immunizations: All"
                                                    class="cho-filter-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                                All (Injected / Not Yet)
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" data-filter-value="injected" data-filter-label="Immunizations: INJECTED"
                                                    class="cho-filter-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                                INJECTED
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" data-filter-value="not_yet" data-filter-label="Immunizations: NOT YET"
                                                    class="cho-filter-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                                NOT YET
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Flowbite Multi-Level Order & Date/Immunization Filter Dropdown -->
                <div class="relative flex items-center">
                    <button id="cho-nip-pop-order2"
                            data-dropdown-toggle="cho-nip-pop-order-dropdown2"
                            data-dropdown-placement="bottom-start"
                            type="button"
                            aria-label="Order By"
                            class="inline-flex items-center justify-between gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-800 text-xs sm:text-sm font-medium rounded-lg focus:ring-2 focus:ring-cho-blue-600 focus:border-cho-blue-600 w-full sm:w-52 px-3 py-2.5 cursor-pointer transition-colors">
                        <span class="inline-flex items-center gap-2 min-w-0">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                            </svg>
                            <span id="cho-nip-pop-order-label2" class="truncate">By # (1 &rarr; N)</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Level 1 Order Dropdown Menu -->
                    <div id="cho-nip-pop-order-dropdown2"
                         class="z-40 hidden bg-white border border-slate-200 rounded-xl shadow-xl w-52">
                        <ul class="p-2 text-sm text-slate-700 font-medium space-y-0.5" aria-labelledby="cho-nip-pop-order2">
                            <li>
                                <button type="button" data-order-value="asc" data-order-label="By # (1 &rarr; N)"
                                        class="cho-order-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                    By # (1 &rarr; N)
                                </button>
                            </li>
                            <li>
                                <button type="button" data-order-value="newest" data-order-label="Newly Added First"
                                        class="cho-order-opt2 inline-flex items-center w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left cursor-pointer">
                                    Newly Added First
                                </button>
                            </li>
                            <li class="pt-1 mt-1 border-t border-slate-100">
                                <button id="cho-nip-pop-date-dropdown-btn2"
                                        data-dropdown-toggle="cho-nip-pop-date-dropdown2"
                                        data-dropdown-placement="right-start"
                                        type="button"
                                        class="inline-flex items-center justify-between w-full p-2 hover:bg-slate-100 hover:text-cho-blue-800 rounded-lg text-left font-semibold cursor-pointer">
                                    <span>By Date &amp; Immunization</span>
                                    <svg class="h-4 w-4 ms-auto text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Level 2 Submenu: Date + Immunization Picker -->
                                <div id="cho-nip-pop-date-dropdown2"
                                     class="z-50 hidden bg-white border border-slate-200 rounded-xl shadow-xl w-72 p-3 space-y-2.5">
                                    <div>
                                        <label for="cho-nip-pop-date-imm2" class="block mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">Immunization</label>
                                        <select id="cho-nip-pop-date-imm2"
                                                class="bg-slate-50 border border-slate-300 text-slate-900 text-xs sm:text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-2.5 py-2">
                                            <option value="any">Any Immunization / Reg. Date</option>
                                            <option value="reg_date">Registration Date</option>
                                            <option value="bcg">BCG</option>
                                            <option value="hepa">Hepa - B</option>
                                            <option value="dpt">DPT-HiB-HepB</option>
                                            <option value="opv">OPV</option>
                                            <option value="pcv">PCV</option>
                                            <option value="ipv">IPV</option>
                                            <option value="mcv1">MCV1 (9 mos)</option>
                                            <option value="mcv2">MCV2 (12 mos)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="cho-nip-pop-date-input2" class="block mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">Select Date</label>
                                        <input type="date"
                                               id="cho-nip-pop-date-input2"
                                               class="bg-slate-50 border border-slate-300 text-slate-900 text-xs sm:text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-2.5 py-2" />
                                    </div>
                                    <div class="flex items-center gap-2 pt-1">
                                        <button type="button"
                                                id="cho-nip-pop-date-apply2"
                                                class="flex-1 inline-flex items-center justify-center text-white bg-cho-blue-900 hover:bg-cho-blue-950 font-semibold rounded-lg text-xs px-3 py-2 cursor-pointer transition-colors">
                                            Apply Date
                                        </button>
                                        <button type="button"
                                                id="cho-nip-pop-date-clear2"
                                                class="inline-flex items-center justify-center text-slate-700 bg-slate-100 hover:bg-slate-200 font-semibold rounded-lg text-xs px-3 py-2 cursor-pointer transition-colors">
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span id="cho-nip-pop-active-workbook-badge2"
                          class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-300 text-slate-700 text-xs sm:text-sm font-medium rounded-lg px-3 py-2.5 whitespace-nowrap">
                        <span class="w-2 h-2 rounded-full bg-emerald-500" id="cho-nip-pop-active-dot2"></span>
                        <span id="cho-nip-pop-active-workbook-name2">TCL Database (Mock)</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Infinite Scrollable Table Wrapper -->
        <div id="cho-nip-pop-scroll-container2" class="relative overflow-x-auto overflow-y-auto max-h-[62vh] bg-white">
            <table id="cho-nip-pop-table2" class="w-full text-sm text-center text-slate-600">
                <thead class="text-xs text-white uppercase bg-cho-blue-900 sticky top-0 z-10 border-b border-cho-blue-800">
                    <tr>
                        <th scope="col" data-sort-key="row_index" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-900 hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>#</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="barangay_address" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-[#092b57] hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>BARANGAY ADDRESS</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="registration_date" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-900 hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>DATE OF REGISTRATION</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="child_name" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-[#092b57] hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>NAME OF CHILD</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="dob" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-900 hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>DATE OF BIRTH</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="age" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-[#092b57] hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>AGE</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="sex" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-900 hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>SEX</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="bcg_status" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-[#092b57] hover:bg-cho-blue-800 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>BCG</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 font-bold whitespace-nowrap text-center bg-cho-blue-900">
                            <span>Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="cho-nip-pop-tbody2" class="divide-y divide-slate-200/80 bg-white">
                    <?php
                    $tclMockSamples = [
                        ['id' => 1,  'barangay' => 'Tuca',                  'reg_date' => '2026-10-04', 'child' => 'Al-Amin M. Macaraya',      'dob' => '2026-09-18', 'age' => '2 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-10-04', 'lvl' => 'dose1'],
                        ['id' => 2,  'barangay' => 'Basak Malutlut',        'reg_date' => '2026-10-09', 'child' => 'Sittie Aisha D. Ampaso',   'dob' => '2026-09-25', 'age' => '1 mo',    'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-10-09', 'lvl' => 'birth'],
                        ['id' => 3,  'barangay' => 'Banggolo',              'reg_date' => '2026-10-14', 'child' => 'Mohammad R. Sultan',       'dob' => '2026-10-02', 'age' => '1 mo',    'sex' => 'Male',   'bcg' => 'NOT YET',  'vax_date' => '',           'lvl' => 'none'],
                        ['id' => 4,  'barangay' => 'Marawi Central',        'reg_date' => '2026-10-19', 'child' => 'Norhanie K. Hadji Ali',    'dob' => '2026-08-12', 'age' => '3 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-10-19', 'lvl' => 'dose2'],
                        ['id' => 5,  'barangay' => 'Dulay',                 'reg_date' => '2026-10-22', 'child' => 'Abdulrahman P. Dimaporo',  'dob' => '2026-07-05', 'age' => '4 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-10-22', 'lvl' => 'dose3'],
                        ['id' => 6,  'barangay' => 'Kilala',                'reg_date' => '2026-10-27', 'child' => 'Fatimah Z. Balindong',     'dob' => '2026-10-15', 'age' => '1 mo',    'sex' => 'Female', 'bcg' => 'NOT YET',  'vax_date' => '',           'lvl' => 'birth'],
                        ['id' => 7,  'barangay' => 'Matampay',              'reg_date' => '2026-11-02', 'child' => 'Hamza L. Pangcoga',        'dob' => '2026-05-20', 'age' => '6 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-11-02', 'lvl' => 'dose3'],
                        ['id' => 8,  'barangay' => 'Lilod Madaya',          'reg_date' => '2026-11-06', 'child' => 'Jannah R. Marohombsar',    'dob' => '2026-03-11', 'age' => '8 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-11-06', 'lvl' => 'dose3'],
                        ['id' => 9,  'barangay' => 'Marinaut',              'reg_date' => '2026-11-11', 'child' => 'Yusuf A. Usman',           'dob' => '2026-11-01', 'age' => '19 days', 'sex' => 'Male',   'bcg' => 'NOT YET',  'vax_date' => '',           'lvl' => 'none'],
                        ['id' => 10, 'barangay' => 'Poblacion',             'reg_date' => '2026-11-15', 'child' => 'Amina S. Lucman',          'dob' => '2025-11-15', 'age' => '12 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2025-11-20', 'lvl' => 'full'],
                        ['id' => 11, 'barangay' => 'Tuca',                  'reg_date' => '2026-01-12', 'child' => 'Ibrahim B. Alonto',        'dob' => '2025-01-10', 'age' => '12 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2025-01-15', 'lvl' => 'full'],
                        ['id' => 12, 'barangay' => 'Tuca',                  'reg_date' => '2026-03-08', 'child' => 'Rohaima M. Benito',        'dob' => '2025-06-01', 'age' => '9 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2025-06-05', 'lvl' => 'dose3'],
                        ['id' => 13, 'barangay' => 'Basak Malutlut',        'reg_date' => '2026-02-14', 'child' => 'Khalid T. Guro',           'dob' => '2025-02-10', 'age' => '12 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2025-02-14', 'lvl' => 'full'],
                        ['id' => 14, 'barangay' => 'Banggolo',              'reg_date' => '2026-04-21', 'child' => 'Naima A. Disomangcop',     'dob' => '2025-11-18', 'age' => '5 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2025-11-20', 'lvl' => 'dose2'],
                        ['id' => 15, 'barangay' => 'Saduc Proper',          'reg_date' => '2026-05-09', 'child' => 'Zaid H. Mimbalawag',       'dob' => '2026-03-01', 'age' => '2 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-03-05', 'lvl' => 'dose1'],
                        ['id' => 16, 'barangay' => 'Calocan East',          'reg_date' => '2026-06-18', 'child' => 'Salma K. Dipatuan',        'dob' => '2025-06-12', 'age' => '12 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2025-06-15', 'lvl' => 'full'],
                        ['id' => 17, 'barangay' => 'Dansalan',              'reg_date' => '2026-07-03', 'child' => 'Omar F. Amerol',           'dob' => '2026-05-10', 'age' => '2 mos',   'sex' => 'Male',   'bcg' => 'NOT YET',  'vax_date' => '',           'lvl' => 'birth'],
                        ['id' => 18, 'barangay' => 'Gadongan',              'reg_date' => '2026-08-19', 'child' => 'Asiyah R. Sarip',          'dob' => '2025-08-14', 'age' => '12 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2025-08-18', 'lvl' => 'full'],
                        ['id' => 19, 'barangay' => 'Mipaga Proper',         'reg_date' => '2026-09-11', 'child' => 'Tariq S. Mangondato',      'dob' => '2026-07-20', 'age' => '2 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-07-24', 'lvl' => 'dose1'],
                        ['id' => 20, 'barangay' => 'Moncado Colony',        'reg_date' => '2026-12-02', 'child' => 'Hafsah L. Pangarungan',    'dob' => '2026-08-15', 'age' => '4 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-08-19', 'lvl' => 'dose2'],
                        ['id' => 21, 'barangay' => 'Poblacion',             'reg_date' => '2025-01-20', 'child' => 'Rayyan D. Munder',         'dob' => '2024-01-15', 'age' => '12 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2024-01-18', 'lvl' => 'full'],
                        ['id' => 22, 'barangay' => 'Tuca',                  'reg_date' => '2025-02-11', 'child' => 'Mariam C. Bansil',         'dob' => '2024-08-05', 'age' => '6 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2024-08-10', 'lvl' => 'dose3'],
                        ['id' => 23, 'barangay' => 'Dulay',                 'reg_date' => '2025-03-17', 'child' => 'Ismail N. Bara',           'dob' => '2024-03-10', 'age' => '12 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2024-03-14', 'lvl' => 'full'],
                        ['id' => 24, 'barangay' => 'Kilala',                'reg_date' => '2025-04-25', 'child' => 'Khadija P. Tomawis',       'dob' => '2024-11-12', 'age' => '5 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2024-11-16', 'lvl' => 'dose2'],
                        ['id' => 25, 'barangay' => 'Matampay',              'reg_date' => '2025-05-14', 'child' => 'Haris G. Balt',            'dob' => '2025-04-20', 'age' => '1 mo',    'sex' => 'Male',   'bcg' => 'NOT YET',  'vax_date' => '',           'lvl' => 'none'],
                        ['id' => 26, 'barangay' => 'Lilod Madaya',          'reg_date' => '2025-06-22', 'child' => 'Zainab M. Tanggol',        'dob' => '2024-06-18', 'age' => '12 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2024-06-20', 'lvl' => 'full'],
                        ['id' => 27, 'barangay' => 'Marinaut',              'reg_date' => '2025-07-08', 'child' => 'Bilal K. Lomondot',        'dob' => '2025-01-05', 'age' => '6 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2025-01-09', 'lvl' => 'dose3'],
                        ['id' => 28, 'barangay' => 'Raya Madaya I',         'reg_date' => '2025-08-30', 'child' => 'Sumayyah A. Pacasum',      'dob' => '2025-06-25', 'age' => '2 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2025-06-29', 'lvl' => 'dose1'],
                        ['id' => 29, 'barangay' => 'Saduc Proper',          'reg_date' => '2025-09-12', 'child' => 'Luqman R. Sangcopan',      'dob' => '2024-09-08', 'age' => '12 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2024-09-12', 'lvl' => 'full'],
                        ['id' => 30, 'barangay' => 'Basak Malutlut',        'reg_date' => '2025-10-19', 'child' => 'Safiya T. Macaumbos',      'dob' => '2025-08-10', 'age' => '2 mos',   'sex' => 'Female', 'bcg' => 'NOT YET',  'vax_date' => '',           'lvl' => 'birth'],
                        ['id' => 31, 'barangay' => 'Banggolo',              'reg_date' => '2024-01-15', 'child' => 'Ammar S. Benito',          'dob' => '2023-01-12', 'age' => '12 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2023-01-15', 'lvl' => 'full'],
                        ['id' => 32, 'barangay' => 'Marawi Central',        'reg_date' => '2024-02-24', 'child' => 'Hana D. Paporo',           'dob' => '2023-08-20', 'age' => '6 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2023-08-25', 'lvl' => 'dose3'],
                        ['id' => 33, 'barangay' => 'Dansalan',              'reg_date' => '2024-03-19', 'child' => 'Idris M. Abbas',           'dob' => '2024-01-10', 'age' => '2 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2024-01-14', 'lvl' => 'dose1'],
                        ['id' => 34, 'barangay' => 'Gadongan',              'reg_date' => '2024-04-28', 'child' => 'Rania F. Abdullah',        'dob' => '2023-04-20', 'age' => '12 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2023-04-24', 'lvl' => 'full'],
                        ['id' => 35, 'barangay' => 'Calocan East',          'reg_date' => '2024-05-16', 'child' => 'Musa H. Cali',             'dob' => '2024-04-02', 'age' => '1 mo',    'sex' => 'Male',   'bcg' => 'NOT YET',  'vax_date' => '',           'lvl' => 'none'],
                        ['id' => 36, 'barangay' => 'Mipaga Proper',         'reg_date' => '2024-06-21', 'child' => 'Yasmin L. Maruhom',        'dob' => '2024-03-15', 'age' => '3 mos',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2024-03-19', 'lvl' => 'dose2'],
                        ['id' => 37, 'barangay' => 'Moncado Colony',        'reg_date' => '2024-07-11', 'child' => 'Anas P. Dima',             'dob' => '2023-07-05', 'age' => '12 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2023-07-09', 'lvl' => 'full'],
                        ['id' => 38, 'barangay' => 'Tuca',                  'reg_date' => '2024-08-25', 'child' => 'Suhaila R. Pundato',       'dob' => '2024-07-20', 'age' => '1 mo',    'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2024-07-24', 'lvl' => 'birth'],
                        ['id' => 39, 'barangay' => 'Poblacion',             'reg_date' => '2024-10-05', 'child' => 'Sulaiman N. Gandamra',     'dob' => '2024-04-01', 'age' => '6 mos',   'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2024-04-05', 'lvl' => 'dose3'],
                        ['id' => 40, 'barangay' => 'Raya Madaya I',         'reg_date' => '2024-11-18', 'child' => 'Ayesha B. Cosain',         'dob' => '2023-11-12', 'age' => '12 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2023-11-16', 'lvl' => 'full'],
                    ];
                    foreach ($tclMockSamples as $row):
                        $isInjected = $row['bcg'] === 'INJECTED';
                        $bcgBadgeClass = $isInjected
                            ? 'bg-emerald-600 text-white border-emerald-700 shadow-xs'
                            : 'bg-rose-600 text-white border-rose-700 shadow-xs';
                        $sexBadgeClass = $row['sex'] === 'Male'
                            ? 'bg-sky-600 text-white border-sky-700 shadow-xs'
                            : 'bg-pink-500 text-white border-pink-600 shadow-xs';
                        $ageFull = preg_replace(
                            ['/\b1 mo\b/i', '/\b(\d+)\s+mos\b/i', '/\b(\d+)\s+days?\b/i'],
                            ['1 month old', '$1 months old', '$1 days old'],
                            $row['age']
                        );
                        $dobTs = strtotime($row['dob']);
                        $regFmt = date('M j, Y', strtotime($row['reg_date']));
                        $regFull = date('F j, Y', strtotime($row['reg_date']));
                        $dobFmt = date('n/j/Y', $dobTs);
                        $dobFull = date('F j, Y', $dobTs);
                        $vaxFmt = $row['vax_date'] ? date('M j, Y', strtotime($row['vax_date'])) : '';
                        $lvl = $row['lvl'] ?? 'none';
                        $hepaInjected = in_array($lvl, ['birth', 'dose1', 'dose2', 'dose3', 'full'], true) && $row['id'] !== 4;
                        $hepaDate = $hepaInjected ? date('M j, Y', $dobTs) : '';
                        $hasDose1 = in_array($lvl, ['dose1', 'dose2', 'dose3', 'full'], true);
                        $hasDose2 = in_array($lvl, ['dose2', 'dose3', 'full'], true);
                        $hasDose3 = in_array($lvl, ['dose3', 'full'], true);
                        $hasIpv2  = ($lvl === 'full');
                        $hasMcv1  = ($lvl === 'full');
                        $hasMcv2  = ($lvl === 'full');
                        $overviewNotes = [
                            1 => "[Oct 4, 2026 • Nurse Rohaida]\n• Birth weight: 3.2 kg (Normal).\n• Exclusive breastfeeding confirmed during 6-week checkup.\n• Scheduled for 2nd dose DPT-HiB-HepB, OPV, and PCV on Nov 28, 2026.",
                            2 => '',
                            3 => "[Oct 14, 2026 • BHW Sittie]\n• Infant delivered at home in Brgy. Banggolo.\n• Parents advised to bring baby to CHO Marawi RHU this Tuesday for catch-up BCG and Hepa B-BD.",
                            4 => "[Nov 10, 2026 • Midwife Amina]\n• Completed 1st and 2nd routine doses with no fever or adverse reaction.\n• Next visit scheduled for 3rd dose PCV & IPV1.",
                            5 => "[Nov 18, 2026 • CHO RHU Team]\n• All 10 primary infant doses (0–6 months) verified complete.\n• Mother reminded to return at 9 months for Measles (MCV1) and Vitamin A.",
                            6 => '',
                            7 => "[Nov 2, 2026 • Nurse Farida]\n• Full primary series completed on schedule.\n• Weight: 7.4 kg • Length: 66 cm • Nutritional status: Normal.",
                            8 => '',
                            9 => '',
                            10 => "[Nov 15, 2026 • Dr. Macarambon]\n• Completed all 10 tracked doses in Immunization I & II.\n• TCL record audited and cleared for FIC cohort tally."
                        ];
                        $imm1Notes = [
                            1 => "[Oct 4, 2026 • Lying-In Clinic]\n• BCG (0.05 mL intradermal, right deltoid) and Hepa B-BD (0.5 mL IM, anterolateral thigh) administered.\n• Normal BCG wheal observed; mother briefed on injection site care.",
                            2 => "[Oct 9, 2026 • Midwife Norhata]\n• Both at-birth vaccines given within 24 hours of delivery.\n• No swelling or pustule complications reported.",
                            3 => '',
                            4 => "[Oct 19, 2026 • CHO Immunization Desk]\n• BCG administered at RHU. Mother lost initial birthing home slip for Hepa B-BD; follow-up verification in progress.",
                            5 => "[Oct 22, 2026 • Nurse Rohaida]\n• Verified BCG scar present on right arm.\n• Hepa B-BD birth dose confirmed from hospital discharge record.",
                            6 => "[Oct 27, 2026 • BHW Station]\n• Hepa B-BD administered at birth.\n• BCG deferred because multi-dose vial session is scheduled every Wednesday.",
                            7 => '',
                            8 => "[Nov 6, 2026 • RHU Registry]\n• At-birth BCG and Hepa B-BD dates cross-checked with Mother & Child Book (ECCD Card).",
                            9 => '',
                            10 => "[Nov 15, 2026 • CHO Audit]\n• Both at-birth antigens verified complete and encoded in TCL master database."
                        ];
                        $tclRecord = [
                            'id' => $row['id'],
                            'child' => $row['child'],
                            'barangay' => $row['barangay'],
                            'reg_date' => $row['reg_date'],
                            'reg_date_fmt' => $regFmt,
                            'reg_date_full' => $regFull,
                            'dob' => $row['dob'],
                            'dob_fmt' => $dobFmt,
                            'dob_full' => $dobFull,
                            'age' => $row['age'],
                            'age_full' => $ageFull,
                            'sex' => strtoupper($row['sex']),
                            'overview_note' => $overviewNotes[$row['id']] ?? '',
                            'imm1_note' => $imm1Notes[$row['id']] ?? '',
                            'imm1' => [
                                'bcg' => ['status' => $row['bcg'], 'date' => $vaxFmt, 'iso' => $row['vax_date']],
                                'hepa' => ['status' => $hepaInjected ? 'INJECTED' : 'NOT YET', 'date' => $hepaDate, 'iso' => $hepaInjected ? $row['dob'] : ''],
                            ],
                            'imm2' => [
                                'dpt' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose1 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose1 ? date('M j, Y', strtotime('+6 weeks', $dobTs)) : '', 'iso' => $hasDose1 ? date('Y-m-d', strtotime('+6 weeks', $dobTs)) : ''],
                                    ['dose' => '2nd Dose', 'status' => $hasDose2 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose2 ? date('M j, Y', strtotime('+10 weeks', $dobTs)) : '', 'iso' => $hasDose2 ? date('Y-m-d', strtotime('+10 weeks', $dobTs)) : ''],
                                    ['dose' => '3rd Dose', 'status' => $hasDose3 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose3 ? date('M j, Y', strtotime('+14 weeks', $dobTs)) : '', 'iso' => $hasDose3 ? date('Y-m-d', strtotime('+14 weeks', $dobTs)) : ''],
                                ],
                                'opv' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose1 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose1 ? date('M j, Y', strtotime('+6 weeks', $dobTs)) : '', 'iso' => $hasDose1 ? date('Y-m-d', strtotime('+6 weeks', $dobTs)) : ''],
                                    ['dose' => '2nd Dose', 'status' => $hasDose2 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose2 ? date('M j, Y', strtotime('+10 weeks', $dobTs)) : '', 'iso' => $hasDose2 ? date('Y-m-d', strtotime('+10 weeks', $dobTs)) : ''],
                                    ['dose' => '3rd Dose', 'status' => $hasDose3 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose3 ? date('M j, Y', strtotime('+14 weeks', $dobTs)) : '', 'iso' => $hasDose3 ? date('Y-m-d', strtotime('+14 weeks', $dobTs)) : ''],
                                ],
                                'pcv' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose1 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose1 ? date('M j, Y', strtotime('+6 weeks', $dobTs)) : '', 'iso' => $hasDose1 ? date('Y-m-d', strtotime('+6 weeks', $dobTs)) : ''],
                                    ['dose' => '2nd Dose', 'status' => $hasDose2 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose2 ? date('M j, Y', strtotime('+10 weeks', $dobTs)) : '', 'iso' => $hasDose2 ? date('Y-m-d', strtotime('+10 weeks', $dobTs)) : ''],
                                    ['dose' => '3rd Dose', 'status' => $hasDose3 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose3 ? date('M j, Y', strtotime('+14 weeks', $dobTs)) : '', 'iso' => $hasDose3 ? date('Y-m-d', strtotime('+14 weeks', $dobTs)) : ''],
                                ],
                                'ipv' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose3 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose3 ? date('M j, Y', strtotime('+14 weeks', $dobTs)) : '', 'iso' => $hasDose3 ? date('Y-m-d', strtotime('+14 weeks', $dobTs)) : ''],
                                    ['dose' => '2nd Dose', 'status' => $hasIpv2 ? 'INJECTED' : 'NOT YET', 'date' => $hasIpv2 ? date('M j, Y', strtotime('+9 months', $dobTs)) : '', 'iso' => $hasIpv2 ? date('Y-m-d', strtotime('+9 months', $dobTs)) : ''],
                                ],
                                'mcv1' => [
                                    ['dose' => '1st Dose', 'status' => $hasMcv1 ? 'INJECTED' : 'NOT YET', 'date' => $hasMcv1 ? date('M j, Y', strtotime('+9 months', $dobTs)) : '', 'iso' => $hasMcv1 ? date('Y-m-d', strtotime('+9 months', $dobTs)) : ''],
                                ],
                                'mcv2' => [
                                    ['dose' => '1st Dose', 'status' => $hasMcv2 ? 'INJECTED' : 'NOT YET', 'date' => $hasMcv2 ? date('M j, Y', strtotime('+12 months', $dobTs)) : '', 'iso' => $hasMcv2 ? date('Y-m-d', strtotime('+12 months', $dobTs)) : ''],
                                ],
                            ],
                        ];
                    ?>
                    <tr class="group hover:bg-sky-100 hover:shadow-sm transition-all duration-150 cursor-pointer"
                        data-row-id="<?= (int) $row['id'] ?>"
                        data-barangay="<?= e($row['barangay']) ?>"
                        data-bcg="<?= e($row['bcg']) ?>"
                        data-sex="<?= e(strtolower($row['sex'])) ?>"
                        data-tcl-record="<?= e(json_encode($tclRecord, JSON_UNESCAPED_UNICODE)) ?>">
                        <td class="px-4 py-3 font-mono text-xs font-bold text-slate-500 group-hover:text-slate-800 whitespace-nowrap">
                            <?= $row['id'] ?>
                        </td>
                        <td class="px-4 py-3 bg-slate-50/60 group-hover:bg-transparent font-semibold text-slate-800 whitespace-nowrap">
                            <?= e($row['barangay']) ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span id="tcl-reg-date-<?= $row['id'] ?>-2"
                                  data-tooltip-target="tcl-reg-tooltip-<?= $row['id'] ?>-2"
                                  data-tooltip-placement="top"
                                  class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200 cursor-help">
                                <span class="w-1.5 h-1.5 rounded-full bg-cho-blue-700"></span>
                                <?= e(date('M j, Y', strtotime($row['reg_date']))) ?>
                            </span>
                            <div id="tcl-reg-tooltip-<?= $row['id'] ?>-2"
                                 role="tooltip"
                                 class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">
                                <?= e(date('F j, Y', strtotime($row['reg_date']))) ?>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                        <td class="px-4 py-3 bg-slate-50/60 group-hover:bg-transparent font-bold text-slate-900 whitespace-nowrap">
                            <?= e($row['child']) ?>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-600 whitespace-nowrap">
                            <span id="tcl-dob-text-<?= $row['id'] ?>-2"
                                  data-tooltip-target="tcl-dob-tooltip-<?= $row['id'] ?>-2"
                                  data-tooltip-placement="top"
                                  class="inline-flex items-center underline decoration-dotted decoration-slate-400 underline-offset-4 cursor-help">
                                <?= e(date('n/j/Y', strtotime($row['dob']))) ?>
                            </span>
                            <div id="tcl-dob-tooltip-<?= $row['id'] ?>-2"
                                 role="tooltip"
                                 class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">
                                <?= e(date('F j, Y', strtotime($row['dob']))) ?>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                        <td class="px-4 py-3 bg-slate-50/60 group-hover:bg-transparent text-xs font-semibold text-slate-700 whitespace-nowrap">
                            <span id="tcl-age-text-<?= $row['id'] ?>-2"
                                  data-tooltip-target="tcl-age-tooltip-<?= $row['id'] ?>-2"
                                  data-tooltip-placement="top"
                                  class="inline-flex items-center underline decoration-dotted decoration-slate-400 underline-offset-4 cursor-help">
                                <?= e($row['age']) ?>
                            </span>
                            <div id="tcl-age-tooltip-<?= $row['id'] ?>-2"
                                 role="tooltip"
                                 class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">
                                <?= e($ageFull) ?>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <?php $isMale = $row['sex'] === 'Male'; ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wide rounded-md border <?= $sexBadgeClass ?>">
                                <?php if ($isMale): ?>
                                    <svg class="w-3.5 h-3.5 shrink-0 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                                        <path d="M19 5l-5.4 5.4" />
                                        <path d="M19 5h-5" />
                                        <path d="M19 5v5" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-3.5 h-3.5 shrink-0 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7 9a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                                        <path d="M12 14v7" />
                                        <path d="M9 18h6" />
                                    </svg>
                                <?php endif; ?>
                                <?= e(strtoupper($row['sex'])) ?>
                            </span>
                        </td>
                        <td class="px-4 py-2.5 bg-slate-50/60 group-hover:bg-transparent text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center gap-3">
                                <span id="tcl-bcg-badge-<?= $row['id'] ?>-2"
                                      class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-extrabold tracking-wide rounded-full border <?= $bcgBadgeClass ?>">
                                    <?= e($row['bcg']) ?>
                                </span>
                                <div class="pl-3 border-l border-slate-200 text-center">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-none mb-1">
                                        Date of Vaccination
                                    </div>
                                    <?php if (!empty($row['vax_date'])): ?>
                                        <span id="tcl-vax-date-<?= $row['id'] ?>-2"
                                              data-tooltip-target="tcl-vax-tooltip-<?= $row['id'] ?>-2"
                                              data-tooltip-placement="top"
                                              class="text-xs font-semibold text-slate-800 underline decoration-dotted decoration-slate-400 underline-offset-4 cursor-help">
                                            <?= e(date('M j, Y', strtotime($row['vax_date']))) ?>
                                        </span>
                                        <div id="tcl-vax-tooltip-<?= $row['id'] ?>-2"
                                             role="tooltip"
                                             class="absolute z-30 invisible inline-block px-3 py-1.5 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">
                                            <?= e(date('F j, Y', strtotime($row['vax_date']))) ?>
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    <?php else: ?>
                                        <span id="tcl-vax-date-<?= $row['id'] ?>-2" class="text-xs font-medium text-slate-400">
                                            &mdash;
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center gap-1.5">
                                <button type="button"
                                        id="tcl-btn-edit-<?= $row['id'] ?>-2"
                                        data-tooltip-target="tcl-edit-tooltip-<?= $row['id'] ?>-2"
                                        data-tooltip-placement="top"
                                        aria-label="Edit record"
                                        class="p-1.5 text-slate-500 hover:text-cho-blue-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <div id="tcl-edit-tooltip-<?= $row['id'] ?>-2"
                                     role="tooltip"
                                     class="absolute z-30 invisible inline-block px-2.5 py-1 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Edit Record
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                                <button type="button"
                                        id="tcl-btn-delete-<?= $row['id'] ?>-2"
                                        data-tooltip-target="tcl-delete-tooltip-<?= $row['id'] ?>-2"
                                        data-tooltip-placement="top"
                                        aria-label="Delete record"
                                        class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <div id="tcl-delete-tooltip-<?= $row['id'] ?>-2"
                                     role="tooltip"
                                     class="absolute z-30 invisible inline-block px-2.5 py-1 text-xs font-semibold text-white transition-opacity duration-200 bg-slate-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Delete Record
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Infinite Scroll Sentinel -->
            <div id="cho-nip-pop-scroll-sentinel2" class="h-4 w-full"></div>
        </div>

        <!-- Table Footer Status -->
        <div id="cho-nip-pop-footer2" class="px-4 py-3 border-t border-slate-200/90 bg-slate-50/70 rounded-b-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-slate-500">
            <span id="cho-nip-pop-count-status2" class="font-medium">Showing 40 of 40 infant records</span>
            <span id="cho-nip-pop-source-status2" class="text-[11px] text-slate-400">Source: Target Client List (40 Mock Samples)</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchEl = document.getElementById('cho-nip-pop-search2');
            const clearEl = document.getElementById('cho-nip-pop-search-clear2');
            const filterBtnEl = document.getElementById('cho-nip-pop-filter2');
            const filterLabelEl = document.getElementById('cho-nip-pop-filter-label2');
            const filterDropdownEl = document.getElementById('cho-nip-pop-filter-dropdown2');
            const brgyDropdownEl = document.getElementById('cho-nip-pop-brgy-dropdown2');
            const brgySearchEl = document.getElementById('cho-nip-pop-brgy-search2');
            const brgyListEl = document.getElementById('cho-nip-pop-brgy-list2');
            const brgyClearEl = document.getElementById('cho-nip-pop-brgy-clear2');
            const genderDropdownEl = document.getElementById('cho-nip-pop-gender-dropdown2');
            const immDropdownBtnEl = document.getElementById('cho-nip-pop-imm-dropdown-btn2');
            const immDropdownEl = document.getElementById('cho-nip-pop-imm-dropdown2');
            const orderLabelEl = document.getElementById('cho-nip-pop-order-label2');
            const orderDropdownEl = document.getElementById('cho-nip-pop-order-dropdown2');
            const dateDropdownEl = document.getElementById('cho-nip-pop-date-dropdown2');
            const dateImmEl = document.getElementById('cho-nip-pop-date-imm2');
            const dateInputEl = document.getElementById('cho-nip-pop-date-input2');
            const dateApplyEl = document.getElementById('cho-nip-pop-date-apply2');
            const dateClearEl = document.getElementById('cho-nip-pop-date-clear2');
            const tbodyEl = document.getElementById('cho-nip-pop-tbody2');
            const countEl = document.getElementById('cho-nip-pop-count-status2');
            if (!tbodyEl) return;

            let activeFilter = 'all';
            let activeBarangay = '';
            let activeOrder = 'asc';
            let activeDateImm = 'any';
            let activeDateIso = '';

            const isRecordFullyCompleted = (tr) => {
                try {
                    const rec = JSON.parse(tr.dataset.tclRecord || '{}');
                    const doses = [
                        rec.imm1?.bcg,
                        rec.imm1?.hepa,
                        ...(rec.imm2?.dpt || []),
                        ...(rec.imm2?.opv || []),
                        ...(rec.imm2?.pcv || []),
                        ...(rec.imm2?.ipv || []),
                        ...(rec.imm2?.mcv1 || []),
                        ...(rec.imm2?.mcv2 || [])
                    ].filter(Boolean);
                    return doses.length > 0 && doses.every((d) => d.status === 'INJECTED');
                } catch {
                    return false;
                }
            };

            const computeStatsForRows = (rows) => {
                const total = rows.length;
                const fullyCompleted = rows.filter(isRecordFullyCompleted).length;
                const male = rows.filter((r) => r.dataset.sex === 'male').length;
                const female = rows.filter((r) => r.dataset.sex === 'female').length;
                const notYetCompleted = total - fullyCompleted;
                return { total, fullyCompleted, male, female, notYetCompleted };
            };

            const getRowBarangay = (tr) => {
                if (tr.dataset.barangay) return tr.dataset.barangay;
                try {
                    return JSON.parse(tr.dataset.tclRecord || '{}').barangay || '';
                } catch {
                    return '';
                }
            };

            const renderBarangayList = (query = '') => {
                if (!brgyListEl) return;
                const rows = Array.from(tbodyEl.querySelectorAll('tr'));
                const counts = new Map();
                rows.forEach((tr) => {
                    const b = getRowBarangay(tr).trim();
                    if (b) counts.set(b, (counts.get(b) || 0) + 1);
                });

                const q = query.trim().toLowerCase();
                const items = Array.from(counts.entries())
                    .sort((a, b) => a[0].localeCompare(b[0]))
                    .filter(([name]) => !q || name.toLowerCase().includes(q));

                if (items.length === 0) {
                    brgyListEl.innerHTML = `<li class="px-2 py-3 text-center text-xs text-slate-400">No matching barangay found</li>`;
                    return;
                }

                brgyListEl.innerHTML = items.map(([name, cnt]) => {
                    const checked = activeFilter === 'barangay' && activeBarangay.toLowerCase() === name.toLowerCase();
                    return `
                        <li>
                            <button type="button"
                                    data-brgy-filter="${name.replace(/"/g, '&quot;')}"
                                    class="w-full flex items-center justify-between p-2 rounded-lg text-left cursor-pointer transition-colors ${checked ? 'bg-cho-blue-50 text-cho-blue-900 font-bold' : 'hover:bg-slate-100 text-slate-700'}">
                                <span class="truncate pr-2">${name}</span>
                                <span class="inline-flex items-center gap-1.5 shrink-0">
                                    <span class="text-[11px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold">${cnt}</span>
                                    <input type="checkbox" ${checked ? 'checked' : ''} tabindex="-1" class="w-4 h-4 text-cho-blue-800 border-slate-300 rounded pointer-events-none" />
                                </span>
                            </button>
                        </li>
                    `;
                }).join('');

                brgyListEl.querySelectorAll('[data-brgy-filter]').forEach((btn) => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        activeFilter = 'barangay';
                        activeBarangay = btn.dataset.brgyFilter || '';
                        if (filterLabelEl) filterLabelEl.textContent = `Brgy: ${activeBarangay}`;
                        brgyDropdownEl?.classList.add('hidden');
                        filterDropdownEl?.classList.add('hidden');
                        applyFilter();
                        hydrateStats();
                        renderBarangayList(brgySearchEl?.value || '');
                    });
                });
            };

            const applyOrder = () => {
                const mode = activeOrder || 'asc';
                const rows = Array.from(tbodyEl.querySelectorAll('tr'));
                rows.sort((a, b) => {
                    const idA = Number(a.dataset.rowId || 0);
                    const idB = Number(b.dataset.rowId || 0);
                    return mode === 'newest' ? idB - idA : idA - idB;
                });
                rows.forEach((tr) => tbodyEl.appendChild(tr));
            };

            const matchesDateAndImm = (tr) => {
                if (!activeDateIso) return true;
                try {
                    const rec = JSON.parse(tr.dataset.tclRecord || '{}');
                    const checkDoseArr = (arr) => Array.isArray(arr) && arr.some((d) => d?.iso === activeDateIso);
                    switch (activeDateImm) {
                        case 'reg_date': return rec.reg_date === activeDateIso;
                        case 'bcg': return rec.imm1?.bcg?.iso === activeDateIso;
                        case 'hepa': return rec.imm1?.hepa?.iso === activeDateIso;
                        case 'dpt': return checkDoseArr(rec.imm2?.dpt);
                        case 'opv': return checkDoseArr(rec.imm2?.opv);
                        case 'pcv': return checkDoseArr(rec.imm2?.pcv);
                        case 'ipv': return checkDoseArr(rec.imm2?.ipv);
                        case 'mcv1': return checkDoseArr(rec.imm2?.mcv1);
                        case 'mcv2': return checkDoseArr(rec.imm2?.mcv2);
                        case 'any':
                        default:
                            return (
                                rec.reg_date === activeDateIso ||
                                rec.imm1?.bcg?.iso === activeDateIso ||
                                rec.imm1?.hepa?.iso === activeDateIso ||
                                checkDoseArr(rec.imm2?.dpt) ||
                                checkDoseArr(rec.imm2?.opv) ||
                                checkDoseArr(rec.imm2?.pcv) ||
                                checkDoseArr(rec.imm2?.ipv) ||
                                checkDoseArr(rec.imm2?.mcv1) ||
                                checkDoseArr(rec.imm2?.mcv2)
                            );
                    }
                } catch {
                    return false;
                }
            };

            const applyFilter = () => {
                const rows = Array.from(tbodyEl.querySelectorAll('tr'));
                const q = (searchEl?.value || '').trim().toLowerCase();
                if (clearEl) clearEl.classList.toggle('hidden', q.length === 0);

                let visible = 0;
                rows.forEach((tr) => {
                    const text = tr.textContent.toLowerCase();
                    const bcg = tr.dataset.bcg || '';
                    const sex = tr.dataset.sex || '';
                    const brgy = getRowBarangay(tr).toLowerCase();
                    const matchQuery = !q || text.includes(q);
                    const matchFilter =
                        activeFilter === 'all' ||
                        (activeFilter === 'injected' && bcg === 'INJECTED') ||
                        (activeFilter === 'not_yet' && bcg === 'NOT YET') ||
                        (activeFilter === 'male' && sex === 'male') ||
                        (activeFilter === 'female' && sex === 'female') ||
                        (activeFilter === 'barangay' && brgy === activeBarangay.toLowerCase());
                    const matchDate = matchesDateAndImm(tr);

                    const show = matchQuery && matchFilter && matchDate;
                    tr.classList.toggle('hidden', !show);
                    if (show) visible++;
                });

                if (countEl) countEl.textContent = `Showing ${visible} of ${rows.length} infant records`;
            };

            const hydrateStats = () => {
                const allRows = Array.from(tbodyEl.querySelectorAll('tr'));
                const globalStats = computeStatsForRows(allRows);
                const allRecords = allRows.map((tr) => {
                    try {
                        return JSON.parse(tr.dataset.tclRecord || '{}');
                    } catch {
                        return null;
                    }
                }).filter(Boolean);
                try {
                    localStorage.setItem('cho_nip2_global_tcl_stats', JSON.stringify(globalStats));
                    localStorage.setItem('cho_nip2_tcl_records', JSON.stringify(allRecords));
                } catch {}

                const scopedRows = activeFilter === 'barangay' && activeBarangay
                    ? allRows.filter((tr) => getRowBarangay(tr).toLowerCase() === activeBarangay.toLowerCase())
                    : allRows;
                const scopedStats = computeStatsForRows(scopedRows);

                const setStat = (id, val) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = Number(val).toLocaleString();
                };
                setStat('cho-nip-pop-stat-records-val2', scopedStats.fullyCompleted);
                setStat('cho-nip-pop-stat-total-val2', scopedStats.male);
                setStat('cho-nip-pop-stat-households-val2', scopedStats.female);
                setStat('cho-nip-pop-stat-target-val2', scopedStats.notYetCompleted);
            };

            const closeFilterMenus = () => {
                brgyDropdownEl?.classList.add('hidden');
                genderDropdownEl?.classList.add('hidden');
                immDropdownEl?.classList.add('hidden');
                filterDropdownEl?.classList.add('hidden');
            };

            document.querySelectorAll('.cho-filter-opt2').forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    activeFilter = btn.dataset.filterValue || 'all';
                    activeBarangay = '';
                    if (filterLabelEl) filterLabelEl.textContent = btn.dataset.filterLabel || 'All Infants';
                    closeFilterMenus();
                    applyFilter();
                    hydrateStats();
                    renderBarangayList(brgySearchEl?.value || '');
                });
            });

            immDropdownBtnEl?.addEventListener('click', () => {
                activeFilter = 'all';
                activeBarangay = '';
                if (filterLabelEl) filterLabelEl.textContent = 'Immunizations: All';
                applyFilter();
                hydrateStats();
            });

            brgySearchEl?.addEventListener('input', () => {
                renderBarangayList(brgySearchEl.value);
            });
            brgySearchEl?.addEventListener('click', (e) => e.stopPropagation());

            brgyClearEl?.addEventListener('click', (e) => {
                e.stopPropagation();
                activeFilter = 'all';
                activeBarangay = '';
                if (brgySearchEl) brgySearchEl.value = '';
                if (filterLabelEl) filterLabelEl.textContent = 'All Infants';
                closeFilterMenus();
                applyFilter();
                hydrateStats();
                renderBarangayList('');
            });

            document.querySelectorAll('.cho-order-opt2').forEach((btn) => {
                btn.addEventListener('click', () => {
                    activeOrder = btn.dataset.orderValue || 'asc';
                    if (orderLabelEl && !activeDateIso) {
                        orderLabelEl.textContent = btn.dataset.orderLabel || 'By # (1 → N)';
                    }
                    dateDropdownEl?.classList.add('hidden');
                    orderDropdownEl?.classList.add('hidden');
                    applyOrder();
                    applyFilter();
                });
            });

            dateDropdownEl?.addEventListener('click', (e) => e.stopPropagation());

            dateApplyEl?.addEventListener('click', () => {
                activeDateImm = dateImmEl?.value || 'any';
                activeDateIso = dateInputEl?.value || '';
                if (orderLabelEl) {
                    if (activeDateIso) {
                        const immText = dateImmEl?.options[dateImmEl.selectedIndex]?.text || 'Date';
                        orderLabelEl.textContent = `${immText}: ${activeDateIso}`;
                    } else {
                        orderLabelEl.textContent = activeOrder === 'newest' ? 'Newly Added First' : 'By # (1 → N)';
                    }
                }
                dateDropdownEl?.classList.add('hidden');
                orderDropdownEl?.classList.add('hidden');
                applyFilter();
            });

            dateClearEl?.addEventListener('click', () => {
                activeDateImm = 'any';
                activeDateIso = '';
                if (dateImmEl) dateImmEl.value = 'any';
                if (dateInputEl) dateInputEl.value = '';
                if (orderLabelEl) {
                    orderLabelEl.textContent = activeOrder === 'newest' ? 'Newly Added First' : 'By # (1 → N)';
                }
                dateDropdownEl?.classList.add('hidden');
                orderDropdownEl?.classList.add('hidden');
                applyFilter();
            });

            window.CHO_TCL_REFRESH_TABLE2 = () => {
                applyOrder();
                applyFilter();
                hydrateStats();
                renderBarangayList(brgySearchEl?.value || '');
            };

            renderBarangayList('');
            setTimeout(hydrateStats, 220);

            searchEl?.addEventListener('input', applyFilter);
            clearEl?.addEventListener('click', () => {
                if (searchEl) searchEl.value = '';
                applyFilter();
            });
        });
    </script>

</div>

<!-- =============================================================================
     FLOWBITE RIGHT DRAWER 1: ADD BARANGAY POPULATION ENTRY
     ============================================================================= -->
<div id="drawer-nip-pop-add2"
     data-cho-nip-drawer
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 rounded-l-2xl w-full sm:w-[520px] md:w-[560px] max-w-full shadow-2xl flex flex-col justify-between"
     tabindex="-1"
     aria-labelledby="drawer-nip-pop-add-label2"
     aria-hidden="true">

    <div class="space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h5 id="drawer-nip-pop-add-label2" class="inline-flex items-center gap-2 text-lg sm:text-xl font-bold text-slate-900">
                <svg class="w-5 h-5 text-cho-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Infant / Child Entry
            </h5>
            <button type="button"
                    id="tcl-add-close"
                    data-cho-drawer-hide="drawer-nip-pop-add2"
                    aria-controls="drawer-nip-pop-add2"
                    class="text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Adds a new infant or child record (0&ndash;12 months) to the <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700 font-mono">Target Client List</code> registry.
        </p>

        <form id="nip-pop-add-form2" class="space-y-3.5">
            <div>
                <label for="tcl-add-child" class="block mb-1.5 text-sm font-semibold text-slate-700">Name of Child</label>
                <input type="text" id="tcl-add-child" placeholder="e.g. Al-Amin M. Macaraya" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
            </div>

            <!-- Searchable Marawi City Barangay with Flowbite Dropdown -->
            <div class="relative">
                <label for="tcl-add-barangay" class="block mb-1.5 text-sm font-semibold text-slate-700">Barangay / Locality (Marawi City)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           id="tcl-add-barangay"
                           autocomplete="off"
                           placeholder="Search 96 Marawi City barangays..."
                           required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full ps-9 pe-8 py-2" />
                    <button type="button"
                            id="tcl-brgy-toggle"
                            aria-label="Toggle barangay list"
                            class="absolute inset-y-0 end-0 flex items-center pe-2.5 text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                <div id="tcl-brgy-dropdown"
                     class="hidden absolute left-0 right-0 mt-1 z-30 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto divide-y divide-slate-100">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="tcl-add-reg-date" class="block mb-1.5 text-sm font-semibold text-slate-700">Date of Registration</label>
                    <input type="date" id="tcl-add-reg-date" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
                <div>
                    <label for="tcl-add-dob" class="block mb-1.5 text-sm font-semibold text-slate-700">Date of Birth</label>
                    <input type="date" id="tcl-add-dob" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
            </div>

            <!-- Sex Flowbite Dropdown with Colored SVG Icon -->
            <div class="relative">
                <label for="tcl-sex-btn" class="block mb-1.5 text-sm font-semibold text-slate-700">Sex</label>
                <input type="hidden" id="tcl-add-sex" value="Male" />
                <button type="button"
                        id="tcl-sex-btn"
                        aria-haspopup="listbox"
                        aria-expanded="false"
                        class="w-full flex items-center justify-between bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 px-3 py-2 cursor-pointer">
                    <span id="tcl-sex-display" class="inline-flex items-center gap-2 font-medium text-slate-900">
                        <svg class="w-4 h-4 text-sky-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                            <path d="M19 5l-5.4 5.4" />
                            <path d="M19 5h-5" />
                            <path d="M19 5v5" />
                        </svg>
                        <span>Male</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="tcl-sex-dropdown"
                     role="listbox"
                     class="hidden absolute left-0 right-0 mt-1 z-30 bg-white border border-slate-200 rounded-lg shadow-lg p-1 space-y-0.5">
                    <button type="button" id="tcl-sex-opt-male" data-sex-value="Male"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-800 hover:bg-slate-100 rounded-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-sky-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                            <path d="M19 5l-5.4 5.4" />
                            <path d="M19 5h-5" />
                            <path d="M19 5v5" />
                        </svg>
                        <span>Male</span>
                    </button>
                    <button type="button" id="tcl-sex-opt-female" data-sex-value="Female"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-800 hover:bg-slate-100 rounded-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-pink-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 9a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                            <path d="M12 14v7" />
                            <path d="M9 18h6" />
                        </svg>
                        <span>Female</span>
                    </button>
                </div>
            </div>

            <!-- At-Birth Vaccines -->
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">At-Birth Vaccines</span>
                    <span class="text-[11px] font-medium text-slate-400">Immunization I</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label for="tcl-add-bcg" class="block mb-1.5 text-sm font-semibold text-slate-700">BCG Status</label>
                        <select id="tcl-add-bcg"
                                class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5">
                            <option value="NOT YET">NOT YET</option>
                            <option value="INJECTED">INJECTED</option>
                        </select>
                    </div>
                    <div>
                        <label for="tcl-add-vax-date" class="block mb-1.5 text-sm font-semibold text-slate-700">BCG Date</label>
                        <input type="date" id="tcl-add-vax-date"
                               class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                    </div>
                </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label for="tcl-add-hepa" class="block mb-1.5 text-sm font-semibold text-slate-700">Hepa - B Status</label>
                            <select id="tcl-add-hepa"
                                    class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-hepa-date" class="block mb-1.5 text-sm font-semibold text-slate-700">Hepa - B Date</label>
                            <input type="date" id="tcl-add-hepa-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                        </div>
                    </div>
            </div>

            <!-- Routine Infant Doses -->
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Routine Infant Doses</span>
                    <span class="text-[11px] font-medium text-slate-400">Immunization II</span>
                </div>

                <!-- DPT-HiB-HepB -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">DPT-HiB-HepB</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-dpt1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-add-dpt1"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-dpt1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-add-dpt1-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-dpt2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-add-dpt2"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-dpt2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-add-dpt2-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-dpt3" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Status</label>
                            <select id="tcl-add-dpt3"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-dpt3-date" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Date</label>
                            <input type="date" id="tcl-add-dpt3-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- OPV -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">OPV</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-opv1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-add-opv1"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-opv1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-add-opv1-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-opv2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-add-opv2"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-opv2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-add-opv2-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-opv3" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Status</label>
                            <select id="tcl-add-opv3"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-opv3-date" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Date</label>
                            <input type="date" id="tcl-add-opv3-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- PCV -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">PCV</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-pcv1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-add-pcv1"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-pcv1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-add-pcv1-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-pcv2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-add-pcv2"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-pcv2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-add-pcv2-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-pcv3" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Status</label>
                            <select id="tcl-add-pcv3"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-pcv3-date" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Date</label>
                            <input type="date" id="tcl-add-pcv3-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- IPV -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">IPV</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-ipv1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-add-ipv1"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-ipv1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-add-ipv1-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-ipv2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-add-ipv2"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-ipv2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-add-ipv2-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- MCV1 (9 mos) -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">MCV1 (9 mos)</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-mcv1" class="block mb-1 text-xs font-semibold text-slate-600">Dose Status</label>
                            <select id="tcl-add-mcv1"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-mcv1-date" class="block mb-1 text-xs font-semibold text-slate-600">Dose Date</label>
                            <input type="date" id="tcl-add-mcv1-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- MCV2 (12 mos) -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">MCV2 (12 mos)</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-add-mcv2" class="block mb-1 text-xs font-semibold text-slate-600">Dose Status</label>
                            <select id="tcl-add-mcv2"
                                    class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-add-mcv2-date" class="block mb-1 text-xs font-semibold text-slate-600">Dose Date</label>
                            <input type="date" id="tcl-add-mcv2-date"
                                   class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                    id="nip-btn-submit-pop-add2"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 text-base font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-add-spinner2" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-5 h-5 text-cho-blue-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="nip-btn-submit-pop-add-text2">Add Child Record</span>
            </button>
        </form>
    </div>

    <div class="border-t border-slate-200 pt-3 text-xs text-slate-400 text-center">
        City Health Office Marawi &bull; Target Client List (0&ndash;12 Months)
    </div>
</div>

<!-- =============================================================================
     FLOWBITE RIGHT DRAWER 2: EDIT INFANT / CHILD ENTRY (CLONE OF ADD DRAWER)
     ============================================================================= -->
<div id="drawer-nip-pop-edit2"
     data-cho-nip-drawer
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 rounded-l-2xl w-full sm:w-[520px] md:w-[560px] max-w-full shadow-2xl flex flex-col justify-between"
     tabindex="-1"
     aria-labelledby="drawer-nip-pop-edit-label2"
     aria-hidden="true">

    <div class="space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h5 id="drawer-nip-pop-edit-label2" class="inline-flex items-center gap-2 text-lg sm:text-xl font-bold text-slate-900">
                <svg class="w-5 h-5 text-cho-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Infant / Child Entry
            </h5>
            <button type="button"
                    id="tcl-edit-close"
                    data-cho-drawer-hide="drawer-nip-pop-edit2"
                    aria-controls="drawer-nip-pop-edit2"
                    class="text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Updates child details &amp; immunization doses for <span id="nip-pop-edit-row-badge2" class="font-semibold text-slate-700">Record #--</span> in the Target Client List.
        </p>

        <form id="nip-pop-edit-form2" class="space-y-3.5">
            <input type="hidden" id="tcl-edit-row-id" />

            <div>
                <label for="tcl-edit-child" class="block mb-1.5 text-sm font-semibold text-slate-700">Name of Child</label>
                <input type="text" id="tcl-edit-child" placeholder="e.g. Al-Amin M. Macaraya" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
            </div>

            <!-- Searchable Marawi City Barangay with Flowbite Dropdown -->
            <div class="relative">
                <label for="tcl-edit-barangay" class="block mb-1.5 text-sm font-semibold text-slate-700">Barangay / Locality (Marawi City)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           id="tcl-edit-barangay"
                           autocomplete="off"
                           placeholder="Search 96 Marawi City barangays..."
                           required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full ps-9 pe-8 py-2" />
                    <button type="button"
                            id="tcl-edit-brgy-toggle"
                            aria-label="Toggle barangay list"
                            class="absolute inset-y-0 end-0 flex items-center pe-2.5 text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                <div id="tcl-edit-brgy-dropdown"
                     class="hidden absolute left-0 right-0 mt-1 z-30 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto divide-y divide-slate-100">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="tcl-edit-reg-date" class="block mb-1.5 text-sm font-semibold text-slate-700">Date of Registration</label>
                    <input type="date" id="tcl-edit-reg-date" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
                <div>
                    <label for="tcl-edit-dob" class="block mb-1.5 text-sm font-semibold text-slate-700">Date of Birth</label>
                    <input type="date" id="tcl-edit-dob" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
            </div>

            <!-- Sex Flowbite Dropdown with Colored SVG Icon -->
            <div class="relative">
                <label for="tcl-edit-sex-btn" class="block mb-1.5 text-sm font-semibold text-slate-700">Sex</label>
                <input type="hidden" id="tcl-edit-sex" value="Male" />
                <button type="button"
                        id="tcl-edit-sex-btn"
                        aria-haspopup="listbox"
                        aria-expanded="false"
                        class="w-full flex items-center justify-between bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 px-3 py-2 cursor-pointer">
                    <span id="tcl-edit-sex-display" class="inline-flex items-center gap-2 font-medium text-slate-900">
                        <svg class="w-4 h-4 text-sky-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                            <path d="M19 5l-5.4 5.4" />
                            <path d="M19 5h-5" />
                            <path d="M19 5v5" />
                        </svg>
                        <span>Male</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="tcl-edit-sex-dropdown"
                     role="listbox"
                     class="hidden absolute left-0 right-0 mt-1 z-30 bg-white border border-slate-200 rounded-lg shadow-lg p-1 space-y-0.5">
                    <button type="button" id="tcl-edit-sex-opt-male" data-sex-value="Male"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-800 hover:bg-slate-100 rounded-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-sky-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                            <path d="M19 5l-5.4 5.4" />
                            <path d="M19 5h-5" />
                            <path d="M19 5v5" />
                        </svg>
                        <span>Male</span>
                    </button>
                    <button type="button" id="tcl-edit-sex-opt-female" data-sex-value="Female"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-800 hover:bg-slate-100 rounded-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-pink-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 9a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                            <path d="M12 14v7" />
                            <path d="M9 18h6" />
                        </svg>
                        <span>Female</span>
                    </button>
                </div>
            </div>

            <!-- At-Birth Vaccines -->
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">At-Birth Vaccines</span>
                    <span class="text-[11px] font-medium text-slate-400">Immunization I</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label for="tcl-edit-bcg" class="block mb-1.5 text-sm font-semibold text-slate-700">BCG Status</label>
                        <select id="tcl-edit-bcg"
                                class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5">
                            <option value="NOT YET">NOT YET</option>
                            <option value="INJECTED">INJECTED</option>
                        </select>
                    </div>
                    <div>
                        <label for="tcl-edit-vax-date" class="block mb-1.5 text-sm font-semibold text-slate-700">BCG Date</label>
                        <input type="date" id="tcl-edit-vax-date"
                               class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label for="tcl-edit-hepa" class="block mb-1.5 text-sm font-semibold text-slate-700">Hepa - B Status</label>
                        <select id="tcl-edit-hepa"
                                class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5">
                            <option value="NOT YET">NOT YET</option>
                            <option value="INJECTED">INJECTED</option>
                        </select>
                    </div>
                    <div>
                        <label for="tcl-edit-hepa-date" class="block mb-1.5 text-sm font-semibold text-slate-700">Hepa - B Date</label>
                        <input type="date" id="tcl-edit-hepa-date"
                               class="bg-white border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                    </div>
                </div>
            </div>

            <!-- Routine Infant Doses -->
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Routine Infant Doses</span>
                    <span class="text-[11px] font-medium text-slate-400">Immunization II</span>
                </div>

                <!-- DPT-HiB-HepB -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">DPT-HiB-HepB</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-dpt1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-edit-dpt1" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-dpt1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-edit-dpt1-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-dpt2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-edit-dpt2" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-dpt2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-edit-dpt2-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-dpt3" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Status</label>
                            <select id="tcl-edit-dpt3" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-dpt3-date" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Date</label>
                            <input type="date" id="tcl-edit-dpt3-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- OPV -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">OPV</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-opv1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-edit-opv1" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-opv1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-edit-opv1-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-opv2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-edit-opv2" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-opv2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-edit-opv2-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-opv3" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Status</label>
                            <select id="tcl-edit-opv3" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-opv3-date" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Date</label>
                            <input type="date" id="tcl-edit-opv3-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- PCV -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">PCV</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-pcv1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-edit-pcv1" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-pcv1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-edit-pcv1-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-pcv2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-edit-pcv2" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-pcv2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-edit-pcv2-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-pcv3" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Status</label>
                            <select id="tcl-edit-pcv3" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-pcv3-date" class="block mb-1 text-xs font-semibold text-slate-600">3rd Dose Date</label>
                            <input type="date" id="tcl-edit-pcv3-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- IPV -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">IPV</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-ipv1" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Status</label>
                            <select id="tcl-edit-ipv1" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-ipv1-date" class="block mb-1 text-xs font-semibold text-slate-600">1st Dose Date</label>
                            <input type="date" id="tcl-edit-ipv1-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-ipv2" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Status</label>
                            <select id="tcl-edit-ipv2" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-ipv2-date" class="block mb-1 text-xs font-semibold text-slate-600">2nd Dose Date</label>
                            <input type="date" id="tcl-edit-ipv2-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- MCV1 (9 mos) -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">MCV1 (9 mos)</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-mcv1" class="block mb-1 text-xs font-semibold text-slate-600">Dose Status</label>
                            <select id="tcl-edit-mcv1" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-mcv1-date" class="block mb-1 text-xs font-semibold text-slate-600">Dose Date</label>
                            <input type="date" id="tcl-edit-mcv1-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>

                <!-- MCV2 (12 mos) -->
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-slate-700">MCV2 (12 mos)</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="tcl-edit-mcv2" class="block mb-1 text-xs font-semibold text-slate-600">Dose Status</label>
                            <select id="tcl-edit-mcv2" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2">
                                <option value="NOT YET">NOT YET</option>
                                <option value="INJECTED">INJECTED</option>
                            </select>
                        </div>
                        <div>
                            <label for="tcl-edit-mcv2-date" class="block mb-1 text-xs font-semibold text-slate-600">Dose Date</label>
                            <input type="date" id="tcl-edit-mcv2-date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                    id="nip-btn-submit-pop-edit2"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 text-base font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-edit-spinner2" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-5 h-5 text-cho-blue-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="nip-btn-submit-pop-edit-text2">Save Changes</span>
            </button>
        </form>
    </div>

    <div class="border-t border-slate-200 pt-3 text-xs text-slate-400 text-center">
        City Health Office Marawi &bull; Target Client List (0&ndash;12 Months)
    </div>
</div>

<!-- =============================================================================
     FLOWBITE CONFIRMATION MODAL: DELETE BARANGAY POPULATION DATA
     ============================================================================= -->
<div id="modal-nip-pop-delete2"
     tabindex="-1"
     aria-hidden="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-[1px]">
    <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <div class="inline-flex items-center gap-2 text-sm font-bold text-slate-900">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </span>
                <span>Delete Barangay Data</span>
            </div>
            <button type="button"
                    id="nip-pop-delete-close-btn2"
                    aria-label="Close modal"
                    class="text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-3">
            <p class="text-sm text-slate-700 leading-relaxed">
                Are you sure you want to delete <strong id="nip-pop-delete-barangay-name2" class="text-slate-900">--</strong> (<span id="nip-pop-delete-row-badge2" class="font-mono text-xs text-slate-600">Row --</span>)?
            </p>
            <p class="text-xs text-slate-500 leading-relaxed">
                Only raw data cells in <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700 font-mono">MARAWI POP 2024</code> will be cleared. Any detected Excel formulas will remain intact, and you can add a new entry into this slot anytime.
            </p>

            <input type="hidden" id="nip-pop-delete-row-index2" />
            <input type="hidden" id="nip-pop-delete-excel-row2" />
            <input type="hidden" id="nip-pop-delete-nip-row2" />

            <div id="nip-pop-delete-feedback2" class="hidden"></div>
        </div>

        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
            <button type="button"
                    id="nip-pop-delete-cancel-btn2"
                    class="px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                Cancel
            </button>
            <button type="button"
                    id="nip-btn-submit-pop-delete2"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs sm:text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 focus:ring-4 focus:ring-rose-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-delete-spinner2" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-4 h-4 text-rose-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>
                <span id="nip-btn-submit-pop-delete-text2">Delete Data</span>
            </button>
        </div>
    </div>
</div>

<!-- =============================================================================
     FLOWBITE RIGHT DRAWER 3: TCL INFANT IMMUNIZATION PROFILE (CLICKED FROM TABLE)
     ============================================================================= -->
<div id="drawer-tcl-infant2"
     data-cho-nip-drawer
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 rounded-l-2xl w-full sm:w-[500px] md:w-[540px] max-w-full shadow-2xl flex flex-col justify-between"
     tabindex="-1"
     aria-labelledby="tcl-drawer-child-name2"
     aria-hidden="true">

    <div id="tcl-drawer-body2" class="space-y-5">
        <!-- Drawer Top Header: Child Name Title + Barangay Subtitle + Top-Right Close Button -->
        <div id="tcl-drawer-header2" class="flex items-start justify-between gap-3 pb-4 border-b border-slate-200">
            <div id="tcl-drawer-title-wrap2" class="min-w-0">
                <h5 id="tcl-drawer-child-name2" class="text-lg sm:text-xl font-black font-heading text-slate-900 tracking-tight truncate">
                    --
                </h5>
                <p id="tcl-drawer-child-meta2" class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1">
                    --
                </p>
            </div>
            <button type="button"
                    id="drawer-tcl-infant-close-btn2"
                    data-cho-drawer-hide="drawer-tcl-infant2"
                    aria-controls="drawer-tcl-infant2"
                    class="shrink-0 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <!-- Pill Tabs / Segmented Tabs -->
        <div id="tcl-drawer-tabs2"
             role="tablist"
             aria-label="Infant record sections"
             class="grid grid-cols-3 gap-1.5 p-1 bg-slate-100 border border-slate-200/80 rounded-xl">
            <button type="button"
                    id="tcl-tab-btn-overview2"
                    role="tab"
                    data-tcl-tab="overview"
                    aria-selected="true"
                    aria-controls="tcl-tab-panel-overview2"
                    class="px-3 py-2 text-xs font-bold rounded-lg bg-white text-slate-900 shadow-xs transition-all cursor-pointer">
                Overview
            </button>
            <button type="button"
                    id="tcl-tab-btn-imm1-2"
                    role="tab"
                    data-tcl-tab="imm1"
                    aria-selected="false"
                    aria-controls="tcl-tab-panel-imm1-2"
                    class="px-3 py-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-900 transition-all cursor-pointer">
                Immunization I
            </button>
            <button type="button"
                    id="tcl-tab-btn-imm2-2"
                    role="tab"
                    data-tcl-tab="imm2"
                    aria-selected="false"
                    aria-controls="tcl-tab-panel-imm2-2"
                    class="px-3 py-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-900 transition-all cursor-pointer">
                Immunization II
            </button>
        </div>

        <!-- TAB PANEL 1: OVERVIEW (Default) -->
        <div id="tcl-tab-panel-overview2" role="tabpanel" aria-labelledby="tcl-tab-btn-overview2" class="space-y-4">
            <div id="tcl-overview-card2" class="bg-slate-50/80 border border-slate-200/90 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/80">
                    <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        Client Demographics
                        <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-white border border-slate-200/80 rounded-lg p-3">
                        <div class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span>Sex</span>
                            <span id="tcl-overview-sex-icon2" class="inline-flex items-center">
                                <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 14a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                                    <path d="M19 5l-5.4 5.4" />
                                    <path d="M19 5h-5" />
                                    <path d="M19 5v5" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-2 pt-0.5">
                            <span id="tcl-overview-sex-badge2"></span>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200/80 rounded-lg p-3">
                        <div class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span>Age</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 7v5l3 3" />
                            </svg>
                        </div>
                        <div id="tcl-overview-age2" class="font-bold text-slate-900 mt-1.5">--</div>
                    </div>
                    <div class="bg-white border border-slate-200/80 rounded-lg p-3">
                        <div class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span>Date of Registration</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                <path d="M16 3v4" />
                                <path d="M8 3v4" />
                                <path d="M4 11h16" />
                                <path d="M9 15l2 2l4 -4" />
                            </svg>
                        </div>
                        <div id="tcl-overview-reg2" class="font-bold text-slate-900 mt-1.5">--</div>
                    </div>
                    <div class="bg-white border border-slate-200/80 rounded-lg p-3">
                        <div class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span>Date of Birth</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 20h18v-8a3 3 0 0 0 -3 -3h-12a3 3 0 0 0 -3 3v8z" />
                                <path d="M3 14.803c.312 .135 .654 .204 1 .197a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1c.35 .007 .692 -.062 1 -.197" />
                                <path d="M12 4l1.465 1.638a2 2 0 1 1 -3.015 .099l1.55 -1.737z" />
                            </svg>
                        </div>
                        <div id="tcl-overview-dob2" class="font-bold text-slate-900 mt-1.5">--</div>
                    </div>
                </div>
            </div>

            <div id="tcl-overview-progress-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 space-y-2.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="inline-flex items-center gap-1.5 font-extrabold uppercase tracking-wider text-slate-700">
                        Immunization Progress
                        <svg class="w-3.5 h-3.5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 12l2 2l4 -4" />
                            <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                        </svg>
                    </span>
                    <span id="tcl-overview-progress-count2" class="font-mono font-bold text-emerald-600">0 / 10 Doses</span>
                </div>
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div id="tcl-overview-progress-bar2" class="h-full bg-emerald-600 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="tcl-overview-progress-note2" class="text-[11px] text-slate-500">
                    Switch to Immunization I or II above to inspect individual vaccine doses and vaccination dates.
                </p>
            </div>

            <!-- Read-Only Clinical Notes Card (Editable via Edit Action) -->
            <div id="tcl-overview-notes-card2" class="bg-slate-50/80 border border-slate-200/90 rounded-xl p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        Health Worker Notes
                        <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                            <path d="M9 12h6" />
                            <path d="M9 16h6" />
                        </svg>
                    </span>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-400">
                        Read-only &bull; Edit via Action
                    </span>
                </div>
                <div id="tcl-overview-notes-text2" class="min-h-[125px] whitespace-pre-line text-xs text-slate-700 leading-relaxed bg-white border border-slate-200/80 rounded-lg p-3.5">
                    --
                </div>
            </div>
        </div>

        <!-- TAB PANEL 2: IMMUNIZATION I (2x2 Grid: BCG & Hepa B-BD | Badge & Date) -->
        <div id="tcl-tab-panel-imm1-2" role="tabpanel" aria-labelledby="tcl-tab-btn-imm1-2" class="hidden space-y-3">
            <div class="flex items-center justify-between px-1">
                <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    At-Birth Vaccines
                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17 3l4 4" />
                        <path d="M19 5l-4.5 4.5" />
                        <path d="M11.5 6.5l6 6" />
                        <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                        <path d="M7.5 12.5l1.5 1.5" />
                        <path d="M10.5 9.5l1.5 1.5" />
                        <path d="M3 21l3 -3" />
                    </svg>
                </span>
                <span class="text-[11px] text-slate-400 font-medium">2x2 Status &amp; Date Matrix</span>
            </div>
            <div id="tcl-imm1-grid2" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Cell 1: BCG Status -->
                <div id="tcl-imm1-bcg-card2" class="bg-white border border-slate-200/90 rounded-xl p-3.5 flex items-center justify-between gap-2 shadow-xs">
                    <div>
                        <div class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span>Vaccine</span>
                            <svg class="w-3 h-3 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3l4 4" />
                                <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                                <path d="M3 21l3 -3" />
                            </svg>
                        </div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">BCG</div>
                    </div>
                    <span id="tcl-imm1-bcg-badge2"></span>
                </div>
                <!-- Cell 2: BCG Date -->
                <div id="tcl-imm1-bcg-date-card2" class="bg-slate-50/80 border border-slate-200/90 rounded-xl p-3.5 flex flex-col justify-center">
                    <div class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <span>Date of Vaccination</span>
                        <svg class="w-3 h-3 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                            <path d="M16 3v4" />
                            <path d="M8 3v4" />
                            <path d="M4 11h16" />
                        </svg>
                    </div>
                    <div id="tcl-imm1-bcg-date2" class="text-sm font-bold text-slate-800 mt-0.5">&mdash;</div>
                </div>
                <!-- Cell 3: Hepa B-BD Status -->
                <div id="tcl-imm1-hepa-card2" class="bg-white border border-slate-200/90 rounded-xl p-3.5 flex items-center justify-between gap-2 shadow-xs">
                    <div>
                        <div class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            <span>Vaccine</span>
                            <svg class="w-3 h-3 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3l4 4" />
                                <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                                <path d="M3 21l3 -3" />
                            </svg>
                        </div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">Hepa - B</div>
                    </div>
                    <span id="tcl-imm1-hepa-badge2"></span>
                </div>
                <!-- Cell 4: Hepa - B Date -->
                <div id="tcl-imm1-hepa-date-card2" class="bg-slate-50/80 border border-slate-200/90 rounded-xl p-3.5 flex flex-col justify-center">
                    <div class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <span>Date of Vaccination</span>
                        <svg class="w-3 h-3 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                            <path d="M16 3v4" />
                            <path d="M8 3v4" />
                            <path d="M4 11h16" />
                        </svg>
                    </div>
                    <div id="tcl-imm1-hepa-date2" class="text-sm font-bold text-slate-800 mt-0.5">&mdash;</div>
                </div>

                <!-- Moved from Immunization II: DPT-HiB-HepB (1st, 2nd & 3rd Dose) -->
                <div id="tcl-imm2-dpt-card2" class="sm:col-span-2 bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>DPT-HiB-HepB</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3l4 4" />
                                <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                                <path d="M3 21l3 -3" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">3 Doses</span>
                    </div>
                    <div id="tcl-imm2-dpt-list2" class="divide-y divide-slate-100"></div>
                </div>

                <!-- Moved from Immunization II: OPV (1st, 2nd & 3rd Dose) -->
                <div id="tcl-imm2-opv-card2" class="sm:col-span-2 bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>OPV</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546z" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">3 Doses</span>
                    </div>
                    <div id="tcl-imm2-opv-list2" class="divide-y divide-slate-100"></div>
                </div>
            </div>

            <!-- Read-Only At-Birth Vaccine Notes Reader (Editable via Edit Action) -->
            <div id="tcl-imm1-notes-card2" class="bg-slate-50/80 border border-slate-200/90 rounded-xl p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        At-Birth Immunization Notes
                        <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                            <path d="M9 12h6" />
                            <path d="M9 16h6" />
                        </svg>
                    </span>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-400">
                        Read-only &bull; Edit via Action
                    </span>
                </div>
                <div id="tcl-imm1-notes-text2" class="min-h-[125px] whitespace-pre-line text-xs text-slate-700 leading-relaxed bg-white border border-slate-200/80 rounded-lg p-3.5">
                    --
                </div>
            </div>
        </div>

        <!-- TAB PANEL 3: IMMUNIZATION II (1x1 Big Grid Cards: DPT-HiB-HepB, OPV, PCV, IPV) -->
        <div id="tcl-tab-panel-imm2-2" role="tabpanel" aria-labelledby="tcl-tab-btn-imm2-2" class="hidden space-y-3">
            <div class="flex items-center justify-between px-1">
                <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Routine Infant Doses
                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                        <path d="M12 8v4" />
                        <path d="M10 10h4" />
                    </svg>
                </span>
                <span class="text-[11px] text-slate-400 font-medium">PCV &bull; IPV &bull; MCV1 &bull; MCV2</span>
            </div>
            <div id="tcl-imm2-grid2" class="grid grid-cols-1 gap-3">
                <!-- Card 1: PCV (1st, 2nd & 3rd Dose) -->
                <div id="tcl-imm2-pcv-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>PCV</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3l4 4" />
                                <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                                <path d="M3 21l3 -3" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">3 Doses</span>
                    </div>
                    <div id="tcl-imm2-pcv-list2" class="divide-y divide-slate-100"></div>
                </div>

                <!-- Card 2: IPV (1st & 2nd Dose) -->
                <div id="tcl-imm2-ipv-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>IPV</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">2 Doses</span>
                    </div>
                    <div id="tcl-imm2-ipv-list2" class="divide-y divide-slate-100"></div>
                </div>

                <!-- Card 3: MCV1 (9 mos) -->
                <div id="tcl-imm2-mcv1-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>MCV1 (9 mos)</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3l4 4" />
                                <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                                <path d="M3 21l3 -3" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">1 Dose</span>
                    </div>
                    <div id="tcl-imm2-mcv1-list2" class="divide-y divide-slate-100"></div>
                </div>

                <!-- Card 4: MCV2 (12 mos) -->
                <div id="tcl-imm2-mcv2-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>MCV2 (12 mos)</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3l4 4" />
                                <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                                <path d="M3 21l3 -3" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">1 Dose</span>
                    </div>
                    <div id="tcl-imm2-mcv2-list2" class="divide-y divide-slate-100"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="tcl-drawer-footer2" class="border-t border-slate-200 pt-3 mt-5 text-[11px] text-slate-400 text-center">
        City Health Office Marawi &bull; Target Client List (0&ndash;12 Months)
    </div>
</div>

<?php
require BASE_PATH . '/frontend/layouts/footer.php';
