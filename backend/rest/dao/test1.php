<?php
require_once __DIR__ . '/BrandDao.php';

$dao = new BrandDao();

$brand = $dao->add(['name' => 'Test brand']); // PROMIJENI kolone
echo "CREATE:\n"; print_r($brand);

echo "READ:\n"; print_r($dao->get_by_id($brand['id']));

$dao->update(['name' => 'Updated brand'], $brand['id']);
echo "UPDATE:\n"; print_r($dao->get_by_id($brand['id']));

$dao->delete($brand['id']);
echo "DELETE (treba biti prazno):\n"; var_dump($dao->get_by_id($brand['id']));