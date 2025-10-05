<form method="post" action="?a=history_search" class="card" style="max-width:480px;">
  <h2>ค้นหาประวัติการประเมิน</h2>
  <?php if (!empty($error)): ?><p style="color:#ef4444;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <label>อีเมลที่ใช้ประเมิน
    <input type="email" name="contact_email" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">ค้นหา</button>
    <a class="btn" href="?">กลับหน้าหลัก</a>
  </div>
</form>