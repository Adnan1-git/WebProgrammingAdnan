<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/VehicleDao.php';

class VehicleService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new VehicleDao());
    }

    protected function validate(array $data, bool $partial): void
    {
        $this->require($data, ['brand_id', 'category_id', 'model', 'year', 'price'], $partial);
        $this->positiveInt($data, 'brand_id');
        $this->positiveInt($data, 'category_id');

        if (isset($data['year'])) {
            $max = (int)date('Y') + 1;
            if (!ctype_digit((string)$data['year']) || $data['year'] < 1950 || $data['year'] > $max) {
                throw new Exception("Year must be between 1950 and $max.", 400);
            }
        }
        if (isset($data['price']) && (!is_numeric($data['price']) || $data['price'] <= 0)) {
            throw new Exception('Price must be greater than 0.', 400);
        }
        if (isset($data['mileage']) && (!is_numeric($data['mileage']) || $data['mileage'] < 0)) {
            throw new Exception('Mileage cannot be negative.', 400);
        }
    }
}