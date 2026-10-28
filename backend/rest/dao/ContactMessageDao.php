<?php
require_once __DIR__ . '/BaseDao.php';

class ContactMessageDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('contact_messages', ['ime', 'email', 'poruka']);
    }
}