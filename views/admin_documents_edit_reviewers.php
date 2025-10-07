<?php
// UI สำหรับกำหนด reviewer หลายคน/กลุ่มในแต่ละเอกสาร
require_once 'db.php';
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$doc = $pdo->query("SELECT * FROM documents WHERE id=$id")->fetch();
if (!$doc) { echo 'Document not found'; exit; }

$users = $pdo->query("SELECT id,username,email,role FROM users WHERE role='reviewer' OR role='admin' ORDER BY role DESC, username")->fetchAll();
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $reviewers = $_POST['reviewers'] ?? [];
  $reviewers_json = json_encode(array_map('intval',$reviewers));
  $pdo->prepare("UPDATE documents SET reviewers=?, current_reviewer_idx=0 WHERE id=?")->execute([$reviewers_json, $id]);
  echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Assign Reviewer</title>';
  echo '<script src="/assets/toast.js"></script>';
  echo '<script>';
  echo 'window.onload=function(){ showToast("มอบหมาย Reviewer สำเร็จ", "success"); setTimeout(function(){ window.location = "?a=admin_documents"; }, 1200); }';
  echo '</script></head><body style="background:#101624;color:#fff;"></body></html>';
  exit;
}
$selected = $doc['reviewers'] ? json_decode($doc['reviewers'],true) : [];
echo '<div style="background:#101624;min-height:100vh;padding:0;margin:0;">';
echo '<section class="card" style="max-width:520px;margin:32px auto 0 auto;background:#181f2a;color:#f5f5f5;box-shadow:0 2px 12px #0003;border-radius:14px;padding:32px 32px 28px 32px;">';
echo '<h2 style="margin-bottom:18px;color:#fff;font-weight:700;">กำหนด Reviewer สำหรับเอกสาร</h2>';
echo '<form method="post">';
echo '<div style="display:flex;flex-direction:column;gap:14px;">';
foreach($users as $u) {
  $checked = in_array($u['id'], $selected) ? 'checked' : '';
  $roleColor = $u['role'] === 'admin' ? '#ff6b81' : '#4fc3f7';
  $bg = $u['role'] === 'admin' ? 'rgba(255,107,129,0.08)' : 'rgba(79,195,247,0.08)';
  echo "<label style='display:flex;align-items:center;gap:12px;padding:13px 16px;background:{$bg};border-radius:8px;border:1px solid #232b3b;box-shadow:0 1px 2px #0002;'>"
    ."<input type='checkbox' name='reviewers[]' value='{$u['id']}' $checked style='width:20px;height:20px;accent-color:{$roleColor};'>"
    ."<span style='font-weight:500;font-size:1.13em;color:#fff'>{$u['username']}</span>"
    ."<span style='color:#b0b8c9;font-size:13px;'>({$u['email']})</span>"
    ."<span style='color:{$roleColor};font-size:13px;font-weight:bold;'>[{$u['role']}]</span>"
    ."</label>";
}
echo '</div>';
echo '<div style="margin-top:28px;display:flex;gap:14px;">';
echo '<button type="submit" class="btn btn-primary" style="font-size:1.08em;padding:8px 22px;">💾 บันทึก</button>';
echo '<a href="?a=admin_documents" class="btn btn-secondary" style="font-size:1.08em;padding:8px 22px;">ย้อนกลับ</a>';
echo '</div>';
echo '</form>';
echo '</section>';
echo '</div>';
