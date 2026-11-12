# CHO MARAWI PORTAL — DASHBOARD & NIP INTEGRATION KNOWLEDGE BASE

**Location:** `d:\laragon\www\CHO-System\DASHBOARD_KNOWLEDGE_BASE.md`  
**Created:** November 11, 2026  
**Purpose:** Comprehensive architecture documentation, endpoint specifications, JavaScript handlers, and data contracts preserved prior to redesigning `frontend/pages/dashboard/index.php`.

---

## 1. Directory Structure & Key Files

```
d:\laragon\www\CHO-System\
├── backend\
│   ├── bootstrap.php                  # Core app loader, session, env init
│   ├── routes\
│   │   └── api.php                   # API route registrations (/api/v1/...)
│   └── controllers\
│       └── NipController.php          # Controller bridging Web <-> Excel Python Engine
├── frontend\
│   ├── assets\
│   │   ├── css\style.css             # Tailwind v4 master styles
│   │   └── js\
│   │       ├── main.js               # Entrypoint importing modules
│   │       └── modules\
│   │           ├── api.js            # CSRF-aware fetch wrapper
│   │           ├── sidebar.js        # Sidebar responsive toggle & backdrop
│   │           └── ...
│   ├── layouts\
│   │   ├── head.php                  # HTML <head>, meta tags, Vite assets injection
│   │   ├── sidebar.php               # Left-docked desktop (w-72) & mobile drawer
│   │   └── footer.php                # Global footer & closing tags
│   └── pages\
│       └── dashboard\
│           └── index.php             # Main Dashboard View (Target for redesign)
└── dist\                             # Production compiled Vite assets
```

---

## 2. Backend API Architecture & Endpoints

