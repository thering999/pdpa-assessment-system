<section class="card">
  <h2>จัดการผู้ใช้</h2>
  <?php if (!empty($flash)): ?><p style="color:#2dd4bf;"><?= htmlspecialchars($flash) ?></p><?php endif; ?>
  <table style="width:100%;border-collapse:collapse;">
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
                <option value="user" <?= $u['role']==='user'?'selected':'' ?>>user</option>
                <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>admin</option>
              </select>
              <button class="btn" type="submit">อัพเดท</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="actions" style="margin-top:12px;">
    <a class="btn" href="?a=admin">กลับ</a>
  </div>
</section>