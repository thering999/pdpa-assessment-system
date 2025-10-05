<form method="post" action="?a=admin_import_submit" enctype="multipart/form-data" class="card" style="max-width:520px;">
  <h2>นำเข้าหมวด/คำถามจาก CSV</h2>
  <p class="muted small">คอลัมน์ที่รองรับ: category_code, category_name, category_weight, category_description, question_code, question_text, question_weight</p>
  <label>ไฟล์ CSV
    <input type="file" name="csv" accept=".csv" required />
  </label>
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">อัปโหลดและนำเข้า</button>
    <a class="btn" href="?a=admin">ยกเลิก</a>
  </div>
</form>