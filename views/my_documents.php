<?php include __DIR__.'/header.php'; ?>
<section class="card">
  <h2>เอกสารของฉัน</h2>
  <p class="muted">แสดงรายการเอกสารที่คุณได้แนบไว้ พร้อมสถานะการตรวจสอบ</p>
  <?php if (empty($docs)): ?>
    <div class="muted">ยังไม่มีเอกสาร</div>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>หมวด</th>
          <th>ไฟล์</th>
          <th>สถานะ</th>
          <th>ผู้ตรวจล่าสุด</th>
          <th>อัปโหลดเมื่อ</th>
          <th>อัปเดตล่าสุด</th>
          <th>ดาวน์โหลด</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($docs as $d): ?>
          <?php
            $lastReviewer = '-';
            $lastAction = '-';
            try {
              $lastStmt = db()->prepare("SELECT drs.*, u.username FROM document_review_steps drs LEFT JOIN users u ON drs.reviewer_id = u.id WHERE drs.document_id = ? ORDER BY drs.id DESC LIMIT 1");
              $lastStmt->execute([(int)$d['id']]);
              $lastStep = $lastStmt->fetch();
              if ($lastStep) {
                $lastReviewer = $lastStep['username'] ?? ('User #'.$lastStep['reviewer_id']);
                $lastAction = $lastStep['action'];
              }
            } catch (Throwable $e) {}
            $statusText = $d['status'];
            $statusClass = '';
            switch($d['status']) {
              case 'PENDING': $statusText = 'รอตรวจ'; $statusClass = 'badge-pending'; break;
              case 'PASS': $statusText = 'อนุมัติ'; $statusClass = 'badge-pass'; break;
              case 'FAIL': $statusText = 'ไม่อนุมัติ'; $statusClass = 'badge-fail'; break;
            }
          ?>
          <tr>
            <td><?= (int)$d['id'] ?></td>
            <td><?= htmlspecialchars($d['category_name']) ?></td>
            <td><?= htmlspecialchars($d['original_name']) ?></td>
            <td><span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($statusText) ?></span></td>
<style>
.badge-status {
  display:inline-block;
  padding:2px 12px;
  border-radius:12px;
  font-size:0.95em;
  font-weight:bold;
  color:#fff;
}
.badge-pass { background:#43a047; }
.badge-fail { background:#e53935; }
.badge-pending { background:#fbc02d; color:#222; }
</style>
            <td>
              <div style="font-size:12px;">
                <strong><?= htmlspecialchars($lastReviewer) ?></strong>
                <div class="muted"><?= htmlspecialchars($lastAction) ?></div>
              </div>
            </td>
            <td><?= htmlspecialchars($d['uploaded_at']) ?></td>
            <td><?= htmlspecialchars($d['reviewed_at'] ?? '-') ?></td>
            <td><a class="btn" href="?a=download_doc&id=<?= (int)$d['id'] ?>">ดาวน์โหลด</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <div style="margin-top:12px">
    <a class="btn" href="?a=notifications">ดูการแจ้งเตือน</a>
    <a class="btn" href="?">กลับหน้าหลัก</a>
  </div>
</section>
<?php include __DIR__.'/footer.php'; ?>
