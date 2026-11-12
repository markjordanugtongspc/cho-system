"""
Marawi City Health Office (CHO) - National Immunization Program (NIP)
Master Workbook Modernization & Automation Engine

Compliant with Anthropic xlsx skill:
- Fixes #REF! formula errors in submission counters across all 4 quarter sheets
- Corrects formula offset typo in Q4 TOP!T3
- Merges Vaccine Stock status from 'new template.xlsx' into VACCINE STOCK tab
- Generates normalized NIP_FACT_DATA flat table (74 brgys x 4 quarters)
- Generates interactive DASHBOARD tab with KPI cards, Year/Month/Day controls,
  action buttons, 2 Column Charts, and 2 Pie Charts.
"""

import os
import sys
from datetime import datetime
import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
from openpyxl.utils import get_column_letter
from openpyxl.chart import BarChart, PieChart, Reference, Series
from openpyxl.worksheet.datavalidation import DataValidation

MASTER_PATH = r"d:\laragon\www\SHEETS\MARAWI NIP DATA - NEW.xlsx"
OUTPUT_PATH = r"d:\laragon\www\SHEETS\MARAWI NIP DATA - UPGRADED.xlsx"

# 74 Barangay Columns starting at B (2), step 4
BRGY_COLS = [2 + i * 4 for i in range(74)]
BRGY_COL_LETTERS = [get_column_letter(c) for c in BRGY_COLS]

# 18 NIP Antigens
ANTIGENS = [
    "1. CPAB",
    "2. BCG",
    "3. HepB, within 24 hours",
    "4. DPT- HiB- HepB 1",
    "5. DPT- HiB- HepB 2",
    "6. DPT- HiB- HepB 3",
    "7. OPV 1",
    "8. OPV 2",
    "9. OPV 3",
    "10. IPV",
    "11. IPV2",
    "12. PCV 1",
    "13. PCV 2",
    "14. PCV 3",
    "15. MCV 1",
    "16. MCV 2",
    "17. FIC",
    "18. CIC"
]

# Indicator Total columns in Q# TOP:
# H=CPAB, L=BCG, P=HepB, T=DPT1, X=DPT2, AB=DPT3, AF=OPV1, AJ=OPV2, AN=OPV3,
# AR=IPV1, AV=IPV2, AZ=PCV1, BD=PCV2, BH=PCV3, BL=MCV1, BP=MCV2, BT=FIC, BX=CIC
Q_TOP_COLS = ["H", "L", "P", "T", "X", "AB", "AF", "AJ", "AN", "AR", "AV", "AZ", "BD", "BH", "BL", "BP", "BT", "BX"]

CATCHMENTS = [
    "CHO A", "CHO B", "CHO C", "BANGON", 
    "BASAK MALUTLUT", "DIMALNA", "SUGOD", 
    "OLO AMBOLONG", "WAWALAYAN"
]

def load_catchment_mapping(wb):
    ws = wb['per brgy & Q1']
    mapping = {}
    current = "CHO A"
    for col in BRGY_COLS:
        c_val = ws.cell(1, col).value
        if c_val and str(c_val).strip():
            raw = str(c_val).strip().upper()
            if "CHO A" in raw: current = "CHO A"
            elif "CHO B" in raw: current = "CHO B"
            elif "CHO C" in raw: current = "CHO C"
            elif "BANGON" in raw: current = "BANGON"
            elif "BASAK" in raw: current = "BASAK MALUTLUT"
            elif "DIMALNA" in raw: current = "DIMALNA"
            elif "SUGOD" in raw: current = "SUGOD"
            elif "OLO" in raw: current = "OLO AMBOLONG"
            elif "WAWALAYAN" in raw: current = "WAWALAYAN"
            else: current = str(c_val).strip()
        brgy_name = ws.cell(2, col).value
        if brgy_name:
            mapping[str(brgy_name).strip()] = current
    return mapping

def fix_ref_counters(wb):
    print("Fixing #REF! in submission counters (A24, A51, A78)...")
    quarters = ['per brgy & Q1', 'per brgy & Q2', 'per brgy & Q3', 'per brgy & Q4']
    for q_name in quarters:
        if q_name not in wb.sheetnames:
            continue
        ws = wb[q_name]
        
        # Month 1 counter (Row 24, checking Row 5)
        m1_cells = ",".join([f"{col}5" for col in BRGY_COL_LETTERS])
        ws['A24'].value = f'=CONCATENATE("FINISHED: ", (COUNTA({m1_cells})&"/74"))'
        
        # Month 2 counter (Row 51, checking Row 32)
        m2_cells = ",".join([f"{col}32" for col in BRGY_COL_LETTERS])
        ws['A51'].value = f'=CONCATENATE("FINISHED: ", (COUNTA({m2_cells})&"/74"))'
        
        # Month 3 counter (Row 78, checking Row 59)
        m3_cells = ",".join([f"{col}59" for col in BRGY_COL_LETTERS])
        ws['A78'].value = f'=CONCATENATE("FINISHED: ", (COUNTA({m3_cells})&"/74"))'
        
        # Missing tracker in Row 51: =IF(ISBLANK(B32),B29,"") across columns
        for col in BRGY_COL_LETTERS:
            ws[f'{col}51'].value = f'=IF(ISBLANK({col}32),{col}29,"")'

