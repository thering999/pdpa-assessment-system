<?php include __DIR__.'/header.php'; ?>
<?php
// Get category information
$pdo = db();
$category_stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
$category_stmt->execute([$cid]);
$category = $category_stmt->fetch();

// Get questions in this category
$questions_stmt = $pdo->prepare('SELECT id, text, code FROM questions WHERE category_id = ? ORDER BY id');
$questions_stmt->execute([$cid]);
$questions = $questions_stmt->fetchAll();

// Get current assessment
$assessment_id = $_SESSION['assessment_id'] ?? null;

// Get existing documents for this category and assessment
$existing_docs = [];
if ($assessment_id) {
    $docs_stmt = $pdo->prepare('
        SELECT d.*, q.text as question_text, q.code as question_code 
        FROM documents d 
        LEFT JOIN questions q ON d.question_id = q.id 
        WHERE d.assessment_id = ? AND d.category_id = ?
        ORDER BY q.id
    ');
    $docs_stmt->execute([$assessment_id, $cid]);
    $existing_docs = $docs_stmt->fetchAll();
}
?>

<div class="card" style="max-width:900px;margin:32px auto;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
    <h2 style="margin:0;">แนบเอกสารประกอบ: <?= htmlspecialchars($category['name'] ?? 'หมวด') ?></h2>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a class="btn" href="javascript:history.back()">← ย้อนกลับ</a>
      <a class="btn" href="?a=questions">กลับไปหน้าคำถาม</a>
    </div>
  </div>

  <?php 
    // แสดงลิงก์สลับไปยังหมวดอื่นอย่างรวดเร็ว
    try {
      $allCats = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
    } catch (Throwable $e) { $allCats = []; }
    if ($allCats): ?>
    <div style="margin-top:10px; display:flex; flex-wrap:wrap; gap:6px;">
      <span class="muted" style="align-self:center;">ไปยังหมวด:</span>
      <?php foreach($allCats as $c): $active = ((int)$c['id'] === (int)$cid); ?>
        <a class="btn" href="?a=upload_doc&cid=<?= (int)$c['id'] ?>" style="<?= $active?'background:#3b82f6;color:#fff;':'' ?>">
          <?= htmlspecialchars($c['name']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  
  <?php if (empty($questions)): ?>
    <p style="color:#666;text-align:center;padding:32px;">ไม่พบคำถามในหมวดนี้</p>
  <?php else: ?>
  
  <div style="margin-bottom:24px;padding:16px;background:#222;border-radius:8px;">
    <p style="color:#fff;"><strong>คำแนะนำ:</strong> สามารถแนบเอกสารหลักฐานได้แยกตามแต่ละข้อคำถาม เพื่อความชัดเจนในการตรวจสอบ</p>
  </div>

  <?php foreach ($questions as $index => $question): ?>
  <div class="question-upload-section" style="margin-bottom:24px;padding:20px;border:1px solid #ddd;border-radius:8px;">
    <h4 style="color:#fff;margin-bottom:12px;">
      ข้อ <?= $index + 1 ?>: <?= htmlspecialchars($question['text']) ?>
    </h4>
    
    <?php if ($question['code']): ?>
    <p style="color:#fff;font-size:0.9em;margin-bottom:16px;">รหัส: <?= htmlspecialchars($question['code']) ?></p>
    <?php endif; ?>
    
    <!-- Show existing documents for this question -->
    <?php 
    $question_docs = array_filter($existing_docs, function($doc) use ($question) {
        return $doc['question_id'] == $question['id'];
    });
    ?>
    
    <?php if (!empty($question_docs)): ?>
    <div style="margin-bottom:16px;">
      <h5 style="color:#28a745;">📎 เอกสารที่แนบแล้ว:</h5>
      <?php foreach ($question_docs as $doc): ?>
      <div style="display:flex;align-items:center;gap:12px;padding:8px;background:#d4edda;border-radius:4px;margin-bottom:8px;">
        <span>📄 <?= htmlspecialchars($doc['original_name']) ?></span>
        <small style="color:#666;">(<?= date('d/m/Y H:i', strtotime($doc['uploaded_at'])) ?>)</small>
        <a href="?a=download_doc&id=<?= $doc['id'] ?>" class="btn" style="font-size:0.8em;padding:4px 8px;">ดาวน์โหลด</a>
        <a href="?a=delete_doc&id=<?= $doc['id'] ?>&cid=<?= $cid ?>" class="btn" style="font-size:0.8em;padding:4px 8px;background:#dc3545;" onclick="return confirm('ต้องการลบเอกสารนี้?')">ลบ</a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- Upload form for this question -->
    <form method="post" action="?a=upload_doc_submit" enctype="multipart/form-data" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap;">
      <input type="hidden" name="cid" value="<?= (int)$cid ?>" />
      <input type="hidden" name="question_id" value="<?= $question['id'] ?>" />
      
      <div style="flex:1;min-width:200px;">
        <label style="display:block;margin-bottom:4px;font-weight:bold;color:#fff;">เลือกไฟล์เอกสาร:</label>
        <input type="file" name="doc" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif" style="width:100%;" />
        <small style="color:#fff;">รองรับ: PDF, Word, Excel, รูปภาพ</small>
      </div>
      
      <div style="min-width:120px;">
        <button type="submit" class="btn primary">แนบเอกสาร</button>
      </div>
    </form>
  </div>
  <?php endforeach; ?>
  
  <?php endif; ?>
  
  <div style="text-align:center;margin-top:24px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
    <a class="btn" href="?a=questions">← กลับไปหน้าคำถาม</a>
    <a class="btn" href="?a=dashboard">กลับไปยัง Dashboard</a>
  </div>
</div>
<?php include __DIR__.'/footer.php'; ?>