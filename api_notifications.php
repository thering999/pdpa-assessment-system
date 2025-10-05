<?php
// API: ดึงรายการแจ้งเตือนใหม่ (unread)
require_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) { http_response_code(401); echo json_encode(['error'=>'unauth']); exit; }
$pdo = db();
$notis = $pdo->prepare("SELECT * FROM notifications WHERE user_id=? AND is_read=0 ORDER BY created_at DESC LIMIT 10");
$notis->execute([$user_id]);
echo json_encode($notis->fetchAll(PDO::FETCH_ASSOC));
