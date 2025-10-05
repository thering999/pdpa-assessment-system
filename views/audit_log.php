<section class="card">
  <h2>Audit Log (Admin)</h2>
  <table style="width:100%;margin-top:12px;border-collapse:collapse;">
    <thead>
      <tr>
        <th>เวลา</th>
        <th>User</th>
        <th>Action</th>
        <th>Details</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
      <tr>
        <td><?= htmlspecialchars($log['created_at']) ?></td>
        <td><?= htmlspecialchars($log['username'] ?? '-') ?></td>
        <td><?= htmlspecialchars($log['action']) ?></td>
        <td><?= nl2br(htmlspecialchars($log['details'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
