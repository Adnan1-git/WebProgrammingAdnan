<?php
require_once __DIR__ . '/UserDao.php';

$dao = new UserDao();

// PROMIJENI kolone prema schema.sql
$user = $dao->add([
    'first_name' => 'Test',
    'last_name'  => 'User',
    'email'      => 'testuser@plaza.ba',
    'password'   => password_hash('123456', PASSWORD_DEFAULT)
]);
echo "CREATE:\n"; print_r($user);

echo "READ:\n"; print_r($dao->get_by_id($user['id']));

echo "GET BY EMAIL:\n"; print_r($dao->get_by_email('testuser@plaza.ba'));

$dao->update(['first_name' => 'Updated'], $user['id']);
echo "UPDATE:\n"; print_r($dao->get_by_id($user['id']));

$dao->delete($user['id']);
echo "DELETE (treba biti prazno):\n"; var_dump($dao->get_by_id($user['id']));