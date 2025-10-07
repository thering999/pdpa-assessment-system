<?php include __DIR__.'/../header.php'; ?>
<form method="post" action="?a=admin_login_submit" class="card">
  <h2>เข้าสู่ระบบผู้ดูแล</h2>
  <?php if (!empty($error)): ?><p style="color:#ef4444;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <label>รหัสผ่านผู้ดูแล
    <input type="password" name="password" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">เข้าสู่ระบบ</button>
    <a class="btn" href="?">กลับหน้าหลัก</a>
  </div>
</form>
<?php include __DIR__.'/../footer.php'; ?>
