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

// Calculate score and risk_level fresh from answers
$rows = $pdo->prepare('SELECT answer_value FROM answers WHERE assessment_id=?');
$rows->execute([$id]);
$total = 0; $count = 0;
foreach ($rows as $r) {
	$val = max(1, min(3, (int)$r['answer_value']));
	$total += $val;
	$count++;
}
$avg = $count > 0 ? round($total / $count, 2) : 0;
$color = 'แดง';
if ($avg >= 2.6) $color = 'เขียว';
elseif ($avg >= 2.1) $color = 'เหลือง';

// Use calculated values instead of possibly stale DB values
$assess['score'] = $avg;
$assess['risk_level'] = $color;

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
