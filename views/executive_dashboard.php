<?php
// Executive Dashboard (KPI + filters + export)
?>
<h2>Executive Dashboard</h2>
<div class="kpi-cards" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin:12px 0;">
  <div class="card" style="padding:12px;border:1px solid #ddd;border-radius:6px;">
    <div style="font-size:13px;color:#666;">จำนวนประเมิน (ทั้งหมด)</div>
    <div style="font-size:24px;font-weight:bold;"><?= (int)$kpi['assess_all'] ?></div>
  </div>
  <div class="card" style="padding:12px;border:1px solid #ddd;border-radius:6px;">
    <div style="font-size:13px;color:#666;">คะแนนเฉลี่ย (ทั้งหมด)</div>
    <div style="font-size:24px;font-weight:bold;"><?= number_format((float)$kpi['avg_all'],2) ?></div>
  </div>
  <div class="card" style="padding:12px;border:1px solid #ddd;border-radius:6px;">
    <div style="font-size:13px;color:#666;">อัตรา PASS เอกสาร (ทั้งหมด)</div>
    <div style="font-size:24px;font-weight:bold;"><?= number_format((float)$kpi['pass_rate_all'],2) ?>%</div>
  </div>
  <div class="card" style="padding:12px;border:1px solid #ddd;border-radius:6px;">
    <div style="font-size:13px;color:#666;">จำนวนประเมิน (ช่วงที่เลือก)</div>
    <div style="font-size:24px;font-weight:bold;"><?= (int)$kpi['assess_period'] ?></div>
  </div>
  <div class="card" style="padding:12px;border:1px solid #ddd;border-radius:6px;">
    <div style="font-size:13px;color:#666;">คะแนนเฉลี่ย (ช่วงที่เลือก)</div>
    <div style="font-size:24px;font-weight:bold;"><?= number_format((float)$kpi['avg_period'],2) ?></div>
  </div>
  <div class="card" style="padding:12px;border:1px solid #ddd;border-radius:6px;">
    <div style="font-size:13px;color:#666;">อัตรา PASS เอกสาร (ช่วงที่เลือก)</div>
    <div style="font-size:24px;font-weight:bold;"><?= number_format((float)$kpi['pass_rate_period'],2) ?>%</div>
  </div>
  
</div>
<form method="get" class="dashboard-filter" style="margin:10px 0;">
  <input type="hidden" name="a" value="executive_dashboard" />
  <label>ช่วงเวลา:
    <select name="period">
      <option value="month" <?=($period==='month'?'selected':'')?>>รายเดือน</option>
      <option value="year" <?=($period==='year'?'selected':'')?>>รายปี</option>
    </select>
  </label>
  <label>เดือน:
    <input type="month" name="month" value="<?=htmlspecialchars($month)?>" />
  </label>
  <label>ปี:
    <input type="number" name="year" value="<?=htmlspecialchars((string)$year)?>" />
  </label>
  <button type="submit">แสดงผล</button>
  <a class="btn" href="?a=export_dashboard&amp;period=<?=urlencode($period)?>&amp;month=<?=urlencode($month)?>&amp;year=<?=urlencode((string)$year)?>&amp;type=excel">Export Excel</a>
  <a class="btn" href="?a=export_dashboard&amp;period=<?=urlencode($period)?>&amp;month=<?=urlencode($month)?>&amp;year=<?=urlencode((string)$year)?>&amp;type=pdf">Export PDF</a>
  <a class="btn" href="?a=export_dashboard&amp;period=<?=urlencode($period)?>&amp;month=<?=urlencode($month)?>&amp;year=<?=urlencode((string)$year)?>&amp;type=csv">Export CSV</a>
  <button type="button" class="btn" onclick="navigator.clipboard.writeText(location.href);showToast('คัดลอกลิงก์แล้ว');">คัดลอกลิงก์</button>
  </form>

<canvas id="kpiChart" height="80"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?=json_encode($labels)?>;
const scores = <?=json_encode($scores)?>;
const counts = <?=json_encode($counts)?>;
const ctx = document.getElementById('kpiChart');
new Chart(ctx, {
  data: {
    labels,
    datasets: [
      { type: 'line', label: 'คะแนนเฉลี่ย', data: scores, borderColor: '#4e79a7', backgroundColor: 'transparent', tension: 0.2 },
      { type: 'bar', label: 'จำนวนการประเมิน', data: counts, backgroundColor: '#59a14f' }
    ]
  },
  options: { responsive: true, interaction: { mode: 'index', intersect: false }, stacked: false }
});
</script>
