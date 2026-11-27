"""
CHO MARAWI PORTAL - NIP POPULATION EXCEL MUTATOR
Safely adds or updates rows in the 'MARAWI POP 2024' worksheet using openpyxl
while preserving all existing formulas, shared formulas, drawings, and styles.
"""

import argparse
import base64
import json
import os
import re
import sys
import xml.etree.ElementTree as ET
import zipfile
import openpyxl

SHEET_NS = "http://schemas.openxmlformats.org/spreadsheetml/2006/main"
OOXML_NAMESPACES = {
    "": SHEET_NS,
    "r": "http://schemas.openxmlformats.org/officeDocument/2006/relationships",
    "mc": "http://schemas.openxmlformats.org/markup-compatibility/2006",
    "x14ac": "http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac",
    "xr": "http://schemas.microsoft.com/office/spreadsheetml/2014/revision",
    "xr2": "http://schemas.microsoft.com/office/spreadsheetml/2015/revision2",
    "xr3": "http://schemas.microsoft.com/office/spreadsheetml/2016/revision3",
}


def extract_cached_formula_values(xlsx_path: str) -> dict:
    """Capture existing <v> cached results for formula cells before openpyxl strips them on save."""
    cached = {}
    with zipfile.ZipFile(xlsx_path, "r") as zf:
        for name in zf.namelist():
            if not (name.startswith("xl/worksheets/sheet") and name.endswith(".xml")):
                continue
            root = ET.fromstring(zf.read(name))
            f_tag = f"{{{SHEET_NS}}}f"
            v_tag = f"{{{SHEET_NS}}}v"
            for c in root.iter(f"{{{SHEET_NS}}}c"):
                ref = c.attrib.get("r")
                if not ref:
                    continue
                f_el = c.find(f_tag)
                v_el = c.find(v_tag)
                if f_el is not None and v_el is not None and v_el.text is not None:
                    cached[(name, ref)] = (v_el.text, c.attrib.get("t"))
    return cached


def restore_cached_formula_values(xlsx_path: str, cached: dict) -> None:
    """Re-inject preserved <v> cached formula results into worksheet XMLs after openpyxl save."""
    if not cached:
        return

    for prefix, uri in OOXML_NAMESPACES.items():
        ET.register_namespace(prefix, uri)

    tmp_path = f"{xlsx_path}.tmp"
    f_tag = f"{{{SHEET_NS}}}f"
    v_tag = f"{{{SHEET_NS}}}v"
    c_tag = f"{{{SHEET_NS}}}c"

    with zipfile.ZipFile(xlsx_path, "r") as zin, zipfile.ZipFile(tmp_path, "w", zipfile.ZIP_DEFLATED) as zout:
        for item in zin.infolist():
            data = zin.read(item.filename)
            if item.filename.startswith("xl/worksheets/sheet") and item.filename.endswith(".xml"):
                root = ET.fromstring(data)
                modified = False
                for c in root.iter(c_tag):
                    ref = c.attrib.get("r")
                    if not ref:
                        continue
                    key = (item.filename, ref)
                    if key not in cached:
                        continue
                    if c.find(f_tag) is not None:
                        v_el = c.find(v_tag)
                        if v_el is None:
                            v_el = ET.SubElement(c, v_tag)
                        if v_el.text is None or v_el.text == "":
                            v_text, orig_t = cached[key]
                            v_el.text = v_text
                            if orig_t and orig_t != "s":
                                c.attrib["t"] = orig_t
                            modified = True
                if modified:
                    data = ET.tostring(root, encoding="utf-8", xml_declaration=True)
            zout.writestr(item, data)

    os.replace(tmp_path, xlsx_path)


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


def find_marawi_pop_sheet(wb):
    """Auto-detect MARAWI POP <year>, MARAWI POP SEMI - <year>, or fallback MARAWI POP* sheet."""
    patterns = [
        re.compile(r"^MARAWI\s+POP\s+\d{4}$", re.IGNORECASE),
        re.compile(r"^MARAWI\s+POP\s+SEMI\s*-\s*\d{4}$", re.IGNORECASE),
        re.compile(r"^MARAWI\s+POP", re.IGNORECASE),
    ]
    for pattern in patterns:
        for name in wb.sheetnames:
            if pattern.match(name.strip()):
                return wb[name], name
    raise ValueError("Worksheet matching 'MARAWI POP <year>' or 'MARAWI POP SEMI - <year>' not found.")