def fix_q4_top_typo(wb):
    print("Fixing Q4 TOP cell T3 typo...")
    if 'Q4 TOP' in wb.sheetnames:
        ws = wb['Q4 TOP']
        ws['T3'].value = '=R3+S3'

def fix_status_sheets(wb):
    print("Cleaning up STATUS 1..4 sheets...")
    for s_idx in range(1, 5):
        s_name = f'STATUS {s_idx}'
        if s_name not in wb.sheetnames:
            continue
        ws = wb[s_name]
        # Clear ghost copy-paste in columns E through K if present
        for r in range(1, 80):
            for c in range(5, 15):
                ws.cell(r, c).value = None

def merge_vaccine_stock(wb):
    print("Merging Vaccine Stock Tracker from new template.xlsx...")
    if 'VACCINE STOCK' in wb.sheetnames:
        del wb['VACCINE STOCK']
    
    ws = wb.create_sheet('VACCINE STOCK')
    ws.views.sheetView[0].showGridLines = True
    
    # Styles
    title_font = Font(name="Segoe UI", size=14, bold=True, color="FFFFFF")
    head_font = Font(name="Segoe UI", size=10, bold=True, color="FFFFFF")
    data_font = Font(name="Segoe UI", size=10)
    center = Alignment(horizontal="center", vertical="center")
    left = Alignment(horizontal="left", vertical="center")
    thin = Side(border_style="thin", color="CBD5E1")
    box_border = Border(left=thin, right=thin, top=thin, bottom=thin)
    
    header_fill = PatternFill(start_color="0F172A", end_color="0F172A", fill_type="solid")
    red_fill = PatternFill(start_color="DC2626", end_color="DC2626", fill_type="solid")
    green_fill = PatternFill(start_color="059669", end_color="059669", fill_type="solid")
    zebra_fill = PatternFill(start_color="F8FAFC", end_color="F8FAFC", fill_type="solid")
    
    ws.merge_cells('B2:G2')
    ws['B2'] = "CITY HEALTH OFFICE - VACCINE INVENTORY & STOCK STATUS"
    ws['B2'].font = title_font
    ws['B2'].fill = header_fill
    ws['B2'].alignment = center
    ws.row_dimensions[2].height = 32
    
    # Stock Out Table (Cols B-C)
    ws.merge_cells('B4:C4')
    ws['B4'] = "VACCINE STOCK OUT"
    ws['B4'].font = head_font
    ws['B4'].fill = red_fill
    ws['B4'].alignment = center
    
    ws['B5'] = "VACCINE / ITEM"
    ws['C5'] = "REPORTED DATE"
    ws['B5'].font = head_font
    ws['C5'].font = head_font
    ws['B5'].fill = header_fill
    ws['C5'].fill = header_fill
    ws['B5'].alignment = center
    ws['C5'].alignment = center
    
    stock_out_data = [
        ("BCG", "2025-11-19"),
        ("PCV 23", "2025-11-19"),
        ("PCV 10", "2025-11-19")
    ]
    for idx, (item, dt) in enumerate(stock_out_data, start=6):
        ws.cell(idx, 2, item).font = data_font
        ws.cell(idx, 2).alignment = left
        ws.cell(idx, 2).border = box_border
        ws.cell(idx, 3, dt).font = data_font
        ws.cell(idx, 3).alignment = center
        ws.cell(idx, 3).border = box_border
    
    # Available Table (Cols E-G)
    ws.merge_cells('E4:G4')
    ws['E4'] = "VACCINE AVAILABLE"
    ws['E4'].font = head_font
    ws['E4'].fill = green_fill
    ws['E4'].alignment = center
    
    ws['E5'] = "VACCINE"
    ws['F5'] = "EXPIRY DATE"
    ws['G5'] = "STOCK STATUS"
    for col_idx, h in enumerate(["E5", "F5", "G5"], start=5):
        ws[h].font = head_font
        ws[h].fill = header_fill
        ws[h].alignment = center
        
    avail_data = [
        ("BOPV", "2026-06-06", "Adequate"),
        ("PENTAVALENT", "2026-08-15", "Adequate"),
        ("TD (Tetanus Diphtheria)", "2026-09-30", "Adequate"),
        ("HPV", "2026-10-12", "Adequate"),
        ("IPV", "2026-07-20", "Adequate"),
        ("ANTI-RABIES", "2026-11-05", "Adequate"),
        ("MEASLES-RUBELLA (MR)", "2026-08-30", "Adequate"),
        ("HEPATITIS B", "2026-12-31", "Adequate")
    ]
    for idx, (vac, exp, stat) in enumerate(avail_data, start=6):
        ws.cell(idx, 5, vac).font = data_font
        ws.cell(idx, 5).alignment = left
        ws.cell(idx, 5).border = box_border
        ws.cell(idx, 6, exp).font = data_font
        ws.cell(idx, 6).alignment = center
        ws.cell(idx, 6).border = box_border
        ws.cell(idx, 7, stat).font = data_font
        ws.cell(idx, 7).alignment = center
        ws.cell(idx, 7).border = box_border
        if idx % 2 == 1:
            ws.cell(idx, 5).fill = zebra_fill
            ws.cell(idx, 6).fill = zebra_fill
            ws.cell(idx, 7).fill = zebra_fill
            
    ws.column_dimensions['B'].width = 22
    ws.column_dimensions['C'].width = 16
    ws.column_dimensions['D'].width = 6
    ws.column_dimensions['E'].width = 26
    ws.column_dimensions['F'].width = 16
    ws.column_dimensions['G'].width = 16

