<section class="card">
  <h2>จัดการคำถาม</h2>
  <?php if (!empty($flash)): ?><p style="color:#2dd4bf;"><?= htmlspecialchars($flash) ?></p><?php endif; ?>
  <div class="actions">
    <a class="btn primary" href="?a=admin_new_q">เพิ่มคำถาม</a>
    <a class="btn" href="?a=admin_logout">ออกจากระบบ</a>
    <a class="btn" href="?a=admin_export_csv" target="_blank">Export CSV</a>
  <a class="btn" href="?a=admin_settings">ตั้งค่า</a>
    <a class="btn" href="?a=admin_categories">หมวดหัวข้อ</a>
    <a class="btn" href="?a=admin_import">นำเข้า CSV</a>
    <a class="btn" href="?a=admin_import_xlsx">นำเข้า XLSX</a>
  </div>
  <table style="width:100%;margin-top:12px;border-collapse:collapse;">
    <thead>
      <tr>
        <th style="text-align:left;border-bottom:1px solid #24314f;padding:8px;">ID</th>
        <th style="text-align:left;border-bottom:1px solid #24314f;padding:8px;">Code</th>
        <th style="text-align:left;border-bottom:1px solid #24314f;padding:8px;">คำถาม</th>
        <th style="text-align:left;border-bottom:1px solid #24314f;padding:8px;">หมวด</th>
        <th style="text-align:left;border-bottom:1px solid #24314f;padding:8px;">Weight</th>
        <th style="text-align:left;border-bottom:1px solid #24314f;padding:8px;">จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($qs as $q): ?>
        <tr>
          <td style="padding:8px;border-bottom:1px solid #24314f;"><?= (int)$q['id'] ?></td>
          <td style="padding:8px;border-bottom:1px solid #24314f;"><?= htmlspecialchars($q['code']) ?></td>
          <td style="padding:8px;border-bottom:1px solid #24314f;"><?= htmlspecialchars($q['text']) ?></td>
          <td style="padding:8px;border-bottom:1px solid #24314f;"><?= htmlspecialchars($q['category'] ?? '') ?></td>
          <td style="padding:8px;border-bottom:1px solid #24314f;"><?= (int)$q['weight'] ?></td>
          <td style="padding:8px;border-bottom:1px solid #24314f;">
            <a class="btn" href="?a=admin_edit_q&id=<?= (int)$q['id'] ?>">แก้ไข</a>
            <a class="btn" href="?a=admin_delete_q&id=<?= (int)$q['id'] ?>" onclick="return confirm('ลบคำถามนี้?');">ลบ</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>