<section class="card result-excel">
  <h2>แบบประเมินสถานภาพการดำเนินงานด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ (Self Assessment for CII)</h2>
  <div class="result-header-grid">
    <div><b>ชื่อหน่วยงาน:</b> <?= htmlspecialchars($assessment['organization_name'] ?? '-') ?></div>
    <div><b>ชื่อผู้ทำการประเมิน:</b> <?= htmlspecialchars($assessment['assessor_name'] ?? '-') ?></div>
    <div><b>อีเมลติดต่อ:</b> <?= htmlspecialchars($assessment['contact_email'] ?? '-') ?></div>
    <div><b>วันที่ทำการประเมิน:</b> <?= htmlspecialchars($assessment['started_at'] ?? '-') ?></div>
    <div><b>สถานะของหน่วยงาน:</b> <span><?= htmlspecialchars($assessment['org_status'] ?? '-') ?></span></div>
  </div>
  <div class="objectives card">
    <b style="color:#111">วัตถุประสงค์ของการทำแบบประเมิน</b>
    <ol>
      <li style="color:#111">เป็นการตรวจสอบว่าเราได้ปฏิบัติตามที่กฏหมายกำหนดไว้ หรือไม่</li>
      <li style="color:#111">เพื่อดูว่าตัวเราเองมีความสอดคล้องกับ พรบ ไซเบอร์ หรือไม่</li>
      <li style="color:#111">เพื่อดูว่าเราเอง มีเอกสารอะไรที่ยังขาดอยู่บ้างในตอนนี้</li>
      <li style="color:#111">สามารถนำไปเป็นส่วนหนึ่งของการตรวจสอบได้ เช่น Audit Compliance CheckList</li>
    </ol>
  </div>
  <div class="howto card">
    <b style="color:#111">วิธีการทำแบบประเมินสถานภาพการดำเนินงาน</b>
    <ul>
      <li style="color:#111">หากมีเอกสารหรือหลักฐานดังกล่าว ให้ใส่เลข <b style="color:#111">3</b></li>
      <li style="color:#111">หากมีเอกสารหรือหลักฐานเป็นบางส่วน ให้ใส่เลข <b style="color:#111">2</b></li>
      <li style="color:#111">หากไม่มีเอกสารหรือหลักฐานดังกล่าว ให้ใส่เลข <b style="color:#111">1</b></li>
    </ul>
  </div>
  <div class="legend-bar card">
    <b>ผลการประเมินสถานภาพการดำเนินงาน</b>
    <div class="legend-row">
      <span class="legend-box green"></span> <span style="color:#111">แถบสีเขียว: สอดคล้องกับ พรบ ไซเบอร์ (2.6 - 3.0)</span>
      <span class="legend-box yellow"></span> <span style="color:#111">แถบสีเหลือง: อยู่ระหว่างการทำให้สอดคล้อง (2.1 - 2.5)</span>
      <span class="legend-box red"></span> <span style="color:#111">แถบสีแดง: ยังไม่สอดคล้อง (0 - 2.0)</span>
    </div>
  </div>
  <div class="score-summary card">
    <div class="score-main">
      <div class="score-label">คะแนนเฉลี่ยรวม</div>
      <div class="score-value score-<?= $level ?>"><?= $percent ?></div>
      <div class="score-color-label score-<?= $level ?>"><?= $level ?></div>
    </div>
    <div class="score-desc">
      <span>เกณฑ์: 3 = มี, 2 = มีบางส่วน, 1 = ไม่มีเลย</span>
    </div>
  </div>
  <?php if (!empty($categories)): ?>
    <div class="cat-summary-grid">
      <?php foreach ($categories as $c): ?>
        <div class="cat-card card cat-<?= $c['color'] ?>">
          <div class="cat-title" style="color:#111;"> <?= htmlspecialchars($c['category_name']) ?> </div>
          <div class="cat-avg" style="color:#111;">เฉลี่ย: <b style="color:#111;"><?= $c['avg'] ?? '-' ?></b></div>
          <div class="cat-color-label cat-<?= $c['color'] ?>" style="color:#111;"><?= $c['color'] ?></div>
          <a class="btn" href="?a=upload_doc&cid=<?= (int)$c['category_id'] ?>">แนบเอกสาร</a>
        </div>
      <?php endforeach; ?>
    </div>
    <div style="margin:16px 0;">
      <a class="btn" href="?a=dashboard">ดูแดชบอร์ด</a>
      <a class="btn" href="?a=summary">สรุปรวม</a>
    </div>
    <!-- ตารางผลประเมินแบบ Excel -->
    <div class="excel-table-wrap card" style="margin-top:24px;overflow-x:auto;">
      <table class="excel-table" style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:#f8f9fa; color:#111">
            <th>ลำดับ</th>
            <th>รหัส</th>
            <th>รายการ (Objective)</th>
            <th>หมวด</th>
            <th>น้ำหนัก</th>
            <th>คะแนน</th>
            <th>หมายเหตุ</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach ($answers as $row): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['code']) ?></td>
            <td><?= htmlspecialchars($row['text']) ?></td>
            <td><?= htmlspecialchars($row['category'] ?? '-') ?></td>
            <td><?= (int)($row['weight'] ?? 1) ?></td>
            <td style="text-align:center;<?php
              $v = (int)($row['answer_value'] ?? 0);
              if ($v==3) echo 'background:#d4edda;';
              elseif ($v==2) echo 'background:#fff3cd;';
              elseif ($v==1) echo 'background:#f8d7da;';
            ?>">
              <?= $v ?>
            </td>
            <td><?= htmlspecialchars($row['notes'] ?? '') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
  <details class="answers">
    <summary>รายละเอียดคำตอบ</summary>
    <ul>
      <?php foreach ($answers as $row): ?>
        <li>
          <strong><?= htmlspecialchars($row['code']) ?></strong> - <?= htmlspecialchars($row['text']) ?>
          <em>(น้ำหนัก <?= (int)$row['weight'] ?>)</em> →
          <span class="badge">คะแนน: <?= (int)($row['answer_value'] ?? 0) ?>/3</span>
          <?php if (!empty($row['notes'])): ?>
            <div class="answer-notes" style="margin-top:4px;color:#444;font-size:0.97em;">
              <b>หมายเหตุ:</b> <?= htmlspecialchars($row['notes']) ?>
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </details>
  <div class="actions">
    <a class="btn" href="?">เริ่มประเมินใหม่</a>
    <a class="btn" href="?a=print" target="_blank">พิมพ์/บันทึก PDF</a>
    <a class="btn" href="?a=export_excel" target="_blank">Export Excel</a>
    <a class="btn" href="?a=export_pdf" target="_blank">Export PDF</a>
  </div>
</section>
