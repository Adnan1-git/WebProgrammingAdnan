<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/InquiryDao.php';

class InquiryService extends BaseService
{
    private const STATUSES = ['new', 'in_progress', 'answered', 'closed'];

    public function __construct()
    {
        parent::__construct(new InquiryDao());
    }

    protected function validate(array $data, bool $partial): void
    {
        $this->require($data, ['vehicle_id', 'name', 'email', 'message'], $partial);
        $this->positiveInt($data, 'vehicle_id');
        $this->email($data);

        if (isset($data['message']) && mb_strlen(trim($data['message'])) < 10) {
            throw new Exception('Message must be at least 10 characters.', 400);
        }
        $this->oneOf($data, 'status', self::STATUSES);
    }

    public function create(array $data)
    {
        $data['status'] = $data['status'] ?? 'new'; // default business rule
        return parent::create($data);
    }
}