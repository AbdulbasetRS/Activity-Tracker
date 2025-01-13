<?php

namespace Abdulbaset\ActivityTracker;

use Abdulbaset\ActivityTracker\Services\ActivityLogger;
use Illuminate\Support\Facades\Config;

/**
 * Class ActivityTracker
 *
 * This class is used to log activity events to the activity log.
 * It provides a simple interface for logging activity events, with the ability to specify
 * the subject, description, and data to be logged.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityTracker
{
    public static function log(string $subject, string $description = null,  array $data = [])
    {
        if (!Config::get('activity-tracker.enabled')) {
            return;
        }

        return app(ActivityLogger::class)->log(null,
            $subject,
            $description,
            $data
        );
    }
}
