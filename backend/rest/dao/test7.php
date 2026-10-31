<?php
require_once __DIR__ . '/BrandDao.php';
require_once __DIR__ . '/VehicleDao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/FinancingRequestDao.php';
require_once __DIR__ . '/RegistrationDao.php';

$brandDao = new BrandDao();
$vehicleDao = new VehicleDao();
$userDao = new UserDao();

$brand = $brandDao->add(['naziv' => 'Test brand' . time()]);
$vehicle = $vehicleDao->add(['brand_id' => $brand['id']]);
$user = $userDao->add([
    'ime' => 'Test',
    'prezime' => 'User',
    'email' => 'fin' . uniqid() . '@plaza.ba',
    'lozinka' => '123456'
]);

$financingDao = new FinancingRequestDao();
$fin = $financingDao->add([
    'user_id' => $user['id'],
    'vehicle_id' => $vehicle['id'],
]);
echo "FINANCING CREATE:\n"; print_r($fin);
echo "FINANCING READ:\n"; print_r($financingDao->get_by_id($fin['id']));
$financingDao->delete($fin['id']);
echo "FINANCING DELETE:\n"; var_dump($financingDao->get_by_id($fin['id']));

$regDao = new RegistrationDao();
$reg = $regDao->add([
    'user_id' => $user['id'],
    'status' => 'pending'   // vidi napomenu ispod
]);
echo "REGISTRATION CREATE:\n"; print_r($reg);
echo "REGISTRATION READ:\n"; print_r($regDao->get_by_id($reg['id']));
$regDao->delete($reg['id']);
echo "REGISTRATION DELETE:\n"; var_dump($regDao->get_by_id($reg['id']));

// Čišćenje
$userDao->delete($user['id']);
$vehicleDao->delete($vehicle['id']);
$brandDao->delete($brand['id']);