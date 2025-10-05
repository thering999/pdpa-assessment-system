<section class="card">
  <h2>ประวัติการประเมินของ <?= htmlspecialchars($contact_email) ?></h2>
  <?php if (empty($rows)): ?>
    <p>ไม่พบประวัติการประเมินสำหรับอีเมลนี้</p>
    <a class="btn" href="?a=history">ค้นหาใหม่</a>
  <?php else: ?>
    <table style="width:100%;margin-top:12px;border-collapse:collapse;">
      <thead>
        <tr>
          <th>วันที่</th>
          <th>องค์กร</th>
          <th>คะแนน</th>
          <th>ระดับ</th>
          <th>ดูผล</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['completed_at'] ?? $r['started_at']) ?></td>
            <td><?= htmlspecialchars($r['organization_name'] ?? '-') ?></td>
            <td><?= (int)($r['score'] ?? 0) ?>%</td>
            <td><?= htmlspecialchars($r['risk_level'] ?? '-') ?></td>
            <td><a class="btn" href="?a=history_view&id=<?= (int)$r['id'] ?>" target="_blank">ดูผล</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a class="btn" href="?a=history">ค้นหาใหม่</a>
  <?php endif; ?>
</section>