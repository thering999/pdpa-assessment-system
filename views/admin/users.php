<?php include __DIR__.'/../header.php'; ?>
<section class="card">
  <h2>จัดการผู้ใช้</h2>
  <?php if (!empty($flash)): ?><p style="color:#2dd4bf;"><?= htmlspecialchars($flash) ?></p><?php endif; ?>
  <table class="table" style="width:100%;border-collapse:collapse;">
    <thead>
      <tr>
        <th>ID</th>
        <th>ชื่อผู้ใช้</th>
        <th>อีเมล</th>
        <th>สิทธิ์</th>
        <th>สร้างเมื่อ</th>
        <th>จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['role']) ?></td>
          <td><?= htmlspecialchars($u['created_at']) ?></td>
          <td>
            <form method="post" action="?a=admin_user_role" style="display:inline-flex;gap:8px;align-items:center;">
              <input type="hidden" name="id" value="<?= (int)$u['id'] ?>" />
              <select name="role">
                <option value="evaluator" <?= $u['role']==='evaluator'?'selected':'' ?>>evaluator</option>
                <option value="reviewer" <?= $u['role']==='reviewer'?'selected':'' ?>>reviewer</option>
                <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>admin</option>
              </select>
              <button class="btn" type="submit">อัพเดท</button>
            </form>
            <a class="btn" href="?a=admin_user_role_history&id=<?= (int)$u['id'] ?>">ประวัติสิทธิ์</a>
            <a class="btn" href="?a=admin_user_edit&id=<?= (int)$u['id'] ?>">แก้ไข</a>
            <a class="btn" href="?a=admin_user_password&id=<?= (int)$u['id'] ?>">เปลี่ยนรหัสผ่าน</a>
            <form method="post" action="?a=admin_user_delete" style="display:inline;" onsubmit="return confirm('ยืนยันการลบผู้ใช้นี้?');">
              <input type="hidden" name="id" value="<?= (int)$u['id'] ?>" />
              <button class="btn" type="submit" style="background:#ef4444;color:white;">ลบ</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p class="muted" style="margin-top:8px;">
    <strong>ข้อมูลการใช้งาน:</strong><br>
    • ผู้ใช้ที่มีสิทธิ์ <strong>reviewer</strong> จะเห็นเมนู "งานรีวิว" เพื่อเข้าถึงเอกสารที่ต้องตรวจ<br>
    • ระบบมี reviewer ตัวอย่าง: <code>reviewer1</code>, <code>reviewer2</code>, <code>chief_reviewer</code> (รหัสผ่าน: password123)<br>
    • Admin สามารถกำหนดลำดับ reviewer และดูประวัติการกำหนดสิทธิ์ได้
  </p>
  <div class="actions" style="margin-top:12px;">
    <a class="btn" href="?a=admin">กลับ</a>
  </div>
</section>
<?php include __DIR__.'/../footer.php'; ?>