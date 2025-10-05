<section class="card" style="max-width:900px;margin:32px auto;">
  <h2>Permission Matrix (กำหนดสิทธิ์ละเอียด)</h2>
  <form method="post" action="?a=save_permission_matrix">
    <table style="width:100%;margin-top:16px;border-collapse:collapse;">
      <thead>
        <tr>
          <th>Role</th>
          <th>ดูผลรวม</th>
          <th>ดูรายละเอียด</th>
          <th>แก้ไข</th>
          <th>ลบ</th>
          <th>Export</th>
          <th>เห็นหมวด D1</th>
          <th>เห็นหมวด D2</th>
          <th>เห็นหมวด D3</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($roles as $role): ?>
        <tr>
          <td><?= htmlspecialchars($role['name']) ?></td>
          <?php foreach (["view_summary","view_detail","edit","delete","export","see_d1","see_d2","see_d3"] as $perm): ?>
          <td><input type="checkbox" name="perm[<?= htmlspecialchars($role['id']) ?>][<?= $perm ?>]" value="1" <?= !empty($role['perms'][$perm]) ? 'checked' : '' ?>></td>
          <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="actions" style="margin-top:16px;">
      <button class="btn primary" type="submit">บันทึกสิทธิ์</button>
    </div>
  </form>
</section>
