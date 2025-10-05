<section class="card">
  <h2>แบบประเมินความพร้อม PDPA</h2>
  <p>ตอบคำถามสั้น ๆ เพื่อประเมินระดับความเสี่ยงและความพร้อมด้านการคุ้มครองข้อมูลส่วนบุคคลขององค์กรของคุณ</p>
  <?php if (!empty($_SESSION['user'])): ?>
    <!-- ปุ่มสำหรับผู้ใช้ที่ล็อกอินแล้ว -->
  <?php else: ?>
    <a class="btn primary" href="?a=login">เข้าสู่ระบบเพื่อเริ่มประเมิน</a>
    <a class="btn" href="?a=register">สมัครสมาชิก</a>
  <?php endif; ?>
</section>
<div class="home-hero" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:60vh;background:linear-gradient(120deg,#e0e7ff 0%,#f0fdfa 100%);padding:48px 0;">
  <img src="/assets/pdpa_logo.svg" alt="PDPA Logo" style="width:96px;height:96px;margin-bottom:16px;filter:drop-shadow(0 2px 8px #b3b3b3);">
  <h1 style="font-size:2.5rem;font-weight:700;margin-bottom:12px;color:#222;">PDPA Assessment System</h1>
  <p style="font-size:1.2rem;color:#444;max-width:480px;text-align:center;margin-bottom:32px;">
    ระบบประเมินความพร้อม PDPA สำหรับองค์กร <br>ใช้งานง่าย ครบทุกฟีเจอร์ <span style="color:#2563eb;font-weight:500;">(PHP + MySQL + Docker)</span>
  </p>
  <div style="display:flex;gap:16px;flex-wrap:wrap;justify-content:center;">
    <?php if (!empty($_SESSION['user'])): ?>
      <a class="btn primary" href="?a=start" style="font-size:1.1rem;padding:12px 32px;">เริ่มทำแบบประเมิน</a>
      <a class="btn" href="?a=dashboard" style="font-size:1.1rem;padding:12px 32px;">แดชบอร์ด</a>
      <a class="btn" href="?a=logout" style="font-size:1.1rem;padding:12px 32px;">ออกจากระบบ</a>
    <?php else: ?>
      <a class="btn primary" href="?a=login" style="font-size:1.1rem;padding:12px 32px;">เข้าสู่ระบบ</a>
      <a class="btn" href="?a=register" style="font-size:1.1rem;padding:12px 32px;">สมัครสมาชิก</a>
    <?php endif; ?>
    <a class="btn" href="?a=history" style="font-size:1.1rem;padding:12px 32px;">ดูประวัติการประเมิน</a>
  </div>
  <div style="margin-top:40px;max-width:600px;text-align:center;color:#666;font-size:1rem;">
    <ul style="list-style:none;padding:0;margin:0;">
      <li>• สรุปผลการประเมินและ export Excel</li>
      <li>• Dashboard กราฟสรุปคะแนน</li>
      <li>• ระบบสิทธิ์ผู้ใช้หลายระดับ (admin, reviewer, evaluator)</li>
      <li>• Import/Export, แจ้งเตือน, แนบไฟล์, API, FAQ, Webhook</li>
      <li>• รองรับมือถือและ multi-language</li>
    </ul>
  </div>
</div>
<style>
.btn.primary { background:#2563eb;color:#fff;border:none;border-radius:6px;box-shadow:0 2px 8px #b3b3b3;transition:background 0.2s; }
.btn.primary:hover { background:#1d4ed8; }
.btn { display:inline-block; background:#fff; color:#2563eb; border:1px solid #2563eb; border-radius:6px; padding:10px 24px; text-decoration:none; font-weight:500; transition:background 0.2s,color 0.2s; }
.btn:hover { background:#f0fdfa; color:#1d4ed8; }
@media (max-width:600px) {
  .home-hero h1 { font-size:1.5rem; }
  .home-hero p { font-size:1rem; }
  .btn, .btn.primary { font-size:1rem; padding:10px 16px; }
}
</style>
</section>
