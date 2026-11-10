<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../RegistrationDao.php';
require_once __DIR__ . '/../EventDao.php';
require_once __DIR__ . '/../UserDao.php';
 
class RegistrationService extends BaseService
{

 const MAX_UPCOMING_REGISTRATIONS = 3;

    protected $entity_name = 'Registration';
    private $event_dao;
    private $user_dao;

    /** DAOs can be passed in (used by unit tests); by default real DAOs are created. */
    public function __construct($dao = null, $event_dao = null, $user_dao = null)
    {
        parent::__construct($dao ?? new RegistrationDao());
        $this->event_dao = $event_dao ?? new EventDao();
        $this->user_dao = $user_dao ?? new UserDao();
    }
 
    protected function validate($registration, $id = null)
    {
        Validator::required($registration, ['event_id', 'user_id']);
 
        // Rule: the event must exist
        $event = $this->event_dao->get_by_id($registration['event_id']);
        if (!$event) {
            throw new ValidationException("Event with id {$registration['event_id']} does not exist.");
        }
 
        // Rule: a user can register only once for the same event
        $existing = $this->dao->get_by_user_and_event($registration['user_id'], $registration['event_id']);
        if ($existing && $existing['id'] != $id) {
            throw new ValidationException("This user is already registered for this event.");
        }
 
        // The rules below only apply to NEW registrations
        if ($id === null) {
            // Rule: cannot register for an event that already happened
            if (strtotime($event['event_date']) < time()) {
                throw new ValidationException("This event has already taken place.");
            }
 
            // Rule: the event must not be full
            if (!empty($event['capacity'])) {
                $taken = $this->dao->count_by_event($registration['event_id']);
                if ($taken >= $event['capacity']) {
                    throw new ValidationException("This event is fully booked.");
                }
            }
        }
    }
 
    public function get_by_event($event_id)
    {
        return $this->dao->get_by_event($event_id);
    }
 
    public function get_by_user($user_id)
    {
        return $this->dao->get_by_user($user_id);
    }



    public function register($user_id, $event_id)
{
    Validator::integer($user_id, 'user_id', 1);
    Validator::integer($event_id, 'event_id', 1);

    if (!$this->user_dao->get_by_id($user_id)) {
        throw new ValidationException("The selected student does not exist.");
    }
    $event = $this->event_dao->get_by_id($event_id);
    if (!$event) {
        throw new ValidationException("The selected event does not exist.");
    }
    if ($event['starts_at'] <= date('Y-m-d H:i:s')) {
        throw new ValidationException("'{$event['title']}' has already started – registration is closed.");
    }
    if ($event['available_seats'] < 1) {
        throw new ValidationException("'{$event['title']}' is sold out.");
    }
    if ($this->dao->get_active_by_user_and_event($user_id, $event_id)) {
        throw new ValidationException("This student is already registered for '{$event['title']}'.");
    }
    if ($this->dao->count_upcoming_by_user($user_id) >= self::MAX_UPCOMING_REGISTRATIONS) {
        throw new ValidationException("A student can have at most " . self::MAX_UPCOMING_REGISTRATIONS . " upcoming registrations. Cancel one first.");
    }

    // Two tables change together (registrations + events) -> transaction: all or nothing
    $this->dao->begin_transaction();
    try {
        $registration = $this->dao->add([
            'user_id' => $user_id,
            'event_id' => $event_id,
            'registered_at' => date('Y-m-d H:i:s'),
            'status' => 'registered'
        ]);
        if ($this->event_dao->change_available_seats($event_id, -1) === 0) {
            throw new ValidationException("'{$event['title']}' is sold out.");
        }
        $this->dao->commit();
    } catch (Throwable $e) {
        $this->dao->rollback();
        throw $e;
    }

    return $this->dao->get_by_id($registration['id']);
}


}