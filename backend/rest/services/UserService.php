<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/RegistrationDao.php';
require_once __DIR__ . '/../../utils/Validator.php';
// require_once for Roles too, if it lives in its own file

class UserService extends BaseService
{
    protected $entity_name = 'User';
    private $registration_dao;

    public function __construct($dao = null, $registration_dao = null)
    {
        parent::__construct($dao ?? new UserDao());
        $this->registration_dao = $registration_dao ?? new RegistrationDao();
    }

    protected function validate(array $data, bool $partial): void
    {
        // Add your other required columns here (e.g. 'first_name', 'last_name')
        $this->require($data, ['email'], $partial);

        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address.', 400);
        }
    }

    public function add($user)
    {
        $user['role'] = $user['role'] ?? Roles::STUDENT;
        $user = $this->empty_to_null($user, ['faculty']);
        $this->validate($user, false);
        Validator::required($user, ['password']);
        Validator::password($user['password']);

        // Rule: passwords are NEVER stored in plain text
        $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
        $user['email'] = strtolower(trim($user['email']));

        $created = $this->dao->add($user);
        return $this->dao->get_by_id($created['id']); // get_by_id does not return the hash
    }

    public function update($id, $user)
    {
        $this->get_by_id($id); // throws 404 if the user does not exist

        $user = $this->empty_to_null($user, ['faculty']);
        $this->validate($user, true);

        if (isset($user['email'])) {
            $user['email'] = strtolower(trim($user['email']));
        }
        if (isset($user['password'])) {
            Validator::password($user['password']);
            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
        }

        $this->dao->update($id, $user);
        return $this->dao->get_by_id($id);
    }

    /**
     * Rule: a user cannot be deleted if they have registrations.
     */
    public function delete($id)
    {
        $this->get_by_id($id); // throws 404 if the user does not exist

        if ($this->registration_dao->count_by_user($id) > 0) {
            throw new Exception('User has registrations and cannot be deleted.', 409);
        }

        return $this->dao->delete($id);
    }
}













