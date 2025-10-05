<?php
// UI สำหรับกำหนด reviewer หลายคน/กลุ่มในแต่ละเอกสาร
require_once 'db.php';
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$doc = $pdo->query("SELECT * FROM documents WHERE id=$id")->fetch();
if (!$doc) { echo 'Document not found'; exit; }
$users = $pdo->query("SELECT id,username,email FROM users WHERE role='reviewer' OR role='admin'")->fetchAll();
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $reviewers = $_POST['reviewers'] ?? [];
  $reviewers_json = json_encode(array_map('intval',$reviewers));
  $pdo->prepare("UPDATE documents SET reviewers=?, current_reviewer_idx=0 WHERE id=?")->execute([$reviewers_json, $id]);
  echo "<script>showToast('บันทึก reviewer สำเร็จ');window.location='?a=admin_documents';</script>"; exit;
}
$selected = $doc['reviewers'] ? json_decode($doc['reviewers'],true) : [];
echo '<h2>กำหนด Reviewer สำหรับเอกสาร</h2>';
echo '<form method="post">';
echo '<div style="display:flex;gap:16px;">';
echo '<div><div>ผู้ใช้ทั้งหมด</div><select id="allUsers" size="10" style="min-width:240px;">';
foreach($users as $u) {
  if (in_array($u['id'], $selected)) continue;
  echo "<option value='{$u['id']}'>{$u['username']} ({$u['email']})</option>";
}
echo '</select></div>';
echo '<div style="align-self:center;display:flex;flex-direction:column;gap:8px;">';
echo '<button type="button" onclick="addReviewer()">&gt;&gt;</button>';
echo '<button type="button" onclick="removeReviewer()">&lt;&lt;</button>';
echo '</div>';
echo '<div><div>ลำดับ Reviewer (ลากจัดเรียงได้)</div><ul id="reviewerList" style="list-style:none;padding:0;margin:0;min-width:280px;border:1px solid #ccc;min-height:200px;">';
foreach($selected as $idSel) {
  $u = array_values(array_filter($users, fn($x)=>$x['id']==$idSel))[0] ?? null;
  if ($u) echo "<li draggable='true' data-id='{$u['id']}' style='padding:6px 8px;border-bottom:1px solid #eee;cursor:grab;'>{$u['username']} ({$u['email']})</li>";
}
echo '</ul></div>';
echo '</div>';
echo '<input type="hidden" name="reviewers[]" id="reviewersHidden" />';
echo '<div style="margin-top:12px;"><button type="submit" onclick="beforeSubmit()">บันทึก</button></div>';
echo '</form>';
?>
<script>
function addReviewer(){
  const all = document.getElementById('allUsers');
  const list = document.getElementById('reviewerList');
  Array.from(all.selectedOptions).forEach(opt=>{
    const li=document.createElement('li');li.draggable=true;li.dataset.id=opt.value;li.textContent=opt.textContent;li.style='padding:6px 8px;border-bottom:1px solid #eee;cursor:grab;';
    list.appendChild(li); opt.remove();
  });
}
function removeReviewer(){
  const list = document.getElementById('reviewerList');
  const all = document.getElementById('allUsers');
  Array.from(list.querySelectorAll('li.selected')).forEach(li=>{
    const opt=document.createElement('option'); opt.value=li.dataset.id; opt.textContent=li.textContent; all.appendChild(opt); li.remove();
  });
}
function beforeSubmit(){
  const ids=Array.from(document.querySelectorAll('#reviewerList li')).map(li=>li.dataset.id);
  // clear existing inputs then append
  document.querySelectorAll('input[name="reviewers[]"]').forEach(e=>e.remove());
  const form=document.forms[0];
  ids.forEach(id=>{ const input=document.createElement('input'); input.type='hidden'; input.name='reviewers[]'; input.value=id; form.appendChild(input); });
}
// basic drag-drop
const list=document.getElementById('reviewerList');
let dragEl=null;
list.addEventListener('dragstart',e=>{ if(e.target.tagName==='LI'){dragEl=e.target; e.dataTransfer.effectAllowed='move';}});
list.addEventListener('dragover',e=>{ e.preventDefault(); const after=Array.from(list.children).find(li=>e.clientY<li.getBoundingClientRect().top+li.offsetHeight/2); list.insertBefore(dragEl, after||null);});
list.addEventListener('click',e=>{ if(e.target.tagName==='LI'){ e.target.classList.toggle('selected'); }});
</script>
