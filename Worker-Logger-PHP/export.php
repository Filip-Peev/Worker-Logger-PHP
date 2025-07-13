<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = new PDO('sqlite:worker_logs.db');
$logs = $db->query("SELECT worker_id, arrival_time, leaving_time FROM worker_logs")->fetchAll(PDO::FETCH_ASSOC);

if (!$logs) {
    session_start();
    $_SESSION['message'] = "No data to export.";
    $_SESSION['type'] = "warning";
    header('Location: index.php');
    exit;
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the header row (for Worker ID, Arrival Time, Leaving Time)
$sheet->setCellValue('A1', 'Worker ID');
$sheet->setCellValue('B1', 'Arrival Time');
$sheet->setCellValue('C1', 'Leaving Time');

// Write the logs to the sheet starting from row 2
$row = 2; // Start at row 2 because row 1 is for headers
foreach ($logs as $log) {
    $sheet->setCellValue('A' . $row, $log['worker_id']);
    $sheet->setCellValue('B' . $row, $log['arrival_time']);
    $sheet->setCellValue('C' . $row, $log['leaving_time']);
    $row++;
}

$filename = 'worker_logs_' . date('Y-m-d') . '.xlsx';

// Set headers for file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
