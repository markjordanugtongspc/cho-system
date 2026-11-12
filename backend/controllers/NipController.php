<?php
/**
 * NIP / EPI Excel Controller
 * Integrates Marawi City NIP Sheets with CHO-System Portal.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

class NipController
{
    private static string $engineScript = 'd:\\laragon\\www\\SHEETS\\nip_workbook_engine.py';
    private static string $sheetsDir = 'd:\\laragon\\www\\SHEETS';

    /**
     * List all available Excel workbooks (.xlsx / .xlsm) in the SHEETS workspace
     */
    public static function files(): void
    {
        $files = [];
        if (is_dir(self::$sheetsDir)) {
            $entries = scandir(self::$sheetsDir) ?: [];
            foreach ($entries as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                $fullPath = self::$sheetsDir . DIRECTORY_SEPARATOR . $entry;
                if (is_file($fullPath) && preg_match('/\.(xlsx|xlsm)$/i', $entry)) {
                    $files[] = [
                        'name' => $entry,
                        'size' => filesize($fullPath),
                        'size_kb' => round(filesize($fullPath) / 1024, 1),
                        'modified' => date('Y-m-d H:i:s', (int)filemtime($fullPath)),
                        'is_default' => ($entry === 'MARAWI NIP DATA - UPGRADED.xlsx')
                    ];
                }
            }
        }

        // Put MARAWI NIP DATA - UPGRADED.xlsx first if present
        usort($files, static function (array $a, array $b): int {
            if ($a['is_default']) return -1;
            if ($b['is_default']) return 1;
            return strcmp($a['name'], $b['name']);
        });

        Response::success([
            'files' => $files,
            'active_default' => 'MARAWI NIP DATA - UPGRADED.xlsx'
        ]);
    }

    /**
     * Upload a custom .xlsx / .xlsm workbook to the SHEETS workspace
     */
    public static function upload(): void
    {
        if (empty($_FILES['workbook']) || $_FILES['workbook']['error'] !== UPLOAD_ERR_OK) {
            Response::error('Please select a valid Excel workbook (.xlsx or .xlsm) to upload.', 422);
            return;
        }

        $uploaded = $_FILES['workbook'];
        $origName = basename((string)$uploaded['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['xlsx', 'xlsm'], true)) {
            Response::error('Only .xlsx and .xlsm Excel workbooks are allowed.', 422);
            return;
        }

        // Sanitize filename
        $safeName = preg_replace('/[^A-Za-z0-9 _\-\.\(\)]/', '', $origName) ?: ('UPLOADED_NIP_' . time() . '.' . $ext);
        $destPath = self::$sheetsDir . DIRECTORY_SEPARATOR . $safeName;

        if (!move_uploaded_file($uploaded['tmp_name'], $destPath)) {
            Response::error('Failed to save uploaded workbook to workspace.', 500);
            return;
        }

        Response::success([
            'message' => "Workbook '{$safeName}' uploaded and ready for selection!",
            'file' => [
                'name' => $safeName,
                'size_kb' => round(filesize($destPath) / 1024, 1),
                'modified' => date('Y-m-d H:i:s', (int)filemtime($destPath))
            ]
        ]);
    }

    /**
     * Get real-time NIP KPIs and indicators for the selected workbook
     */
    public static function kpis(): void
    {
        $file = basename((string)($_GET['file'] ?? 'MARAWI NIP DATA - UPGRADED.xlsx'));
        $cmd = sprintf(
            'python %s get-kpis --file %s 2>&1',
            escapeshellarg(self::$engineScript),
            escapeshellarg($file)
        );
        $output = shell_exec($cmd);

        if ($output) {
            $start = strpos($output, '{');
            $end = strrpos($output, '}');
            if ($start !== false && $end !== false) {
                $data = json_decode(substr($output, $start, $end - $start + 1), true);
                if (is_array($data)) {
                    Response::success($data);
                    return;
                }
            }
        }

        // Fallback default KPIs if python fails
        Response::success([
            'status' => 'fallback',
            'source_file' => $file,
            'active_year' => 2026,
            'active_quarter' => 'Q1',
            'active_month' => 'JANUARY',
            'active_day' => 15,
            'total_population' => 176029,
            'annual_target' => 4965,
            'fic_accomplished' => 3661,
            'fic_coverage_pct' => 73.7,
            'reporting_compliance_pct' => 100.0,
            'antigens' => [],
            'catchments' => []
        ]);
    }

    /**
     * Generate a new year / period workbook from the selected base workbook
     */
    public static function generateYear(): void
    {
        $year = (int)(Request::input('year') ?? 2027);
        $month = strtoupper((string)(Request::input('month') ?? 'JANUARY'));
        $day = (int)(Request::input('day') ?? 1);
        $sourceFile = basename((string)(Request::input('source_file') ?? 'MARAWI NIP DATA - UPGRADED.xlsx'));

        $cmd = sprintf(
            'python %s create-year --year %d --month %s --day %d --file %s 2>&1',
            escapeshellarg(self::$engineScript),
            $year,
            escapeshellarg($month),
            $day,
            escapeshellarg($sourceFile)
        );

        $output = (string)shell_exec($cmd);
        $start = strpos($output, '{');
        $end = strrpos($output, '}');
        $res = null;
        if ($start !== false && $end !== false) {
            $jsonStr = substr($output, $start, $end - $start + 1);
            $res = json_decode($jsonStr, true);
        }

        if (is_array($res) && ($res['status'] ?? '') === 'success') {
            $outFile = $res['output_file'] ?? "MARAWI NIP DATA - {$year}.xlsx";
            Response::success([
                'message' => "Successfully generated clean workbook '{$outFile}' from '{$sourceFile}'!",
                'data' => $res,
                'download_url' => "/CHO-System/api/v1/nip/download?file=" . urlencode($outFile)
            ]);
            return;
        }

        Response::error("Failed to generate workbook: " . ($output ?: 'Unknown engine error'), 500);
    }

    /**
     * Reset a specific Quarter or Month slot inside the selected workbook
     */
    public static function resetQuarter(): void
    {
        $sourceFile = basename((string)(Request::input('source_file') ?? 'MARAWI NIP DATA - UPGRADED.xlsx'));
        $quarter = strtoupper((string)(Request::input('quarter') ?? 'Q1'));
        $monthSlot = Request::input('month_slot');

        $cmd = sprintf(
            'python %s reset-quarter --file %s --quarter %s %s 2>&1',
            escapeshellarg(self::$engineScript),
            escapeshellarg($sourceFile),
            escapeshellarg($quarter),
            $monthSlot ? ('--month-slot ' . (int)$monthSlot) : ''
        );

        $output = (string)shell_exec($cmd);
        $start = strpos($output, '{');
        $end = strrpos($output, '}');
        $res = null;
        if ($start !== false && $end !== false) {
            $jsonStr = substr($output, $start, $end - $start + 1);
            $res = json_decode($jsonStr, true);
        }

        if (is_array($res) && ($res['status'] ?? '') === 'success') {
            Response::success([
                'message' => "Cleared {$res['total_cleared_cells']} raw input cells in {$quarter} of '{$sourceFile}' (all formulas preserved)!",
                'data' => $res,
                'download_url' => "/CHO-System/api/v1/nip/download?file=" . urlencode($sourceFile)
            ]);
            return;
        }

        Response::error("Failed to reset quarter: " . ($output ?: 'Unknown engine error'), 500);
    }

    /**
     * Download generated or selected Excel file
     */
    public static function download(): void
    {
        $file = basename((string)($_GET['file'] ?? 'MARAWI NIP DATA - UPGRADED.xlsx'));
        $filePath = self::$sheetsDir . DIRECTORY_SEPARATOR . $file;

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "File not found.";
            exit;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
