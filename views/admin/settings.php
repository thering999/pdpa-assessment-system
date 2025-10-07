<?php include __DIR__.'/../header.php'; ?>
<?php $token = form_token_issue(); ?>
<form method="post" action="?a=admin_settings_save" class="card" style="max-width:560px;">
  <h2>ตั้งค่า</h2>
  <?php if (!empty($flash)): ?><p style="color:#2dd4bf;"><?= htmlspecialchars($flash) ?></p><?php endif; ?>
  <label>รหัสผ่านผู้ดูแลใหม่
    <input type="password" name="new_password" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <input type="hidden" name="form_token" value="<?= htmlspecialchars($token) ?>">
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">บันทึก</button>
    <a class="btn" href="?a=admin">กลับ</a>
  </div>
</form>
<?php include __DIR__.'/../footer.php'; ?>