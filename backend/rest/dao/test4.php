<?php
require_once __DIR__ . '/UserDao.php';

$dao = new UserDao();

// PROMIJENI kolone prema schema.sql
$user = $dao->add([
    'ime' => 'Test',
    'prezime'  => 'User',
    'email'      => 'testuser@plaza.ba',
    'lozinka'   => password_hash('123456', PASSWORD_DEFAULT)
]);
echo "CREATE:\n"; print_r($user);

echo "READ:\n"; print_r($dao->get_by_id($user['id']));

echo "GET BY EMAIL:\n"; print_r($dao->get_by_email('testuser@plaza.ba'));

$dao->update(['ime' => 'Updated'], $user['id']);
echo "UPDATE:\n"; print_r($dao->get_by_id($user['id']));

$dao->delete($user['id']);
echo "DELETE (treba biti prazno):\n"; var_dump($dao->get_by_id($user['id']));