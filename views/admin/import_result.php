<section class="card" style="max-width:520px;">
  <h2>ผลการนำเข้า</h2>
  <ul>
    <li>สร้างหมวดใหม่: <?= (int)$stats['categories_created'] ?></li>
    <li>อัปเดตหมวด: <?= (int)$stats['categories_updated'] ?></li>
    <li>สร้างคำถามใหม่: <?= (int)$stats['questions_created'] ?></li>
    <li>อัปเดตคำถาม: <?= (int)$stats['questions_updated'] ?></li>
  </ul>
  <div class="actions">
    <a class="btn" href="?a=admin">กลับหน้าผู้ดูแล</a>
  </div>
</section>