
<?php include __DIR__.'/header.php'; ?>
</style>

<section class="card result-excel">
  <?php 
  $user_role = $user_role ?? 'evaluator';
  if ($user_role === 'reviewer'): 
  ?>
  <h2>แดชบอร์ด Reviewer - ระบบตรวจสอบเอกสาร</h2>
  <?php elseif ($user_role === 'admin'): ?>
  <h2>แดชบอร์ด Admin - ระบบจัดการ PDPA</h2>
  <?php else: ?>
  <h2>แดชบอร์ดผลการประเมินตามหัวข้อ (Self Assessment for CII)</h2>
  <?php endif; ?>
  
  <?php if (isset($error)): ?>
    <div class="card" style="background:#fff3cd;border:1px solid #ffeaa7;color:#856404;padding:16px;margin:16px 0;">
      <p><?= htmlspecialchars($error) ?></p>
      <a class="btn" href="?a=start">เริ่มทำแบบประเมิน</a>
    </div>
  <?php elseif ($user_role === 'reviewer'): ?>
  
  <!-- Reviewer Dashboard -->
  <div class="card" style="margin-bottom:24px;">
    <h3>สถิติงานตรวจสอบ</h3>
    <?php 
    $user_id = $_SESSION['user']['id'] ?? 0;
    $pdo = db();
    
    // นับเอกสารทั้งหมดที่ reviewer คนนี้ถูก assign
    $total_assigned = $pdo->prepare("
      SELECT COUNT(*) as count FROM documents d 
      WHERE (d.reviewers IS NOT NULL AND d.reviewers != '' AND d.reviewers LIKE ?)
    ");
    $needle = '%"'.((string)$user_id).'"%';
    $total_assigned->execute([$needle]);
    $assigned_count = $total_assigned->fetch()['count'];
    
    // นับเอกสารที่ reviewer คนนี้ตรวจเสร็จแล้ว (ผ่าน document_review_steps)
    $total_reviewed = $pdo->prepare("
      SELECT COUNT(DISTINCT document_id) as count FROM document_review_steps 
      WHERE reviewer_id = ?
    ");
    $total_reviewed->execute([$user_id]);
    $reviewed_count = $total_reviewed->fetch()['count'];
    
    // นับเอกสารที่ยังรอตรวจ และถึงลำดับ reviewer คนนี้แล้ว
    $pending_my_turn = $pdo->prepare("
      SELECT COUNT(*) as count FROM documents d 
      WHERE d.status = 'PENDING' 
      AND (d.reviewers IS NOT NULL AND d.reviewers != '' AND d.reviewers LIKE ?)
      AND JSON_EXTRACT(d.reviewers, CONCAT('$[', IFNULL(d.current_reviewer_idx, 0), ']')) = ?
    ");
    $pending_my_turn->execute([$needle, $user_id]);
    $pending_count = $pending_my_turn->fetch()['count'];
    ?>
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;margin-top:16px;">
      <div style="background:#e3f2fd;padding:20px;border-radius:8px;text-align:center;">
        <h3 style="margin:0;color:#1976d2;"><?= $assigned_count ?></h3>
        <p style="margin:8px 0 0 0;color:#666;">งานที่ได้รับมอบหมาย</p>
      </div>
      <div style="background:#f1f8e9;padding:20px;border-radius:8px;text-align:center;">
        <h3 style="margin:0;color:#388e3c;"><?= $reviewed_count ?></h3>
        <p style="margin:8px 0 0 0;color:#666;">งานที่ตรวจเสร็จแล้ว</p>
      </div>
      <div style="background:#fff3cd;padding:20px;border-radius:8px;text-align:center;">
        <h3 style="margin:0;color:#856404;"><?= $pending_count ?></h3>
        <p style="margin:8px 0 0 0;color:#666;">งานที่ถึงลำดับฉัน</p>
      </div>
    </div>
  </div>
  
  <div class="card" style="margin-bottom:24px;">
    <h3>เครื่องมือ Reviewer</h3>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a class="btn" href="?a=reviewer_documents">คิวงานตรวจสอบ</a>
      <a class="btn" href="?a=history">ประวัติการตรวจ</a>
      <a class="btn" href="?a=notifications">การแจ้งเตือน</a>
    </div>
  </div>
  
  <?php elseif ($user_role === 'admin'): ?>
  
  <!-- Admin Dashboard -->
  <div class="card" style="margin-bottom:24px;">
    <h3>สถิติระบบ</h3>
    <?php 
    $pdo = db();
    
    // Get system statistics
    $total_users = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];
    $total_assessments = $pdo->query("SELECT COUNT(*) as count FROM assessments")->fetch()['count'];
    $pending_reviews = $pdo->query("SELECT COUNT(*) as count FROM documents WHERE status = 'PENDING'")->fetch()['count'];
    ?>
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-top:16px;">
      <div style="background:#f8f9fa;padding:20px;border-radius:8px;text-align:center;">
        <h3 style="margin:0;color:#007bff;"><?= $total_users ?></h3>
        <p style="margin:8px 0 0 0;color:#666;">ผู้ใช้ทั้งหมด</p>
      </div>
      <div style="background:#d4edda;padding:20px;border-radius:8px;text-align:center;">
        <h3 style="margin:0;color:#155724;"><?= $total_assessments ?></h3>
        <p style="margin:8px 0 0 0;color:#666;">เอกสารทั้งหมด</p>
      </div>
      <div style="background:#fff3cd;padding:20px;border-radius:8px;text-align:center;">
        <h3 style="margin:0;color:#856404;"><?= $pending_reviews ?></h3>
        <p style="margin:8px 0 0 0;color:#666;">รอการตรวจ</p>
      </div>
    </div>
  </div>
  
  <div class="card" style="margin-bottom:24px;">
    <h3>เครื่องมือ Admin</h3>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a class="btn" href="?a=admin_users">จัดการผู้ใช้</a>
      <a class="btn" href="?a=admin_documents">จัดการเอกสาร</a>
      <a class="btn" href="?a=history">ประวัติทั้งหมด</a>
      <a class="btn" href="?a=notifications">การแจ้งเตือน</a>
    </div>
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
    
    <!-- Reviewer Activity Section -->
    <div class="card" style="margin-top:24px;">
      <h3>ประวัติการตรวจสอบล่าสุด</h3>
      <?php 
      // Get recent reviewer activity for current user's assessments
      $user_id = $_SESSION['user']['id'] ?? 0; // Fix: use correct session key
      $pdo = db(); // Initialize PDO connection
      $recent_reviews = $pdo->prepare("
        SELECT drs.*, u.username as reviewer_name, d.id as doc_id, a.assessor_name
        FROM document_review_steps drs
        JOIN users u ON drs.reviewer_id = u.id
        JOIN documents d ON drs.document_id = d.id
        JOIN assessments a ON d.assessment_id = a.id
        WHERE a.user_id = ?
        ORDER BY drs.created_at DESC
        LIMIT 5
      ");
      $recent_reviews->execute([$user_id]);
      $reviews = $recent_reviews->fetchAll();
      ?>
      
      <?php if (!empty($reviews)): ?>
      <div style="margin-top:16px;">
        <?php foreach($reviews as $review): ?>
        <div style="padding:12px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
          <div>
            <strong><?= htmlspecialchars($review['reviewer_name']) ?></strong>
            <span style="margin:0 8px;color:#666;">•</span>
            <?= htmlspecialchars($review['action']) ?>
            <div style="font-size:0.9em;color:#666;margin-top:4px;">
              <?= htmlspecialchars($review['created_at']) ?>
            </div>
          </div>
          <div style="font-size:0.9em;color:#666;">
            เอกสาร #<?= $review['doc_id'] ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p style="text-align:center;color:#666;padding:16px;">ยังไม่มีประวัติการตรวจสอบ</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <div class="actions" style="margin-top:12px;">
    <a class="btn" href="?a=result">ย้อนกลับ</a>
  </div>
  <?php endif; ?>
</section>
<?php include __DIR__.'/footer.php'; ?>