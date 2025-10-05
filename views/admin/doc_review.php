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
      <div class="muted" style="margin:8px 0;">
        ลำดับ Reviewer:
        <ol style="margin:6px 0 12px 20px;">
          <?php foreach($reviewers as $i=>$uid): ?>
            <li>
              ผู้รีวิว #<?=($i+1)?> (User ID: <?=$uid?>)
              <?php if($i === $curIdx): ?><span class="badge">รอรีวิว</span><?php endif; ?>
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
      <div style="margin:10px 0;">
        <div style="font-size:13px;color:#666;">ไทม์ไลน์การรีวิว</div>
        <ul style="list-style:none;padding:0;margin:6px 0;">
          <?php foreach($steps as $s): ?>
            <li style="padding:6px 8px;border:1px solid #eee;border-radius:6px;margin-bottom:6px;">
              <strong><?=htmlspecialchars($s['action'])?></strong>
              โดย User ID: <?= (int)$s['reviewer_id'] ?>
              <small style="color:#666;"> (<?=htmlspecialchars($s['created_at'])?>)</small>
              <?php if(!empty($s['notes'])): ?><div class="muted">หมายเหตุ: <?=nl2br(htmlspecialchars($s['notes']))?></div><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form method="post" action="?a=doc_review_save">
      <input type="hidden" name="id" value="<?= (int)$doc['id'] ?>" />
      <label>สถานะ
        <select name="status">
          <option value="PENDING" <?= $doc['status']==='PENDING'?'selected':'' ?>>รอตรวจสอบ</option>
          <option value="PASS" <?= $doc['status']==='PASS'?'selected':'' ?>>อนุมัติ</option>
          <option value="FAIL" <?= $doc['status']==='FAIL'?'selected':'' ?>>ไม่อนุมัติ(มีการแก้ไข)</option>
        </select>
      </label>
      <label>หมายเหตุ
        <textarea name="notes" rows="3" style="width:100%;padding:8px;"><?= htmlspecialchars($doc['notes'] ?? '') ?></textarea>
      </label>
      <div class="actions" style="margin-top:12px;">
        <button class="btn primary" type="submit">บันทึกผล</button>
        <a class="btn" href="?a=admin">กลับ</a>
      </div>
    </form>
  </section>
<?php endif; ?>