<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/FinancingRequestDao.php';

class FinancingRequestService extends BaseService
{
    private const STATUSES = ['pending', 'approved', 'rejected'];
    private const TERMS = [12, 24, 36, 48, 60, 72, 84];

    public function __construct()
    {
        parent::__construct(new FinancingRequestDao());
    }

    protected function validate(array $data, bool $partial): void
    {
        $this->require($data, ['user_id', 'vehicle_id', 'down_payment', 'term_months', 'monthly_income'], $partial);
        $this->positiveInt($data, 'user_id');
        $this->positiveInt($data, 'vehicle_id');

        if (isset($data['down_payment']) && (!is_numeric($data['down_payment']) || $data['down_payment'] < 0)) {
            throw new Exception('Down payment cannot be negative.', 400);
        }
        if (isset($data['monthly_income']) && (!is_numeric($data['monthly_income']) || $data['monthly_income'] <= 0)) {
            throw new Exception('Monthly income must be greater than 0.', 400);
        }
        if (isset($data['term_months']) && !in_array((int)$data['term_months'], self::TERMS, true)) {
            throw new Exception('Term must be one of: ' . implode(', ', self::TERMS) . ' months.', 400);
        }
        $this->oneOf($data, 'status', self::STATUSES);
    }

    public function create(array $data)
    {
        $data['status'] = 'pending'; // clients cannot self-approve
        return parent::create($data);
    }
}