<?php include __DIR__.'/../header.php'; ?>
<?php 
  $me = $_SESSION['user'] ?? null; 
  $backUrl = (isset($me['role']) && $me['role'] === 'reviewer') ? '?a=reviewer_documents' : '?a=admin';
?>
<?php if (!$doc): ?>
  <section class="card"><p>ไม่พบเอกสาร</p></section>
<?php else: ?>
  <section class="card" style="max-width:760px;">
    <h2>ตรวจสอบเอกสาร - <?= htmlspecialchars($doc['category_name']) ?></h2>
    <p>ไฟล์: <a class="btn" href="/uploads/<?= htmlspecialchars($doc['stored_name']) ?>" target="_blank"><?= htmlspecialchars($doc['original_name']) ?></a></p>
    <?php
      $reviewers = $doc['reviewers'] ? json_decode($doc['reviewers'], true) : [];
      $curIdx = (int)($doc['current_reviewer_idx'] ?? 0);
    ?>
    <?php if($reviewers): ?>
      <?php
        // Get reviewer names
        $reviewerNames = [];
        if (!empty($reviewers)) {
          $placeholders = str_repeat('?,', count($reviewers) - 1) . '?';
          $nameStmt = db()->prepare("SELECT id, username, email FROM users WHERE id IN ($placeholders)");
          $nameStmt->execute($reviewers);
          foreach ($nameStmt->fetchAll() as $u) {
            $reviewerNames[(int)$u['id']] = ['username' => $u['username'], 'email' => $u['email']];
          }
        }
      ?>
      <div class="muted" style="margin:8px 0;">
        ลำดับ Reviewer:
        <ol style="margin:6px 0 12px 20px;">
          <?php foreach($reviewers as $i=>$uid): ?>
            <?php 
              $info = $reviewerNames[(int)$uid] ?? null;
              $name = $info ? $info['username'] : "User #{$uid}";
              $email = $info ? $info['email'] : '';
            ?>
            <li style="margin-bottom:4px;">
              <strong><?=$name?></strong>
              <?php if($email): ?><small class="muted"> (<?=$email?>)</small><?php endif; ?>
              <?php if($i === $curIdx): ?>
                <span class="badge" style="background:#ff9800;color:white;padding:2px 6px;border-radius:3px;margin-left:6px;">รอรีวิว</span>
              <?php elseif($i < $curIdx): ?>
                <span class="badge" style="background:#4caf50;color:white;padding:2px 6px;border-radius:3px;margin-left:6px;">ผ่านแล้ว</span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>
    <?php endif; ?>
    <?php
      $total = count($reviewers);
      $done = $curIdx; if ($doc['status']==='PASS' || $doc['status']==='FAIL') { $done = $total; }
      $percent = $total>0 ? round(($done/$total)*100) : 0;
      $steps = function_exists('get_document_review_steps') ? get_document_review_steps((int)$doc['id']) : [];
    ?>
    <div style="margin:6px 0 14px;">
      <div style="font-size:13px;color:#666;">ความคืบหน้า: <?=$done?>/<?=$total?> (<?=$percent?>%)</div>
      <div style="height:10px;background:#eee;border-radius:6px;overflow:hidden;">
        <div style="height:10px;width:<?=$percent?>%;background:#59a14f;"></div>
      </div>
    </div>
    <?php if($steps): ?>
      <?php include __DIR__ . '/review_timeline.php'; ?>
    <?php endif; ?>
    <form method="post" action="?a=doc_review_save">
      <input type="hidden" name="id" value="<?= (int)$doc['id'] ?>" />
      <label>สถานะ
        <select name="status">
          <option value="PENDING" <?= $doc['status']==='PENDING'?'selected':'' ?>>รอตรวจสอบ</option>
          <option value="COMMENT">ให้ความเห็นเพิ่มเติม</option>
          <option value="PASS" <?= $doc['status']==='PASS'?'selected':'' ?>>อนุมัติ</option>
          <option value="FAIL" <?= $doc['status']==='FAIL'?'selected':'' ?>>ไม่อนุมัติ(มีการแก้ไข)</option>
        </select>
      </label>
      <label>หมายเหตุ
        <textarea name="notes" rows="3" style="width:100%;padding:8px;"><?= htmlspecialchars($doc['notes'] ?? '') ?></textarea>
      </label>
      <div class="actions" style="margin-top:12px;">
        <button class="btn primary" type="submit">บันทึกผล</button>
        <a class="btn" href="<?= $backUrl ?>">กลับ</a>
      </div>
    </form>
  </section>
<?php endif; ?>
<?php include __DIR__.'/../footer.php'; ?>