<?php include __DIR__.'/header.php'; ?>
<?php
// views/notifications.php - Full page notifications view
// $notifications should be passed from controller
if (!isset($notifications)) $notifications = [];
?>
<section class="card" style="max-width:900px;margin:32px auto;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
    <h2 style="margin:0;">การแจ้งเตือน</h2>
    <div>
      <a class="btn" href="?a=my_documents">เอกสารของฉัน</a>
      <a class="btn" href="?a=notifications_mark_all_read">ทำเป็นอ่านแล้วทั้งหมด</a>
    </div>
  </div>
  
  <?php if (!empty($notifications)): ?>
  <div style="margin-top:16px;">
    <?php foreach($notifications as $n): ?>
  <div class="notification-item" style="padding:16px;border-bottom:1px solid #222;color:#fff;background:<?php echo !($n['is_read'] ?? true)?'#222b':'#1a2140'; ?>;">
      <div style="margin-bottom:8px;">
        <?php
          // Prefer explicit event_type when available
          $badge = '';
          $etype = $n['event_type'] ?? '';
          if ($etype === 'doc_assigned') $badge = '<span style="background:#0288d1;color:#fff;padding:2px 8px;border-radius:10px;font-size:0.85em;margin-right:8px;">มอบหมายงาน</span>';
          elseif ($etype === 'doc_reviewed') $badge = '<span style="background:#007bff;color:#fff;padding:2px 8px;border-radius:10px;font-size:0.85em;margin-right:8px;">รีวิวเอกสาร</span>';
          else {
            // Fallback: derive from message
            $msg = $n['message'] ?? '';
            if (stripos($msg, 'มอบหมาย') !== false) {
              $badge = '<span style="background:#0288d1;color:#fff;padding:2px 8px;border-radius:10px;font-size:0.85em;margin-right:8px;">มอบหมายงาน</span>';
            } elseif (stripos($msg, 'แนบเอกสาร') !== false || stripos($msg, 'upload') !== false) {
              $badge = '<span style="background:#43a047;color:#fff;padding:2px 8px;border-radius:10px;font-size:0.85em;margin-right:8px;">แนบเอกสาร</span>';
            } elseif (stripos($msg, 'รีวิว') !== false || stripos($msg, 'ตรวจสอบ') !== false) {
              $badge = '<span style="background:#007bff;color:#fff;padding:2px 8px;border-radius:10px;font-size:0.85em;margin-right:8px;">รีวิวเอกสาร</span>';
            }
          }
        ?>
  <?= $badge ?><span style="color:#fff;"><?= htmlspecialchars($n['message']) ?></span>
        <?php 
        // If this is a review-related notification, show reviewer info
        if (!empty($n['document_id'])): 
          $review_steps = get_document_review_steps($n['document_id']);
          if (!empty($review_steps)):
            $latest_review = end($review_steps);
        ?>
        <div style="margin-top:8px;padding:8px;background:#222;border-left:3px solid #007bff;font-size:0.95em;color:#fff;box-shadow:0 2px 8px #0002;">
          <strong style="color:#90caf9;">ตรวจโดย:</strong> <?= htmlspecialchars($latest_review['reviewer_name']) ?><br>
          <strong style="color:#90caf9;">การดำเนินการ:</strong> <?= htmlspecialchars($latest_review['action']) ?><br>
          <strong style="color:#90caf9;">เวลา:</strong> <?= htmlspecialchars($latest_review['created_at']) ?>
          <?php if (!empty($latest_review['notes'])): ?>
          <br><strong style="color:#90caf9;">ความเห็น:</strong> <?= htmlspecialchars($latest_review['notes']) ?>
          <?php endif; ?>
        </div>
        <?php 
          endif;
        endif; 
        ?>
      </div>
  <small style="color:#b0bec5;">
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
<?php include __DIR__.'/footer.php'; ?>
</section>
