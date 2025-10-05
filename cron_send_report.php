<?php
// cron_send_report.php
// ส่งอีเมลรายงานสรุปผลประเมินอัตโนมัติ (Scheduled Reports)

require_once 'db.php';
require_once 'config.php';

$pdo = db();

// ดึงรายชื่ออีเมล admin ทั้งหมด
$stmt = $pdo->query("SELECT email FROM users WHERE role = 'admin'");
$admins = $stmt->fetchAll(PDO::FETCH_COLUMN);

// ดึงสรุปผลประเมินล่าสุด (ตัวอย่าง: คะแนนเฉลี่ย, จำนวนการประเมิน, เอกสารใหม่)
$summary = $pdo->query("SELECT COUNT(*) as total, AVG(score) as avg_score FROM assessments")->fetch(PDO::FETCH_ASSOC);
$new_docs = $pdo->query("SELECT COUNT(*) FROM documents WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)")->fetchColumn();

$subject = "[PDPA] รายงานสรุปผลประเมินประจำวันที่ ".date('Y-m-d');
$body = "สรุปผลประเมินประจำวันที่ ".date('Y-m-d')."\n";
$body .= "จำนวนการประเมิน: ".$summary['total']."\n";
$body .= "คะแนนเฉลี่ย: ".number_format($summary['avg_score'],2)."\n";
$body .= "เอกสารใหม่ 24 ชม.: ".$new_docs;

foreach ($admins as $email) {
    mail($email, $subject, $body, "From: ".ADMIN_EMAIL);
}

// log action
$pdo->prepare("INSERT INTO log (user_id, action, details) VALUES (NULL, 'cron_report', ?)")
    ->execute(["ส่งอีเมลรายงานอัตโนมัติถึง ".implode(", ", $admins)]);

?>