All endpoints are registered in [`backend/routes/api.php`](file:///d:/laragon/www/CHO-System/backend/routes/api.php) and handled by [`NipController.php`](file:///d:/laragon/www/CHO-System/backend/controllers/NipController.php).

### 2.1. GET `/api/v1/nip/files`
- **Method:** `GET`
- **Action:** Scans `d:\laragon\www\SHEETS` for all `.xlsx` and `.xlsm` workbooks.
- **Response Format:**
  ```json
  {
    "success": true,
    "message": "OK",
    "data": {
      "files": [
        {
          "name": "MARAWI NIP DATA - UPGRADED.xlsx",
          "size": 1354419,
          "size_kb": 1322.7,
          "modified": "2026-11-11 12:25:15",
          "is_default": true
        },
        {
          "name": "MARAWI NIP DATA - NEW.xlsx",
          "size": 1354414,
          "size_kb": 1322.7,
          "modified": "2026-11-11 12:25:15",
          "is_default": false
        }
      ],
      "active_default": "MARAWI NIP DATA - UPGRADED.xlsx"
    }
  }
  ```

### 2.2. POST `/api/v1/nip/upload`
- **Method:** `POST` (`multipart/form-data`)
- **Payload:** `workbook: <File>` (accepts `.xlsx`, `.xlsm`)
- **Action:** Validates file extension, sanitizes filename, and moves it to `d:\laragon\www\SHEETS`.
- **Response Format:**
  ```json
  {
    "success": true,
    "message": "Workbook 'CUSTOM_NIP.xlsx' uploaded and ready for selection!",
    "data": {
      "file": {
        "name": "CUSTOM_NIP.xlsx",
        "size_kb": 1024.5,
        "modified": "2026-11-11 12:30:00"
      }
    }
  }
  ```

### 2.3. GET `/api/v1/nip/kpis`
- **Method:** `GET`
- **Query Params:** `?file=MARAWI+NIP+DATA+-+UPGRADED.xlsx`
- **Action:** Executes `python nip_workbook_engine.py get-kpis --file <filename>` to extract cached values.
- **Response Format:**
  ```json
  {
    "success": true,
    "message": "OK",
    "data": {
      "status": "success",
      "source_file": "MARAWI NIP DATA - UPGRADED.xlsx",
      "active_year": 2026,
      "active_quarter": "Q1",
      "active_month": "JANUARY",
      "active_day": 15,
      "total_population": 176029,
      "annual_target": 4965,
      "fic_accomplished": 3661,
      "fic_coverage_pct": 73.7,
      "reporting_compliance_pct": 100.0,
      "antigens": [
        { "name": "1. CPAB", "target": 4965, "q1": 3545, "q2": 0, "q3": 0, "q4": 0, "total": 3545, "coverage_pct": 71.4 },
        { "name": "2. BCG", "target": 4965, "q1": 3213, "q2": 0, "q3": 0, "q4": 0, "total": 3213, "coverage_pct": 64.7 },
        { "name": "17. FIC", "target": 4965, "q1": 3661, "q2": 0, "q3": 0, "q4": 0, "total": 3661, "coverage_pct": 73.7 }
      ],
      "catchments": [
        { "name": "CHO C", "barangays": 21, "fic_total": 929, "share_pct": 25.4 },
        { "name": "CHO B", "barangays": 11, "fic_total": 567, "share_pct": 15.5 }
      ]
    }
  }
  ```

### 2.4. POST `/api/v1/nip/generate-year`
- **Method:** `POST` (`application/json`)
- **Payload:**
  ```json
  {
    "year": 2027,
    "month": "JANUARY",
    "day": 1,
    "source_file": "MARAWI NIP DATA - UPGRADED.xlsx"
  }
  ```
- **Action:** Executes `python nip_workbook_engine.py create-year --year 2027 --month JANUARY --day 1 --file <source_file>`.
  - Clones all 29 sheets.
  - Updates period parameters in `DASHBOARD!C5`, `G5`, `I5`.
  - Clears all **26,418** raw Male/Female input cells in `per brgy & Q1..Q4` (`B, C, F, G...` on rows `5-22`, `32-49`, `59-76`).
  - Keeps all **8,500+ formulas** 100% intact.
- **Response Format:**
  ```json
  {
    "success": true,
    "message": "Successfully generated clean workbook 'MARAWI NIP DATA - 2027.xlsx' from 'MARAWI NIP DATA - UPGRADED.xlsx'!",
    "data": {
      "status": "success",
      "source_file": "MARAWI NIP DATA - UPGRADED.xlsx",
      "output_file": "MARAWI NIP DATA - 2027.xlsx",
      "output_path": "d:\\laragon\\www\\SHEETS\\MARAWI NIP DATA - 2027.xlsx",
      "target_year": 2027,
      "total_cleared_cells": 26418
    },
    "download_url": "/CHO-System/api/v1/nip/download?file=MARAWI+NIP+DATA+-+2027.xlsx"
  }
  ```

### 2.5. POST `/api/v1/nip/reset-quarter`
- **Method:** `POST` (`application/json`)
- **Payload:**
  ```json
  {
    "source_file": "MARAWI NIP DATA - UPGRADED.xlsx",
    "quarter": "Q1",
    "month_slot": null
  }
  ```
- **Action:** Executes `python nip_workbook_engine.py reset-quarter --file <source_file> --quarter Q1`. Zeroes raw input cells for that quarter only.

### 2.6. GET `/api/v1/nip/download`
- **Method:** `GET`
- **Query Params:** `?file=MARAWI+NIP+DATA+-+UPGRADED.xlsx`
- **Action:** Streams the Excel file directly as binary download with `Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`.

---

## 3. Python Engine Reference (`nip_workbook_engine.py`)

Located at `d:\laragon\www\SHEETS\nip_workbook_engine.py`:
- `BRGY_COLS`: 74 columns starting at column 2 (B) with step 4 (`2 + i * 4`).
- `MONTH_ROW_RANGES`:
  - Month 1: Rows 5–22
  - Month 2: Rows 32–49
  - Month 3: Rows 59–76
- `clear_quarter_raw_inputs(ws, month_num)`:
  - Iterates rows and columns; clears only Male (`base_col`) and Female (`base_col + 1`).
  - Strictly leaves Total (`base_col + 2`) and `%` (`base_col + 3`) untouched.

---

## 4. Client-Side JavaScript Logic Snippet (Reference)

```javascript
const apiBase = window.location.pathname.includes('/CHO-System') ? '/CHO-System/api' : '/api';

// 1. Fetch available files
async function loadAvailableFiles() {
  const resp = await fetch(`${apiBase}/v1/nip/files`);
  const json = await resp.json();
  return json.data.files;
}

// 2. Fetch KPIs for chosen file
async function loadFileKpis(fileName) {
  const resp = await fetch(`${apiBase}/v1/nip/kpis?file=${encodeURIComponent(fileName)}`);
  return await resp.json();
}

// 3. Generate clean workbook
async function generateCleanWorkbook(params) {
  const resp = await fetch(`${apiBase}/v1/nip/generate-year`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(params)
  });
  return await resp.json();
}

// 4. Upload file
async function uploadWorkbook(fileBlob) {
  const fd = new FormData();
  fd.append('workbook', fileBlob);
  const resp = await fetch(`${apiBase}/v1/nip/upload`, {
    method: 'POST',
    body: fd
  });
  return await resp.json();
}
```

---

## 5. Layout Dependencies To Keep Untouched
- **`frontend/layouts/head.php`**: Includes meta tags, CSRF token, and Vite tags.
- **`frontend/layouts/sidebar.php`**: Desktop sidebar (`w-72`) and mobile drawer (`id="logo-sidebar"`).
- **`frontend/layouts/footer.php`**: Scripts and closing body tags.
- **`frontend/assets/js/modules/sidebar.js`**: Handles drawer toggle and backdrop events.

