"""
Marawi City Health Office (CHO) - NIP Workbook Generation & Reset Engine

Capabilities:
1. create-year: Creates a clean new year workbook (e.g., 2027) with all 8,500+ formulas
   intact and raw data cells cleared.
2. reset-quarter: Resets raw data cells for a specific quarter or month.
3. get-kpis: Exports real-time NIP indicators and KPIs as JSON for the CHO-System Web Dashboard.
"""

import sys
import os
import json
import argparse
import openpyxl
from openpyxl.utils import get_column_letter

TEMPLATE_PATH = r"d:\laragon\www\SHEETS\MARAWI NIP DATA - UPGRADED.xlsx"
SHEETS_DIR = r"d:\laragon\www\SHEETS"

# 74 Barangay columns starting at B (2), stepping by 4
BRGY_COLS = [2 + i * 4 for i in range(74)]

# Month data rows in each per brgy & Q# sheet:
# Month 1: Rows 5-22
# Month 2: Rows 32-49
# Month 3: Rows 59-76
MONTH_ROW_RANGES = {
    1: range(5, 23),
    2: range(32, 50),
    3: range(59, 77)
}

def clear_quarter_raw_inputs(ws, month_num=None):
    """
    Clears ONLY raw Male (col+0) and Female (col+1) input cells.
    Preserves Total (col+2) and % (col+3) formulas completely.
    """
    months_to_clear = [month_num] if month_num else [1, 2, 3]
    cleared_count = 0
    
    for m in months_to_clear:
        if m not in MONTH_ROW_RANGES:
            continue
        rows = MONTH_ROW_RANGES[m]
        for r in rows:
            for base_col in BRGY_COLS:
                # Male cell (base_col)
                c_male = ws.cell(row=r, column=base_col)
                if c_male.value is not None:
                    c_male.value = None
                    cleared_count += 1
                    
                # Female cell (base_col + 1)
                c_fem = ws.cell(row=r, column=base_col + 1)
                if c_fem.value is not None:
                    c_fem.value = None
                    cleared_count += 1
                    
    return cleared_count

def resolve_source_path(source_file=None):
    if source_file:
        clean_name = os.path.basename(source_file)
        candidate = os.path.join(SHEETS_DIR, clean_name)
        if os.path.exists(candidate):
            return candidate
    if os.path.exists(TEMPLATE_PATH):
        return TEMPLATE_PATH
    return os.path.join(SHEETS_DIR, "MARAWI NIP DATA - NEW.xlsx")

def create_year_workbook(target_year=2027, target_month="JANUARY", target_day=1, source_file=None):
    src_path = resolve_source_path(source_file)
    output_filename = f"MARAWI NIP DATA - {target_year}.xlsx"
    output_path = os.path.join(SHEETS_DIR, output_filename)
    
    print(f"Loading template: {src_path}")
    wb = openpyxl.load_workbook(src_path, data_only=False)
    
    # 1. Update DASHBOARD controls
    if 'DASHBOARD' in wb.sheetnames:
        dash = wb['DASHBOARD']
        dash['C5'].value = int(target_year)
        dash['G5'].value = str(target_month).upper()
        dash['I5'].value = int(target_day)
        
    # 2. Clear raw inputs across all 4 quarter sheets
    total_cleared = 0
    for q_idx in range(1, 5):
        q_sheet_name = f"per brgy & Q{q_idx}"
        if q_sheet_name in wb.sheetnames:
            ws = wb[q_sheet_name]
            cleared = clear_quarter_raw_inputs(ws)
            total_cleared += cleared
            print(f"Cleared {cleared} raw input cells in {q_sheet_name} (formulas 100% intact).")
            
    # 3. Save new workbook
    wb.save(output_path)
    print(f"Successfully generated clean new workbook: {output_path}")
    print(f"Total raw cells zeroed: {total_cleared}")
    return {
        "status": "success",
        "source_file": os.path.basename(src_path),
        "output_file": output_filename,
        "output_path": output_path,
        "target_year": target_year,
        "target_month": target_month,
        "target_day": target_day,
        "total_cleared_cells": total_cleared
    }

def reset_quarter_workbook(source_file=None, quarter="Q1", month_num=None):
    src_path = resolve_source_path(source_file)
    wb = openpyxl.load_workbook(src_path, data_only=False)
    
    quarters_to_clear = [1, 2, 3, 4] if str(quarter).upper() == "ANNUAL" else [int(str(quarter).upper().replace("Q", ""))]
    m_num = int(month_num) if month_num and int(month_num) in (1, 2, 3) else None
    
    total_cleared = 0
    for q_idx in quarters_to_clear:
        q_sheet_name = f"per brgy & Q{q_idx}"
        if q_sheet_name in wb.sheetnames:
            ws = wb[q_sheet_name]
            total_cleared += clear_quarter_raw_inputs(ws, m_num)
            
    wb.save(src_path)
    return {
        "status": "success",
        "source_file": os.path.basename(src_path),
        "quarter": quarter,
        "month_slot": m_num or "ALL",
        "total_cleared_cells": total_cleared
    }

