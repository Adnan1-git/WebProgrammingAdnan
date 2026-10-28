<?php
require_once __DIR__ . '/CategoryDao.php';
require_once __DIR__ . '/EventDao.php';
require_once __DIR__ . '/UserDao.php';

// READ
echo "Events: " . count((new BrandDao())->get_all()) . "\n";
print_r((new VehicleDao())->get_by_id(9));
print_r((new UserDao())->get_by_email('test@plaza.ba'));

// CREATE – "hacker" is NOT a column of categories, so only_columns() drops it
$dao = new CategoryDao();
$category = $dao->add(['name' => 'Test category', 'description' => 'Created by test.php', 'hacker' => 'DROP TABLE']);
print_r($category);

// UPDATE
$dao->update(['description' => 'Updated description'], $category['id']);
print_r($dao->get_by_id($category['id']));

// DELETE – afterwards get_by_id() must return NULL
$dao->delete($category['id']);
var_dump($dao->get_by_id($category['id']));
