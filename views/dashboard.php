</style>

<section class="card result-excel">
  <h2>แดชบอร์ดผลการประเมินตามหัวข้อ (Self Assessment for CII)</h2>
  
  <?php if (isset($error)): ?>
    <div class="card" style="background:#fff3cd;border:1px solid #ffeaa7;color:#856404;padding:16px;margin:16px 0;">
      <p><?= htmlspecialchars($error) ?></p>
      <a class="btn" href="?a=start">เริ่มทำแบบประเมิน</a>
    </div>
  <?php else: ?>
  
  <div class="objectives card">
    <b style="color:#111">วัตถุประสงค์ของการทำแบบประเมิน</b>
    <ol>
      <li style="color:#111">เป็นการตรวจสอบว่าเราได้ปฏิบัติตามที่กฏหมายกำหนดไว้ หรือไม่</li>
      <li style="color:#111">เพื่อดูว่าตัวเราเองมีความสอดคล้องกับ พรบ ไซเบอร์ หรือไม่</li>
      <li style="color:#111">เพื่อดูว่าเราเอง มีเอกสารอะไรที่ยังขาดอยู่บ้างในตอนนี้</li>
      <li style="color:#111">สามารถนำไปเป็นส่วนหนึ่งของการตรวจสอบได้ เช่น Audit Compliance CheckList</li>
    </ol>
  </div>
  <div class="howto card">
    <b style="color:#111">วิธีการทำแบบประเมินสถานภาพการดำเนินงาน</b>
    <ul>
      <li style="color:#111">หากมีเอกสารหรือหลักฐานดังกล่าว ให้ใส่เลข <b style="color:#111">3</b></li>
      <li style="color:#111">หากมีเอกสารหรือหลักฐานเป็นบางส่วน ให้ใส่เลข <b style="color:#111">2</b></li>
      <li style="color:#111">หากไม่มีเอกสารหรือหลักฐานดังกล่าว ให้ใส่เลข <b style="color:#111">1</b></li>
    </ul>
  </div>
  <div class="legend-bar card">
    <b style="color:#111">ผลการประเมินสถานภาพการดำเนินงาน</b>
    <div class="legend-row">
      <span class="legend-box green"></span> <span style="color:#111">แถบสีเขียว: สอดคล้องกับ พรบ ไซเบอร์ (2.6 - 3.0)</span>
      <span class="legend-box yellow"></span> <span style="color:#111">แถบสีเหลือง: อยู่ระหว่างการทำให้สอดคล้อง (2.1 - 2.5)</span>
      <span class="legend-box red"></span> <span style="color:#111">แถบสีแดง: ยังไม่สอดคล้อง (0 - 2.0)</span>
    </div>
  </div>

  <div class="card" style="margin-bottom:24px;">
    <canvas id="catChart" height="120"></canvas>
  </div>

  <script src="assets/chartjs-loader.js"></script>
  <script>
    const catLabels = <?php echo json_encode(array_map(fn($c) => $c['category_name'], $cats)); ?>;
    const catData = <?php echo json_encode(array_map(fn($c) => floatval($c['avg']), $cats)); ?>;
    const catColors = <?php echo json_encode(array_map(fn($c) => $c['color']==='green'?'#2ecc40':($c['color']==='yellow'?'#ffdc00':'#ff4136'), $cats)); ?>;
    function renderChart() {
      if (!window.Chart) return;
      const ctx = document.getElementById('catChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: catLabels,
          datasets: [{
            label: 'คะแนนเฉลี่ย',
            data: catData,
            backgroundColor: catColors,
            borderRadius: 8,
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            y: { min: 0, max: 3, ticks: { stepSize: 1 } }
          }
        }
      });
    }
    if (window.Chart) renderChart();
    else window.addEventListener('chartjs:ready', renderChart);
  </script>

  <?php if (empty($cats)): ?>
    <p>ยังไม่มีข้อมูล</p>
  <?php else: ?>
    <div class="cat-summary-grid">
      <?php foreach ($cats as $c): ?>
        <div class="cat-card card cat-<?= $c['color'] ?>">
          <div class="cat-title" style="color:#111;"> <?= htmlspecialchars($c['category_name']) ?> </div>
          <div class="cat-avg" style="color:#111;">เฉลี่ย: <b style="color:#111;"><?= $c['avg'] ?? '-' ?></b></div>
          <div class="cat-color-label cat-<?= $c['color'] ?>" style="color:#111;"><?= $c['color'] ?></div>
          <a class="btn" href="?a=upload_doc&cid=<?= (int)$c['category_id'] ?>">แนบเอกสาร</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div class="actions" style="margin-top:12px;">
    <a class="btn" href="?a=result">ย้อนกลับ</a>
  </div>
  <?php endif; ?>
</section>