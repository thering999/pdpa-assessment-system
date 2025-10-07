<style>
.table {
  border-collapse: collapse;
  width: 100%;
}
.table th, .table td {
  border: 1px solid #374151;
  padding: 8px;
}
.table th {
  background: #222b;
  color: #fff;
}
.table tr {
  background: #1a2140;
}
.table tr:nth-child(even) {
  background: #222b;
}
</style>
<?php $cfg = require __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($cfg['app_name']); ?></title>
  <link rel="stylesheet" href="/assets/style.css" />
</head>
<body>
  <header class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
  <h1><?php echo htmlspecialchars($cfg['app_name']); ?></h1>
      <nav style="display:flex;gap:8px;align-items:center;">
        <a class="btn" href="?">หน้าแรก</a>
        <a class="btn" href="?a=history">ประวัติ</a>
        <?php 
          $navUser = $_SESSION['user'] ?? null;
          if (!$navUser && (!empty($_SESSION['is_admin']))) {
            // Virtual admin user for header display
            $navUser = ['username'=>'admin','role'=>'admin'];
          }
        ?>
        <?php if (!empty($navUser)): ?>
          <a class="btn" href="?a=dashboard">แดชบอร์ด</a>
          <?php if (($navUser['role'] ?? '') === 'evaluator'): ?>
            <a class="btn" href="?a=result">ภาพรวมการประเมินของหน่วยงาน</a>
          <?php endif; ?>
          <a class="btn" href="?a=my_documents">เอกสารของฉัน</a>
          <?php if (in_array(($navUser['role'] ?? ''), ['reviewer','admin'])): ?>
            <a class="btn" href="?a=reviewer_documents">งานรีวิว 
              <?php 
                // Show pending count for reviewer
                if (($navUser['role'] ?? '') === 'reviewer') {
                  try {
                    $uid = (int)($navUser['id'] ?? 0);
                    $needle = '%"'.((string)$uid).'"%';
                    $pendingStmt = db()->prepare("SELECT COUNT(*) FROM documents d WHERE d.status='PENDING' AND d.reviewers IS NOT NULL AND d.reviewers != '' AND d.reviewers LIKE ?");
                    $pendingStmt->execute([$needle]);
                    $pending = (int)$pendingStmt->fetchColumn();
                    if ($pending > 0) echo "<span style='background:#ff9800;color:white;padding:1px 6px;border-radius:10px;font-size:11px;margin-left:4px;'>$pending</span>";
                  } catch (Throwable $e) { /* ignore */ }
                }
              ?>
            </a>
          <?php endif; ?>
          <a class="btn" id="bell" href="?a=notifications">การแจ้งเตือน <span style="margin-left:4px;"></span></a>
          <span class="muted small">สวัสดี, <?php echo htmlspecialchars($navUser['username'] ?? 'user'); ?> 
            <small style="color:#666;">[<?= htmlspecialchars($navUser['role'] ?? 'user') ?>]</small>
          </span>
          <a class="btn" href="?a=logout">ออกจากระบบ</a>
        <?php else: ?>
          <a class="btn" href="?a=login">เข้าสู่ระบบ</a>
          <a class="btn" href="?a=register">สมัครสมาชิก</a>
        <?php endif; ?>
        <a class="btn" href="?a=admin">Admin</a>
      </nav>
    </div>
    <button onclick="toggleDark()" style="float:right;">🌙</button>
  <?php /* Removed including the full notifications page here to avoid recursion/duplication. */ ?>
  </header>
  <div id="loading-spinner"><div class="spinner"></div></div>
  <div id="toast"></div>
  <script src="/assets/main.js"></script>
  <script>
  // Loading on form submit
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form').forEach(f=>{
      f.addEventListener('submit', function(){ showLoading(); });
    });
    // Poll notifications
    if(document.getElementById('bell')) {
      setInterval(async function() {
        let res = await fetch('/api_notifications.php');
        if(res.ok) {
          let notis = await res.json();
          let badge = document.querySelector('#bell span');
          if(badge) badge.innerText = notis.length>0 ? notis.length : '';
          if(notis.length>0) document.getElementById('bell').classList.add('has-new');
          else document.getElementById('bell').classList.remove('has-new');
        }
      }, 10000);
    }
  });
  </script>
  <style>
  #bell.has-new { animation: bellshake 0.5s infinite alternate; }
  @keyframes bellshake { 0%{transform:rotate(-10deg);} 100%{transform:rotate(10deg);} }
  </style>
  <main class="container">
