"""
CHO MARAWI PORTAL - NIP POPULATION EXCEL MUTATOR
Safely adds or updates rows in the 'MARAWI POP 2024' worksheet using openpyxl
while preserving all existing formulas, shared formulas, drawings, and styles.
"""

import argparse
import base64
import json
import os
import sys
import openpyxl


def resolve_workbook_path(filename: str) -> str:
    safe_name = os.path.basename(filename)
    base_dir = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
    candidates = [
        os.path.join(base_dir, "SHEETS", safe_name),
        os.path.join(os.path.dirname(base_dir), "SHEETS", safe_name),
    ]
    for path in candidates:
        if os.path.isfile(path):
            return path
    raise FileNotFoundError(f"Workbook '{safe_name}' not found in SHEETS directory.")


def find_nip_row(sheet, barangay_name: str):
    target = (barangay_name or "").strip().lower()
    if not target:
        return None
    for r in range(5, sheet.max_row + 1):
        val = sheet.cell(r, 7).value  # Column G
        if val is not None and str(val).strip().lower() == target:
            return r
    return None


def find_next_empty_row(sheet, col_a: int, col_b: int, start_row: int = 5) -> int:
    for r in range(start_row, sheet.max_row + 2):
        va = sheet.cell(r, col_a).value
        vb = sheet.cell(r, col_b).value
        if (va is None or str(va).strip() == "") and (vb is None or str(vb).strip() == ""):
            return r
    return sheet.max_row + 1


def handle_update(sheet, payload: dict) -> dict:
    excel_row = int(payload.get("excel_row") or 0)
    if excel_row < 5:
        raise ValueError("Invalid excel_row for update (must be >= 5).")

    old_barangay = str(sheet.cell(excel_row, 2).value or "").strip()
    psgc10 = str(payload.get("psgc10") or "").strip()
    barangay = str(payload.get("barangay") or "").strip()
    total_pop = int(payload.get("total_pop") or 0)
    household_pop = int(payload.get("household_pop") or 0)
    households = int(payload.get("households") or 0)
    nip_pop = int(payload.get("nip_pop") or 0)

    sheet.cell(excel_row, 1).value = psgc10
    sheet.cell(excel_row, 2).value = barangay
    sheet.cell(excel_row, 3).value = total_pop
    sheet.cell(excel_row, 4).value = household_pop
    sheet.cell(excel_row, 5).value = households

    nip_row = payload.get("nip_row")
    if nip_row:
        nip_row = int(nip_row)
    else:
        nip_row = find_nip_row(sheet, old_barangay) or find_nip_row(sheet, barangay)

    if nip_row and nip_row >= 5:
        sheet.cell(nip_row, 7).value = barangay
        sheet.cell(nip_row, 8).value = nip_pop
    elif nip_pop > 0:
        nip_row = find_next_empty_row(sheet, 7, 8, start_row=5)
        sheet.cell(nip_row, 7).value = barangay
        sheet.cell(nip_row, 8).value = nip_pop
        sheet.cell(nip_row, 9).value = f"=H{nip_row}*2.056%"

    return {
        "excel_row": excel_row,
        "nip_row": nip_row,
        "nip_target": round(nip_pop * 0.02056, 1) if nip_pop > 0 else 0,
    }


def handle_add(sheet, payload: dict) -> dict:
    psgc10 = str(payload.get("psgc10") or "").strip()
    barangay = str(payload.get("barangay") or "").strip()
    if not barangay:
        raise ValueError("Barangay / Locality name is required.")

    total_pop = int(payload.get("total_pop") or 0)
    household_pop = int(payload.get("household_pop") or 0)
    households = int(payload.get("households") or 0)
    nip_pop = int(payload.get("nip_pop") or 0)

    excel_row = find_next_empty_row(sheet, 1, 2, start_row=5)
    sheet.cell(excel_row, 1).value = psgc10
    sheet.cell(excel_row, 2).value = barangay
    sheet.cell(excel_row, 3).value = total_pop
    sheet.cell(excel_row, 4).value = household_pop
    sheet.cell(excel_row, 5).value = households

    nip_row = None
    if nip_pop > 0:
        nip_row = find_next_empty_row(sheet, 7, 8, start_row=5)
        sheet.cell(nip_row, 7).value = barangay
        sheet.cell(nip_row, 8).value = nip_pop
        sheet.cell(nip_row, 9).value = f"=H{nip_row}*2.056%"

    return {
        "excel_row": excel_row,
        "nip_row": nip_row,
        "nip_target": round(nip_pop * 0.02056, 1) if nip_pop > 0 else 0,
    }


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--action", required=True, choices=["add", "update"])
    parser.add_argument("--file", required=True)
    parser.add_argument("--payload-b64", required=True)
    args = parser.parse_args()

    try:
        payload_bytes = base64.b64decode(args.payload_b64)
        payload = json.loads(payload_bytes.decode("utf-8"))
        file_path = resolve_workbook_path(args.file)

        wb = openpyxl.load_workbook(file_path)
        if "MARAWI POP 2024" not in wb.sheetnames:
            raise ValueError("Worksheet 'MARAWI POP 2024' not found.")
        sheet = wb["MARAWI POP 2024"]

        if args.action == "update":
            result = handle_update(sheet, payload)
        else:
            result = handle_add(sheet, payload)

        wb.save(file_path)
        print(json.dumps({
            "status": "success",
            "action": args.action,
            "file": os.path.basename(file_path),
            **result,
        }))
    except Exception as exc:
        print(json.dumps({
            "status": "error",
            "message": str(exc),
        }))
        sys.exit(1)


if __name__ == "__main__":
    main()
