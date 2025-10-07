<?php include __DIR__.'/../header.php'; ?>
<?php
// Debug: แสดง notification ล่าสุดของ reviewer
$me = $_SESSION['user'] ?? null;
$uid = (int)($me['id'] ?? 0);
if ($uid) {
  try {
    $notis = db()->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 5");
    $notis->execute([$uid]);
    $rows = $notis->fetchAll();
    if ($rows) {
      echo '<div style="background:#fffbe7;border:1px solid #ffe082;padding:10px 16px;margin-bottom:16px;font-size:15px;">';
      echo '<span style="color:#c00"><b>🔔 แจ้งเตือนล่าสุด:</b></span><ul style="margin:0 0 0 18px;">';
      foreach($rows as $n) {
        $read = $n['is_read'] ? 'style="color:#888;"' : 'style="color:#333;"';
        echo '<li '.$read.'>'.htmlspecialchars($n['message']).' <span style="font-size:11px;color:#999">['.htmlspecialchars($n['created_at']).']</span>';
        // เพิ่มปุ่มรับงาน/ไม่รับงานถ้าเป็น notification เกี่ยวกับการมอบหมาย
        if (strpos($n['message'], 'มอบหมายให้ตรวจเอกสาร') !== false && !$n['is_read']) {
          // หา doc_id จาก format จริง: "ตรวจเอกสาร #123"
          $docId = 0;
          if (preg_match('/ตรวจเอกสาร #(\d+)/', $n['message'], $matches)) {
            $docId = $matches[1];
          }
          // ลองหาจาก "#123" ในกรณีที่มีเฉพาะตัวเลข
          elseif (preg_match('/#(\d+)/', $n['message'], $matches)) {
            $docId = $matches[1];
          }
          
          if ($docId > 0) {
            echo '<div style="margin-left:8px;display:inline-block;">';
            echo '<form method="post" action="?a=assign_reviewer" style="display:inline;margin-right:4px;" onsubmit="this.style.opacity=0.5;">';
            echo '<input type="hidden" name="doc_id" value="'.(int)$docId.'">';
            echo '<input type="hidden" name="reviewer_id" value="'.(int)$uid.'">';
            echo '<input type="hidden" name="form_token" value="'.htmlspecialchars(form_token_issue()).'">';
            echo '<button type="submit" style="background:#28a745;color:white;padding:4px 12px;border:none;border-radius:4px;font-size:12px;cursor:pointer;font-weight:bold;">รับงาน</button>';
            echo '</form>';
            echo '<form method="post" action="?a=assign_reviewer_remove" style="display:inline;" onsubmit="this.style.opacity=0.5;">';
            echo '<input type="hidden" name="doc_id" value="'.(int)$docId.'">';
            echo '<input type="hidden" name="reviewer_id" value="'.(int)$uid.'">';
            echo '<input type="hidden" name="form_token" value="'.htmlspecialchars(form_token_issue()).'">';
              echo '<button type="submit" style="background:#dc3545;color:white;padding:4px 12px;border:none;border-radius:4px;font-size:12px;cursor:pointer;font-weight:bold;" onclick="return confirm(&quot;ไม่รับงานนี้? จะคืนสถานะให้ Admin&quot;);">ไม่รับ</button>';
            echo '</form>';
            echo '</div>';
          }
        }
        echo '</li>';
      }
      echo '</ul></div>';
    }
  } catch (Throwable $e) {}
}

// ไม่ต้อง filter ซ้ำ เพราะ backend แยกให้แล้ว  
$me = $_SESSION['user'] ?? null;
$uid = (int)($me['id'] ?? 0);
?>
<?php
  $me = $_SESSION['user'] ?? null;
  $myName = $me['username'] ?? 'User';
  $roleText = $me['role'] === 'admin' ? 'Admin' : 'Reviewer';
