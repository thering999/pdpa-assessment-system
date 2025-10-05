<?php $token = form_token_issue(); ?>
<form method="post" action="?a=admin_save_q" class="card" style="max-width:760px;">
  <h2><?= isset($q) ? 'แก้ไขคำถาม' : 'เพิ่มคำถาม' ?></h2>
  <input type="hidden" name="id" value="<?= isset($q) ? (int)$q['id'] : 0 ?>" />
  <input type="hidden" name="form_token" value="<?= htmlspecialchars($token) ?>" />
  <label>Code
    <input type="text" name="code" value="<?= htmlspecialchars($q['code'] ?? '') ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <label>คำถาม
    <textarea name="text" rows="3" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;"><?= htmlspecialchars($q['text'] ?? '') ?></textarea>
  </label>
  <label>หมวด
    <input type="text" name="category" value="<?= htmlspecialchars($q['category'] ?? '') ?>" style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <label>น้ำหนัก
    <input type="number" name="weight" min="1" max="10" value="<?= isset($q) ? (int)$q['weight'] : 1 ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #2a3357;background:#0f1530;color:#e7eaf3;">
  </label>
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">บันทึก</button>
    <a class="btn" href="?a=admin">ยกเลิก</a>
  </div>
</form>
