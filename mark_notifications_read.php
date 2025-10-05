<?php
// mark all notifications as read for current user
require_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
  $pdo = db();
  $pdo->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?")->execute([$user_id]);
}
