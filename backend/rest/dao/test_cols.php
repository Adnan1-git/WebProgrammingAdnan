<?php
require_once __DIR__ . '/BaseDao.php';

$cols = BaseDao::connection()->query("SHOW COLUMNS FROM brands")->fetchAll();
echo "<pre>";
foreach ($cols as $c) {
    echo $c['Field'] . "  (" . $c['Type'] . ")  " . ($c['Null'] === 'NO' ? 'REQUIRED' : 'optional') . "\n";



    
}