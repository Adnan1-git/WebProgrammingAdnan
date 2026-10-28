<?php
require_once __DIR__ . '/BaseDao.php';

class VehicleDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('vehicles', [
            'brand_id', 'model', 'godina', 'cijena', 'kilometraza', 'mjenjac',
            'gorivo', 'snaga_kw', 'boja', 'opis', 'slika_url', 'status'
        ]);
    }


}