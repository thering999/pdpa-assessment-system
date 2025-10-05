<section class="card" style="max-width:1200px;margin:32px auto;">
  <h2>เปรียบเทียบผลการประเมิน</h2>
  
  <div class="current-assessment card" style="margin:16px 0;">
    <h3>การประเมินปัจจุบัน</h3>
    <p><strong>หน่วยงาน:</strong> <?= htmlspecialchars($assessment['organization_name'] ?? '') ?></p>
    <p><strong>ผู้ประเมิน:</strong> <?= htmlspecialchars($assessment['assessor_name'] ?? '') ?></p>
    <p><strong>วันที่:</strong> <?= htmlspecialchars($assessment['started_at'] ?? '') ?></p>
    <p><strong>คะแนน:</strong> <?= htmlspecialchars($assessment['score'] ?? '') ?></p>
    <p><strong>ระดับความเสี่ยง:</strong> <?= htmlspecialchars($assessment['risk_level'] ?? '') ?></p>
  </div>

  <?php if (!empty($categories)): ?>
  <div class="categories-comparison card" style="margin:16px 0;">
    <h3>คะแนนตามหมวด</h3>
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr>
          <th style="padding:8px;border:1px solid #ddd;">หมวด</th>
          <th style="padding:8px;border:1px solid #ddd;">คะแนนเฉลี่ย</th>
          <th style="padding:8px;border:1px solid #ddd;">ระดับ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $cat): ?>
        <tr>
          <td style="padding:8px;border:1px solid #ddd;"><?= htmlspecialchars($cat['category_name'] ?? '') ?></td>
          <td style="padding:8px;border:1px solid #ddd;"><?= htmlspecialchars($cat['avg'] ?? '') ?></td>
          <td style="padding:8px;border:1px solid #ddd;">
            <span style="background:<?= $cat['color'] === 'green' ? '#2ecc40' : ($cat['color'] === 'yellow' ? '#ffdc00' : '#ff4136') ?>;color:white;padding:4px 8px;border-radius:4px;">
              <?= htmlspecialchars($cat['color'] ?? '') ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <?php if (!empty($other_assessments)): ?>
  <div class="other-assessments card" style="margin:16px 0;">
    <h3>การประเมินอื่นๆ ของคุณ</h3>
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr>
          <th style="padding:8px;border:1px solid #ddd;">วันที่</th>
          <th style="padding:8px;border:1px solid #ddd;">หน่วยงาน</th>
          <th style="padding:8px;border:1px solid #ddd;">คะแนน</th>
          <th style="padding:8px;border:1px solid #ddd;">ระดับ</th>
          <th style="padding:8px;border:1px solid #ddd;">การดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($other_assessments as $other): ?>
        <tr>
          <td style="padding:8px;border:1px solid #ddd;"><?= htmlspecialchars($other['started_at'] ?? '') ?></td>
          <td style="padding:8px;border:1px solid #ddd;"><?= htmlspecialchars($other['organization_name'] ?? '') ?></td>
          <td style="padding:8px;border:1px solid #ddd;"><?= htmlspecialchars($other['score'] ?? '') ?></td>
          <td style="padding:8px;border:1px solid #ddd;"><?= htmlspecialchars($other['risk_level'] ?? '') ?></td>
          <td style="padding:8px;border:1px solid #ddd;">
            <a class="btn" href="?a=compare_assessment&id=<?= (int)$other['id'] ?>">ดูรายละเอียด</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <div class="no-other-assessments card" style="margin:16px 0;text-align:center;padding:32px;">
    <p>ยังไม่มีการประเมินอื่นเพื่อเปรียบเทียบ</p>
  </div>
  <?php endif; ?>

  <div class="actions" style="margin-top:16px;text-align:center;">
    <a class="btn" href="?a=history">กลับไปประวัติการประเมิน</a>
    <a class="btn" href="?a=export_excel&id=<?= (int)$assessment['id'] ?>">Export Excel</a>
    <a class="btn" href="?a=export_pdf&id=<?= (int)$assessment['id'] ?>">Export PDF</a>
  </div>
</section>
