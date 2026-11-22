# CHO MARAWI PORTAL — UPDATED KNOWLEDGE BASE & LLM INSTRUCTION SKILL

**Location:** `d:\laragon\www\GITHUB\cho-system\UPDATED_KNOWLEDGE_BASE.md`  
**Stack:** PHP 8+, Vite 8, Tailwind CSS v4.3+, Flowbite v4, Vanilla ES Modules (OOP Subchild Architecture)

---

## 1. Mandatory Coding Conventions for LLMs
1. **Strict `START` and `END` Comments:** Every new or updated function, method, or subchild module MUST be wrapped with explicit `// START - <name>: <purpose>` and `// END - <name>` comments.
2. **Subchild OOP Separation:** Never bloat existing classes with unrelated logic. Every new feature/controller must be created as a **separate Subchild Class** (e.g., `NipPopAddDrawer2`, `NipPopEditDrawer2`, `OfflineSyncMonitor`, `DrawerDraftCache`) or a dedicated subchild object (e.g., `Storage.offlineSync`) and registered in its parent manager (`Drawers` / `Storage`).
3. **UI Framework & Accessibility:**
   - Use **Flowbite** components (Off-Canvas Drawers with `rounded-l-2xl`, Flowbite Toasts, Flowbite Tooltips, Flowbite `animate-pulse` Skeletons).
   - **Never** use SweetAlert2.
   - Design for users aged **35–40+**: readable `text-sm`/`text-base` labels & inputs, comfortable padding, and clear `id` attributes (1–2 words or short kebab-case) on every button, input, and dropdown.
4. **Images & Media Standard (`.webp` + Lazy Loading):**
   - All photographic/banner assets in `frontend/assets/images/` MUST be in **`.webp`** format (except `cho-logo.png`, `favicon.png`, and `.svg` icons).
   - Add `loading="lazy" decoding="async"` to photo `<img>` tags so `initImageSkeletons()` in `frontend/assets/js/main.js` automatically shows a Flowbite `animate-pulse` skeleton until loaded.
   - **Do NOT** apply lazy-loading or skeletons to SVG icon grids (`#cho-quick-access-grid`, `#cho-systems-grid`, or `.svg` files).

---

## 2. Performance & Low-Bandwidth Architecture (20–30 Mbps Optimization)
1. **Dynamic `import()` Code Splitting:**
   - `exceljs` is dynamically imported inside `NipLoadDrawer.parseWorkbookDashboardStats()` (`drawer.js`) only when Excel parsing runs.
   - `apexcharts` is dynamically imported inside `Charts.loadApexCharts()` (`charts.js`) only on pages containing chart containers.
2. **Self-Hosted Fonts (Zero External Google Fonts Calls):**
   - `@fontsource/poppins` and `@fontsource/outfit` are bundled locally in `frontend/assets/js/main.js` (no `fonts.googleapis.com` `@import` in `style.css`).

---

## 3. IndexedDB Offline Layer (`Storage.offlineSync`) & `pending_sync`
Implemented as a standalone subchild on `Storage` in `frontend/assets/js/modules/storage.js` without altering existing `localStorage`/cookie helpers:
- **Database:** `cho_marawi_offline_db` (v1)
- **Object Stores:**
  1. `pending_sync` (`keyPath: 'id', autoIncrement: true`): Stores queued `{ endpoint, method, payload, label, createdAt }` mutations when offline or on network failure.
  2. `tcl_records` (`keyPath: 'id'`): Persists `NIP2` Target Client List (`0–12 Months`) records locally in IndexedDB and hydrates them on page load (`NipPopAddDrawer2.hydrateFromIndexedDb()`).

### How `OfflineSyncMonitor` Works (`drawer.js`)
1. **Heartbeat Detection:** Listens to `online` / `offline` events and probes real internet reachability (`https://www.gstatic.com/generate_204`, `mode: 'no-cors'`, `3.5s` timeout) every `15s` so offline mode is detected even on `localhost/` (Laragon).
2. **Offline Banner & 5-Day Cookie Preference:**
   - **Default (`#cho-nip-offline-toast`):** Compact bottom-center red-rose banner (`opacity-60 hover:opacity-100`).
   - **Dismiss to Sidebar (`#cho-nip-offline-toast-close`):** Smoothly dissolves out via `AppAnimations.banner.dissolveOut()` (`BannerAnimator` subchild in `animations.js`) and stores `cho_offline_banner_pos=sidebar` in cookies with a **5-day inactivity expiry** (preserved across logout, auto-destroyed after 5 days of inactivity).
   - **Trimmed Sidebar Banner (`#cho-sidebar-offline-banner` in `sidebar.php`):** Shown on refresh when `cho_offline_banner_pos=sidebar`. Clicking `#cho-sidebar-offline-close` dissolves it out and removes the cookie, returning to the default bottom-center position.
3. **Auto-Flush (`flushPendingSync`):** When the heartbeat confirms internet is back and stable, it dismisses both offline banners, pushes all `pending_sync` items via `Api.request()`, removes each synced record, and shows a bottom-right emerald `"Offline Queue Synced"` toast.

---

## 4. Temporary Drawer Draft Cache (`DrawerDraftCache`)
Implemented as a subchild class in `frontend/assets/js/modules/drawer.js`:
- **Storage:** `sessionStorage` with a **10-minute TTL** (`10 * 60 * 1000` ms).
- **Behavior:** Auto-saves `input`, `select`, and `textarea` values as the user types in `nip-pop-add-form`, `nip-pop-edit-form`, `nip-pop-add-form2`, and `nip-pop-edit-form2`, restoring them across accidental refreshes or page navigation.
- **Instant Cleanup:** Immediately destroys the draft on:
  1. Successful form submission (`clearFormDraft(formId)`).
  2. Clicking any drawer close button (`[data-cho-drawer-hide]`).
  3. Clicking logout (`clearAllDrafts()`).
  4. Closing the tab or browser (handled natively by `sessionStorage`).

---

## 5. Key Files Reference
- `frontend/assets/js/main.js` — Entrypoint, self-hosted fonts, `initImageSkeletons()`, module bootstrapper.
- `frontend/assets/js/modules/storage.js` — `Storage` + `offlineSync` IndexedDB subchild.
- `frontend/assets/js/modules/drawer.js` — `BaseDrawerController`, `NipPopAddDrawer`, `NipPopEditDrawer`, `NipPopAddDrawer2`, `NipPopEditDrawer2`, `TclInfantDrawer`, `OfflineSyncMonitor`, `DrawerDraftCache`.
- `frontend/assets/js/modules/charts.js` — Lazy `ApexCharts` loader + `NIP`/`NIP2` chart skeletons (`#cho-nip-charts-skeleton`, `#cho-nip-charts-skeleton2`).
- `frontend/assets/js/modules/animations.js` — `ButtonAnimator`, `IconMorpher`, `ElementTransition`, `MoneyCounterRunner`, and `BannerAnimator` (`AppAnimations.banner`).
- `frontend/layouts/sidebar.php` — Left-docked navigation sidebar + trimmed `#cho-sidebar-offline-banner`.
- `frontend/pages/NIP2/population/index.php` — Target Client List (`0–12 Months`), 96 Marawi City barangays search dropdown, colored Sex icon dropdown, At-Birth & Routine Infant Doses, toolbar filter/order icons with tooltips.
- `vite.config.js` & `backend/core/Vite.php` — `localhost/` HMR bridge + `.php` auto-reload watcher.
