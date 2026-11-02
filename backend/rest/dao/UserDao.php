<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{
    /** Columns safe to return to the frontend (never the password hash). */
    const PUBLIC_COLUMNS = 'id, ime, prezime, email, uloga, datum_registracije';

    public function __construct()
    {
        parent::__construct('users', ['ime', 'prezime', 'email', 'lozinka', 'uloga']);
    }

    /** All users, without password hash. */
    public function get_all()
    {
        return $this->query("SELECT " . self::PUBLIC_COLUMNS . " FROM users ORDER BY ime, prezime");
    }

    /** One user by id, without password hash. */
    public function get_by_id($id)
    {
        return $this->query_unique(
            "SELECT " . self::PUBLIC_COLUMNS . " FROM users WHERE id = :id",
            ['id' => $id]
        );
    }

    /** Full row including password hash, used only to check a login. */
    public function get_by_email($email)
    {
        return $this->query_unique(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $email]
        );
    }

    /** Number of users with a given role (user or admin). */
    public function count_by_role($role)
    {
        return $this->query_unique(
            "SELECT COUNT(*) AS total FROM users WHERE uloga = :role",
            ['role' => $role]
        )['total'];
    }
}
