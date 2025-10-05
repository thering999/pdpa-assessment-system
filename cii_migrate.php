<?php
require __DIR__.'/db.php';
ensure_cii_tables();
cii_seed_items_D2();
echo "CII OK\n";
