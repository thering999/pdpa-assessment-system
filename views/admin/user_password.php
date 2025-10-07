<?php include __DIR__.'/../header.php'; ?>
<section class="card">
  <h2>เปลี่ยนรหัสผ่านผู้ใช้</h2>
  <form method="post" action="?a=admin_user_password_update">
    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>" />
    <div>
      <label>รหัสผ่านใหม่:<br>
        <input type="password" name="password" required minlength="6" />
      </label>
    </div>
    <div style="margin-top:12px;">
      <button class="btn" type="submit">บันทึก</button>
      <a class="btn" href="?a=admin_users">ยกเลิก</a>
    </div>
  </form>
</section>
<?php include __DIR__.'/../footer.php'; ?>
