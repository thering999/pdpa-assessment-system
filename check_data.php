<?php
require_once __DIR__ . '/db.php';

// ตรวจสอบข้อมูลในแต่ละตาราง
$pdo = db();

echo "=== Database Status ===\n";

$tables = ['questions', 'categories', 'users', 'cii_items', 'assessments', 'answers'];
foreach ($tables as $table) {
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "$table: $count rows\n";
    } catch (Exception $e) {
        echo "$table: ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\n=== Force seed questions if empty ===\n";
$questionCount = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
if ($questionCount == 0) {
    echo "Seeding questions...\n";
    seed_questions();
    echo "Questions seeded!\n";
} else {
    echo "Questions already exist ($questionCount rows)\n";
}

echo "\n=== Force seed categories if empty ===\n";
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
if ($categoryCount == 0) {
    echo "Seeding categories...\n";
    seed_categories_from_questions();
    echo "Categories seeded!\n";
} else {
    echo "Categories already exist ($categoryCount rows)\n";
}

echo "\nDone!\n";