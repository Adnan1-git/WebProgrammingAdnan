<?php
require_once __DIR__ . '/BaseDao.php';

class BrandDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('brands', ['naziv']);
    }
}