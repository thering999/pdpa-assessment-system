<?php
require_once __DIR__ . '/db.php';

echo "Starting database setup...\n";

// สร้างตารางและเพิ่มข้อมูล seed
ensure_tables();
echo "Tables created/updated.\n";

ensure_cii_tables();
echo "CII tables created/updated.\n";

seed_questions();
echo "Questions seeded.\n";

seed_categories_from_questions();
echo "Categories seeded.\n";

cii_seed_items_D1();
echo "CII D1 items seeded.\n";

cii_seed_items_D2();
echo "CII D2 items seeded.\n";

cii_seed_items_D3();
echo "CII D3 items seeded.\n";

echo "Database setup completed successfully!\n";