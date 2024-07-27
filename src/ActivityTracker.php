<?php

namespace Abdulbaset\ActivityTracker;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Abdulbaset\ActivityTracker\Helpers;

class ActivityTracker
{
    /**
     * Log a custom activity.
     *
     * @param  mixed   $model
     * @param  string  $event
     * @param  string  $description
     * @param  array   $otherInfo
     * @return bool
     */
    public static function log($model, $event = null, $description = null, $otherInfo = [])
    {
        if (!config('activity-tracker.enabled')) {
            return false; // Activity logging is disabled
        }

        $logData = self::prepareLogData($model, $event, $description, $otherInfo);

        return self::logData($logData);
    }

    /**
     * Log a retrieved model activity.
     *
     * @param  mixed   $model
     * @param  string  $description
     * @param  array   $otherInfo
     * @return bool
     */
    public static function retrieved($model, $description = null, $otherInfo = [])
    {
        if (!config('activity-tracker.enabled')) {
            return false; // Activity logging is disabled
        }

        $logData = self::prepareLogData($model, 'retrieved', $description, $otherInfo);

        return self::logData($logData);
    }

    /**
     * Log a visited page activity.
     *
     * @param  string  $description
     * @param  array   $otherInfo
     * @return bool
     */
    public static function visited($description = null, $otherInfo = [])
    {
        if (!config('activity-tracker.enabled')) {
            return false; // Activity logging is disabled
        }

        $logData = self::prepareLogData(null, 'visited', $description, $otherInfo);

        return self::logData($logData);
    }

    /**
     * Log a custom event activity.
     *
     * @param  string  $event
     * @param  string  $description
     * @param  array   $otherInfo
     * @return bool
     */
    public static function event($event = 'default', $description = null, $otherInfo = [])
    {
        if (!config('activity-tracker.enabled')) {
            return false; // Activity logging is disabled
        }

        $logData = self::prepareLogData(null, $event, $description, $otherInfo);

        return self::logData($logData);
    }

    /**
     * Prepare the log data for storage.
     *
     * @param  mixed   $model
     * @param  string  $event
     * @param  string  $description
     * @param  array   $otherInfo
     * @return array
     */
    protected static function prepareLogData($model, $event, $description, $otherInfo)
    {
        $userAgent = Request::header('User-Agent') ?? null;
        $queryParameters = Request::query() ?? null;
        $requestMethod = Request::method() ?? null;

        // Get all headers
        $headers = Request::header();

        $logData = [
            'event' => $event,
            'user_id' => Auth::id() ?: null,
            'model' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old' => null,
            'new' => null,
            'ip' => Request::ip(),

            'user_agent' => $userAgent,
            'query_parameters' => !empty($queryParameters) ? json_encode($queryParameters) : null,
            'request_method' => $requestMethod,
            'headers' => !empty($headers) ? json_encode($headers) : null,

            'browser' => Helpers\getBrowser($userAgent),
            'browser_version' => Helpers\getBrowserVersion($userAgent),
            'referring_url' => Request::server('HTTP_REFERER'),
            'current_url' => Request::fullUrl(),
            'device_type' => Helpers\getDeviceType($userAgent),
            'operating_system' => Helpers\getOperatingSystem($userAgent),
            'description' => $description,
            'other_info' => empty($otherInfo) ? null : json_encode($otherInfo),
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];

        if ($event === 'updated') {
            $logData['old'] = json_encode($model->getOriginal());
            $logData['new'] = json_encode($model->getChanges());
            if (config('activity-tracker.log_only_changes')) {
                $logData['old'] = json_encode(array_intersect_key($model->getOriginal(), $model->getChanges()));
                $logData['new'] = json_encode($model->getChanges());
            } else {
                $logData['old'] = json_encode($model->getOriginal());
                $logData['new'] = json_encode($model->toArray());
            }
        } elseif ($event === 'created') {
            $logData['new'] = json_encode($model->toArray());
        } elseif ($event === 'deleted') {
            $logData['old'] = json_encode($model->toArray());
        } elseif ($event === 'retrieved') {
            $logData['old'] = json_encode($model->toArray());
        }

        return $logData;
    }

    /**
     * Log the prepared log data to the appropriate storage.
     *
     * @param  array   $logData
     * @return bool
     */
    protected static function logData($logData)
    {
        switch (config('activity-tracker.log_method')) {
            case 'file':
                return self::logToFile($logData);
            case 'database':
            default:
                return self::logToDatabase($logData);
        }
    }

    /**
     * Log the prepared log data to the database.
     *
     * @param  array   $logData
     * @return bool
     */
    protected static function logToDatabase($logData)
    {
        return DB::table(config('activity-tracker.table_name'))->insert($logData) ? true : false;
    }
    
    /**
     * Log the prepared log data to a file.
     *
     * @param  array   $logData
     * @return bool
     */
    protected static function logToFile($logData)
    {
        $logFilePath = config('activity-tracker.log_file_path');
        $logEntry = json_encode($logData) . PHP_EOL;
        // Append the log entry to the file
        return file_put_contents($logFilePath, $logEntry, FILE_APPEND) !== false;
    }
}
