<?php
// Export assessment result as Excel (simple demo)
require_once __DIR__.'/db.php';
require_once __DIR__.'/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id = (int)($_GET['id'] ?? 0);
$pdo = db();
$st = $pdo->prepare('SELECT * FROM assessments WHERE id=?');
$st->execute([$id]);
$assess = $st->fetch();
if (!$assess) { die('Not found'); }

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'PDPA Assessment Report');
$sheet->setCellValue('A2', 'วันที่');
$sheet->setCellValue('B2', $assess['started_at']);
$sheet->setCellValue('A3', 'ผู้ประเมิน');
$sheet->setCellValue('B3', $assess['assessor_name']);
$sheet->setCellValue('A4', 'หน่วยงาน');
$sheet->setCellValue('B4', $assess['organization_name']);
$sheet->setCellValue('A5', 'คะแนนรวม');
$sheet->setCellValue('B5', $assess['score']);
$sheet->setCellValue('A6', 'ระดับ');
$sheet->setCellValue('B6', $assess['risk_level']);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="assessment_'.$id.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
