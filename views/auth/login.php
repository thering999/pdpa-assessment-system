<?php include __DIR__.'/../header.php'; ?>
<form method="post" action="?a=login_submit" class="card" style="max-width:480px;">
  <h2>เข้าสู่ระบบ</h2>
  <?php if (!empty($flash)): ?><p style="color:#2dd4bf;"><?= htmlspecialchars($flash) ?></p><?php endif; ?>
  <label>ชื่อผู้ใช้
    <input type="text" name="username" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <label>รหัสผ่าน
    <input type="password" name="password" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">เข้าสู่ระบบ</button>
    <a class="btn" href="?a=register">สมัครสมาชิก</a>
  </div>
</form>
<?php include __DIR__.'/../footer.php'; ?>