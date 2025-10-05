<section class="card result-excel">
  <h2>สรุปผลการประเมิน (Self Assessment for CII)</h2>
  <div class="objectives card">
    <b>วัตถุประสงค์ของการทำแบบประเมิน</b>
    <ol>
      <li>เป็นการตรวจสอบว่าเราได้ปฏิบัติตามที่กฏหมายกำหนดไว้ หรือไม่</li>
      <li>เพื่อดูว่าตัวเราเองมีความสอดคล้องกับ พรบ ไซเบอร์ หรือไม่</li>
      <li>เพื่อดูว่าเราเอง มีเอกสารอะไรที่ยังขาดอยู่บ้างในตอนนี้</li>
      <li>สามารถนำไปเป็นส่วนหนึ่งของการตรวจสอบได้ เช่น Audit Compliance CheckList</li>
    </ol>
  </div>
  <div class="howto card">
    <b>วิธีการทำแบบประเมินสถานภาพการดำเนินงาน</b>
    <ul>
      <li>หากมีเอกสารหรือหลักฐานดังกล่าว ให้ใส่เลข <b>3</b></li>
      <li>หากมีเอกสารหรือหลักฐานเป็นบางส่วน ให้ใส่เลข <b>2</b></li>
      <li>หากไม่มีเอกสารหรือหลักฐานดังกล่าว ให้ใส่เลข <b>1</b></li>
    </ul>
  </div>
  <div class="legend-bar card">
    <b>ผลการประเมินสถานภาพการดำเนินงาน</b>
    <div class="legend-row">
      <span class="legend-box green"></span> <span>แถบสีเขียว: สอดคล้องกับ พรบ ไซเบอร์ (2.6 - 3.0)</span>
      <span class="legend-box yellow"></span> <span>แถบสีเหลือง: อยู่ระหว่างการทำให้สอดคล้อง (2.1 - 2.5)</span>
      <span class="legend-box red"></span> <span>แถบสีแดง: ยังไม่สอดคล้อง (0 - 2.0)</span>
    </div>
  </div>
  <?php if (empty($cats)): ?>
    <p>ไม่พบข้อมูล</p>
  <?php else: ?>
    <div class="cat-summary-grid">
      <?php foreach ($cats as $c): ?>
        <div class="cat-card card cat-<?= $c['color'] ?>">
          <div class="cat-title"> <?= htmlspecialchars($c['category_name']) ?> </div>
          <div class="cat-avg">เฉลี่ย: <b><?= $c['avg'] ?? '-' ?></b></div>
          <div class="cat-color-label cat-<?= $c['color'] ?>"><?= $c['color'] ?></div>
          <div class="cat-docs">
            <b>เอกสารแนบ:</b>
            <?php if (empty($c['docs'])): ?>
              <span class="muted">ไม่มีเอกสาร</span>
            <?php else: ?>
              <ul>
                <?php foreach ($c['docs'] as $d): ?>
                  <li>
                    <a class="btn" href="/uploads<?= '/' . htmlspecialchars($d['stored_name']) ?>" target="_blank"><?= htmlspecialchars($d['original_name']) ?></a>
                    <span class="muted">(<?= htmlspecialchars($d['status']) ?>)</span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div class="actions" style="margin-top:12px; display: flex; gap: 10px;">
    <a class="btn" href="?a=dashboard">ย้อนกลับ</a>
    <a class="btn primary" href="?a=export_summary_excel" target="_blank">Export Excel</a>
  </div>
</section>