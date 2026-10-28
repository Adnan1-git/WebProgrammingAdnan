<?php
require_once __DIR__ . '/BaseDao.php';

class WishlistDao extends BaseDao {

    public function __construct() {
        parent::__construct("wishlist");
    }

    // Sva vozila u wishlisti jednog korisnika
    public function get_by_user_id($user_id) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM wishlist WHERE user_id = :user_id"
        );
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Provjera da li je vozilo već u wishlisti tog korisnika
    public function get_by_user_and_vehicle($user_id, $vehicle_id) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM wishlist WHERE user_id = :user_id AND vehicle_id = :vehicle_id"
        );
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':vehicle_id', $vehicle_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Brisanje vozila iz wishlist-e
    public function remove($user_id, $vehicle_id) {
        $stmt = $this->connection->prepare(
            "DELETE FROM wishlist WHERE user_id = :user_id AND vehicle_id = :vehicle_id"
        );
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':vehicle_id', $vehicle_id);
        return $stmt->execute();
    }
}