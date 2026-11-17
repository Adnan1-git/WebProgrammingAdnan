<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ContactMessageDao.php';

class ContactMessageService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new ContactMessageDao());
    }

    public function validate(array $data, bool $partial): void
    {
        $this->require($data, ['name', 'email', 'subject', 'message'], $partial);
        $this->email($data);

        if (isset($data['name']) && mb_strlen($data['name']) > 100) {
            throw new Exception('Name must be at most 100 characters.', 400);
        }
        if (isset($data['subject']) && mb_strlen($data['subject']) > 150) {
            throw new Exception('Subject must be at most 150 characters.', 400);
        }
        if (isset($data['message']) && mb_strlen(trim($data['message'])) < 10) {
            throw new Exception('Message must be at least 10 characters.', 400);
        }
    }
}