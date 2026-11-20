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
            <img src="<?= asset('images/nip/nip1.jpeg') ?>"
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
                <div id="cho-nip-pop-stat-records-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
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
        <div id="cho-nip-pop-stat-total2" class="group relative overflow-hidden rounded-none p-5 bg-[#e66a00] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img src="<?= asset('images/nip/nip2.jpeg') ?>"
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
                <div id="cho-nip-pop-stat-total-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
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
        <div id="cho-nip-pop-stat-households2" class="group relative overflow-hidden rounded-none p-5 bg-[#007a64] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img src="<?= asset('images/nip/nip3.jpg') ?>"
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
                <div id="cho-nip-pop-stat-households-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
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
        <div id="cho-nip-pop-stat-target2" class="group relative overflow-hidden rounded-none p-5 bg-[#c00030] text-white shadow-xs transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-xl cursor-default">
            <img src="<?= asset('images/nip/nip4.jpg') ?>"
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
                <div id="cho-nip-pop-stat-target-val2" class="font-money text-3xl sm:text-4xl font-black text-white tracking-tight mt-1">
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
    <div id="cho-nip-population-table-card2"
         data-sheets-base-url="<?= e(url('/SHEETS/')) ?>"
         class="relative bg-white border border-slate-200/90 rounded-xl shadow-xs overflow-hidden">

        <!-- Table Toolbar: Wided Search + Table Filter + Database Status -->
        <div id="cho-nip-pop-toolbar2" class="p-4 border-b border-slate-200/90 bg-white flex flex-col lg:flex-row lg:items-center gap-3">
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

            <!-- Table Filter & Database Source -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
                <div class="flex items-center gap-2">
                    <label for="cho-nip-pop-filter2" class="sr-only">Filter Records</label>
                    <select id="cho-nip-pop-filter2"
                            class="bg-slate-50 border border-slate-300 text-slate-800 text-xs sm:text-sm font-medium rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full sm:w-48 px-3 py-2.5 cursor-pointer">
                        <option value="all">Filter: All Infants</option>
                        <option value="injected">BCG: INJECTED</option>
                        <option value="not_yet">BCG: NOT YET</option>
                        <option value="male">Sex: Male</option>
                        <option value="female">Sex: Female</option>
                    </select>
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
                        <th scope="col" data-sort-key="row_index" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>#</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="barangay_address" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>BARANGAY ADDRESS</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="registration_date" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>DATE OF REGISTRATION</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="child_name" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>NAME OF CHILD</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="dob" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>DATE OF BIRTH</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="age" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>AGE</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="sex" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center hover:bg-cho-blue-800/70 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>SEX</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" data-sort-key="bcg_status" class="px-4 py-3.5 font-bold cursor-pointer select-none whitespace-nowrap text-center bg-cho-blue-950/35 hover:bg-cho-blue-800/80 transition-colors">
                            <div class="flex items-center justify-center gap-1.5">
                                <span>BCG</span>
                                <span class="cho-sort-icon text-white/60 text-[10px]">&#8693;</span>
                            </div>
                        </th>
                        <th scope="col" class="px-4 py-3.5 font-bold whitespace-nowrap text-center">
                            <span>Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="cho-nip-pop-tbody2" class="divide-y divide-slate-200/80 bg-white">
                    <?php
                    $tclMockSamples = [
                        ['id' => 1,  'barangay' => 'Tuca',     'reg_date' => '2026-10-04', 'child' => 'Al-Amin M. Macaraya',      'dob' => '2026-09-18', 'age' => '2 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-10-04'],
                        ['id' => 2,  'barangay' => 'Basak Malutlut',  'reg_date' => '2026-10-09', 'child' => 'Sittie Aisha D. Ampaso',   'dob' => '2026-09-25', 'age' => '1 mo',   'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-10-09'],
                        ['id' => 3,  'barangay' => 'Banggolo',        'reg_date' => '2026-10-14', 'child' => 'Mohammad R. Sultan',       'dob' => '2026-10-02', 'age' => '1 mo',   'sex' => 'Male',   'bcg' => 'NOT YET',  'vax_date' => ''],
                        ['id' => 4,  'barangay' => 'Marawi Central',  'reg_date' => '2026-10-19', 'child' => 'Norhanie K. Hadji Ali',    'dob' => '2026-08-12', 'age' => '3 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-10-19'],
                        ['id' => 5,  'barangay' => 'Dulay',           'reg_date' => '2026-10-22', 'child' => 'Abdulrahman P. Dimaporo',  'dob' => '2026-07-05', 'age' => '4 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-10-22'],
                        ['id' => 6,  'barangay' => 'Kilala',          'reg_date' => '2026-10-27', 'child' => 'Fatimah Z. Balindong',     'dob' => '2026-10-15', 'age' => '1 mo',   'sex' => 'Female', 'bcg' => 'NOT YET',  'vax_date' => ''],
                        ['id' => 7,  'barangay' => 'Matampay',        'reg_date' => '2026-11-02', 'child' => 'Hamza L. Pangcoga',        'dob' => '2026-05-20', 'age' => '6 mos',  'sex' => 'Male',   'bcg' => 'INJECTED', 'vax_date' => '2026-11-02'],
                        ['id' => 8,  'barangay' => 'Lilod Madaya',    'reg_date' => '2026-11-06', 'child' => 'Jannah R. Marohombsar',    'dob' => '2026-03-11', 'age' => '8 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-11-06'],
                        ['id' => 9,  'barangay' => 'Marinaut',        'reg_date' => '2026-11-11', 'child' => 'Yusuf A. Usman',           'dob' => '2026-11-01', 'age' => '19 days','sex' => 'Male',   'bcg' => 'NOT YET',  'vax_date' => ''],
                        ['id' => 10, 'barangay' => 'Poblacion',       'reg_date' => '2026-11-15', 'child' => 'Amina S. Lucman',          'dob' => '2026-01-28', 'age' => '9 mos',  'sex' => 'Female', 'bcg' => 'INJECTED', 'vax_date' => '2026-11-15'],
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
                        $hepaInjected = in_array($row['id'], [1, 2, 5, 6, 7, 8, 10], true);
                        $hepaDate = $hepaInjected ? date('M j, Y', $dobTs) : '';
                        $hasDose1 = in_array($row['id'], [1, 4, 5, 7, 8, 10], true);
                        $hasDose2 = in_array($row['id'], [4, 5, 7, 8, 10], true);
                        $hasDose3 = in_array($row['id'], [5, 7, 8, 10], true);
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
                            'reg_date_fmt' => $regFmt,
                            'reg_date_full' => $regFull,
                            'dob_fmt' => $dobFmt,
                            'dob_full' => $dobFull,
                            'age' => $row['age'],
                            'age_full' => $ageFull,
                            'sex' => strtoupper($row['sex']),
                            'overview_note' => $overviewNotes[$row['id']] ?? '',
                            'imm1_note' => $imm1Notes[$row['id']] ?? '',
                            'imm1' => [
                                'bcg' => ['status' => $row['bcg'], 'date' => $vaxFmt],
                                'hepa' => ['status' => $hepaInjected ? 'INJECTED' : 'NOT YET', 'date' => $hepaDate],
                            ],
                            'imm2' => [
                                'dpt' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose1 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose1 ? date('M j, Y', strtotime('+6 weeks', $dobTs)) : ''],
                                    ['dose' => '2nd Dose', 'status' => $hasDose2 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose2 ? date('M j, Y', strtotime('+10 weeks', $dobTs)) : ''],
                                ],
                                'opv' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose1 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose1 ? date('M j, Y', strtotime('+6 weeks', $dobTs)) : ''],
                                    ['dose' => '2nd Dose', 'status' => $hasDose2 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose2 ? date('M j, Y', strtotime('+10 weeks', $dobTs)) : ''],
                                ],
                                'pcv' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose1 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose1 ? date('M j, Y', strtotime('+6 weeks', $dobTs)) : ''],
                                    ['dose' => '2nd Dose', 'status' => $hasDose2 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose2 ? date('M j, Y', strtotime('+10 weeks', $dobTs)) : ''],
                                    ['dose' => '3rd Dose', 'status' => $hasDose3 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose3 ? date('M j, Y', strtotime('+14 weeks', $dobTs)) : ''],
                                ],
                                'ipv' => [
                                    ['dose' => '1st Dose', 'status' => $hasDose3 ? 'INJECTED' : 'NOT YET', 'date' => $hasDose3 ? date('M j, Y', strtotime('+14 weeks', $dobTs)) : ''],
                                ],
                            ],
                        ];
                    ?>
                    <tr class="group hover:bg-sky-100 hover:shadow-sm transition-all duration-150 cursor-pointer"
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
                                        title="Edit record"
                                        class="p-1.5 text-slate-500 hover:text-cho-blue-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button type="button"
                                        id="tcl-btn-delete-<?= $row['id'] ?>-2"
                                        title="Delete record"
                                        class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
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
        <div id="cho-nip-pop-footer2" class="px-4 py-3 border-t border-slate-200/90 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-slate-500">
            <span id="cho-nip-pop-count-status2" class="font-medium">Showing 10 of 10 infant records</span>
            <span id="cho-nip-pop-source-status2" class="text-[11px] text-slate-400">Source: Target Client List (10 Mock Samples)</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchEl = document.getElementById('cho-nip-pop-search2');
            const clearEl = document.getElementById('cho-nip-pop-search-clear2');
            const filterEl = document.getElementById('cho-nip-pop-filter2');
            const tbodyEl = document.getElementById('cho-nip-pop-tbody2');
            const countEl = document.getElementById('cho-nip-pop-count-status2');
            if (!tbodyEl) return;

            const rows = Array.from(tbodyEl.querySelectorAll('tr'));
            const applyFilter = () => {
                const q = (searchEl?.value || '').trim().toLowerCase();
                const f = filterEl?.value || 'all';
                if (clearEl) clearEl.classList.toggle('hidden', q.length === 0);

                let visible = 0;
                rows.forEach((tr) => {
                    const text = tr.textContent.toLowerCase();
                    const bcg = tr.dataset.bcg || '';
                    const sex = tr.dataset.sex || '';
                    const matchQuery = !q || text.includes(q);
                    const matchFilter =
                        f === 'all' ||
                        (f === 'injected' && bcg === 'INJECTED') ||
                        (f === 'not_yet' && bcg === 'NOT YET') ||
                        (f === 'male' && sex === 'male') ||
                        (f === 'female' && sex === 'female');

                    const show = matchQuery && matchFilter;
                    tr.classList.toggle('hidden', !show);
                    if (show) visible++;
                });

                if (countEl) countEl.textContent = `Showing ${visible} of ${rows.length} infant records`;
            };

            searchEl?.addEventListener('input', applyFilter);
            filterEl?.addEventListener('change', applyFilter);
            clearEl?.addEventListener('click', () => {
                if (searchEl) searchEl.value = '';
                applyFilter();
            });

            // Auto-sync BCG badge when user enters or clears Date of Vaccination
            tbodyEl.querySelectorAll('input[type="date"]').forEach((input) => {
                input.addEventListener('change', () => {
                    const rowId = input.dataset.rowId;
                    const tr = input.closest('tr');
                    const badge = document.getElementById(`tcl-bcg-badge-${rowId}-2`);
                    const hasDate = Boolean(input.value);
                    const status = hasDate ? 'INJECTED' : 'NOT YET';
                    if (tr) tr.dataset.bcg = status;
                    if (badge) {
                        badge.textContent = status;
                        badge.className = `inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-extrabold tracking-wide rounded-full border shadow-xs ${
                            hasDate
                                ? 'bg-emerald-600 text-white border-emerald-700'
                                : 'bg-rose-600 text-white border-rose-700'
                        }`;
                    }
                    applyFilter();
                });
            });
        });
    </script>

