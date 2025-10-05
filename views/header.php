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
        <?php if (!empty($_SESSION['user'])): ?>
          <a class="btn" href="?a=dashboard">แดชบอร์ด</a>
          <a class="btn" href="?a=notifications">การแจ้งเตือน</a>
          <span class="muted small">สวัสดี, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
          <a class="btn" href="?a=logout">ออกจากระบบ</a>
        <?php else: ?>
          <a class="btn" href="?a=login">เข้าสู่ระบบ</a>
          <a class="btn" href="?a=register">สมัครสมาชิก</a>
        <?php endif; ?>
        <a class="btn" href="?a=admin">Admin</a>
      </nav>
    </div>
    <button onclick="toggleDark()" style="float:right;">🌙</button>
    <?php if(isset($_SESSION['user_id'])) include __DIR__.'/notifications.php'; ?>
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