def normalize_barangay_name(name: str) -> str:
    return re.sub(r"\s+", " ", str(name or "").strip()).upper()


def handle_sync_per_brgy(wb, payload: dict) -> dict:
    """Auto-detect per brgy & Q# sheets, months (JANUARY..DECEMBER), and barangay columns, syncing live formulas/values."""
    rows = payload.get("rows") or []
    pop_lookup = {}
    nip_row_lookup = {}
    excel_row_lookup = {}

    pop_sheet, pop_sheet_name = find_marawi_pop_sheet(wb)
    for r in range(5, pop_sheet.max_row + 1):
        bname_b = normalize_barangay_name(pop_sheet.cell(r, 2).value)
        if bname_b:
            excel_row_lookup[bname_b] = r
            if bname_b not in pop_lookup:
                try:
                    pop_lookup[bname_b] = int(round(float(pop_sheet.cell(r, 3).value or 0)))
                except (TypeError, ValueError):
                    pop_lookup[bname_b] = 0

        bname_g = normalize_barangay_name(pop_sheet.cell(r, 7).value)
        if bname_g:
            nip_row_lookup[bname_g] = r
            if bname_g not in pop_lookup:
                try:
                    pop_lookup[bname_g] = int(round(float(pop_sheet.cell(r, 8).value or 0)))
                except (TypeError, ValueError):
                    pop_lookup[bname_g] = 0

    for item in rows:
        bname = normalize_barangay_name(item.get("barangay"))
        if not bname:
            continue
        try:
            pop_val = int(round(float(item.get("total_pop") or 0)))
        except (TypeError, ValueError):
            pop_val = 0
        pop_lookup[bname] = pop_val

    all_known_barangays = set(pop_lookup.keys()) | set(nip_row_lookup.keys()) | set(excel_row_lookup.keys())
    months_set = {
        "JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE",
        "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"
    }
    q_pattern = re.compile(r"^per\s+brgy\s*&\s*Q\d+$", re.IGNORECASE)

    sheets_updated = []
    months_updated = []
    matched_barangays = set()
    cells_updated = 0

    for sheet_name in wb.sheetnames:
        if not q_pattern.match(sheet_name.strip()):
            continue
        ws = wb[sheet_name]
        sheet_touched = False

        for r in range(1, ws.max_row + 1):
            col_a = ws.cell(r, 1).value
            if not isinstance(col_a, str):
                continue
            month_label = re.sub(r"\s+", " ", col_a.strip()).upper()
            if month_label not in months_set:
                continue

            # Auto-detect barangay header row within r..r+3 (normally r+1, e.g. B2 for JANUARY, B29 for FEBRUARY)
            name_row = r + 1
            best_hits = -1
            for candidate_r in range(r, min(r + 4, ws.max_row + 1)):
                hits = 0
                for c in range(2, ws.max_column + 1):
                    v = ws.cell(candidate_r, c).value
                    if isinstance(v, str) and normalize_barangay_name(v) in all_known_barangays:
                        hits += 1
                if hits > best_hits:
                    best_hits = hits
                    name_row = candidate_r

            val_row = name_row + 1
            if val_row > ws.max_row or best_hits <= 0:
                continue

            month_touched = False
            for c in range(2, ws.max_column + 1):
                cell_name = ws.cell(name_row, c).value
                if not isinstance(cell_name, str) or not cell_name.strip():
                    continue
                norm_name = normalize_barangay_name(cell_name)
                if norm_name not in all_known_barangays:
                    continue

                if norm_name in nip_row_lookup:
                    ws.cell(val_row, c).value = f"='{pop_sheet_name}'!$H${nip_row_lookup[norm_name]}"
                elif norm_name in excel_row_lookup:
                    ws.cell(val_row, c).value = f"='{pop_sheet_name}'!$C${excel_row_lookup[norm_name]}"
                else:
                    ws.cell(val_row, c).value = pop_lookup[norm_name]

                matched_barangays.add(norm_name)
                cells_updated += 1
                month_touched = True
                sheet_touched = True

            if month_touched and month_label not in months_updated:
                months_updated.append(month_label)

        if sheet_touched:
            sheets_updated.append(sheet_name)

    return {
        "sheets_updated": sheets_updated,
        "months_updated": months_updated,
        "matched_barangays_count": len(matched_barangays),
        "cells_updated": cells_updated,
    }


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