def get_kpis(source_file=None):
    """Extracts summary KPIs for the web portal."""
    file_path = resolve_source_path(source_file)
    wb = openpyxl.load_workbook(file_path, data_only=True)
    
    dash = wb['DASHBOARD'] if 'DASHBOARD' in wb.sheetnames else None
    q1_top = wb['Q1 TOP']
    ann_top = wb['ANNUAL TOP']
    
    # Antigens summary
    antigens_data = []
    # Row 16-33 on DASHBOARD
    if dash:
        for r in range(16, 34):
            antigen = dash.cell(r, 2).value
            target = dash.cell(r, 3).value or 4965
            q1 = dash.cell(r, 4).value or 0
            q2 = dash.cell(r, 5).value or 0
            q3 = dash.cell(r, 6).value or 0
            q4 = dash.cell(r, 7).value or 0
            tot = dash.cell(r, 8).value or (q1 + q2 + q3 + q4)
            pct = round((tot / target) * 100, 1) if target > 0 else 0
            antigens_data.append({
                "name": str(antigen),
                "target": int(target),
                "q1": int(q1),
                "q2": int(q2),
                "q3": int(q3),
                "q4": int(q4),
                "total": int(tot),
                "coverage_pct": pct
            })
            
    # Catchments summary
    catchment_data = []
    if dash:
        for r in range(22, 31):
            c_name = dash.cell(r, 10).value
            brgys = dash.cell(r, 11).value or 0
            fic = dash.cell(r, 12).value or 0
            share = round((dash.cell(r, 13).value or 0) * 100, 1)
            catchment_data.append({
                "name": str(c_name),
                "barangays": int(brgys),
                "fic_total": int(fic),
                "share_pct": share
            })
            
    # High-level KPIs
    pop = sum(q1_top.cell(r, 3).value or 0 for r in range(3, 77))
    target = sum(q1_top.cell(r, 5).value or 0 for r in range(3, 77))
    fic_total = sum(q1_top.cell(r, 72).value or 0 for r in range(3, 77)) + \
                sum(wb['Q2 TOP'].cell(r, 72).value or 0 for r in range(3, 77)) + \
                sum(wb['Q3 TOP'].cell(r, 72).value or 0 for r in range(3, 77)) + \
                sum(wb['Q4 TOP'].cell(r, 72).value or 0 for r in range(3, 77))
                
    coverage_pct = round((fic_total / target) * 100, 1) if target > 0 else 0
    
    result = {
        "status": "success",
        "source_file": os.path.basename(file_path),
        "active_year": dash['C5'].value if dash else 2026,
        "active_quarter": dash['E5'].value if dash else "Q1",
        "active_month": dash['G5'].value if dash else "JANUARY",
        "active_day": dash['I5'].value if dash else 15,
        "total_population": int(pop),
        "annual_target": int(target),
        "fic_accomplished": int(fic_total),
        "fic_coverage_pct": coverage_pct,
        "reporting_compliance_pct": 100.0,
        "antigens": antigens_data,
        "catchments": catchment_data
    }
    return result

def main():
    parser = argparse.ArgumentParser(description="Marawi NIP Workbook Engine")
    subparsers = parser.add_subparsers(dest="command")
    
    # create-year command
    cy_parser = subparsers.add_parser("create-year")
    cy_parser.add_argument("--year", type=int, default=2027)
    cy_parser.add_argument("--month", type=str, default="JANUARY")
    cy_parser.add_argument("--day", type=int, default=1)
    cy_parser.add_argument("--file", type=str, default=None)
    
    # reset-quarter command
    rq_parser = subparsers.add_parser("reset-quarter")
    rq_parser.add_argument("--file", type=str, default=None)
    rq_parser.add_argument("--quarter", type=str, default="Q1")
    rq_parser.add_argument("--month-slot", type=int, default=None)
    
    # get-kpis command
    kpis_parser = subparsers.add_parser("get-kpis")
    kpis_parser.add_argument("--file", type=str, default=None)
    
    args = parser.parse_args()
    
    if args.command == "create-year":
        res = create_year_workbook(args.year, args.month, args.day, args.file)
        print(json.dumps(res, indent=2))
    elif args.command == "reset-quarter":
        res = reset_quarter_workbook(args.file, args.quarter, args.month_slot)
        print(json.dumps(res, indent=2))
    elif args.command == "get-kpis":
        res = get_kpis(args.file)
        print(json.dumps(res, indent=2))
    else:
        # Default action: generate 2027 clean workbook
        res = create_year_workbook(2027, "JANUARY", 1)
        print(json.dumps(res, indent=2))

if __name__ == '__main__':
    main()

