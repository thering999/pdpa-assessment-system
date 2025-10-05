<?php
require_once __DIR__ . '/db.php';

echo "=== Settings table structure ===\n";
$cols = db()->query('DESCRIBE settings')->fetchAll();
foreach($cols as $c) {
    echo $c['Field'] . ' => ' . $c['Type'] . "\n";
}

echo "\n=== Test settings functions ===\n";
try {
    // Test settings_get
    $result = settings_get('test_key', 'default_value');
    echo "settings_get() works: $result\n";
    
    // Test settings_set
    settings_set('test_key', 'test_value');
    echo "settings_set() works\n";
    
    // Test admin password retrieval
    $admin_pass = settings_get('admin_password_hash', null);
    echo "Admin password exists: " . ($admin_pass ? 'YES' : 'NO') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";