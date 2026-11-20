<?php
/**
 * Global Sidebar partial for CHO Marawi Portal.
 * Clean Flowbite Logo Sidebar using official CHO Marawi Logo.
 * Desktop: Left-docked (wider width: w-72).
 * Mobile: Right-side off-canvas drawer (translate-x-full to right-0).
 */
?>

<!-- Mobile Drawer Trigger Button (Positioned at top right for right-side drawer) -->
<div class="sm:hidden flex justify-end p-3">
   <button id="cho-sidebar-mobile-open-btn" data-drawer-placement="right" aria-controls="logo-sidebar" type="button" class="text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none inline-flex items-center">
      <span class="sr-only">Open sidebar</span>
      <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
         <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/>
      </svg>
   </button>
</div>

<!-- Sidebar: Desktop Left (w-72), Mobile Right Drawer (right-0 translate-x-full sm:translate-x-0 sm:left-0 sm:right-auto) -->
<aside id="logo-sidebar" class="fixed top-0 right-0 sm:left-0 sm:right-auto z-40 w-72 h-full transition-transform translate-x-full sm:translate-x-0" tabindex="-1" aria-label="Sidebar">
   <div class="relative h-full overflow-hidden bg-cho-blue-900 text-white border-s sm:border-s-0 sm:border-e border-cho-blue-800">
      <!-- Background Slideshow: CHO Building 1 & 2 (full top-to-bottom fill with 85% primary overlay) -->
      <div id="cho-sidebar-bg-slideshow" class="absolute inset-0 pointer-events-none">
         <img id="cho-sidebar-bg-slide-1"
              src="<?= asset('images/CHO-Building.png') ?>"
              alt=""
              aria-hidden="true"
              class="absolute inset-0 w-full h-full object-cover object-center opacity-100 transition-opacity duration-700 ease-in-out" />
         <img id="cho-sidebar-bg-slide-2"
              src="<?= asset('images/CHO-Building2.png') ?>"
              data-fallback-src="<?= asset('images/CHO-Building.png') ?>"
              alt=""
              aria-hidden="true"
              class="absolute inset-0 w-full h-full object-cover object-center opacity-0 transition-opacity duration-700 ease-in-out" />
      </div>
      <div class="absolute inset-0 bg-cho-blue-900/85 pointer-events-none"></div>

      <div class="relative z-10 h-full px-4 py-5 overflow-y-auto">
      
      <!-- Header with CHO Logo and Close Button (visible on mobile) -->
      <div class="flex items-center justify-between ps-2.5 mb-6">
         <a href="<?= url('/dashboard') ?>" class="flex items-center">
            <img src="<?= asset('images/cho-logo.png') ?>" class="h-8 me-3 drop-shadow-sm" alt="CHO Logo" />
            <span class="self-center text-lg text-white font-bold whitespace-nowrap tracking-tight">CHO MARAWI</span>
         </a>
         <button type="button" id="cho-sidebar-mobile-close-btn" aria-controls="logo-sidebar" class="sm:hidden text-white/80 bg-transparent hover:text-white hover:bg-white/10 rounded-base w-8 h-8 flex items-center justify-center p-1 cursor-pointer">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
            <span class="sr-only">Close sidebar</span>
         </button>
      </div>

      <ul id="cho-sidebar-nav-list" class="space-y-2 font-medium">
         <!-- Homepage (Formerly Dashboard) -->
         <li id="cho-sidebar-item-dashboard">
            <a href="<?= url('/dashboard') ?>" id="cho-sidebar-link-dashboard" class="flex items-center px-3 py-2 text-white/95 rounded-base hover:bg-white/10 hover:text-white group transition-colors">
               <svg id="cho-sidebar-icon-dashboard" class="w-5 h-5 text-white/90 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
               </svg>
               <span id="cho-sidebar-text-dashboard" class="ms-3 text-sm font-semibold">Homepage</span>
            </a>
         </li>

         <!-- NIP Program System Multi-level Dropdown (Role Based) -->
         <li id="cho-sidebar-item-nip-system">
            <button type="button" id="cho-nip-dropdown-btn" data-collapse-toggle="cho-nip-dropdown-menu" aria-controls="cho-nip-dropdown-menu" aria-expanded="false" class="flex items-center justify-between w-full px-3 py-2 text-white/95 rounded-base hover:bg-white/10 hover:text-white group transition-colors cursor-pointer">
               <div class="flex items-center">
                  <svg id="cho-sidebar-icon-nip" class="shrink-0 w-5 h-5 text-white/90 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10"/>
                  </svg>
                  <span class="ms-3 text-sm font-semibold whitespace-nowrap">NIP Program System</span>
               </div>
               <svg id="cho-nip-dropdown-chevron" class="w-4 h-4 transition-transform duration-200 cho-dropdown-chevron group-aria-expanded:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
               </svg>
            </button>

            <!-- NIP Submenu Items -->
            <ul id="cho-nip-dropdown-menu" class="hidden py-1 pl-6 space-y-1">
               <li id="cho-sidebar-subitem-nip-dash">
                  <a href="<?= url('/frontend/pages/NIP/dashboard/') ?>" id="cho-sidebar-link-nip-dash" class="flex items-center p-2 text-xs font-semibold text-white/90 rounded-base hover:bg-white/10 hover:text-white transition-colors group">
                     <svg id="cho-sidebar-icon-nip-dash" class="w-4 h-4 me-2 text-white/85 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z"/>
                     </svg>
                     Dashboard
                  </a>
               </li>
               <li id="cho-sidebar-subitem-nip-population">
                  <a href="<?= url('/frontend/pages/NIP/population/') ?>" id="cho-sidebar-link-nip-population" class="flex items-center p-2 text-xs font-semibold text-white/90 rounded-base hover:bg-white/10 hover:text-white transition-colors group">
                     <svg id="cho-sidebar-icon-nip-population" class="w-4 h-4 me-2 text-white/85 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
                     </svg>
                     Marawi Population
                  </a>
               </li>
               <li id="cho-sidebar-subitem-nip-reports">
                  <a href="#" id="cho-sidebar-link-nip-reports" class="flex items-center p-2 text-xs font-semibold text-white/90 rounded-base hover:bg-white/10 hover:text-white transition-colors group">
                     <svg class="w-4 h-4 me-2 text-white/85 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                     </svg>
                     Quarterly Reports
                  </a>
               </li>
               <li id="cho-sidebar-subitem-nip-stock">
                  <a href="#" id="cho-sidebar-link-nip-stock" class="flex items-center p-2 text-xs font-semibold text-white/90 rounded-base hover:bg-white/10 hover:text-white transition-colors group">
                     <svg class="w-4 h-4 me-2 text-white/85 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                     </svg>
                     Vaccine Stock Log
                  </a>
               </li>
            </ul>
         </li>

         <!-- NIP Program System 2 Multi-level Dropdown (Database-Driven) -->
         <li id="cho-sidebar-item-nip-system2">
            <button type="button" id="cho-nip-dropdown-btn2" data-collapse-toggle="cho-nip-dropdown-menu2" aria-controls="cho-nip-dropdown-menu2" aria-expanded="false" class="flex items-center justify-between w-full px-3 py-2 text-white/95 rounded-base hover:bg-white/10 hover:text-white group transition-colors cursor-pointer">
               <div class="flex items-center">
                  <svg id="cho-sidebar-icon-nip2" class="shrink-0 w-5 h-5 text-white/90 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10"/>
                  </svg>
                  <span class="ms-3 text-sm font-semibold whitespace-nowrap">NIP Program System 2</span>
               </div>
               <svg id="cho-nip-dropdown-chevron2" class="w-4 h-4 transition-transform duration-200 cho-dropdown-chevron group-aria-expanded:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
               </svg>
            </button>

            <!-- NIP2 Submenu Items -->
            <ul id="cho-nip-dropdown-menu2" class="hidden py-1 pl-6 space-y-1">
               <li id="cho-sidebar-subitem-nip-dash2">
                  <a href="<?= url('/frontend/pages/NIP2/dashboard/') ?>" id="cho-sidebar-link-nip-dash2" class="flex items-center p-2 text-xs font-semibold text-white/90 rounded-base hover:bg-white/10 hover:text-white transition-colors group">
                     <svg id="cho-sidebar-icon-nip-dash2" class="w-4 h-4 me-2 text-white/85 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z"/>
                     </svg>
                     Dashboard
                  </a>
               </li>
               <li id="cho-sidebar-subitem-nip-population2">
                  <a href="<?= url('/frontend/pages/NIP2/population/') ?>" id="cho-sidebar-link-nip-population2" title="TARGET CLIENT LIST FOR CHILD HEALTH CARE NEWBORN AND INFANTS AGE 0-12 MONTHS OLD" class="flex items-center p-2 text-xs font-semibold text-white/90 rounded-base hover:bg-white/10 hover:text-white transition-colors group">
                     <svg id="cho-sidebar-icon-nip-population2" class="w-4 h-4 me-2 shrink-0 text-white/85 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M16 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M2 5h2.5l1.632 4.897a6 6 0 0 0 5.693 4.103h2.675a5.5 5.5 0 0 0 0 -11h-.5v6" />
                        <path d="M6 9h14" />
                        <path d="M9 17l1 -3" />
                        <path d="M16 14l1 3" />
                     </svg>
                     <span class="truncate">Newborns Baby</span>
                  </a>
               </li>
            </ul>
         </li>

         <li id="cho-sidebar-item-inbox">
            <a href="#" id="cho-sidebar-link-inbox" class="flex items-center px-3 py-2 text-white/95 rounded-base hover:bg-white/10 hover:text-white group transition-colors">
               <svg id="cho-sidebar-icon-inbox" class="shrink-0 w-5 h-5 text-white/90 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13h3.439a.991.991 0 0 1 .908.6 3.978 3.978 0 0 0 7.306 0 .99.99 0 0 1 .908-.6H20M4 13v6a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-6M4 13l2-9h12l2 9M9 7h6m-7 3h8"/></svg>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm font-semibold">Inbox</span>
               <span class="inline-flex items-center justify-center w-5 h-5 ms-2 text-xs font-bold text-fg-danger-strong bg-danger-soft border border-danger-subtle rounded-full">2</span>
            </a>
         </li>
         <li id="cho-sidebar-item-users">
            <a href="#" id="cho-sidebar-link-users" class="flex items-center px-3 py-2 text-white/95 rounded-base hover:bg-white/10 hover:text-white group transition-colors">
               <svg id="cho-sidebar-icon-users" class="shrink-0 w-5 h-5 text-white/90 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm font-semibold">Users</span>
            </a>
         </li>
         <li id="cho-sidebar-item-auth">
            <a href="#" id="cho-logout-btn" class="flex items-center px-3 py-2 text-white/95 rounded-base hover:bg-rose-500/20 hover:text-rose-200 group transition-colors">
               <svg id="cho-sidebar-icon-logout" class="shrink-0 w-5 h-5 text-white/90 transition duration-75 group-hover:text-rose-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm font-semibold">Log out</span>
            </a>
         </li>
      </ul>
      </div>
   </div>
</aside>
