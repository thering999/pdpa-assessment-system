<?php include __DIR__.'/header.php'; ?>
<section class="card" style="max-width:900px;margin:32px auto;">
  <h2>รายละเอียดผลการประเมิน</h2>
  <?php if (empty($assessment)): ?>
    <p>ไม่พบข้อมูล</p>
  <?php else: ?>
    <div style="margin-bottom:16px;">
      <b>วันที่:</b> <?= htmlspecialchars($assessment['started_at']) ?><br>
      <b>ผู้ประเมิน:</b> <?= htmlspecialchars($assessment['assessor_name']) ?><br>
      <b>หน่วยงาน:</b> <?= htmlspecialchars($assessment['organization_name']) ?><br>
      <b>คะแนนรวม:</b> <?= htmlspecialchars($assessment['score']) ?><br>
      <b>ระดับ:</b> <?= htmlspecialchars($assessment['risk_level']) ?><br>
    </div>
    <table border="1" cellpadding="4" cellspacing="0" width="100%" style="border-collapse:collapse;">
      <tr style="background:#eee;">
        <th>ลำดับ</th><th>รหัส</th><th>รายการ (Objective)</th><th>หมวด</th><th>น้ำหนัก</th><th>คะแนน</th><th>หมายเหตุ</th>
      </tr>
      <?php foreach ($answers as $i => $row): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= htmlspecialchars($row['code']) ?></td>
          <td><?= htmlspecialchars($row['text']) ?></td>
          <td><?= htmlspecialchars($row['category']??'-') ?></td>
          <td><?= (int)($row['weight']??1) ?></td>
          <td><?= (int)($row['answer_value']??0) ?></td>
          <td><?= htmlspecialchars($row['notes']??'') ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
  <div style="margin-top:16px;">
    <a class="btn" href="?a=history">ย้อนกลับ</a>
    <?php if (!empty($assessment['id'])): ?>
      <a class="btn" href="?a=export_excel&id=<?= (int)$assessment['id'] ?>" target="_blank">Export Excel</a>
      <a class="btn" href="?a=export_pdf&id=<?= (int)$assessment['id'] ?>" target="_blank">Export PDF</a>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__.'/footer.php'; ?>