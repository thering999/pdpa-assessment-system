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



$html = '<h2>ผลการประเมินตนเอง PDPA สำหรับ CII</h2>';
$html .= '<b>วันที่ประเมิน:</b> '.htmlspecialchars($assess['started_at']).'<br>';
$html .= '<b>ผู้ประเมิน:</b> '.htmlspecialchars($assess['assessor_name']).'<br>';
$html .= '<b>หน่วยงาน:</b> '.htmlspecialchars($assess['organization_name']).'<br>';
$html .= '<b>คะแนนรวม:</b> '.htmlspecialchars($assess['score']).'<br>';
$html .= '<b>ระดับความเสี่ยง:</b> '.htmlspecialchars($assess['risk_level']).'<br>';

// ดึงข้อประเมินและคะแนนแต่ละข้อ (cii_items, cii_scores)
$items = $pdo->query('SELECT * FROM cii_items ORDER BY section, num')->fetchAll();
$scores = $pdo->prepare('SELECT item_id, score, note FROM cii_scores WHERE assessment_id=?');
$scores->execute([$id]);
$score_map = [];
foreach ($scores as $r) { $score_map[(int)$r['item_id']] = $r; }

$html .= '<br><table border="1" cellpadding="4" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:14pt;">';
$html .= '<tr style="background:#eee;"><th>ลำดับ</th><th>รหัส</th><th>รายการ (Objective)</th><th>หมวด</th><th>น้ำหนัก</th><th>คะแนน</th><th>หมายเหตุ</th></tr>';
foreach ($items as $i => $item) {
	$score = isset($score_map[$item['id']]) ? $score_map[$item['id']]['score'] : '';
	$note = isset($score_map[$item['id']]) ? htmlspecialchars($score_map[$item['id']]['note']) : '';
	$html .= '<tr>';
	$html .= '<td>'.($i+1).'</td>';
	$html .= '<td>'.htmlspecialchars($item['section']).'-'.htmlspecialchars($item['num']).'</td>';
	$html .= '<td>'.htmlspecialchars($item['objective']).'</td>';
	$html .= '<td>'.htmlspecialchars($item['requirement']).'</td>';
	$html .= '<td>'.htmlspecialchars($item['weight'] ?? '').'</td>';
	$html .= '<td>'.htmlspecialchars($score).'</td>';
	$html .= '<td>'.$note.'</td>';
	$html .= '</tr>';
}
$html .= '</table>';


$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];
$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new Mpdf([
	'tempDir' => __DIR__ . '/tmp',
	'fontDir' => array_merge($fontDirs, [__DIR__ . '/assets/fonts']),
	'fontdata' => $fontData + [
		'thsarabun' => [
			'R' => 'THSarabunNew.ttf',
			'B' => 'THSarabunNew-Bold.ttf',
			'I' => 'THSarabunNew-Italic.ttf',
			'BI' => 'THSarabunNew-BoldItalic.ttf',
		],
	],
	'default_font' => 'thsarabun',
]);

$mpdf->WriteHTML($html);
$mpdf->Output('assessment_'.$id.'.pdf', 'D');