def is_formula_cell(cell) -> bool:
    val = cell.value
    if val is None:
        return False
    if isinstance(val, str) and val.lstrip().startswith("="):
        return True
    if hasattr(val, "text") and isinstance(getattr(val, "text", None), str) and val.text.lstrip().startswith("="):
        return True
    return False


def clear_cell_if_not_formula(cell) -> str:
    if is_formula_cell(cell):
        return "formula"
    if cell.value is not None:
        cell.value = None
        return "cleared"
    return "empty"


def handle_delete(sheet, payload: dict) -> dict:
    excel_row = int(payload.get("excel_row") or 0)
    if excel_row < 5:
        raise ValueError("Invalid excel_row for delete (must be >= 5).")

    old_barangay = str(sheet.cell(excel_row, 2).value or "").strip()
    target_barangay = str(payload.get("barangay") or "").strip() or old_barangay

    nip_row = payload.get("nip_row")
    if nip_row:
        nip_row = int(nip_row)
    else:
        nip_row = find_nip_row(sheet, old_barangay) or find_nip_row(sheet, target_barangay)

    cleared_cells = 0
    preserved_formulas = 0

    # Clear MARAWI POP 2024 main table cells (Cols A-E), preserving any detected formulas
    for col in range(1, 6):
        status = clear_cell_if_not_formula(sheet.cell(excel_row, col))
        if status == "cleared":
            cleared_cells += 1
        elif status == "formula":
            preserved_formulas += 1

    # Clear corresponding NIP 73-Brgy cells (Cols G-I), preserving any detected formulas (e.g. Col I target formula)
    if nip_row and nip_row >= 5:
        for col in range(7, 10):
            status = clear_cell_if_not_formula(sheet.cell(nip_row, col))
            if status == "cleared":
                cleared_cells += 1
            elif status == "formula":
                preserved_formulas += 1

    return {
        "excel_row": excel_row,
        "nip_row": nip_row,
        "cleared_cells": cleared_cells,
        "preserved_formulas": preserved_formulas,
    }


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--action", required=True, choices=["add", "update", "delete", "sync_per_brgy"])
    parser.add_argument("--file", required=True)
    parser.add_argument("--payload-b64", required=True)
    args = parser.parse_args()

    try:
        payload_bytes = base64.b64decode(args.payload_b64)
        payload = json.loads(payload_bytes.decode("utf-8"))
        file_path = resolve_workbook_path(args.file)
        cached_formulas = extract_cached_formula_values(file_path)

        wb = openpyxl.load_workbook(file_path)
        if args.action == "sync_per_brgy":
            result = handle_sync_per_brgy(wb, payload)
        else:
            sheet, _ = find_marawi_pop_sheet(wb)
            if args.action == "update":
                result = handle_update(sheet, payload)
            elif args.action == "delete":
                result = handle_delete(sheet, payload)
            else:
                result = handle_add(sheet, payload)

        wb.save(file_path)
        restore_cached_formula_values(file_path, cached_formulas)
        print(json.dumps({
            "status": "success",
            "action": args.action,
            "file": os.path.basename(file_path),
            **result,
        }))
    except PermissionError:
        print(json.dumps({
            "status": "error",
            "message": f"Cannot write to '{os.path.basename(args.file)}' because it is currently open or locked in another program (e.g., Microsoft Excel or WPS Office). Please close the workbook there and try again.",
        }))
        sys.exit(1)
    except Exception as exc:
        print(json.dumps({
            "status": "error",
            "message": str(exc),
        }))
        sys.exit(1)


if __name__ == "__main__":
    main()
