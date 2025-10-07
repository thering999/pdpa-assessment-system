<?php include __DIR__.'/../header.php'; ?>
<section class="card">
  <h2>หมวดหัวข้อประเมิน (Categories)</h2>
  <?php if (!empty($flash)): ?><p style="color:#2dd4bf;"><?= htmlspecialchars($flash) ?></p><?php endif; ?>
  <form method="post" action="?a=admin_categories_save" style="display:grid;gap:8px;max-width:720px;">
    <input type="hidden" name="id" value="0" />
    <label>Code <input type="text" name="code" /></label>
    <label>Name <input type="text" name="name" required /></label>
    <label>Description <textarea name="description" rows="2"></textarea></label>
    <label>Weight <input type="number" name="weight" min="1" value="1" /></label>
    <button class="btn" type="submit">เพิ่มหมวด</button>
  </form>
  <hr />
  <table style="width:100%;border-collapse:collapse;margin-top:12px;">
    <thead><tr><th>ID</th><th>Code</th><th>Name</th><th>Weight</th><th>จัดการ</th></tr></thead>
    <tbody>
    <?php foreach ($cats as $c): ?>
      <tr>
        <td><?= (int)$c['id'] ?></td>
        <td><?= htmlspecialchars($c['code']) ?></td>
        <td><?= htmlspecialchars($c['name']) ?></td>
        <td><?= (int)$c['weight'] ?></td>
        <td>
          <form method="post" action="?a=admin_categories_save" style="display:inline-grid;grid-template-columns:1fr 1fr 80px 80px;gap:6px;align-items:center;">
            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>" />
            <input type="text" name="code" value="<?= htmlspecialchars($c['code']) ?>" />
            <input type="text" name="name" value="<?= htmlspecialchars($c['name']) ?>" />
            <input type="number" name="weight" value="<?= (int)$c['weight'] ?>" />
            <button class="btn" type="submit">บันทึก</button>
            <a class="btn" href="?a=admin_categories_delete&id=<?= (int)$c['id'] ?>" onclick="return confirm('ลบหมวดนี้?');">ลบ</a>
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
<?php include __DIR__.'/../footer.php'; ?>