<?php
require_once __DIR__ . '/BrandDao.php';
require_once __DIR__ . '/VehicleDao.php';

$brandDao = new BrandDao();
$dao = new VehicleDao();

$brand = $brandDao->add(['name' => 'Test brand']);

// PROMIJENI kolone prema schema.sql
$vehicle = $dao->add([
    'brand_id' => $brand['id'],
    'model'    => 'Test model',
    'price'    => 10000
]);
echo "CREATE:\n"; print_r($vehicle);

echo "READ:\n"; print_r($dao->get_by_id($vehicle['id']));

echo "GET BY BRAND:\n"; print_r($dao->get_by_id($brand['id']));

$dao->update(['price' => 12000], $vehicle['id']);
echo "UPDATE:\n"; print_r($dao->get_by_id($vehicle['id']));

$dao->delete($vehicle['id']);
$brandDao->delete($brand['id']);
echo "DELETE (treba biti prazno):\n"; var_dump($dao->get_by_id($vehicle['id']));