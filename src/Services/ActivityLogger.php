<?php

namespace Abdulbaset\ActivityTracker\Services;

use Abdulbaset\ActivityTracker\Enums\ActivityEventType;
use Abdulbaset\ActivityTracker\Models\ActivityTracker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

/**
 * Class ActivityLogger
 *
 * This class is used to log activities to the activity log.
 * It provides a simple interface for logging activities, with the ability to specify
 * the event type, subject, description, and additional data to be logged.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityLogger
{
    public function log(ActivityEventType $eventType = null, string $subject, string $description = null, array $data = [])
    {
        try {
            $logData = array_merge(
                [
                    'event' => $eventType->value ?? null,
                    'subject' => $subject,
                    'description' => $description,
                ],
                $data,
                $this->getRequestData(),
                $this->getUserData(),
                $this->deviceDetector()
            );

            $logMethod = config('activity-tracker.log_method');
            if ($logMethod === 'file') {
                $this->logToFile($logData);
            } else {
                return ActivityTracker::create($logData);
            }
        } catch (\Exception $e) {
            Log::error('Error logging activity: ' . $e->getMessage());
            return null;
        }

    }

    protected function getRequestData(): array
    {
        $queryParameters = Request::query();

        return [
            'ip' => Request::ip(),
            'headers' => Request::header(),
            'query_parameters' => empty($queryParameters) ? null : $queryParameters,
            'request_method' => Request::method(),
            'current_url' => Request::fullUrl(),
            'referring_url' => Request::header('referer'),
        ];
    }

    protected function getUserData(): array
    {
        return [
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
        ];
    }

    protected function deviceDetector()
    {
        $deviceDetector = new Agent();

        return [
            'browser' => $deviceDetector->browser(),
            'device_type' => $deviceDetector->deviceType(),
            'operating_system' => $deviceDetector->platform(),
        ];
    }

    protected function logToFile(array $logData): void
    {
        $logFilePath = config('activity-tracker.log_file_path', storage_path('logs/activity_tracker.log'));
        $logEntry = '[' . now() . '] ' . json_encode($logData, JSON_PRETTY_PRINT) . PHP_EOL;
        
        file_put_contents($logFilePath, $logEntry, FILE_APPEND);
    }
}