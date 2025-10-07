<?php include __DIR__.'/../header.php'; ?>
<section class="card">
  <h2>รายการเอกสารทั้งหมด</h2>
  <div class="actions" style="margin-bottom:8px;">
    <a href="?a=admin" class="btn">กลับไปหน้า Admin</a>
  </div>
  <style>
    .reviewer-list { display: flex; flex-direction: column; gap: 2px; min-width: 120px; max-width: 180px; word-break: break-all; }
    .reviewer-list li { display: flex; align-items: center; justify-content: flex-start; gap: 6px; margin-bottom: 2px; }
    .reviewer-badge { background: #ff9800; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-left: 4px; }
    .reviewer-badge-done { background: #4caf50; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-left: 4px; }
    .reviewer-none { color: #999; font-style: italic; }
    @media (max-width: 900px) {
      .table th, .table td { font-size: 13px; padding: 4px; }
      .reviewer-list { min-width: 80px; max-width: 120px; }
    }
  </style>
  <table class="table" style="table-layout:fixed;word-break:break-all;">
  <colgroup>
    <col style="width:32px;">
    <col style="width:120px;">
    <col style="width:180px;">
    <col style="width:60px;">
    <col style="width:90px;">
    <col style="width:110px;">
    <col style="width:180px;">
    <col style="width:90px;">
  </colgroup>
  <thead>
    <tr>
      <th>ID</th>
      <th>องค์กร</th>
      <th>หมวดหมู่</th>
      <th>ไฟล์</th>
      <th>สถานะ</th>
      <th>อัปโหลดเมื่อ</th>
      <th>Reviewer</th>
      <th>ตรวจสอบ</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($docs as $d): ?>
      <tr>
        <td><?= (int)$d['id'] ?></td>
        <td><?= htmlspecialchars($d['organization_name'] ?: $d['contact_email']) ?></td>
        <td><?= htmlspecialchars($d['category_name']) ?></td>
  <td><a href="/uploads/<?= rawurlencode($d['stored_name']) ?>" target="_blank">เปิดไฟล์</a></td>
        <td>
          <?php 
            $status_text = $d['status'];
            $reviewers = $d['reviewers'] ?? [];
            if (is_string($reviewers)) { $reviewers = json_decode($reviewers, true) ?: []; }
            $hasReviewers = !empty($reviewers);
            
            switch($d['status']) {
              case 'PENDING': 
                if ($hasReviewers) {
                  $status_text = 'รอตรวจสอบ'; 
                } else {
                  $status_text = '<span style="color:#ff9800;">รอรับงาน</span>';
                }
                break;
              case 'PASS': $status_text = 'อนุมัติ'; break;
              case 'FAIL': $status_text = 'ไม่อนุมัติ(มีการแก้ไข)'; break;
            }
          ?>
          <strong><?= $status_text ?></strong>
          <?php if (!empty($d['notes'])): ?>
            <div class="muted">หมายเหตุ: <?= nl2br(htmlspecialchars($d['notes'])) ?></div>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($d['uploaded_at']) ?></td>
        <td>
          <div class="reviewer-list-wrap">
            <ol class="reviewer-list">
              <?php
                $list = $d['reviewers'] ?? [];
                if (is_string($list)) { $list = json_decode($list, true) ?: []; }
                if ($list) {
                  // Get reviewer names
                  $reviewerNames = [];
                  if (!empty($list)) {
                    $placeholders = str_repeat('?,', count($list) - 1) . '?';
                    $nameStmt = db()->prepare("SELECT id, username FROM users WHERE id IN ($placeholders)");
                    $nameStmt->execute($list);
                    foreach ($nameStmt->fetchAll() as $u) {
                      $reviewerNames[(int)$u['id']] = $u['username'];
                    }
                  }
                  foreach ($list as $idx => $uid) {
                    $name = $reviewerNames[(int)$uid] ?? "User #{$uid}";
                    $mark = ($d['current_reviewer_idx'] ?? 0) == $idx ? '<span class="reviewer-badge">รอคนนี้</span>' :
                            ($idx < ($d['current_reviewer_idx'] ?? 0) ? '<span class="reviewer-badge-done">ผ่านแล้ว</span>' : '');
                    echo '<li>'.$name.' '.$mark.'</li>';
                  }
                } else {
                  echo '<span class="reviewer-none">ยังไม่กำหนด</span>';
                }
              ?>
            </ol>
            <div style="margin-top:4px;"><a class="btn" href="?a=admin_documents_edit_reviewers&id=<?= urlencode($d['id']) ?>">กำหนด Reviewer</a></div>
          </div>
        </td>
        <td>
          <a class="btn" href="?a=doc_review&id=<?= urlencode($d['id']) ?>">ตรวจสอบ/แก้ไขสถานะ</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  </table>
</section>
<?php include __DIR__.'/../footer.php'; ?>
