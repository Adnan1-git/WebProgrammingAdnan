<?php
require_once __DIR__ . '/InquiryDao.php';
require_once __DIR__ . '/ContactMessageDao.php';

// PROMIJENI kolone prema schema.sql
$inquiryDao = new InquiryDao();
$inquiry = $inquiryDao->add(['name' => 'Test', 'email' => 'test@plaza.ba', 'message' => 'Test upit']);
echo "INQUIRY CREATE:\n"; print_r($inquiry);
echo "INQUIRY READ:\n"; print_r($inquiryDao->get_by_id($inquiry['id']));
$inquiryDao->delete($inquiry['id']);
echo "INQUIRY DELETE:\n"; var_dump($inquiryDao->get_by_id($inquiry['id']));

$contactDao = new ContactMessageDao();
$msg = $contactDao->add(['name' => 'Test', 'email' => 'test@plaza.ba', 'message' => 'Test poruka']);
echo "CONTACT CREATE:\n"; print_r($msg);
echo "CONTACT READ:\n"; print_r($contactDao->get_by_id($msg['id']));
$contactDao->delete($msg['id']);
echo "CONTACT DELETE:\n"; var_dump($contactDao->get_by_id($msg['id']));