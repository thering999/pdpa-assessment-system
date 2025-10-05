<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>รายงาน PDPA - <?= htmlspecialchars($assessment['organization_name'] ?? '') ?></title>
  <style>
    body{font-family: system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#111;padding:24px}
    h1,h2{margin:0 0 8px 0}
    .meta{margin-bottom:16px;color:#333}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{border:1px solid #ddd;padding:8px;text-align:left}
    th{background:#f5f5f5}
    .yes{color:#0a7d37;font-weight:600}
    .no{color:#b30000;font-weight:600}
    .toolbar{margin-bottom:12px}
    @media print{.toolbar{display:none}}
  </style>
</head>
<body>
  <div class="toolbar">
    <button onclick="window.print()">พิมพ์ / บันทึกเป็น PDF</button>
    <a href="?a=result">กลับหน้าผลลัพธ์</a>
  </div>
  <h1>รายงานผลการประเมิน PDPA</h1>
  <div class="meta">
    <div>องค์กร: <strong><?= htmlspecialchars($assessment['organization_name'] ?? '-') ?></strong></div>
    <div>อีเมลติดต่อ: <strong><?= htmlspecialchars($assessment['contact_email'] ?? '-') ?></strong></div>
    <div>วันที่: <strong><?= htmlspecialchars($assessment['completed_at'] ?? $assessment['started_at'] ?? '-') ?></strong></div>
  </div>
  <h2>รายละเอียดคำตอบ</h2>
  <table>
    <thead>
      <tr>
        <th>รหัส</th>
        <th>คำถาม</th>
        <th>หมวด</th>
        <th>น้ำหนัก</th>
        <th>คำตอบ</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($answers as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['code']) ?></td>
          <td><?= htmlspecialchars($row['text']) ?></td>
          <td><?= htmlspecialchars($row['category'] ?? '') ?></td>
          <td><?= (int)$row['weight'] ?></td>
          <td class="<?= ((int)($row['answer_value'] ?? 0)) === 1 ? 'yes' : 'no' ?>"><?= ((int)($row['answer_value'] ?? 0)) === 1 ? 'Yes' : 'No' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
