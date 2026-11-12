# City Health Office (CHO) Marawi Portal — National Immunization Program (NIP) System

The **CHO Marawi Portal** is a public health intelligence and operations management system tailored for the City Health Office of Marawi City. It focuses on the **National Immunization Program (NIP / EPI)** across Marawi's **74 Master Barangays** and **4 Main Catchments**.

---

## 🌟 Key Features

- **NIP / EPI Health Intelligence Dashboard**:
  - Live cohort and performance KPI cards with smooth interactive hover effects and thematic photography.
  - Interactive **18-Antigen Routine Immunization Performance** column chart (target vs. accomplished).
  - Catchment quota distribution and Fully Immunized Child (FIC) share donut charts.
  - Dynamic switching between empty analytics placeholder canvas and live data engine visualizer.

- **Automated Excel Data Engine & Workbook Operations**:
  - Seamless handling of `.xlsx` / `.xlsm` workbooks with **8,500+ formulas** preserved.
  - **Load / Switch Active Sheet**: Automatic detection of workspace sheets (`d:/laragon/www/SHEETS`) and instant drag-and-drop file upload.
  - **Create Blank Year Sheet**: Safely zeros out 26,400+ raw entries while keeping all formulas 100% intact.
  - **Quarter Input Reset Tool**: Targeted reset for Q1–Q4 raw input entries with automatic timestamped backups.
  - **Direct Master Export**: Instant download of formula-intact master workbooks.
  - **Zero-Python Client Ready**: Supports pure client-side processing using `exceljs` alongside PHP 8.3 & openpyxl server capabilities.

- **Modern Architecture & UI**:
  - **Tailwind CSS v4** + **Flowbite v4** component library.
  - **ApexCharts** data visualizations.
  - Dedicated right-side action drawers with backdrop protection preventing sidebar overlap.
  - Fully responsive layout for desktop, tablet, and mobile clinics.

---

## 🛠 Tech Stack

- **Backend**: PHP 8.3 (MVC, PSR-4, custom routing, CSRF protection, secure sessions)
- **Frontend**: Vanilla ES Modules, Tailwind CSS v4, Flowbite v4, ApexCharts
- **Build Tool**: Vite v8
- **Spreadsheet Processing**: ExcelJS (NPM), openpyxl (Python engine fallback), native PHP streaming
- **Database / Cache**: MySQL / MariaDB, local storage, secure cookies

---

## 🚀 Getting Started

### Prerequisites
- [Laragon](https://laragon.org/) or any local server running **PHP 8.3+** and **MySQL/MariaDB**
- [Node.js](https://nodejs.org/) (v18+) & NPM

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/markjordanugtongspc/cho-system.git
   cd cho-system
   ```

2. **Install frontend & node dependencies:**
   ```bash
   npm install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your local database credentials and app URL.

4. **Run the Vite Dev Server:**
   ```bash
   npm run dev
   ```

5. **Build for Production:**
   ```bash
   npm run build
   ```

---

## 📂 Project Structure

```
CHO-System/
├── backend/
│   ├── bootstrap.php            # Core application loader
│   ├── controllers/             # NipController, AuthController, etc.
│   ├── core/                    # Router, Request, Response, CSRF, Session
│   └── routes/                  # API and web route registrations
├── frontend/
│   ├── assets/
│   │   ├── css/                 # Tailwind CSS styles
│   │   ├── js/                  # ES Modules (charts.js, drawer.js, api.js, etc.)
│   │   └── images/              # Icons and NIP thematic photography
│   ├── layouts/                 # head.php, sidebar.php, footer.php
│   └── pages/                   # NIP dashboard, login, settings
├── config/                      # App configuration
├── database/                    # Migrations and seeds
├── dist/                        # Production compiled Vite assets
└── index.php                    # Web entry point
```

---

## 🏛 City Health Office — Marawi City
Developed for the City Health Office (CHO) of Marawi City to streamline data reporting, eliminate manual clerical errors, and ensure high immunization coverage for every child.

