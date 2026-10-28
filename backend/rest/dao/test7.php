<?php
require_once __DIR__ . '/FinancingRequestDao.php';
require_once __DIR__ . '/RegistrationDao.php';

// PROMIJENI kolone prema schema.sql
$financingDao = new FinancingRequestDao();
$fin = $financingDao->add(['name' => 'Test', 'email' => 'test@plaza.ba', 'amount' => 5000]);
echo "FINANCING CREATE:\n"; print_r($fin);
echo "FINANCING READ:\n"; print_r($financingDao->get_by_id($fin['id']));
$financingDao->delete($fin['id']);
echo "FINANCING DELETE:\n"; var_dump($financingDao->get_by_id($fin['id']));

$regDao = new RegistrationDao();
$reg = $regDao->add(['name' => 'Test', 'email' => 'test@plaza.ba']);
echo "REGISTRATION CREATE:\n"; print_r($reg);
echo "REGISTRATION READ:\n"; print_r($regDao->get_by_id($reg['id']));
$regDao->delete($reg['id']);
echo "REGISTRATION DELETE:\n"; var_dump($regDao->get_by_id($reg['id']));