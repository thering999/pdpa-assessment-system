<?php
// API สำหรับ AJAX polling แจ้งเตือน (unread count)
require_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) { echo json_encode(['unread'=>0]); exit; }
$pdo = db();
$unread = $pdo->query("SELECT COUNT(*) FROM notifications WHERE user_id=$user_id AND is_read=0")->fetchColumn();
echo json_encode(['unread'=>(int)$unread]);
