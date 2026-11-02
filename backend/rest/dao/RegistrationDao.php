<?php
require_once __DIR__ . '/BaseDao.php';

class RegistrationDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('registrations', ['user_id', 'status']);
    }

    /** Sve registracije jednog korisnika. */
    public function get_by_user($user_id)
    {
        return $this->query(
            "SELECT * FROM registrations WHERE user_id = :user_id ORDER BY id DESC",
            ['user_id' => $user_id]
        );
    }

    /** Sve registracije s određenim statusom. */
    public function get_by_status($status)
    {
        return $this->query(
            "SELECT * FROM registrations WHERE status = :status ORDER BY id DESC",
            ['status' => $status]
        );
    }

    /** Broj registracija jednog korisnika. */
    public function count_by_user($user_id)
    {
        return (int) $this->query_unique(
            "SELECT COUNT(*) AS total FROM registrations WHERE user_id = :user_id",
            ['user_id' => $user_id]
        )['total'];
    }


    public function count_upcoming_by_user($user_id)
{
    $row = $this->query_unique(
        "SELECT COUNT(*) AS total
         FROM {$this->table_name} r
         JOIN events e ON e.id = r.event_id
         WHERE r.user_id = :user_id AND e.event_date >= NOW()",
        ['user_id' => $user_id]
    );
    return (int) $row['total'];
}

}