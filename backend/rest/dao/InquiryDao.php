<?php
require_once __DIR__ . '/BaseDao.php';

class InquiryDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('inquiries', ['user_id', 'vehicle_id', 'poruka']);
    }
}