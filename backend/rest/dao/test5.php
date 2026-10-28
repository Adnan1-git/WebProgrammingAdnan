<?php
require_once __DIR__ . '/BrandDao.php';
require_once __DIR__ . '/VehicleDao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/WishlistDao.php';

$brandDao = new BrandDao();
$vehicleDao = new VehicleDao();
$userDao = new UserDao();
$dao = new WishlistDao();

// Priprema podataka (PROMIJENI kolone)
$brand = $brandDao->add(['name' => 'Test brand']);
$vehicle = $vehicleDao->add(['brand_id' => $brand['id'], 'model' => 'Test model', 'price' => 10000]);
$user = $userDao->add(['first_name' => 'Test', 'last_name' => 'User', 'email' => 'wish@plaza.ba', 'password' => '123456']);

$item = $dao->add(['user_id' => $user['id'], 'vehicle_id' => $vehicle['id']]);
echo "CREATE:\n"; print_r($item);

echo "GET BY USER:\n"; print_r($dao->get_by_user_id($user['id']));

echo "GET BY USER AND VEHICLE:\n"; print_r($dao->get_by_user_and_vehicle($user['id'], $vehicle['id']));

$dao->remove($user['id'], $vehicle['id']);
echo "REMOVE (treba biti prazno):\n"; var_dump($dao->get_by_user_and_vehicle($user['id'], $vehicle['id']));

// Čišćenje
$userDao->delete($user['id']);
$vehicleDao->delete($vehicle['id']);
$brandDao->delete($brand['id']);