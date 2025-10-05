<form method="post" action="?a=upload_doc_submit" enctype="multipart/form-data" class="card" style="max-width:560px;">
  <h2>แนบเอกสารประกอบหัวข้อ</h2>
  <input type="hidden" name="cid" value="<?= (int)$cid ?>" />
  <label>ไฟล์เอกสาร (PDF, DOCX, XLSX, รูปภาพ)
    <input type="file" name="doc" required />
  </label>
  <div class="actions" style="margin-top:12px;">
    <button class="btn primary" type="submit">อัปโหลด</button>
    <a class="btn" href="?a=dashboard">ยกเลิก</a>
  </div>
</form>