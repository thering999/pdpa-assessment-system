<?php
// Export assessment result as PDF (simple demo)
require_once __DIR__.'/db.php';
require_once __DIR__.'/vendor/autoload.php';
use Mpdf\Mpdf;

$id = (int)($_GET['id'] ?? 0);
$pdo = db();
$st = $pdo->prepare('SELECT * FROM assessments WHERE id=?');
$st->execute([$id]);
$assess = $st->fetch();
if (!$assess) { die('Not found'); }

$html = '<h2>PDPA Assessment Report</h2>';
$html .= '<b>วันที่:</b> '.htmlspecialchars($assess['started_at']).'<br>';
$html .= '<b>ผู้ประเมิน:</b> '.htmlspecialchars($assess['assessor_name']).'<br>';
$html .= '<b>หน่วยงาน:</b> '.htmlspecialchars($assess['organization_name']).'<br>';
$html .= '<b>คะแนนรวม:</b> '.htmlspecialchars($assess['score']).'<br>';
$html .= '<b>ระดับ:</b> '.htmlspecialchars($assess['risk_level']).'<br>';

$mpdf = new Mpdf(['tempDir' => __DIR__ . '/tmp']);
$mpdf->WriteHTML($html);
$mpdf->Output('assessment_'.$id.'.pdf', 'D');
