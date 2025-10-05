<?php
// Admin Sidebar (responsive, dropdown)
?>
<div class="sidebar">
  <a href="?a=admin">จัดการคำถาม</a>
  <a href="?a=admin_categories">หมวดหมู่</a>
  <div class="dropdown" onclick="toggleDropdown(this)">Export/Import
    <div class="dropdown-content">
      <a href="?a=admin_export_import">Export/Import</a>
    </div>
  </div>
  <a href="?a=admin_documents">เอกสาร</a>
  <a href="?a=executive_dashboard">Executive Dashboard</a>
  <a href="?a=faq">FAQ/คู่มือ</a>
  <a href="?a=logout">ออกจากระบบ</a>
  <button onclick="toggleDark()" style="margin:20px 0 0 20px;">🌙/☀️</button>
</div>
