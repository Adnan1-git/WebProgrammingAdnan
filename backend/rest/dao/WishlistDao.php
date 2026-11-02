<?php
require_once __DIR__ . '/BaseDao.php';

class WishlistDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('wishlist', ['user_id', 'vehicle_id']);
    }

    public function get_by_user_id($user_id)
    {
        return $this->query(
            "SELECT * FROM wishlist WHERE user_id = :user_id",
            ['user_id' => $user_id]
        );
    }

    public function get_by_user_and_vehicle($user_id, $vehicle_id)
    {
        return $this->query_unique(
            "SELECT * FROM wishlist WHERE user_id = :user_id AND vehicle_id = :vehicle_id",
            ['user_id' => $user_id, 'vehicle_id' => $vehicle_id]
        );
    }

    public function remove($user_id, $vehicle_id)
    {
        return $this->execute(
            "DELETE FROM wishlist WHERE user_id = :user_id AND vehicle_id = :vehicle_id",
            ['user_id' => $user_id, 'vehicle_id' => $vehicle_id]
        );
    }
}