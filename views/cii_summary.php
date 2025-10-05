<?php
// $summary = ['D1'=>['avg_now'=>..,'avg_last'=>..], ...]
$labels = ['D1'=>'หมวด D1','D2'=>'หมวด D2','D3'=>'หมวด D3'];
?>
<div class="container">
  <h2>สรุปผลรวมทุกหมวด (CII Executive Summary)</h2>
  <div style="max-width:600px;margin:24px auto;">
    <canvas id="ciiChart"></canvas>
  </div>
  <table class="table" style="max-width:600px;margin:auto;">
    <thead>
      <tr>
        <th>หมวด</th>
        <th>คะแนนเฉลี่ยรอบล่าสุด</th>
        <th>คะแนนเฉลี่ยรอบก่อน</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($summary as $sec=>$row): ?>
      <tr>
        <td><?= $labels[$sec] ?></td>
        <td><?= number_format($row['avg_now'],1) ?></td>
        <td><?= number_format($row['avg_last'],1) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div style="margin:24px auto;text-align:center;">
    <a class="btn" href="?a=cii_summary_export&type=excel">Export Excel</a>
    <a class="btn" href="?a=cii_summary_export&type=csv">Export CSV</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ciiChart').getContext('2d');
const chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php foreach($summary as $sec=>$row) echo "'{$labels[$sec]}',"; ?>],
    datasets: [
      {
        label: 'รอบล่าสุด',
        data: [<?php foreach($summary as $row) echo ($row['avg_now']?:0).','; ?>],
        backgroundColor: '#4caf50',
      },
      {
        label: 'รอบก่อน',
        data: [<?php foreach($summary as $row) echo ($row['avg_last']?:0).','; ?>],
        backgroundColor: '#ffc107',
      }
    ]
  },
  options: {
    scales: { y: { min: 0, max: 3, ticks: { stepSize: 1 } } },
    plugins: { legend: { position: 'bottom' } }
  }
});
</script>
