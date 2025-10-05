<?php
// Export/Import handler (ZIP/CSV/Excel)
// --- Export ---
if (isset($_GET['action']) && $_GET['action']==='export') {
    // ตัวอย่าง: export assessments, users, documents เป็น ZIP
    $zip = new ZipArchive();
    $tmp = tempnam(sys_get_temp_dir(), 'pdpa_export_');
    $zip->open($tmp, ZipArchive::CREATE);
    $pdo = require 'db.php';
    foreach(['assessments','users','documents','categories','questions'] as $table) {
        $rows = $pdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
        $csv = fopen('php://temp','r+');
        if ($rows) {
            fputcsv($csv, array_keys($rows[0]));
            foreach($rows as $r) fputcsv($csv, $r);
        }
        rewind($csv);
        $zip->addFromString("$table.csv", stream_get_contents($csv));
        fclose($csv);
    }
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="pdpa_export_'.date('Ymd_His').'.zip"');
    readfile($tmp); unlink($tmp); exit;
}
// --- Import ---
if (isset($_GET['action']) && $_GET['action']==='import' && $_SERVER['REQUEST_METHOD']==='POST') {
    // รับไฟล์ ZIP แล้ว import (ตัวอย่างโครงสร้าง)
    if (!isset($_FILES['zipfile'])) die('No file');
    $zip = new ZipArchive();
    $zip->open($_FILES['zipfile']['tmp_name']);
    $pdo = require 'db.php';
    foreach(['assessments','users','documents','categories','questions'] as $table) {
        $csv = $zip->getFromName("$table.csv");
        if ($csv) {
            $lines = explode("\n", $csv);
            $cols = str_getcsv(array_shift($lines));
            foreach($lines as $line) {
                if (trim($line)==='') continue;
                $vals = str_getcsv($line);
                $place = implode(',', array_fill(0, count($cols), '?'));
                $pdo->prepare("REPLACE INTO $table (".implode(',',$cols).") VALUES ($place)")->execute($vals);
            }
        }
    }
    echo 'Import success'; exit;
}
// --- UI ---
?><h2>Export/Import ข้อมูลระบบ</h2>
<a href="?action=export"><button>Export ZIP</button></a>
<form method="post" enctype="multipart/form-data" action="?action=import">
  <input type="file" name="zipfile" required>
  <button type="submit">Import ZIP</button>
</form>
