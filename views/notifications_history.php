<?php
// หน้าแสดงรายการแจ้งเตือนย้อนหลัง
require_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) { echo 'กรุณาเข้าสู่ระบบ'; exit; }
$notis = get_notifications($user_id, 100);
echo '<h2>แจ้งเตือนย้อนหลัง</h2>';
echo '<ul style="max-width:600px;">';
foreach($notis as $n) {
  echo '<li style="padding:8px;border-bottom:1px solid #eee;'.(!$n['is_read']?'background:#f9f9f9;':'').'">';
  echo htmlspecialchars($n['message']).' <small>'.htmlspecialchars($n['created_at']).'</small>';
  echo '</li>';
}
echo '</ul>';