def create_nip_fact_data(wb, catchment_mapping):
    print("Building normalized NIP_FACT_DATA sheet...")
    if 'NIP_FACT_DATA' in wb.sheetnames:
        del wb['NIP_FACT_DATA']
    
    ws = wb.create_sheet('NIP_FACT_DATA')
    ws.views.sheetView[0].showGridLines = True
    
    headers = [
        "Year", "Quarter", "Catchment", "Barangay", "Total_Pop", "Q_Target", "Year_Target",
        "CPAB", "BCG", "HepB", "DPT1", "DPT2", "DPT3", "OPV1", "OPV2", "OPV3",
        "IPV1", "IPV2", "PCV1", "PCV2", "PCV3", "MCV1", "MCV2", "FIC", "CIC",
        "FIC_Coverage_%", "Status_M1", "Status_M2", "Status_M3", "Quarter_Status"
    ]
    
    head_font = Font(name="Segoe UI", size=9, bold=True, color="FFFFFF")
    head_fill = PatternFill(start_color="1E293B", end_color="1E293B", fill_type="solid")
    center = Alignment(horizontal="center", vertical="center")
    thin = Side(border_style="thin", color="E2E8F0")
    box_border = Border(left=thin, right=thin, top=thin, bottom=thin)
    
    for c_idx, h in enumerate(headers, 1):
        cell = ws.cell(1, c_idx, h)
        cell.font = head_font
        cell.fill = head_fill
        cell.alignment = center
        cell.border = box_border
    ws.row_dimensions[1].height = 24
    
    current_row = 2
    for q_idx in range(1, 5):
        q_sheet = f"Q{q_idx} TOP"
        s_sheet = f"STATUS {q_idx}"
        q_label = f"Q{q_idx}"
        
        for brgy_idx in range(74):
            src_row = 3 + brgy_idx       # Row 3 to 76 in Q# TOP
            status_row = 2 + brgy_idx    # Row 2 to 75 in STATUS #
            
            # Col 1: Year linked to DASHBOARD
            ws.cell(current_row, 1, "=DASHBOARD!$C$5")
            # Col 2: Quarter
            ws.cell(current_row, 2, q_label)
            
            # Catchment & Barangay
            brgy_name_static = wb['Q1 TOP'].cell(src_row, 2).value
            catchment = catchment_mapping.get(str(brgy_name_static).strip(), "CHO A") if brgy_name_static else "CHO A"
            ws.cell(current_row, 3, catchment)
            ws.cell(current_row, 4, f"='{q_sheet}'!B{src_row}")
            
            # Col 5: Total Pop
            ws.cell(current_row, 5, f"='{q_sheet}'!C{src_row}")
            # Col 6: Q Target
            ws.cell(current_row, 6, f"='{q_sheet}'!D{src_row}")
            # Col 7: Year Target
            ws.cell(current_row, 7, f"='{q_sheet}'!E{src_row}")
            
            # 18 Antigens (Cols 8-25)
            for a_idx, c_letter in enumerate(Q_TOP_COLS):
                ws.cell(current_row, 8 + a_idx, f"='{q_sheet}'!{c_letter}{src_row}")
                
            # Col 26: FIC Coverage %
            ws.cell(current_row, 26, f"='{q_sheet}'!BU{src_row}")
            
            # Cols 27-29: Submission status for Months 1, 2, 3
            ws.cell(current_row, 27, f"='{s_sheet}'!B{status_row}")
            ws.cell(current_row, 28, f"='{s_sheet}'!C{status_row}")
            ws.cell(current_row, 29, f"='{s_sheet}'!D{status_row}")
            
            # Col 30: Overall Quarter Status
            ws.cell(current_row, 30, f'=IF(AND(AA{current_row}="ok",AB{current_row}="ok",AC{current_row}="ok"),"Complete","Pending")')
            
            current_row += 1
            
    print(f"NIP_FACT_DATA created with {current_row - 2} normalized rows.")
    for col in ws.columns:
        col_letter = get_column_letter(col[0].column)
        ws.column_dimensions[col_letter].width = 14
    ws.column_dimensions['A'].width = 8
    ws.column_dimensions['B'].width = 8
    ws.column_dimensions['C'].width = 18
    ws.column_dimensions['D'].width = 24

