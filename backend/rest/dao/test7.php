<?php
echo "TEST7 VERSION 3<br>";
require_once __DIR__ . '/BrandDao.php';
require_once __DIR__ . '/VehicleDao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/FinancingRequestDao.php';
require_once __DIR__ . '/RegistrationDao.php';

$brandDao   = new BrandDao();
$vehicleDao = new VehicleDao();
$userDao    = new UserDao();

$brand = $brandDao->add(['naziv' => 'Test brand ' . uniqid()]);

$vehicle = $vehicleDao->add([
    'brand_id' => $brand['id'],
    'model'    => 'Test model',
    'cijena'   => 10000
]);

$user = $userDao->add([
    'ime'     => 'Test',
    'prezime' => 'User',
    'email'   => 'fin' . uniqid() . '@plaza.ba',
    'lozinka' => '123456'
]);

// ---- DEBUG: show the rule and the real columns ----
$pdo = BaseDao::connection();
$rule = $pdo->query("
    SELECT CHECK_CLAUSE
    FROM information_schema.CHECK_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = 'plaza' AND CONSTRAINT_NAME = 'chk_fin_rate'
")->fetchColumn();
echo "<pre>RULE chk_fin_rate: " . $rule . "\n\nCOLUMNS:\n";
foreach ($pdo->query("SHOW COLUMNS FROM financing_requests")->fetchAll(PDO::FETCH_ASSOC) as $c) {
    echo $c['Field'] . "  (" . $c['Type'] . ")  null=" . $c['Null'] . "  default=" . $c['Default'] . "\n";
}
echo "</pre>";
// ---------------------------------------------------

$financing_requests = new FinancingRequestDao();

try {
        $financing = $financing_requests->add([
        'user_id'       => $user['id'],
        'vehicle_id'    => $vehicle['id'],
        'ucesce'        => 2000,
        'broj_rata'     => 24,
        'mjesecna_rata' => 350
    ]);
} catch (PDOException $e) {
    echo "<b>FINANCING INSERT FAILED:</b> " . $e->getMessage();
    exit;
}

echo "FINANCING CREATE:\n"; print_r($financing);
echo "FINANCING READ:\n"; print_r($financing_requests->get_by_id($financing['id']));
$financing_requests->delete($financing['id']);
echo "FINANCING DELETE:\n"; var_dump($financing_requests->get_by_id($financing['id']));

$regDao = new RegistrationDao();
$reg = $regDao->add([
    'user_id' => $user['id'],
    'status'  => 'pending'
]);
echo "REGISTRATION CREATE:\n"; print_r($reg);
echo "REGISTRATION READ:\n"; print_r($regDao->get_by_id($reg['id']));
$regDao->delete($reg['id']);
echo "REGISTRATION DELETE:\n"; var_dump($regDao->get_by_id($reg['id']));

// Cleanup
$userDao->delete($user['id']);
$vehicleDao->delete($vehicle['id']);
$brandDao->delete($brand['id']);