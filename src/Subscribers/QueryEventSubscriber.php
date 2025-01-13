<?php

namespace Abdulbaset\ActivityTracker\Subscribers;

use Abdulbaset\ActivityTracker\Enums\ActivityEventType;
use Abdulbaset\ActivityTracker\Services\ActivityLogger;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Config;

/**
 * Class QueryEventSubscriber
 *
 * This class is used to subscribe to query events and log them to the activity log.
 * It provides a simple interface for logging query events, with the ability to specify
 * which queries should be tracked and the source of the tracking.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class QueryEventSubscriber
{
    protected $logger;

    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    public function handleQuery(QueryExecuted $event)
    {
        if (!$this->shouldLogQuery($event)) {
            return;
        }

        $this->logger->log(
            ActivityEventType::QUERY_EXECUTED,
            'Database Query',
            "Query executed: {$event->sql}",
            [
                'properties' => [
                    'sql' => $event->sql,
                    'bindings' => $event->bindings,
                    'time' => $event->time
                ],
            ]
        );
    }

    protected function shouldLogQuery(QueryExecuted $event): bool
    {
        return !str_contains($event->sql, 'activity_logs')
            && Config::get('activity-tracker.tracking.queries', false);
    }

    public function subscribe($events)
    {
        $events->listen(QueryExecuted::class, [self::class, 'handleQuery']);
    }
}