<section class="card" style="max-width:1100px;margin:32px auto;">
  <h2>Dashboard ผู้บริหาร (KPI & กราฟเปรียบเทียบ)</h2>
  <div style="margin-bottom:32px;">
    <canvas id="kpiChart" width="1000" height="300"></canvas>
  </div>
  <table style="width:100%;margin-top:16px;border-collapse:collapse;">
    <thead>
      <tr>
        <th>หมวด</th>
        <th>จำนวนประเมิน</th>
        <th>คะแนนเฉลี่ย</th>
        <th>ระดับเฉลี่ย</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($kpi as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= (int)$row['count'] ?></td>
        <td><?= number_format($row['avg'],2) ?></td>
        <td><?= htmlspecialchars($row['level']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('kpiChart').getContext('2d');
const data = {
  labels: [<?php foreach ($kpi as $row) { echo "'".$row['category']."',"; } ?>],
  datasets: [{
    label: 'คะแนนเฉลี่ย',
    data: [<?php foreach ($kpi as $row) { echo (float)$row['avg'].","; } ?>],
    borderColor: '#2dd4bf',
    backgroundColor: 'rgba(45,212,191,0.2)',
    fill: true,
    tension: 0.2
  }]
};
new Chart(ctx, { type: 'bar', data });
</script>
