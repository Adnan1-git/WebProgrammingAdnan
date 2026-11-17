<?php
require_once __DIR__ . '/BaseDao.php';

class EventDao extends BaseDao
{
    public function __construct()
    {
        // Table name + columns that may be inserted/updated
        parent::__construct('events', [
            'title',
            'description',
            'location',
            'event_date',
            'category_id',
            'price',
            'capacity'
        ]);
    }

    /** All events that have not happened yet, soonest first. */
    public function get_upcoming()
    {
        return $this->query(
            "SELECT * FROM {$this->table_name} WHERE event_date >= NOW() ORDER BY event_date ASC"
        );
    }

    /** All events that already happened, most recent first. */
    public function get_past()
    {
        return $this->query(
            "SELECT * FROM {$this->table_name} WHERE event_date < NOW() ORDER BY event_date DESC"
        );
    }

    /** All events in a given category. */
    public function get_by_category($category_id)
    {
        return $this->query(
            "SELECT * FROM {$this->table_name} WHERE category_id = :category_id ORDER BY event_date ASC",
            ['category_id' => $category_id]
        );
    }

    /** Events happening in a date range (inclusive). Dates as 'Y-m-d' or 'Y-m-d H:i:s'. */
    public function get_by_date_range($start, $end)
    {
        return $this->query(
            "SELECT * FROM {$this->table_name}
             WHERE event_date BETWEEN :start AND :end
             ORDER BY event_date ASC",
            ['start' => $start, 'end' => $end]
        );
    }

    /** Search events by title, description or location. */
    public function search($term)
    {
        return $this->query(
            "SELECT * FROM {$this->table_name}
             WHERE title LIKE :term OR description LIKE :term OR location LIKE :term
             ORDER BY event_date ASC",
            ['term' => '%' . $term . '%']
        );
    }
}