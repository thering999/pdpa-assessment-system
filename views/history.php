<?php include __DIR__.'/header.php'; ?>
<section class="card" style="max-width:900px;margin:32px auto;">
  <h2>ประวัติการประเมินย้อนหลัง</h2>
  <?php if (empty($_SESSION['user']) && empty($_SESSION['is_admin'])): ?>
    <div style="padding:32px;text-align:center;color:#eab308;font-size:1.2em;">
      โปรดเข้าสู่ระบบหรือลงทะเบียนถ้ายังไม่มี user<br>
      <a class="btn primary" href="?a=login">เข้าสู่ระบบ</a>
      <a class="btn" href="?a=register">ลงทะเบียน</a>
    </div>
  <?php elseif (empty($rows)): ?>
    <div style="padding:32px;text-align:center;color:#888;">ไม่พบประวัติการประเมิน</div>
  <?php else: ?>
  <table style="width:100%;margin-top:16px;border-collapse:collapse;">
    <thead>
      <tr>
        <th style="padding:8px;border-bottom:1px solid #24314f;">วันที่</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">ชื่อผู้ประเมิน</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">หน่วยงาน</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">คะแนนรวม</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">ระดับ</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">ตรวจโดย</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">ผลการตรวจ</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">เปรียบเทียบ</th>
        <th style="padding:8px;border-bottom:1px solid #24314f;">Export</th>
        <?php if (!empty($isAdmin)): ?>
          <th style="padding:8px;border-bottom:1px solid #24314f;">ลบ</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $h): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['started_at']) ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['assessor_name']) ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['organization_name'] ?? '') ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?= htmlspecialchars($h['score'] ?? '') ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?php 
          $risk_level = $h['risk_level'] ?? '';
          $bg_color = '';
          $text_color = '';
          // Set colors based on risk level
          switch(strtolower($risk_level)) {
            case 'แดง':
            case 'red':
              $bg_color = '#ff4136';
              $text_color = 'white';
              break;
            case 'เหลือง':
            case 'yellow':
              $bg_color = '#ffdc00';
              $text_color = '#333';
              break;
            case 'เขียว':
            case 'green':
              $bg_color = '#2ecc40';
              $text_color = 'white';
              break;
            default:
              $bg_color = '#f8f9fa';
              $text_color = '#333';
          }
          ?>
          
          <?php if ($risk_level): ?>
          <span style="background:<?= $bg_color ?>;color:<?= $text_color ?>;padding:6px 12px;border-radius:20px;font-size:0.9em;font-weight:bold;display:inline-block;">
            <?= htmlspecialchars($risk_level) ?>
          </span>
          <?php else: ?>
          <span style="color:#999;">-</span>
          <?php endif; ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?php 
          // Get reviewer information for this document
          $review_steps = get_document_review_steps($h['id']);
          if (!empty($review_steps)): 
            foreach ($review_steps as $step): 
          ?>
            <div style="margin-bottom:4px;font-size:0.9em;">
              <strong><?= htmlspecialchars($step['reviewer_name']) ?></strong><br>
              <small style="color:#666;">
                <?= htmlspecialchars($step['action']) ?> - <?= htmlspecialchars($step['created_at']) ?>
              </small>
            </div>
          <?php 
            endforeach;
          else: 
          ?>
            <small style="color:#999;">ยังไม่มีการตรวจ</small>
          <?php endif; ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <?php 
          // Show review result with color coding
          if (!empty($review_steps)): 
            $latest_review = end($review_steps);
            $action = $latest_review['action'];
            $bg_color = '';
            $text_color = '';
            $result_text = '';
            
            switch($action) {
              case 'approved':
                $bg_color = '#d4edda';
                $text_color = '#155724';
                $result_text = 'อนุมัติ';
                break;
              case 'rejected':
                $bg_color = '#f8d7da';
                $text_color = '#721c24';
                $result_text = 'ปฏิเสธ';
                break;
              case 'under_review':
                $bg_color = '#fff3cd';
                $text_color = '#856404';
                $result_text = 'กำลังตรวจ';
                break;
              case 'pending':
                $bg_color = '#e2e3e5';
                $text_color = '#6c757d';
                $result_text = 'รอการตรวจ';
                break;
              default:
                $bg_color = '#f8f9fa';
                $text_color = '#6c757d';
                $result_text = htmlspecialchars($action);
            }
          ?>
            <span style="background:<?= $bg_color ?>;color:<?= $text_color ?>;padding:6px 12px;border-radius:20px;font-size:0.9em;font-weight:bold;display:inline-block;">
              <?= $result_text ?>
            </span>
          <?php else: ?>
            <span style="background:#f8f9fa;color:#6c757d;padding:6px 12px;border-radius:20px;font-size:0.9em;display:inline-block;">
              ยังไม่ตรวจ
            </span>
          <?php endif; ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <a class="btn" href="?a=compare_assessment&id=<?= (int)$h['id'] ?>">เปรียบเทียบ</a>
          <a class="btn" href="?a=assessment_detail&id=<?= (int)$h['id'] ?>">ดูรายละเอียด</a>
        </td>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <a class="btn" href="?a=export_excel&id=<?= (int)$h['id'] ?>" target="_blank">Excel</a>
          <a class="btn" href="?a=export_pdf&id=<?= (int)$h['id'] ?>" target="_blank">PDF</a>
        </td>
        <?php if (!empty($isAdmin)): ?>
        <td style="padding:8px;border-bottom:1px solid #24314f;">
          <form method="post" action="?a=admin_assessment_delete" onsubmit="return confirm('ยืนยันลบประวัติการประเมินนี้ รวมถึงไฟล์และคำตอบที่เกี่ยวข้อง?');">
            <input type="hidden" name="id" value="<?= (int)$h['id'] ?>">
            <input type="hidden" name="form_token" value="<?= htmlspecialchars(form_token_issue()) ?>">
            <button class="btn" style="background:#e53935;color:#fff;">ลบ</button>
          </form>
        </td>
        <?php endif; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</section>
<?php include __DIR__.'/footer.php'; ?>