def build_dashboard(wb):
    print("Building modern DASHBOARD tab...")
    if 'DASHBOARD' in wb.sheetnames:
        del wb['DASHBOARD']
        
    ws = wb.create_sheet('DASHBOARD', 0)
    ws.views.sheetView[0].showGridLines = True
    
    # Palette
    NAVY = "0F172A"
    TEAL = "0F766E"
    SLATE = "334155"
    BORDER_CLR = "CBD5E1"
    BG_CARD = "F8FAFC"
    YELLOW_INP = "FEF08A"
    TEXT_INP = "1E3A8A"
    
    # Fonts
    brand_font = Font(name="Segoe UI", size=14, bold=True, color="FFFFFF")
    label_font = Font(name="Segoe UI", size=9, bold=True, color=NAVY)
    input_font = Font(name="Segoe UI", size=10, bold=True, color=TEXT_INP)
    kpi_title_font = Font(name="Segoe UI", size=8, bold=True, color=SLATE)
    kpi_num_font = Font(name="Segoe UI", size=16, bold=True, color=NAVY)
    kpi_sub_font = Font(name="Segoe UI", size=8, color="64748B")
    sec_header_font = Font(name="Segoe UI", size=10, bold=True, color="FFFFFF")
    tbl_head_font = Font(name="Segoe UI", size=9, bold=True, color="FFFFFF")
    data_font = Font(name="Segoe UI", size=9)
    btn_font = Font(name="Segoe UI", size=9, bold=True, color="FFFFFF")
    
    # Fills
    navy_fill = PatternFill(start_color=NAVY, end_color=NAVY, fill_type="solid")
    teal_fill = PatternFill(start_color=TEAL, end_color=TEAL, fill_type="solid")
    input_fill = PatternFill(start_color=YELLOW_INP, end_color=YELLOW_INP, fill_type="solid")
    card_fill = PatternFill(start_color=BG_CARD, end_color=BG_CARD, fill_type="solid")
    btn_blue = PatternFill(start_color="2563EB", end_color="2563EB", fill_type="solid")
    btn_emerald = PatternFill(start_color="059669", end_color="059669", fill_type="solid")
    btn_amber = PatternFill(start_color="D97706", end_color="D97706", fill_type="solid")
    btn_purple = PatternFill(start_color="7C3AED", end_color="7C3AED", fill_type="solid")
    
    # Borders
    thin = Side(border_style="thin", color=BORDER_CLR)
    box_border = Border(left=thin, right=thin, top=thin, bottom=thin)
    inp_border = Border(left=Side(border_style="medium", color="CA8A04"),
                        right=Side(border_style="medium", color="CA8A04"),
                        top=Side(border_style="medium", color="CA8A04"),
                        bottom=Side(border_style="medium", color="CA8A04"))
    
    center = Alignment(horizontal="center", vertical="center", wrap_text=True)
    left = Alignment(horizontal="left", vertical="center")
    right = Alignment(horizontal="right", vertical="center")
    
    # 1. HEADER ROW (Row 2)
    ws.merge_cells('B2:M2')
    ws['B2'] = "CITY HEALTH OFFICE — NATIONAL IMMUNIZATION PROGRAM (EPI/NIP) EXECUTIVE DASHBOARD"
    ws['B2'].font = brand_font
    ws['B2'].fill = navy_fill
    ws['B2'].alignment = Alignment(horizontal="center", vertical="center")
    ws.row_dimensions[2].height = 36
    
    # 2. PERIOD & DATE CONTROL PANEL (Rows 4-5)
    ws.merge_cells('B4:C4')
    ws['B4'] = "CONTROL PANEL: PERIOD & FILTERS"
    ws['B4'].font = label_font
    ws['B4'].alignment = left
    
    # Selector 1: Year (C5)
    ws['B5'] = "YEAR:"
    ws['B5'].font = label_font
    ws['B5'].alignment = right
    ws['C5'] = 2026
    ws['C5'].font = input_font
    ws['C5'].fill = input_fill
    ws['C5'].alignment = center
    ws['C5'].border = inp_border
    
    # Selector 2: Quarter (E5)
    ws['D5'] = "QUARTER:"
    ws['D5'].font = label_font
    ws['D5'].alignment = right
    ws['E5'] = "Q1"
    ws['E5'].font = input_font
    ws['E5'].fill = input_fill
    ws['E5'].alignment = center
    ws['E5'].border = inp_border
    
    # Selector 3: Month (G5)
    ws['F5'] = "MONTH:"
    ws['F5'].font = label_font
    ws['F5'].alignment = right
    ws['G5'] = "JANUARY"
    ws['G5'].font = input_font
    ws['G5'].fill = input_fill
    ws['G5'].alignment = center
    ws['G5'].border = inp_border
    
    # Selector 4: Reporting Day (I5)
    ws['H5'] = "CUT-OFF DAY:"
    ws['H5'].font = label_font
    ws['H5'].alignment = right
    ws['I5'] = 15
    ws['I5'].font = input_font
    ws['I5'].fill = input_fill
    ws['I5'].alignment = center
    ws['I5'].border = inp_border
    
    # Selector 5: Catchment (K5:L5)
    ws['J5'] = "CATCHMENT:"
    ws['J5'].font = label_font
    ws['J5'].alignment = right
    ws.merge_cells('K5:L5')
    ws['K5'] = "ALL CATCHMENTS"
    ws['K5'].font = input_font
    ws['K5'].fill = input_fill
    ws['K5'].alignment = center
    ws['K5'].border = inp_border
    ws.row_dimensions[5].height = 24
    
    # Data validations
    dv_yr = DataValidation(type="list", formula1='"2024,2025,2026,2027,2028,2029,2030"', allow_blank=False)
    ws.add_data_validation(dv_yr)
    dv_yr.add(ws['C5'])
    
    dv_q = DataValidation(type="list", formula1='"Q1,Q2,Q3,Q4,ANNUAL"', allow_blank=False)
    ws.add_data_validation(dv_q)
    dv_q.add(ws['E5'])
    
    dv_m = DataValidation(type="list", formula1='"JANUARY,FEBRUARY,MARCH,APRIL,MAY,JUNE,JULY,AUGUST,SEPTEMBER,OCTOBER,NOVEMBER,DECEMBER"', allow_blank=False)
    ws.add_data_validation(dv_m)
    dv_m.add(ws['G5'])
    
    dv_d = DataValidation(type="whole", operator="between", formula1=1, formula2=31)
    ws.add_data_validation(dv_d)
    dv_d.add(ws['I5'])
    
    dv_c = DataValidation(type="list", formula1='"ALL CATCHMENTS,CHO A,CHO B,CHO C,BANGON,BASAK MALUTLUT,DIMALNA,SUGOD,OLO AMBOLONG,WAWALAYAN"', allow_blank=False)
    ws.add_data_validation(dv_c)
    dv_c.add(ws['K5'])
    
    # 3. KPI CARDS (Rows 7-9)
    # Card 1: Total PSA Population
    ws.merge_cells('B7:C7')
    ws['B7'] = "TOTAL CITY POPULATION"
    ws['B7'].font = kpi_title_font
    ws['B7'].alignment = center
    ws.merge_cells('B8:C8')
    ws['B8'] = "=SUM('Q1 TOP'!C3:C76)"
    ws['B8'].font = kpi_num_font
    ws['B8'].alignment = center
    ws['B8'].number_format = "#,##0"
    ws.merge_cells('B9:C9')
    ws['B9'] = "74 Barangays Official Census"
    ws['B9'].font = kpi_sub_font
    ws['B9'].alignment = center
    
    # Card 2: Annual Eligible Infants (Target)
    ws.merge_cells('D7:E7')
    ws['D7'] = "ANNUAL ELIGIBLE TARGET"
    ws['D7'].font = kpi_title_font
    ws['D7'].alignment = center
    ws.merge_cells('D8:E8')
    ws['D8'] = "=SUM('Q1 TOP'!E3:E76)"
    ws['D8'].font = kpi_num_font
    ws['D8'].alignment = center
    ws['D8'].number_format = "#,##0"
    ws.merge_cells('D9:E9')
    ws['D9'] = "2.056% Annual Eligible Infants"
    ws['D9'].font = kpi_sub_font
    ws['D9'].alignment = center
    
    # Card 3: Fully Immunized Children (FIC)
    ws.merge_cells('F7:G7')
    ws['F7'] = "ANNUAL FIC ACCOMPLISHED"
    ws['F7'].font = kpi_title_font
    ws['F7'].alignment = center
    ws.merge_cells('F8:G8')
    ws['F8'] = "=SUM('Q1 TOP'!BT3:BT76,'Q2 TOP'!BT3:BT76,'Q3 TOP'!BT3:BT76,'Q4 TOP'!BT3:BT76)"
    ws['F8'].font = kpi_num_font
    ws['F8'].alignment = center
    ws['F8'].number_format = "#,##0"
    ws.merge_cells('F9:G9')
    ws['F9'] = "Fully Immunized Children (All Vaccines)"
    ws['F9'].font = kpi_sub_font
    ws['F9'].alignment = center
    
    # Card 4: FIC City-Wide Coverage %
    ws.merge_cells('H7:I7')
    ws['H7'] = "ANNUAL FIC COVERAGE"
    ws['H7'].font = kpi_title_font
    ws['H7'].alignment = center
    ws.merge_cells('H8:I8')
    ws['H8'] = "=IF(D8>0, F8/D8, 0)"
    ws['H8'].font = kpi_num_font
    ws['H8'].alignment = center
    ws['H8'].number_format = "0.0%"
    ws.merge_cells('H9:I9')
    ws['H9'] = "National Benchmark Target: 95.0%"
    ws['H9'].font = kpi_sub_font
    ws['H9'].alignment = center
    
    # Card 5: Q1 Barangay Reporting Rate
    ws.merge_cells('J7:K7')
    ws['J7'] = "Q1 REPORTING COMPLIANCE"
    ws['J7'].font = kpi_title_font
    ws['J7'].alignment = center
    ws.merge_cells('J8:K8')
    ws['J8'] = "=COUNTIF('STATUS 1'!B2:B75,\"ok\")/74"
    ws['J8'].font = kpi_num_font
    ws['J8'].alignment = center
    ws['J8'].number_format = "0.0%"
    ws.merge_cells('J9:K9')
    ws['J9'] = "74 Master Barangays Tracked"
    ws['J9'].font = kpi_sub_font
    ws['J9'].alignment = center
    
    # Style KPI boxes
    for col_range in [('B','C'), ('D','E'), ('F','G'), ('H','I'), ('J','K')]:
        c1, c2 = col_range
        for r in range(7, 10):
            for c_let in [c1, c2]:
                cell = ws[f'{c_let}{r}']
                cell.fill = card_fill
                cell.border = box_border
                
    ws.row_dimensions[7].height = 18
    ws.row_dimensions[8].height = 28
    ws.row_dimensions[9].height = 16
    
    # (Action Control Buttons removed from Excel sheet per user request — all operations are now controlled via the CHO-System Web Portal)
    
    # 5. SUMMARY TABLES THAT DRIVE CHARTS (Rows 14-33)
    # TABLE 1: 18 NIP Antigens Accomplishment Table (Cols B-H, Rows 14-33)
    ws.merge_cells('B14:H14')
    ws['B14'] = "NATIONAL IMMUNIZATION PROGRAM: 18 INDICATORS PERFORMANCE VS TARGET"
    ws['B14'].font = sec_header_font
    ws['B14'].fill = teal_fill
    ws['B14'].alignment = left
    
    tbl_headers = ["INDICATOR / ANTIGEN", "ANNUAL TARGET", "Q1 TOTAL", "Q2 TOTAL", "Q3 TOTAL", "Q4 TOTAL", "TOTAL ACC"]
    for idx, h in enumerate(tbl_headers, 2):
        cell = ws.cell(15, idx, h)
        cell.font = tbl_head_font
        cell.fill = navy_fill
        cell.alignment = center
        cell.border = box_border
        
    for idx, (antigen, col_let) in enumerate(zip(ANTIGENS, Q_TOP_COLS), start=16):
        r = idx
        ws.cell(r, 2, antigen).font = data_font
        ws.cell(r, 2).alignment = left
        ws.cell(r, 2).border = box_border
        
        # Target
        ws.cell(r, 3, "=$D$8").font = data_font
        ws.cell(r, 3).alignment = right
        ws.cell(r, 3).number_format = "#,##0"
        ws.cell(r, 3).border = box_border
        
        # Q1 Total = SUM('Q1 TOP'!H3:H76)
        ws.cell(r, 4, f"=SUM('Q1 TOP'!{col_let}3:{col_let}76)").font = data_font
        ws.cell(r, 4).alignment = right
        ws.cell(r, 4).number_format = "#,##0"
        ws.cell(r, 4).border = box_border
        
        # Q2 Total = SUM('Q2 TOP'!H3:H76)
        ws.cell(r, 5, f"=SUM('Q2 TOP'!{col_let}3:{col_let}76)").font = data_font
        ws.cell(r, 5).alignment = right
        ws.cell(r, 5).number_format = "#,##0"
        ws.cell(r, 5).border = box_border
        
        # Q3 Total = SUM('Q3 TOP'!H3:H76)
        ws.cell(r, 6, f"=SUM('Q3 TOP'!{col_let}3:{col_let}76)").font = data_font
        ws.cell(r, 6).alignment = right
        ws.cell(r, 6).number_format = "#,##0"
        ws.cell(r, 6).border = box_border
        
        # Q4 Total = SUM('Q4 TOP'!H3:H76)
        ws.cell(r, 7, f"=SUM('Q4 TOP'!{col_let}3:{col_let}76)").font = data_font
        ws.cell(r, 7).alignment = right
        ws.cell(r, 7).number_format = "#,##0"
        ws.cell(r, 7).border = box_border
        
        # Total Accomplishment = SUM(D:G)
        ws.cell(r, 8, f"=SUM(D{r}:G{r})").font = data_font
        ws.cell(r, 8).alignment = right
        ws.cell(r, 8).number_format = "#,##0"
        ws.cell(r, 8).border = box_border
        
    # TABLE 2: Reporting Compliance Summary (Cols J-L, Rows 14-17)
    ws.merge_cells('J14:L14')
    ws['J14'] = "BARANGAY SUBMISSION COMPLIANCE"
    ws['J14'].font = sec_header_font
    ws['J14'].fill = teal_fill
    ws['J14'].alignment = left
    
    ws.cell(15, 10, "STATUS").font = tbl_head_font
    ws.cell(15, 10).fill = navy_fill
    ws.cell(15, 10).alignment = center
    ws.cell(15, 10).border = box_border
    
    ws.cell(15, 11, "BARANGAYS").font = tbl_head_font
    ws.cell(15, 11).fill = navy_fill
    ws.cell(15, 11).alignment = center
    ws.cell(15, 11).border = box_border
    
    ws.cell(15, 12, "PERCENT").font = tbl_head_font
    ws.cell(15, 12).fill = navy_fill
    ws.cell(15, 12).alignment = center
    ws.cell(15, 12).border = box_border
    
    # Row 16: Submitted
    ws.cell(16, 10, "Submitted (OK)").font = data_font
    ws.cell(16, 10).border = box_border
    ws.cell(16, 11, "=COUNTIF('STATUS 1'!B2:B75,\"ok\")").font = data_font
    ws.cell(16, 11).alignment = right
    ws.cell(16, 11).border = box_border
    ws.cell(16, 12, "=K16/74").font = data_font
    ws.cell(16, 12).alignment = right
    ws.cell(16, 12).number_format = "0.0%"
    ws.cell(16, 12).border = box_border
    
    # Row 17: Pending / Not Submitted
    ws.cell(17, 10, "Pending").font = data_font
    ws.cell(17, 10).border = box_border
    ws.cell(17, 11, "=74-K16").font = data_font
    ws.cell(17, 11).alignment = right
    ws.cell(17, 11).border = box_border
    ws.cell(17, 12, "=K17/74").font = data_font
    ws.cell(17, 12).alignment = right
    ws.cell(17, 12).number_format = "0.0%"
    ws.cell(17, 12).border = box_border
    
    # TABLE 3: Catchment Summary (Cols J-M, Rows 20-30)
    ws.merge_cells('J20:M20')
    ws['J20'] = "CATCHMENT IMMUNIZATION SHARE"
    ws['J20'].font = sec_header_font
    ws['J20'].fill = teal_fill
    ws['J20'].alignment = left
    
    c_tbl_heads = ["CATCHMENT", "BRGYS", "FIC TOTAL", "SHARE %"]
    for idx, h in enumerate(c_tbl_heads, 10):
        cell = ws.cell(21, idx, h)
        cell.font = tbl_head_font
        cell.fill = navy_fill
        cell.alignment = center
        cell.border = box_border
        
    for idx, c_name in enumerate(CATCHMENTS, start=22):
        ws.cell(idx, 10, c_name).font = data_font
        ws.cell(idx, 10).border = box_border
        
        # Count barangays in catchment from NIP_FACT_DATA Q1
        ws.cell(idx, 11, f'=COUNTIFS(NIP_FACT_DATA!$B$2:$B$75,"Q1",NIP_FACT_DATA!$C$2:$C$75,"{c_name}")').font = data_font
        ws.cell(idx, 11).alignment = right
        ws.cell(idx, 11).border = box_border
        
        # Sum FIC across all 4 quarters from NIP_FACT_DATA
        ws.cell(idx, 12, f'=SUMIF(NIP_FACT_DATA!$C$2:$C$297,"{c_name}",NIP_FACT_DATA!$X$2:$X$297)').font = data_font
        ws.cell(idx, 12).alignment = right
        ws.cell(idx, 12).number_format = "#,##0"
        ws.cell(idx, 12).border = box_border
        
        # Share %
        ws.cell(idx, 13, f'=IF($F$8>0,L{idx}/$F$8,0)').font = data_font
        ws.cell(idx, 13).alignment = right
        ws.cell(idx, 13).number_format = "0.0%"
        ws.cell(idx, 13).border = box_border
        
    # 6. CHARTS EMBEDDING
    # CHART 1: Column Chart - 18 Antigens Accomplishment vs Target
    chart1 = BarChart()
    chart1.type = "col"
    chart1.style = 10
    chart1.title = "NIP 18 Indicators: Total Accomplished vs Annual Target"
    chart1.y_axis.title = "Number of Children"
    chart1.x_axis.title = "Antigen / Vaccine"
    chart1.height = 14
    chart1.width = 24
    
    # Data: Cols C (Target) and H (Total Acc), Rows 15-33
    data_ref = Reference(ws, min_col=3, min_row=15, max_col=3, max_row=33)
    data_ref2 = Reference(ws, min_col=8, min_row=15, max_col=8, max_row=33)
    cats_ref = Reference(ws, min_col=2, min_row=16, max_row=33)
    
    chart1.add_data(data_ref, titles_from_data=True)
    chart1.add_data(data_ref2, titles_from_data=True)
    chart1.set_categories(cats_ref)
    ws.add_chart(chart1, "B36")
    
    # CHART 2: Pie Chart - Barangay Reporting Compliance
    chart2 = PieChart()
    chart2.title = "Barangay Reporting Status"
    labels = Reference(ws, min_col=10, min_row=16, max_row=17)
    data_pie = Reference(ws, min_col=11, min_row=15, max_row=17)
    chart2.add_data(data_pie, titles_from_data=True)
    chart2.set_categories(labels)
    chart2.height = 14
    chart2.width = 14
    ws.add_chart(chart2, "K36")
    
    # CHART 3: Pie Chart - Catchment FIC Share
    chart3 = PieChart()
    chart3.title = "FIC Accomplishment by Catchment Area"
    c_labels = Reference(ws, min_col=10, min_row=22, max_row=30)
    c_data = Reference(ws, min_col=12, min_row=21, max_row=30)
    chart3.add_data(c_data, titles_from_data=True)
    chart3.set_categories(c_labels)
    chart3.height = 14
    chart3.width = 16
    ws.add_chart(chart3, "B65")
    
    # CHART 4: Column Chart - Catchment Distribution
    chart4 = BarChart()
    chart4.type = "col"
    chart4.style = 11
    chart4.title = "Catchment Area Total FIC Immunized"
    chart4.y_axis.title = "Children Immunized"
    chart4.height = 14
    chart4.width = 18
    chart4.add_data(c_data, titles_from_data=True)
    chart4.set_categories(c_labels)
    ws.add_chart(chart4, "K65")
    
    # Column widths
    ws.column_dimensions['A'].width = 3
    ws.column_dimensions['B'].width = 24
    ws.column_dimensions['C'].width = 14
    ws.column_dimensions['D'].width = 14
    ws.column_dimensions['E'].width = 14
    ws.column_dimensions['F'].width = 14
    ws.column_dimensions['G'].width = 14
    ws.column_dimensions['H'].width = 14
    ws.column_dimensions['I'].width = 14
    ws.column_dimensions['J'].width = 18
    ws.column_dimensions['K'].width = 14
    ws.column_dimensions['L'].width = 14
    ws.column_dimensions['M'].width = 14

def main():
    print(f"Loading master workbook: {MASTER_PATH}")
    wb = openpyxl.load_workbook(MASTER_PATH, data_only=False)
    
    catchment_mapping = load_catchment_mapping(wb)
    print(f"Loaded catchment mapping for {len(catchment_mapping)} barangays.")
    
    # 1. Audit fixes
    fix_ref_counters(wb)
    fix_q4_top_typo(wb)
    fix_status_sheets(wb)
    merge_vaccine_stock(wb)
    
    # 2. Add normalized fact table
    create_nip_fact_data(wb, catchment_mapping)
    
    # 3. Add Dashboard
    build_dashboard(wb)
    
    print(f"Saving modernized workbook to: {OUTPUT_PATH}")
    wb.save(OUTPUT_PATH)
    
    print(f"Updating master file directly: {MASTER_PATH}")
    wb.save(MASTER_PATH)
    print("Done! Both UPGRADED and MASTER workbooks saved successfully.")

if __name__ == '__main__':
    main()
