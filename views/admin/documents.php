<h2>รายการเอกสารทั้งหมด</h2>
<p>
  <a href="?a=admin" class="btn">กลับไปหน้า Admin</a>
</p>
<table class="table">
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
        <td>
          <?php
            $list = $d['reviewers'] ?? [];
            if (is_string($list)) { $list = json_decode($list, true) ?: []; }
            if ($list) {
              echo '<ol style="margin:0;padding-left:18px">';
              foreach ($list as $idx => $uid) {
                $mark = ($d['current_reviewer_idx'] ?? 0) == $idx ? '<span class="badge">รอคนนี้</span>' : '';
                echo '<li>ผู้รีวิว #'.($idx+1).' ID '.$uid.' '.$mark.'</li>';
              }
              echo '</ol>';
            } else {
              echo '<span class="muted">ยังไม่กำหนด</span>';
            }
          ?>
          <div><a class="btn" href="?a=admin_documents_edit_reviewers&id=<?= urlencode($d['id']) ?>">กำหนด Reviewer</a></div>
        </td>
        <td>
        <td><?= htmlspecialchars($d['organization_name'] ?: $d['contact_email']) ?></td>
        <td><?= htmlspecialchars($d['category_name']) ?></td>
  <td><a href="uploads/<?= rawurlencode($d['stored_name']) ?>" target="_blank">เปิดไฟล์</a></td>
        <td>
          <?php 
            $status_text = $d['status'];
            switch($d['status']) {
              case 'PENDING': $status_text = 'รอตรวจสอบ'; break;
              case 'PASS': $status_text = 'อนุมัติ'; break;
              case 'FAIL': $status_text = 'ไม่อนุมัติ(มีการแก้ไข)'; break;
            }
          ?>
          <strong><?= htmlspecialchars($status_text) ?></strong>
          <?php if (!empty($d['notes'])): ?>
            <div class="muted">หมายเหตุ: <?= nl2br(htmlspecialchars($d['notes'])) ?></div>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($d['uploaded_at']) ?></td>
        <td>
          <a class="btn" href="?a=doc_review&id=<?= urlencode($d['id']) ?>">ตรวจสอบ/แก้ไขสถานะ</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