?>
<section class="card">
  <h2>เอกสารที่ต้องตรวจ - <?= htmlspecialchars($myName) ?> [<?= htmlspecialchars($roleText) ?>]</h2>
  <div class="actions">
    <a class="btn" href="?">หน้าแรก</a> 
    <?php if (in_array($me['role'] ?? '', ['admin'])): ?>
    <a class="btn" href="?a=admin_documents">ดูรายการเอกสารทั้งหมด (ทั้งที่กำหนดและยังไม่กำหนด Reviewer)</a>
    <?php endif; ?>
  <p class="info-box" style="background:#e3f2fd;border-left:4px solid #2196f3;padding:12px;margin:12px 0;">
    <?php if ($me['role'] === 'admin'): ?>
      <strong style="color:#111">📋 Admin View: แสดงทุกเอกสารที่ผู้ใช้ส่งมาประเมิน (ทั้งที่ assign แล้วและยังไม่ assign) สามารถมอบหมาย reviewer ได้</strong>
    <?php else: ?>
      <strong style="color:#111">📋 Reviewer View: แสดงงานที่ถูกมอบหมายให้คุณ และงานที่ยังไม่มีผู้รับงาน (คุณสามารถกด “รับงาน” ได้)</strong>
    <?php endif; ?>
  </p>
  
  <?php if ($me['role'] !== 'admin'): ?>
  <!-- แสดงสถิติงานสำหรับ reviewer -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:16px;">
    <?php
      $assignedCount = 0;
      $acceptedCount = 0;
      foreach($docs as $d) {
        $list = [];
        if (!empty($d['reviewers'])) {
          $tmp = is_string($d['reviewers']) ? json_decode($d['reviewers'], true) : $d['reviewers'];
          if (is_array($tmp)) { $list = array_values(array_map('intval', $tmp)); }
        }
        if (in_array($uid, $list, true)) {
          $assignedCount++;
          // ถ้า current_reviewer_idx เท่ากับ position ของฉัน แสดงว่าถึงฉันแล้ว
          $myPos = array_search($uid, $list);
          if ($myPos !== false && $myPos <= ($d['current_reviewer_idx'] ?? 0)) {
            $acceptedCount++;
          }
        }
      }
    ?>
    <div style="background:#fff3cd;padding:12px;border-radius:6px;text-align:center;">
      <h4 style="margin:0;color:#856404;"><?= $assignedCount ?></h4>
      <p style="margin:4px 0 0 0;font-size:12px;color:#666;">งานที่ได้รับมอบหมาย</p>
    </div>
    <div style="background:#d1ecf1;padding:12px;border-radius:6px;text-align:center;">
      <h4 style="margin:0;color:#0c5460;"><?= $acceptedCount ?></h4>
      <p style="margin:4px 0 0 0;font-size:12px;color:#666;">งานที่ถึงลำดับฉัน</p>
    </div>
  </div>
  <?php endif; ?>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>องค์กร</th>
        <th>หมวด</th>
        <th>ไฟล์</th>
        <th>สถานะ</th>
        <th>ลำดับฉัน</th>
  <th>ล่าสุดตรวจโดย</th>
  <th><?= (($me['role'] ?? '') === 'admin') ? 'มอบหมาย Reviewer' : 'คิว Reviewer' ?></th>
        <th>Updated</th>
        <th>ดำเนินการ</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($docs)): ?>
        <tr><td colspan="9" class="muted">ยังไม่มีเอกสารในความรับผิดชอบของคุณ</td></tr>
      <?php else: foreach($docs as $d): ?>
        <?php
          // Calculate my position in reviewer queue
          $list = [];
          if (!empty($d['reviewers'])) {
            $tmp = is_string($d['reviewers']) ? json_decode($d['reviewers'], true) : $d['reviewers'];
            if (is_array($tmp)) { $list = array_values(array_map('intval', $tmp)); }
          }
          $myPos = array_search($uid, $list);
          $currentIdx = (int)($d['current_reviewer_idx'] ?? 0);
          $myTurn = ($myPos !== false && $myPos === $currentIdx);
          $myDone = ($myPos !== false && $myPos < $currentIdx);
          
          // Get last reviewer action
          $lastReviewer = '-';
          $lastReviewAction = '-';
          try {
            $lastStmt = db()->prepare("SELECT drs.*, u.username FROM document_review_steps drs LEFT JOIN users u ON drs.reviewer_id = u.id WHERE drs.document_id = ? ORDER BY drs.id DESC LIMIT 1");
            $lastStmt->execute([(int)$d['id']]);
            $lastStep = $lastStmt->fetch();
            if ($lastStep) {
              $lastReviewer = $lastStep['username'] ?? "User #{$lastStep['reviewer_id']}";
              $actionText = $lastStep['action'];
              switch($lastStep['action']) {
                case 'PASS': $actionText = '✅ อนุมัติ'; break;
                case 'FAIL': $actionText = '❌ ไม่อนุมัติ'; break;
                case 'COMMENT': $actionText = '💬 ให้ความเห็น'; break;
                case 'PENDING': $actionText = '⏳ รอตรวจ'; break;
              }
              $lastReviewAction = $actionText;
            }
          } catch (Throwable $e) { /* ignore */ }
        ?>
  <tr <?= $myTurn ? 'class="row-myturn"' : '' ?>>
          <td><?= (int)$d['id'] ?></td>
          <td><?= htmlspecialchars($d['organization_name'] ?: $d['contact_email']) ?></td>
          <td><?= htmlspecialchars($d['category_name']) ?></td>
          <td><a target="_blank" href="uploads/<?= htmlspecialchars($d['stored_name']) ?>"><?= htmlspecialchars($d['original_name']) ?></a></td>
          <td>
            <?php
              $statusText = $d['status'];
              switch($d['status']) {
                case 'PENDING': $statusText = 'รอตรวจ'; break;
                case 'PASS': $statusText = 'อนุมัติ'; break;
                case 'FAIL': $statusText = 'ไม่อนุมัติ'; break;
              }
            ?>
            <?= htmlspecialchars($statusText) ?>
          </td>
          <td>
            <?php if ($myPos !== false): ?>
              ลำดับที่ <?= ($myPos + 1) ?>/<?= count($list) ?>
              <?php if ($myDone): ?>
                <br><small class="text-success">✓ ผ่านแล้ว</small>
              <?php elseif ($myTurn): ?>
                <br><small class="text-warning">⏳ ตาฉัน</small>
              <?php else: ?>
                <br><small class="text-muted">รอคิว</small>
              <?php endif; ?>
            <?php else: ?>
              <small class="text-muted">ไม่อยู่ในคิว</small>
            <?php endif; ?>
          </td>
          <td>
            <div style="font-size:12px;">
              <strong>ล่าสุด:</strong> <?= htmlspecialchars($lastReviewer) ?><br>
              <span style="color:#666;"><?= htmlspecialchars($lastReviewAction) ?></span>
            </div>
          </td>
          <td>
            <div style="min-width:220px;">
              <?php
                // Show current queue with remove buttons (admin only) and add form
                $queue = [];
                if (!empty($d['reviewers'])) {
                  $tmp = is_string($d['reviewers']) ? json_decode($d['reviewers'], true) : $d['reviewers'];
                  if (is_array($tmp)) { $queue = array_values(array_map('intval', $tmp)); }
                }
                if ($queue) {
                  // Fetch names map from $allReviewers
                  $nameMap = [];
                  if (!empty($allReviewers)) {
                    foreach ($allReviewers as $rv) { $nameMap[(int)$rv['id']] = $rv['username']; }
                  }
                  echo '<ol style="margin:0;padding-left:18px">';
                  foreach ($queue as $idx => $rid) {
                    $nm = $nameMap[$rid] ?? ('User #'.$rid);
                    $mark = ($d['current_reviewer_idx'] ?? 0) == $idx ? '<span class="badge" style="background:#ff9800;color:white;padding:2px 6px;border-radius:3px;font-size:11px;">รอคนนี้</span>' :
                            ($idx < ($d['current_reviewer_idx'] ?? 0) ? '<span class="badge" style="background:#4caf50;color:white;padding:2px 6px;border-radius:3px;font-size:11px;">ผ่านแล้ว</span>' : '');
                    echo '<li style="margin-bottom:4px;">'.htmlspecialchars($nm).' '.$mark;
                    if (in_array($me['role'] ?? '', ['admin'])) {
                      echo '<form method="post" action="?a=assign_reviewer_remove" style="display:inline" onsubmit="return confirm(\'ลบผู้ตรวจคนนี้ออกจากคิว?\');">'
                          .'<input type="hidden" name="doc_id" value="'.(int)$d['id'].'">'
                          .'<input type="hidden" name="reviewer_id" value="'.(int)$rid.'">'
                          .'<input type="hidden" name="form_token" value="'.htmlspecialchars(form_token_issue()).'">'
                          .'<button class="btn btn-xs" style="margin-left:6px;background:#f44336;color:#fff;" type="submit">ลบ</button>'
                          .'</form>';
                    }
                    echo '</li>';
                  }
                  echo '</ol>';
                } else {
                  echo '<span class="muted">ยังไม่กำหนด</span>';
                }
              ?>
              <?php if (($me['role'] ?? '') === 'admin'): ?>
              <form method="post" action="?a=assign_reviewer" style="margin-top:6px;display:flex;gap:6px;align-items:center;">
                <input type="hidden" name="doc_id" value="<?= (int)$d['id'] ?>">
                <input type="hidden" name="form_token" value="<?= htmlspecialchars(form_token_issue()) ?>">
                <select name="reviewer_id" required style="min-width:160px;">
                  <option value="">— เลือก Reviewer —</option>
                  <?php if (!empty($allReviewers)):
                    foreach ($allReviewers as $rv): ?>
                      <option value="<?= (int)$rv['id'] ?>"><?= htmlspecialchars($rv['username']) ?></option>
                  <?php endforeach; endif; ?>
                </select>
                <button class="btn" type="submit">มอบหมาย</button>
              </form>
              <?php else: ?>
                <!-- Reviewer role: ไม่แสดงฟอร์มมอบหมาย ให้ดูคิวอย่างเดียว -->
              <?php endif; ?>
            </div>
          </td>
          <td><?= htmlspecialchars($d['reviewed_at'] ?? $d['uploaded_at']) ?></td>
          <td>
            <?php if ($me['role'] === 'admin'): ?>
              <!-- Admin สามารถตรวจทุกเอกสาร -->
              <a class="btn btn-primary" href="?a=doc_review&id=<?= (int)$d['id'] ?>">ตรวจ</a>
            <?php elseif ($myTurn): ?>
              <!-- Reviewer ตรวจได้เมื่อถึงลำดับ -->
              <a class="btn btn-primary" href="?a=doc_review&id=<?= (int)$d['id'] ?>">ตรวจ</a>
            <?php elseif ((empty($list) || !in_array($uid, $list, true)) && $me['role'] === 'reviewer'): ?>
              <!-- Reviewer สามารถรับงานได้เมื่อยังไม่มีคิว หรือยังไม่ได้อยู่ในคิว -->
              <form method="post" action="?a=assign_reviewer" style="display:inline;">
                <input type="hidden" name="doc_id" value="<?= (int)$d['id'] ?>">
                <input type="hidden" name="reviewer_id" value="<?= (int)$uid ?>">
                <input type="hidden" name="form_token" value="<?= htmlspecialchars(form_token_issue()) ?>">
                <button class="btn btn-success" type="submit">รับงาน</button>
              </form>
            <?php elseif (in_array($uid, $list, true) && $me['role'] === 'reviewer'): ?>
              <!-- Reviewer ที่ถูก assign แล้วสามารถไม่รับงานได้ (ลบ status check ออก) -->
              <form method="post" action="?a=assign_reviewer_remove" style="display:inline;">
                <input type="hidden" name="doc_id" value="<?= (int)$d['id'] ?>">
                <input type="hidden" name="reviewer_id" value="<?= (int)$uid ?>">
                <input type="hidden" name="form_token" value="<?= htmlspecialchars(form_token_issue()) ?>">
                <button class="btn btn-danger" type="submit" onclick="return confirm('ยืนยันไม่รับงานนี้? ระบบจะคืนงานให้ผู้ดูแลเพื่อจัดคิวใหม่');">ไม่รับงาน</button>
              </form>
            <?php else: ?>
              <a class="btn btn-secondary" href="?a=doc_review&id=<?= (int)$d['id'] ?>">ดู</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</section>
<?php include __DIR__.'/../footer.php'; ?>
