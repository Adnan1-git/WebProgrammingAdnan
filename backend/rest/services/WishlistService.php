<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/WishlistDao.php';

class WishlistService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new WishlistDao());
    }

    protected function validate(array $data, bool $partial): void
    {
        $this->require($data, ['user_id', 'vehicle_id'], $partial);
        $this->positiveInt($data, 'user_id');
        $this->positiveInt($data, 'vehicle_id');
    }

    public function create(array $data)
    {
        try {
            return parent::create($data);
        } catch (PDOException $e) {
            // 23000 = integrity constraint (duplicate or bad foreign key)
            if ($e->getCode() === '23000') {
                throw new Exception('Vehicle already in wishlist, or user/vehicle does not exist.', 409);
            }
            throw $e;
        }
    }
}