<?php
// Breadcrumb navigation (admin only)
$map = [
  'admin' => 'จัดการคำถาม',
  'admin_categories' => 'หมวดหมู่',
  'admin_export_import' => 'Export/Import',
  'admin_documents' => 'เอกสาร',
  'executive_dashboard' => 'Executive Dashboard',
  'faq' => 'FAQ/คู่มือ',
  'notifications_history' => 'แจ้งเตือนย้อนหลัง',
];
$a = $_GET['a'] ?? '';
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin'] || !isset($map[$a])) return;
echo '<nav class="breadcrumb" style="margin:20px 0 10px 240px;font-size:15px;">';
echo '<a href="?a=admin">Admin</a>';
if ($a !== 'admin') echo ' &gt; <span>'.htmlspecialchars($map[$a]).'</span>';
echo '</nav>';
