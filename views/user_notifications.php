<?php
// หน้าแสดงการแจ้งเตือนสำหรับผู้ใช้
$user_id = $_SESSION['user']['id'] ?? 0;
if (!$user_id) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ'); window.location='?a=home';</script>";
    exit;
}

$notifications = get_notifications($user_id, 20);
?>

<div class="container">
    <div class="card">
        <h2>การแจ้งเตือน</h2>
        <p><a href="?a=home" class="btn">กลับหน้าหลัก</a></p>
        
        <?php if (empty($notifications)): ?>
            <p class="muted">ไม่มีการแจ้งเตือนใหม่</p>
        <?php else: ?>
            <div class="notifications-list">
                <?php foreach ($notifications as $notif): ?>
                    <div class="notification-item <?= $notif['is_read'] ? 'read' : 'unread' ?>">
                        <div class="notification-message">
                            <?= htmlspecialchars($notif['message']) ?>
                        </div>
                        <div class="notification-time muted small">
                            <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                        </div>
                        <?php if (!$notif['is_read']): ?>
                            <div class="notification-actions">
                                <a href="?a=mark_notification_read&id=<?= (int)$notif['id'] ?>" class="btn small">อ่านแล้ว</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.notifications-list {
    margin: 16px 0;
}

.notification-item {
    border: 1px solid #2a3357;
    border-radius: 8px;
    padding: 12px;
    margin: 8px 0;
    background: var(--card);
}

.notification-item.unread {
    border-left: 4px solid var(--primary);
    background: #1a2140;
}

.notification-item.read {
    opacity: 0.7;
}

.notification-message {
    margin-bottom: 8px;
    line-height: 1.4;
}

.notification-time {
    text-align: right;
    margin-top: 8px;
}

.notification-actions {
    text-align: right;
    margin-top: 8px;
}

.btn.small {
    font-size: 0.85rem;
    padding: 4px 8px;
}
</style>