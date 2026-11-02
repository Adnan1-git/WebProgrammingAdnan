<?php
require_once __DIR__ . '/CategoryDao.php';

$dao = new CategoryDao();

$category = $dao->add(['name' => 'Test category', 'description' => 'Created by test2.php']);
echo "CREATE:\n"; print_r($category);

echo "READ:\n"; print_r($dao->get_by_id($category['id']));

$dao->update(['description' => 'Updated description'], $category['id']);
echo "UPDATE:\n"; print_r($dao->get_by_id($category['id']));

$dao->delete($category['id']);
echo "DELETE (treba biti prazno):\n"; var_dump($dao->get_by_id($category['id']));