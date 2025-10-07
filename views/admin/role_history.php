<?php include __DIR__.'/../header.php'; ?>
<?php
// $userInfo, $history expected
?>
<section class="card">
  <h2>ประวัติการกำหนดสิทธิ์: <?= htmlspecialchars($userInfo['username'] ?? ('User #'.(int)($_GET['id']??0))) ?></h2>
  <p class="muted">อีเมล: <?= htmlspecialchars($userInfo['email'] ?? '-') ?> | สิทธิ์ปัจจุบัน: <strong><?= htmlspecialchars($userInfo['role'] ?? '-') ?></strong> | สมัครเมื่อ: <?= htmlspecialchars($userInfo['created_at'] ?? '-') ?></p>
  <table class="table">
    <thead>
      <tr>
        <th>วันที่/เวลา</th>
        <th>สิทธิ์ที่กำหนด</th>
        <th>ผู้กำหนด</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($history)): ?>
        <tr><td colspan="3" class="muted">ยังไม่มีประวัติการกำหนดสิทธิ์</td></tr>
      <?php else: foreach ($history as $h): ?>
        <tr>
          <td><?= htmlspecialchars($h['assigned_at']) ?></td>
          <td><?= htmlspecialchars($h['role']) ?></td>
          <td><?= htmlspecialchars($h['assigned_by_name'] ?? ('User #'.(int)($h['assigned_by'] ?? 0))) ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
  <div class="actions" style="margin-top:12px;">
    <a class="btn" href="?a=admin_users">กลับ</a>
  </div>
</section>
<?php include __DIR__.'/../footer.php'; ?>
