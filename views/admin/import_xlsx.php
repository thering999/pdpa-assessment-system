<style>
body {
  background: #10172a;
  color: #fff;
  min-height: 100vh;
  margin: 0;
  font-family: 'Noto Sans Thai', 'Noto Sans', Arial, sans-serif;
}
</style>
<?php include __DIR__.'/sidebar.php'; ?>
<div class="card" style="max-width:520px;margin:32px auto;background:#1a2140;padding:32px 28px 24px 28px;border-radius:16px;box-shadow:0 2px 16px #0003;">
  <h2 style="color:#90caf9;margin-bottom:18px;">นำเข้าหมวด/คำถามจาก XLSX</h2>
  <p class="muted small" style="color:#b0bec5;margin-bottom:18px;">แถวแรกเป็นหัวคอลัมน์: <span style="color:#fff;">category_code, category_name, category_weight, category_description, question_code, question_text, question_weight</span></p>
  <form method="post" action="?a=admin_import_xlsx_submit" enctype="multipart/form-data">
    <label style="color:#fff;font-weight:bold;display:block;margin-bottom:12px;">ไฟล์ XLSX
      <input type="file" name="xlsx" accept=".xlsx" required style="margin-top:8px;background:#222;color:#fff;padding:8px;border-radius:8px;" />
    </label>
    <div class="actions" style="margin-top:18px;display:flex;gap:12px;">
      <button class="btn primary" type="submit" style="background:#1976d2;color:#fff;padding:8px 24px;border-radius:8px;font-weight:bold;">อัปโหลดและนำเข้า</button>
      <a class="btn" href="?a=admin" style="background:#374151;color:#fff;padding:8px 24px;border-radius:8px;">ยกเลิก</a>
    </div>
  </form>
</div>