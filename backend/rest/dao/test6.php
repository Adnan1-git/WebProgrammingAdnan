<?php
require_once __DIR__ . '/BrandDao.php';
require_once __DIR__ . '/VehicleDao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/InquiryDao.php';
require_once __DIR__ . '/ContactMessageDao.php';

$brandDao = new BrandDao();
$vehicleDao = new VehicleDao();
$userDao = new UserDao();

$brand = $brandDao->add(['naziv' => 'Test brand' . time()]);
$vehicle = $vehicleDao->add(['brand_id' => $brand['id']]);
$user = $userDao->add([
    'ime' => 'Test',
    'prezime' => 'User',
    'email' => 'inq' . time() . '@plaza.ba',
    'lozinka' => '123456'
]);

$inquiryDao = new InquiryDao();
$inquiry = $inquiryDao->add([
    'user_id' => $user['id'],
    'vehicle_id' => $vehicle['id'],
    'name' => 'Test',
    'email' => 'test@plaza.ba',
    'poruka' => 'Test upit'
]);
echo "INQUIRY CREATE:\n"; print_r($inquiry);
echo "INQUIRY READ:\n"; print_r($inquiryDao->get_by_id($inquiry['id']));
$inquiryDao->delete($inquiry['id']);
echo "INQUIRY DELETE:\n"; var_dump($inquiryDao->get_by_id($inquiry['id']));

$contactDao = new ContactMessageDao();
$msg = $contactDao->add(['name' => 'Test', 'poruka' => 'Test poruka']);
echo "CONTACT CREATE:\n"; print_r($msg);
echo "CONTACT READ:\n"; print_r($contactDao->get_by_id($msg['id']));
$contactDao->delete($msg['id']);
echo "CONTACT DELETE:\n"; var_dump($contactDao->get_by_id($msg['id']));

// Čišćenje
$userDao->delete($user['id']);
$vehicleDao->delete($vehicle['id']);
$brandDao->delete($brand['id']);