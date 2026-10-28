<?php
require_once __DIR__ . '/BaseDao.php';

class FinancingRequestDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('financing_requests', [
            'user_id', 'vehicle_id', 'ucesce', 'broj_rata', 'mjesecna_rata', 'status'
        ]);
    }
}