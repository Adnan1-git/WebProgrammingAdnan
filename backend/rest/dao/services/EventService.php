<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../EventDao.php';
require_once __DIR__ . '/../CategoryDao.php';
require_once __DIR__ . '/../RegistrationDao.php';

class EventService extends BaseService
{
    protected $entity_name = 'Event';
    private $category_dao;
    private $registration_dao;

    /** DAOs can be passed in (used by unit tests); by default real DAOs are created. */
    public function __construct($dao = null, $category_dao = null, $registration_dao = null)
    {
        parent::__construct($dao ?? new EventDao());
        $this->category_dao = $category_dao ?? new CategoryDao();
        $this->registration_dao = $registration_dao ?? new RegistrationDao();
    }

    public function add($event)
    {
        return parent::add($this->empty_to_null($event, ['description', 'location', 'price', 'capacity']));
    }

    public function update($event, $id)
    {
        return parent::update($this->empty_to_null($event, ['description', 'location', 'price', 'capacity']), $id);
    }

    /** Rule: an event that already has registrations cannot be deleted. */
    public function delete($id)
    {
        $this->get_by_id($id);
        $registrations = $this->registration_dao->count_by_event($id);
        if ($registrations > 0) {
            throw new ValidationException("This event already has $registrations registration(s). Cancel them first.");
        }
        $this->dao->delete($id);
    }

    public function get_upcoming()
    {
        return $this->dao->get_upcoming();
    }

    public function get_by_category($category_id)
    {
        return $this->dao->get_by_category($category_id);
    }

    public function search($term)
    {
        return $this->dao->search(trim($term));
    }

    protected function validate($event, $id = null)
    {
        Validator::required($event, ['title', 'event_date', 'category_id']);
        Validator::length($event['title'], 'title', 2, 120);

        if (!empty($event['description'])) {
            Validator::length($event['description'], 'description', 0, 1000);
        }
        if (!empty($event['location'])) {
            Validator::length($event['location'], 'location', 0, 150);
        }

        // Rule: date must be a valid date
        if (strtotime($event['event_date']) === false) {
            throw new ValidationException("event_date is not a valid date.");
        }

        // Rule: the category must exist
        if (!$this->category_dao->get_by_id($event['category_id'])) {
            throw new ValidationException("Category with id {$event['category_id']} does not exist.");
        }

        // Rule: price cannot be negative
        if (isset($event['price']) && (!is_numeric($event['price']) || $event['price'] < 0)) {
            throw new ValidationException("price must be a number that is 0 or more.");
        }

        // Rule: capacity must be a positive whole number
        if (isset($event['capacity']) && (!ctype_digit((string) $event['capacity']) || (int) $event['capacity'] < 1)) {
            throw new ValidationException("capacity must be a whole number greater than 0.");
        }
    }
}