<?php
/**
 * CHO MARAWI PORTAL - MARAWI POPULATION MASTER TABLE
 * Location: frontend/pages/NIP/population/index.php
 */

declare(strict_types=1);

require dirname(dirname(dirname(dirname(__DIR__)))) . '/backend/bootstrap.php';

$pageTitle = 'Marawi Population | City Health Office - Marawi City';

require BASE_PATH . '/frontend/layouts/head.php';
require BASE_PATH . '/frontend/layouts/sidebar.php';
?>

<div id="cho-nip-population-main-container" class="p-4 sm:ml-72 transition-all space-y-6">

    <!-- TOP HEADER -->
    <div id="cho-nip-population-header" class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
        <div>
            <h1 id="cho-nip-population-title" class="text-2xl sm:text-3xl font-black font-heading tracking-tight text-cho-blue-900">
                Marawi Population
            </h1>
            <p id="cho-nip-population-subtitle" class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                MARAWI POP 2024 &bull; Barangay Census Estimates &amp; NIP 73-Brgy Target Breakdown
            </p>
        </div>
        <div id="cho-nip-pop-badge-wrap" class="flex items-center gap-2">
            <button type="button"
                    id="cho-nip-pop-btn-add"
                    data-cho-drawer-target="drawer-nip-pop-add"
                    data-drawer-placement="right"
                    aria-controls="drawer-nip-pop-add"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 active:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Barangay Entry</span>
            </button>
        </div>
    </div>

    <!-- COMPACT SUMMARY STATS (Copied from NIP Dashboard #cho-nip-stats-grid) -->
    <div id="cho-nip-pop-stats-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Barangay Records (Blue Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-records" class="group relative overflow-hidden rounded-none p-5 bg-[#005bf8] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip1.webp') ?>"
                 alt="Barangays Population"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-1.5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out z-[5]" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M11.906 1.994a8.002 8.002 0 0 1 8.09 8.421 7.996 7.996 0 0 1-1.297 3.957.996.996 0 0 1-.133.204l-.108.129c-.178.243-.37.477-.573.699l-5.112 6.224a1 1 0 0 1-1.545 0L5.982 15.26l-.002-.002a18.146 18.146 0 0 1-.309-.38l-.133-.163a.999.999 0 0 1-.13-.202 7.995 7.995 0 0 1 6.498-12.518ZM15 9.997a3 3 0 1 1-5.999 0 3 3 0 0 1 5.999 0Z" clip-rule="evenodd"/>
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90">Barangay Records</span>
                    <span class="text-xs font-black tracking-widest text-white/60">01</span>
                </div>
                <div id="cho-nip-pop-stat-records-val" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-20"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">Marawi City Census</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>↑</span> Active Registry</span>
                </div>
            </div>
        </div>

        <!-- Card 2: City Total Pop 2024 - C4 (Orange Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-total" class="group relative overflow-hidden rounded-none p-5 bg-[#e66a00] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip2.webp') ?>"
                 alt="City Total Population"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-1.5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90">City Total Pop 2024</span>
                    <span class="text-xs font-black tracking-widest text-white/60">02</span>
                </div>
                <div id="cho-nip-pop-stat-total-val" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-28"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">PSA Master Census</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>👥</span> Master Pop</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Household Population Estimates 2024 - D4 (Emerald Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-households" class="group relative overflow-hidden rounded-none p-5 bg-[#007a64] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip3.webp') ?>"
                 alt="Household Population Estimates"
                 class="absolute inset-0 w-full h-full object-cover object-[center_20%] opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out" fill="currentColor" viewBox="0 0 24 24">
                <path d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z"/>
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90 truncate pr-2">Household Population Estimates 2024</span>
                    <span class="text-xs font-black tracking-widest text-white/60 shrink-0">03</span>
                </div>
                <div id="cho-nip-pop-stat-households-val" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-28"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">Marawi Household Pop</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>✓</span> Verified Estimate</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Number of Households Estimates 2024 - E4 (Crimson Theme with Integrated Background Photo) -->
        <div id="cho-nip-pop-stat-target" class="group relative overflow-hidden rounded-none p-5 bg-[#c00030] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img loading="lazy" decoding="async" src="<?= asset('images/nip/nip4.webp') ?>"
                 alt="Number of Households Estimates 2024"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-45 mix-blend-overlay group-hover:opacity-75 group-hover:mix-blend-normal group-hover:scale-105 group-hover:brightness-105 transition-all duration-500 ease-out pointer-events-none" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-[4]"></div>
            <svg class="absolute right-2 bottom-1.5 w-24 h-24 text-white/20 pointer-events-none transform -rotate-6 z-[5] group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500 ease-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-width="2" d="M8.737 8.737a21.49 21.49 0 0 1 3.308-2.724m0 0c3.063-2.026 5.99-2.641 7.331-1.3 1.827 1.828.026 6.591-4.023 10.64-4.049 4.049-8.812 5.85-10.64 4.023-1.33-1.33-.736-4.218 1.249-7.253m6.083-6.11c-3.063-2.026-5.99-2.641-7.331-1.3-1.827 1.828-.026 6.591 4.023 10.64m3.308-9.34a21.497 21.497 0 0 1 3.308 2.724m2.775 3.386c1.985 3.035 2.579 5.923 1.248 7.253-1.336 1.337-4.245.732-7.295-1.275M14 12a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
            </svg>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-white/90 truncate pr-2">Number of Households Estimates 2024</span>
                    <span class="text-xs font-black tracking-widest text-white/60 shrink-0">04</span>
                </div>
                <div id="cho-nip-pop-stat-target-val" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
                    <div role="status" class="animate-pulse py-1.5"><div class="h-8 bg-white/30 rounded-md w-24"></div><span class="sr-only">Loading...</span></div>
                </div>
                <div class="w-full border-t border-white/20 my-2.5"></div>
                <div class="flex items-center justify-between text-xs text-white/90">
                    <span class="text-[11px] font-medium text-white/80">Estimated Households</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white"><span>📊</span> PSA Census</span>
                </div>
            </div>
        </div>

    </div>

    <!-- FLOWBITE TABLE CARD: Striped Columns + Hover State + Sortable Head + Wided Search + Filter + Infinite Scroll -->
    <div id="cho-nip-population-table-card"
         data-sheets-base-url="<?= e(url('/SHEETS/')) ?>"
         class="relative bg-white border border-slate-200/90 rounded-xl shadow-xs overflow-hidden">

        <!-- Table Toolbar: Wided Search + Table Filter + Workbook Selector -->
        <div id="cho-nip-pop-toolbar" class="p-4 border-b border-slate-200/90 bg-white flex flex-col lg:flex-row lg:items-center gap-3">
            <!-- Wided Table Search -->
            <div class="relative flex-1 min-w-0">
                <label for="cho-nip-pop-search" class="sr-only">Search Marawi Population</label>
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text"
                       id="cho-nip-pop-search"
                       class="block w-full ps-10 pe-9 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-300 rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 placeholder-slate-400"
                       placeholder="Search barangay name, PSGC10 code, or population count..." />
                <button type="button"
                        id="cho-nip-pop-search-clear"
                        class="hidden absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400 hover:text-slate-600 cursor-pointer"
                        title="Clear search">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Table Filter & Workbook Source -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
                <div class="flex items-center gap-2">
                    <label for="cho-nip-pop-filter" class="sr-only">Filter Records</label>
                    <select id="cho-nip-pop-filter"
                            class="bg-slate-50 border border-slate-300 text-slate-800 text-xs sm:text-sm font-medium rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full sm:w-48 px-3 py-2.5 cursor-pointer">
                        <option value="all">Filter: All Records</option>
                        <option value="nip_only">73 Brgy + Shelter Only</option>
                        <option value="pop_high">Population &ge; 3,000</option>
                        <option value="pop_mid">Population 1,500 &ndash; 2,999</option>
                        <option value="pop_low">Population &lt; 1,500</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button"
                            id="cho-nip-pop-sync-per-brgy-btn"
                            title="Click to sync Barangay Total Population to per brgy & Q1–Q4 (Jan–Dec) in Excel"
                            class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-300 text-slate-700 hover:bg-cho-blue-50 hover:border-cho-blue-400 hover:text-cho-blue-900 active:scale-[0.99] focus:ring-2 focus:ring-cho-blue-300/40 text-xs sm:text-sm font-medium rounded-lg px-3 py-2.5 whitespace-nowrap transition-colors cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                        <span class="w-2 h-2 rounded-full bg-slate-400" id="cho-nip-pop-active-dot"></span>
                        <span id="cho-nip-pop-active-workbook-name">No Workbook Uploaded</span>
                        <svg id="cho-nip-pop-sync-icon" class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Infinite Scrollable Table Wrapper -->
        <div id="cho-nip-pop-scroll-container" class="relative overflow-x-auto overflow-y-auto max-h-[62vh] bg-white">
            <table id="cho-nip-pop-table" class="w-full text-sm text-left rtl:text-right text-slate-600">
                <thead class="text-xs text-white uppercase bg-cho-blue-900 sticky top-0 z-10 border-b border-cho-blue-800">
                    <tr>
                        <th scope="col" data-sort-key="row_index" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center gap-1.5">
                                <span>#</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="psgc10" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center gap-1.5">
                                <span>PSGC10</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="barangay" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center gap-1.5">
                                <span>Barangay / Locality</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="total_pop" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-right bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center justify-end gap-1.5">
                                <span>Total Pop 2024</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="household_pop" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-right hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center justify-end gap-1.5">
                                <span>Household Pop 2024</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="households" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-right bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center justify-end gap-1.5">
                                <span>Households 2024</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="nip_pop" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-right hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center justify-end gap-1.5">
                                <span>NIP 73-Brgy Pop</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="nip_target" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-right bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center justify-end gap-1.5">
                                <span>NIP Target (2.056%)</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 font-bold whitespace-nowrap text-center">
                            <span>Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="cho-nip-pop-tbody" class="divide-y divide-slate-200/80 bg-white">
                    <?php for ($s = 0; $s < 6; $s++): ?>
                    <tr class="animate-pulse">
                        <td class="px-4 py-3.5"><div class="h-3 bg-slate-200 rounded-full w-6"></div></td>
                        <td class="px-4 py-3.5 bg-slate-50/80"><div class="h-3 bg-slate-200 rounded-full w-24"></div></td>
                        <td class="px-4 py-3.5"><div class="h-3 bg-slate-200 rounded-full w-36"></div></td>
                        <td class="px-4 py-3.5 bg-slate-50/80"><div class="h-3 bg-slate-200 rounded-full w-16 ms-auto"></div></td>
                        <td class="px-4 py-3.5"><div class="h-3 bg-slate-200 rounded-full w-16 ms-auto"></div></td>
                        <td class="px-4 py-3.5 bg-slate-50/80"><div class="h-3 bg-slate-200 rounded-full w-14 ms-auto"></div></td>
                        <td class="px-4 py-3.5"><div class="h-3 bg-slate-200 rounded-full w-16 ms-auto"></div></td>
                        <td class="px-4 py-3.5 bg-slate-50/80"><div class="h-3 bg-slate-200 rounded-full w-14 ms-auto"></div></td>
                        <td class="px-4 py-3.5">
                            <div class="inline-flex items-center justify-center gap-1.5">
                                <div class="h-5 bg-slate-200 rounded-lg w-6"></div>
                                <div class="h-5 bg-slate-200 rounded-lg w-6"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <!-- Infinite Scroll Sentinel -->
            <div id="cho-nip-pop-scroll-sentinel" class="h-4 w-full"></div>
        </div>

        <!-- Table Footer Status -->
        <div id="cho-nip-pop-footer" class="px-4 py-3 border-t border-slate-200/90 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-slate-500">
            <span id="cho-nip-pop-count-status" class="font-medium">Awaiting workbook upload...</span>
            <span id="cho-nip-pop-source-status" class="text-[11px] text-slate-400">Source: None (Upload via NIP Dashboard)</span>
        </div>
    </div>

</div>

<!-- =============================================================================
     FLOWBITE RIGHT DRAWER 1: ADD BARANGAY POPULATION ENTRY
     ============================================================================= -->
<div id="drawer-nip-pop-add"
     data-cho-nip-drawer
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 rounded-l-2xl w-full sm:w-[520px] md:w-[560px] max-w-full shadow-2xl flex flex-col justify-between"
     tabindex="-1"
     aria-labelledby="drawer-nip-pop-add-label"
     aria-hidden="true">

    <div class="space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h5 id="drawer-nip-pop-add-label" class="inline-flex items-center gap-2 text-lg sm:text-xl font-bold text-slate-900">
                <svg class="w-5 h-5 text-cho-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Barangay Population Entry
            </h5>
            <button type="button"
                    data-cho-drawer-hide="drawer-nip-pop-add"
                    aria-controls="drawer-nip-pop-add"
                    class="text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Appends a new barangay row directly into the <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700 font-mono">MARAWI POP 2024</code> worksheet of the active Excel workbook.
        </p>

        <form id="nip-pop-add-form" class="space-y-3.5">
            <div>
                <label for="nip-pop-add-psgc10" class="block mb-1.5 text-sm font-semibold text-slate-700">PSGC10 Code</label>
                <input type="text" id="nip-pop-add-psgc10" placeholder="e.g. 1903617144" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
            </div>
            <div>
                <label for="nip-pop-add-barangay" class="block mb-1.5 text-sm font-semibold text-slate-700">Barangay / Locality</label>
                <input type="text" id="nip-pop-add-barangay" placeholder="e.g. New Barangay Name" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-add-total-pop" class="block mb-1.5 text-sm font-semibold text-slate-700">Total Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-add-total-pop" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
                <div>
                    <label for="nip-pop-add-household-pop" class="block mb-1.5 text-sm font-semibold text-slate-700">Household Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-add-household-pop" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-add-households" class="block mb-1.5 text-sm font-semibold text-slate-700">Households 2024</label>
                    <input type="number" min="0" id="nip-pop-add-households" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
                <div>
                    <label for="nip-pop-add-nip-pop" class="block mb-1.5 text-sm font-semibold text-slate-700">NIP 73-Brgy Pop (Optional)</label>
                    <input type="number" min="0" id="nip-pop-add-nip-pop" value="0"
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
            </div>

            <div id="nip-pop-add-feedback" class="hidden"></div>

            <button type="submit"
                    id="nip-btn-submit-pop-add"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 text-base font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-add-spinner" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-5 h-5 text-cho-blue-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="nip-btn-submit-pop-add-text">Save New Entry to Excel</span>
            </button>
        </form>
    </div>

    <div class="border-t border-slate-200 pt-3 text-[11px] text-slate-400 text-center">
        City Health Office Marawi &bull; NIP Excel Mutator
    </div>
</div>

<!-- =============================================================================
     FLOWBITE RIGHT DRAWER 2: EDIT BARANGAY POPULATION ENTRY (CLONE OF ADD DRAWER)
     ============================================================================= -->
<div id="drawer-nip-pop-edit"
     data-cho-nip-drawer
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 rounded-l-2xl w-full sm:w-[520px] md:w-[560px] max-w-full shadow-2xl flex flex-col justify-between"
     tabindex="-1"
     aria-labelledby="drawer-nip-pop-edit-label"
     aria-hidden="true">

    <div class="space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h5 id="drawer-nip-pop-edit-label" class="inline-flex items-center gap-2 text-lg sm:text-xl font-bold text-slate-900">
                <svg class="w-5 h-5 text-cho-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Barangay Population Entry
            </h5>
            <button type="button"
                    data-cho-drawer-hide="drawer-nip-pop-edit"
                    aria-controls="drawer-nip-pop-edit"
                    class="text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Modifies the exact Excel cells in <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700 font-mono">MARAWI POP 2024</code> (<span id="nip-pop-edit-row-badge" class="font-semibold text-slate-700">Row --</span>) while preserving all workbook formulas.
        </p>

        <form id="nip-pop-edit-form" class="space-y-3.5">
            <input type="hidden" id="nip-pop-edit-excel-row" />
            <input type="hidden" id="nip-pop-edit-nip-row" />
            <input type="hidden" id="nip-pop-edit-row-index" />

            <div>
                <label for="nip-pop-edit-psgc10" class="block mb-1.5 text-sm font-semibold text-slate-700">PSGC10 Code</label>
                <input type="text" id="nip-pop-edit-psgc10" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
            </div>
            <div>
                <label for="nip-pop-edit-barangay" class="block mb-1.5 text-sm font-semibold text-slate-700">Barangay / Locality</label>
                <input type="text" id="nip-pop-edit-barangay" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-edit-total-pop" class="block mb-1.5 text-sm font-semibold text-slate-700">Total Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-edit-total-pop" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
                <div>
                    <label for="nip-pop-edit-household-pop" class="block mb-1.5 text-sm font-semibold text-slate-700">Household Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-edit-household-pop" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-edit-households" class="block mb-1.5 text-sm font-semibold text-slate-700">Households 2024</label>
                    <input type="number" min="0" id="nip-pop-edit-households" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
                <div>
                    <label for="nip-pop-edit-nip-pop" class="block mb-1.5 text-sm font-semibold text-slate-700">NIP 73-Brgy Pop</label>
                    <input type="number" min="0" id="nip-pop-edit-nip-pop"
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-base rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3.5 py-2.5" />
                </div>
            </div>

            <div id="nip-pop-edit-feedback" class="hidden"></div>

            <button type="submit"
                    id="nip-btn-submit-pop-edit"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 text-base font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-edit-spinner" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-5 h-5 text-cho-blue-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="nip-btn-submit-pop-edit-text">Update Excel Cells</span>
            </button>
        </form>
    </div>

    <div class="border-t border-slate-200 pt-3 text-[11px] text-slate-400 text-center">
        City Health Office Marawi &bull; NIP Excel Mutator
    </div>
</div>

<!-- =============================================================================
     FLOWBITE CONFIRMATION MODAL: DELETE BARANGAY POPULATION DATA
     ============================================================================= -->
<div id="modal-nip-pop-delete"
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
                    id="nip-pop-delete-close-btn"
                    aria-label="Close modal"
                    class="text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-3">
            <p class="text-sm text-slate-700 leading-relaxed">
                Are you sure you want to delete <strong id="nip-pop-delete-barangay-name" class="text-slate-900">--</strong> (<span id="nip-pop-delete-row-badge" class="font-mono text-xs text-slate-600">Row --</span>)?
            </p>
            <p class="text-xs text-slate-500 leading-relaxed">
                Only raw data cells in <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700 font-mono">MARAWI POP 2024</code> will be cleared. Any detected Excel formulas will remain intact, and you can add a new entry into this slot anytime.
            </p>

            <input type="hidden" id="nip-pop-delete-row-index" />
            <input type="hidden" id="nip-pop-delete-excel-row" />
            <input type="hidden" id="nip-pop-delete-nip-row" />

            <div id="nip-pop-delete-feedback" class="hidden"></div>
        </div>

        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
            <button type="button"
                    id="nip-pop-delete-cancel-btn"
                    class="px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                Cancel
            </button>
            <button type="button"
                    id="nip-btn-submit-pop-delete"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs sm:text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 focus:ring-4 focus:ring-rose-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-delete-spinner" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-4 h-4 text-rose-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>
                <span id="nip-btn-submit-pop-delete-text">Delete Data</span>
            </button>
        </div>
    </div>
</div>

<?php
require BASE_PATH . '/frontend/layouts/footer.php';
