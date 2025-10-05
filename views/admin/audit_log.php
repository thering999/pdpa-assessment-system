<section class="card" style="max-width:900px;margin:32px auto;">
  <h2>Audit Log (บันทึกการกระทำสำคัญ)</h2>
  <table style="width:100%;margin-top:16px;border-collapse:collapse;">
    <thead>
      <tr>
        <th>เวลา</th>
        <th>ผู้ใช้</th>
        <th>การกระทำ</th>
        <th>รายละเอียด</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
      <tr>
        <td><?= htmlspecialchars($log['created_at']) ?></td>
        <td><?= htmlspecialchars($log['user_id']) ?></td>
        <td><?= htmlspecialchars($log['action']) ?></td>
        <td><?= htmlspecialchars($log['details']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
