<?php
// views/notifications.php - Full page notifications view
// $notifications should be passed from controller
if (!isset($notifications)) $notifications = [];
?>
<section class="card" style="max-width:900px;margin:32px auto;">
  <h2>การแจ้งเตือน</h2>
  
  <?php if (!empty($notifications)): ?>
  <div style="margin-top:16px;">
    <?php foreach($notifications as $n): ?>
    <div class="notification-item" style="padding:16px;border-bottom:1px solid #eee;<?php if(!($n['is_read'] ?? true)):?>background:#f9f9f9;font-weight:bold;<?php endif;?>">
      <div style="margin-bottom:8px;">
        <?= htmlspecialchars($n['message']) ?>
      </div>
      <small style="color:#666;">
        <?= htmlspecialchars($n['created_at']) ?>
        <?php if(!($n['is_read'] ?? true)): ?>
        <span style="color:#e74c3c;margin-left:8px;">● ใหม่</span>
        <?php endif; ?>
      </small>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div style="text-align:center;padding:32px;color:#666;">
    <p>ไม่มีการแจ้งเตือน</p>
  </div>
  <?php endif; ?>
  
  <div style="margin-top:16px;text-align:center;">
    <a class="btn" href="?">กลับหน้าหลัก</a>
  </div>
</section>