</div>

<!-- =============================================================================
     FLOWBITE RIGHT DRAWER 1: ADD BARANGAY POPULATION ENTRY
     ============================================================================= -->
<div id="drawer-nip-pop-add2"
     data-cho-nip-drawer
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 w-full sm:w-[480px] md:w-[520px] max-w-full shadow-2xl flex flex-col justify-between"
     tabindex="-1"
     aria-labelledby="drawer-nip-pop-add-label2"
     aria-hidden="true">

    <div class="space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h5 id="drawer-nip-pop-add-label2" class="inline-flex items-center gap-2 text-base font-bold text-slate-900">
                <svg class="w-5 h-5 text-cho-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Barangay Population Entry
            </h5>
            <button type="button"
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
            Appends a new barangay row directly into the <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700 font-mono">MARAWI POP 2024</code> worksheet of the active Excel workbook.
        </p>

        <form id="nip-pop-add-form2" class="space-y-3.5">
            <div>
                <label for="nip-pop-add-psgc102" class="block mb-1 text-xs font-semibold text-slate-700">PSGC10 Code</label>
                <input type="text" id="nip-pop-add-psgc102" placeholder="e.g. 1903617144" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
            </div>
            <div>
                <label for="nip-pop-add-barangay2" class="block mb-1 text-xs font-semibold text-slate-700">Barangay / Locality</label>
                <input type="text" id="nip-pop-add-barangay2" placeholder="e.g. New Barangay Name" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-add-total-pop2" class="block mb-1 text-xs font-semibold text-slate-700">Total Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-add-total-pop2" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
                <div>
                    <label for="nip-pop-add-household-pop2" class="block mb-1 text-xs font-semibold text-slate-700">Household Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-add-household-pop2" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-add-households2" class="block mb-1 text-xs font-semibold text-slate-700">Households 2024</label>
                    <input type="number" min="0" id="nip-pop-add-households2" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
                <div>
                    <label for="nip-pop-add-nip-pop2" class="block mb-1 text-xs font-semibold text-slate-700">NIP 73-Brgy Pop (Optional)</label>
                    <input type="number" min="0" id="nip-pop-add-nip-pop2" value="0"
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
            </div>

            <div id="nip-pop-add-feedback2" class="hidden"></div>

            <button type="submit"
                    id="nip-btn-submit-pop-add2"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-add-spinner2" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-5 h-5 text-cho-blue-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="nip-btn-submit-pop-add-text2">Save New Entry to Excel</span>
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
<div id="drawer-nip-pop-edit2"
     data-cho-nip-drawer
     class="fixed top-0 right-0 z-50 h-screen p-5 sm:p-6 overflow-y-auto transition-transform translate-x-full bg-white border-l border-slate-200 w-full sm:w-[480px] md:w-[520px] max-w-full shadow-2xl flex flex-col justify-between"
     tabindex="-1"
     aria-labelledby="drawer-nip-pop-edit-label2"
     aria-hidden="true">

    <div class="space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h5 id="drawer-nip-pop-edit-label2" class="inline-flex items-center gap-2 text-base font-bold text-slate-900">
                <svg class="w-5 h-5 text-cho-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Barangay Population Entry
            </h5>
            <button type="button"
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
            Modifies the exact Excel cells in <code class="px-1 py-0.5 bg-slate-100 rounded text-slate-700 font-mono">MARAWI POP 2024</code> (<span id="nip-pop-edit-row-badge2" class="font-semibold text-slate-700">Row --</span>) while preserving all workbook formulas.
        </p>

        <form id="nip-pop-edit-form2" class="space-y-3.5">
            <input type="hidden" id="nip-pop-edit-excel-row2" />
            <input type="hidden" id="nip-pop-edit-nip-row2" />
            <input type="hidden" id="nip-pop-edit-row-index2" />

            <div>
                <label for="nip-pop-edit-psgc102" class="block mb-1 text-xs font-semibold text-slate-700">PSGC10 Code</label>
                <input type="text" id="nip-pop-edit-psgc102" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
            </div>
            <div>
                <label for="nip-pop-edit-barangay2" class="block mb-1 text-xs font-semibold text-slate-700">Barangay / Locality</label>
                <input type="text" id="nip-pop-edit-barangay2" required
                       class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-edit-total-pop2" class="block mb-1 text-xs font-semibold text-slate-700">Total Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-edit-total-pop2" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
                <div>
                    <label for="nip-pop-edit-household-pop2" class="block mb-1 text-xs font-semibold text-slate-700">Household Pop 2024</label>
                    <input type="number" min="0" id="nip-pop-edit-household-pop2" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="nip-pop-edit-households2" class="block mb-1 text-xs font-semibold text-slate-700">Households 2024</label>
                    <input type="number" min="0" id="nip-pop-edit-households2" required
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
                <div>
                    <label for="nip-pop-edit-nip-pop2" class="block mb-1 text-xs font-semibold text-slate-700">NIP 73-Brgy Pop</label>
                    <input type="number" min="0" id="nip-pop-edit-nip-pop2"
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-cho-blue-600 focus:border-cho-blue-600 block w-full px-3 py-2" />
                </div>
            </div>

            <div id="nip-pop-edit-feedback2" class="hidden"></div>

            <button type="submit"
                    id="nip-btn-submit-pop-edit2"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-cho-blue-900 hover:bg-cho-blue-950 focus:ring-4 focus:ring-cho-blue-300/40 rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                <div id="nip-btn-submit-pop-edit-spinner2" role="status" class="hidden text-center">
                    <svg aria-hidden="true" class="inline w-5 h-5 text-cho-blue-200/40 animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span id="nip-btn-submit-pop-edit-text2">Update Excel Cells</span>
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
                        <div class="text-sm font-black text-slate-900 mt-0.5">Hepa B-BD</div>
                    </div>
                    <span id="tcl-imm1-hepa-badge2"></span>
                </div>
                <!-- Cell 4: Hepa B-BD Date -->
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
                <span class="text-[11px] text-slate-400 font-medium">DPT-HiB-HepB &bull; OPV &bull; PCV &bull; IPV</span>
            </div>
            <div id="tcl-imm2-grid2" class="grid grid-cols-1 gap-3">
                <!-- Card 1: DPT-HiB-HepB (1st & 2nd Dose) -->
                <div id="tcl-imm2-dpt-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>DPT-HiB-HepB</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3l4 4" />
                                <path d="M16.5 11.5l-6.5 6.5h-4v-4l6.5 -6.5" />
                                <path d="M3 21l3 -3" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">2 Doses</span>
                    </div>
                    <div id="tcl-imm2-dpt-list2" class="divide-y divide-slate-100"></div>
                </div>

                <!-- Card 2: OPV (1st & 2nd Dose) -->
                <div id="tcl-imm2-opv-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>OPV</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546z" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">2 Doses</span>
                    </div>
                    <div id="tcl-imm2-opv-list2" class="divide-y divide-slate-100"></div>
                </div>

                <!-- Card 3: PCV (1st, 2nd & 3rd Dose) -->
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

                <!-- Card 4: IPV (1st Dose) -->
                <div id="tcl-imm2-ipv-card2" class="bg-white border border-slate-200/90 rounded-xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h6 class="inline-flex items-center gap-1.5 text-sm font-black text-slate-900">
                            <span>IPV</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                        </h6>
                        <span class="text-[11px] font-semibold text-slate-400">1 Dose</span>
                    </div>
                    <div id="tcl-imm2-ipv-list2" class="divide-y divide-slate-100"></div>
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
