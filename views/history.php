<section class="card" style="max-width:900px;margin:32px auto;">
  <h2>ประวัติการประเมินย้อนหลัง</h2>
  <table style="width:100%;margin-top:16px;border-collapse:collapse;">
    <thead>
      <tr>
        <th style="padding:8px;border-bottom:1px solid #24314f;">วันที่</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">ชื่อผู้ประเมิน</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">หน่วยงาน</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">คะแนนรวม</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">ระดับ</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">เปรียบเทียบ</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">Export</th>
      </tr>
    </thead>
    <tbody>
  <?php if (!isset($rows)) $rows = (isset($history) ? $history : []); ?>
  <?php foreach ($rows as $h): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['started_at']) ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['assessor_name']) ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['organization_name'] ?? '') ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['score'] ?? '') ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['risk_level'] ?? '') ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <a class="btn" href="?a=compare_assessment&id=<?= (int)$h['id'] ?>">เปรียบเทียบ</a>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <a class="btn" href="?a=export_excel&id=<?= (int)$h['id'] ?>">Excel</a>
          <a class="btn" href="?a=export_pdf&id=<?= (int)$h['id'] ?>">PDF</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
