public function add($user)
{
    $user['role'] = $user['role'] ?? Roles::STUDENT;
    $user = $this->empty_to_null($user, ['faculty']);
    $this->validate($user);
    Validator::required($user, ['password']);
    Validator::password($user['password']);

    // Rule: passwords are NEVER stored in plain text
    $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
    $user['email'] = strtolower(trim($user['email']));

    $created = $this->dao->add($user);
    return $this->dao->get_by_id($created['id']);   // get_by_id does not return the hash
}
