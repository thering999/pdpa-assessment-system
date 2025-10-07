<?php include __DIR__.'/header.php'; ?>
<form method="post" action="?a=start_submit" class="card" style="max-width:720px;margin:0 auto;">
  <h2>เริ่มทำแบบประเมิน</h2>
  <?php if (!empty($error)): ?>
    <p class="muted" style="color:#ef4444;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <div style="display:grid;gap:8px;max-width:520px;">
    <label>ชื่อองค์กร
      <input type="text" name="organization_name" value="<?= htmlspecialchars($organization_name ?? '') ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
    </label>
    <label>ชื่อผู้ประเมิน
      <input type="text" name="assessor_name" value="<?= htmlspecialchars($assessor_name ?? '') ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
    </label>
    <label>อีเมลติดต่อ
      <input type="email" name="contact_email" value="<?= htmlspecialchars($contact_email ?? '') ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
    </label>
    <label>สถานะหน่วยงาน
      <select name="org_status" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
        <option value="">-- เลือกสถานะ --</option>
        <option value="CII" <?= (isset($org_status) && $org_status==="CII") ? 'selected' : '' ?>>หน่วยงาน CII</option>
        <option value="ทั่วไป" <?= (isset($org_status) && $org_status==="ทั่วไป") ? 'selected' : '' ?>>หน่วยงานทั่วไป</option>
      </select>
    </label>
  </div>
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">เริ่มทำแบบประเมิน</button>
    <a class="btn" href="?">ยกเลิก</a>
  </div>
</form>
<?php include __DIR__.'/footer.php'; ?>
