<?php
class BaseService
{
    protected $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function getAll()
    {
        return $this->dao->getAll();
    }

    public function getById($id)
    {
        $id = $this->validId($id);
        $row = $this->dao->getById($id);
        if (!$row) {
            throw new Exception('Record not found.', 404);
        }
        return $row;
    }

    public function create(array $data)
    {
        $this->validate($data, false);
        return $this->dao->insert($data);
    }

    public function update($id, array $data)
    {
        $this->getById($id);
        $this->validate($data, true);
        $this->dao->update((int)$id, $data);
        return $this->getById($id);
    }

    public function delete($id)
    {
        $this->getById($id);
        $this->dao->delete((int)$id);
        return true;
    }

    protected function validate(array $data, bool $partial): void {}

    protected function validId($id): int
    {
        if (!ctype_digit((string)$id) || (int)$id < 1) {
            throw new Exception('Invalid ID.', 400);
        }
        return (int)$id;
    }

    protected function require(array $data, array $fields, bool $partial): void
    {
        foreach ($fields as $f) {
            $present = array_key_exists($f, $data) && trim((string)$data[$f]) !== '';
            if (!$partial && !$present) {
                throw new Exception("Field '$f' is required.", 400);
            }
            if ($partial && array_key_exists($f, $data) && !$present) {
                throw new Exception("Field '$f' cannot be empty.", 400);
            }
        }
    }

    protected function email(array $data, string $field = 'email'): void
    {
        if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address.', 400);
        }
    }

    protected function positiveInt(array $data, string $field): void
    {
        if (isset($data[$field]) && (!ctype_digit((string)$data[$field]) || (int)$data[$field] < 1)) {
            throw new Exception("Field '$field' must be a positive integer.", 400);
        }
    }

    protected function oneOf(array $data, string $field, array $allowed): void
    {
        if (isset($data[$field]) && !in_array($data[$field], $allowed, true)) {
            throw new Exception("Field '$field' must be one of: " . implode(', ', $allowed) . '.', 400);
        }
    }
}