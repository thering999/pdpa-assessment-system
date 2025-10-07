<?php include __DIR__.'/../header.php'; ?>
<section class="card">
  <h2>แก้ไขข้อมูลผู้ใช้</h2>
  <form method="post" action="?a=admin_user_update">
    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>" />
    <div>
      <label>ชื่อผู้ใช้:<br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required />
      </label>
    </div>
    <div>
      <label>อีเมล:<br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
      </label>
    </div>
    <div>
      <label>สิทธิ์เข้าถึงหน้า (เลือกได้หลายหน้า):<br>
        <select name="allowed_pages[]" multiple size="6" style="width:100%;max-width:400px;">
          <?php
          // กำหนดรายการหน้าที่สามารถจำกัดสิทธิ์ได้
          $pages = [
            'dashboard' => 'แดชบอร์ด',
            'history' => 'ประวัติการประเมิน',
            'assessment_detail' => 'รายละเอียดการประเมิน',
            'compare_assessment' => 'เปรียบเทียบผล',
            'notifications' => 'แจ้งเตือน',
            'documents' => 'เอกสาร',
            'admin' => 'เมนูผู้ดูแล',
          ];
          $allowed = [];
          if (!empty($user['allowed_pages'])) {
            $allowed = json_decode($user['allowed_pages'], true);
            if (!is_array($allowed)) $allowed = [];
          }
          foreach ($pages as $k => $v):
          ?>
            <option value="<?= htmlspecialchars($k) ?>" <?= in_array($k, $allowed) ? 'selected' : '' ?>><?= htmlspecialchars($v) ?></option>
          <?php endforeach; ?>
        </select>
        <br><small>กด Ctrl หรือ Cmd ค้างไว้เพื่อเลือกหลายหน้า</small>
      </label>
    </div>
    <div style="margin-top:12px;">
      <button class="btn" type="submit">บันทึก</button>
      <a class="btn" href="?a=admin_users">ยกเลิก</a>
    </div>
  </form>
</section>
<?php include __DIR__.'/../footer.php'; ?>
