<?php
// Variables: $aid, $items, $avg_now, $avg_last, $token
?>
<div class="container">
  <h2>D2 : นโยบายบริหารจัดการ (Self Assessment for CII)</h2>
  <p class="muted small">
    เกณฑ์การให้คะแนน: 3 = มีเอกสารครบ, 2 = มีเอกสารบางส่วน, 1 = ไม่มีเอกสาร
  </p>
  <div style="display:flex;gap:16px;align-items:center;margin:8px 0;">
    <div class="kpi-card">ผลเฉลี่ยครั้งนี้: <b><?= number_format((float)$avg_now,1) ?></b></div>
    <div class="kpi-card">ผลเฉลี่ยครั้งล่าสุด: <b><?= number_format((float)$avg_last,1) ?></b></div>
  </div>
  <form method="post" action="?a=cii_d2_save" onsubmit="return confirm('บันทึกคะแนน?');">
    <input type="hidden" name="form_token" value="<?= htmlspecialchars($token) ?>"/>
    <input type="hidden" name="aid" value="<?= (int)$aid ?>"/>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:64px;">ลำดับ</th>
            <th>รายการ (Objective)</th>
            <th style="width:220px;">ที่มา (Requirement)</th>
            <th style="width:260px;">หลักฐาน (Evident)</th>
            <th style="width:120px;">คะแนน (1-3)</th>
            <th style="width:240px;">หมายเหตุ</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td class="center"><?= (int)$it['num'] ?></td>
            <td><?= nl2br(htmlspecialchars((string)$it['objective'])) ?></td>
            <td><?= nl2br(htmlspecialchars((string)$it['requirement'])) ?></td>
            <td><?= nl2br(htmlspecialchars((string)$it['evident'])) ?></td>
            <?php $sid = (int)$it['id']; $cur = (int)($it['score'] ?? 0); $bg = $cur===3?'#b8f7c1':($cur===2?'#ffeaa7':($cur===1?'#ffb3aa':'transparent')); ?>
            <td style="background: <?= $bg ?>;">
              <select name="score[<?= $sid ?>]" class="input">
                <option value="">-</option>
                <?php foreach([1,2,3] as $s): $sel = $cur===$s?'selected':''; ?>
                <option value="<?= $s ?>" <?= $sel ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td>
              <textarea name="note[<?= $sid ?>]" class="input" rows="2"><?php echo htmlspecialchars((string)($it['score_note'] ?? '')); ?></textarea>
              <hr/>
              <form method="post" action="?a=cii_evidence_upload" enctype="multipart/form-data" style="margin:0;padding:0;">
                <input type="hidden" name="aid" value="<?= (int)$aid ?>"/>
                <input type="hidden" name="item_id" value="<?= $sid ?>"/>
                <input type="hidden" name="back" value="<?= htmlspecialchars($_GET['a'] ?? 'cii_d2') ?>"/>
                <input type="file" name="file" style="width:70%"/>
                <input type="text" name="note" placeholder="คอมเมนต์/หมายเหตุ" style="width:60%"/>
                <button class="btn small" type="submit">แนบไฟล์/คอมเมนต์</button>
              </form>
              <?php 
                if (!function_exists('cii_get_evidence')) require_once __DIR__.'/../db.php';
                $evs = cii_get_evidence((int)$aid, $sid);
                if ($evs) {
                  echo '<ul class="evidence-list">';
                  foreach ($evs as $ev) {
                    echo '<li style="font-size:12px;">';
                    if ($ev['file']) {
                      echo '<a href="/uploads_cii/'.htmlspecialchars($ev['file']).'" target="_blank">'.htmlspecialchars($ev['file']).'</a> ';
                    }
                    if ($ev['note']) echo nl2br(htmlspecialchars($ev['note'])).' ';
                    echo '<span class="muted">['.htmlspecialchars($ev['uploaded_at']).']</span>';
                    echo '</li>';
                  }
                  echo '</ul>';
                }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div style="margin-top:12px;display:flex;gap:8px;">
      <button class="btn" type="submit">บันทึก</button>
      <a class="btn" href="?a=cii_d2_start">เริ่มแบบประเมิน D2 ใหม่</a>
    </div>
  </form>
</div>
