<?php

namespace Abdulbaset\ActivityTracker\Traits;

use Abdulbaset\ActivityTracker\Services\ModelTracker;
use Abdulbaset\ActivityTracker\Services\ActivityLogger;
use Abdulbaset\ActivityTracker\Enums\ActivityEventType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * Trait Trackable
 *
 * This trait is used to track model events and log them to the activity log.
 * It provides a simple interface for tracking model events, with the ability to specify
 * which events should be tracked and the source of the tracking.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
trait Trackable
{
    protected static function bootTrackable()
    {
        if (!Config::get('activity-tracker.tracking.models')) {
            return;
        }

        $modelTracker = app()->make(ModelTracker::class, ['trackingSource' => 'trait']);

        static::creating(function ($model) use ($modelTracker) {
            $modelTracker->trackCreating($model);
        });

        static::created(function ($model) use ($modelTracker) {
            $modelTracker->trackCreated($model);
        });

        static::updating(function ($model) use ($modelTracker) {
            $modelTracker->trackUpdating($model);
        });

        static::updated(function ($model) use ($modelTracker) {
            $modelTracker->trackUpdated($model);
        });

        static::deleting(function ($model) use ($modelTracker) {
            $modelTracker->trackDeleting($model);
        });

        static::deleted(function ($model) use ($modelTracker) {
            $modelTracker->trackDeleted($model);
        });

        if (method_exists(static::class, 'forceDeleting')) {
            static::forceDeleting(function ($model) use ($modelTracker) {
                $modelTracker->trackForceDeleting($model);
            });
        }

        if (method_exists(static::class, 'forceDeleted')) {
            static::forceDeleted(function ($model) use ($modelTracker) {
                $modelTracker->trackForceDeleted($model);
            });
        }

        if (method_exists(static::class, 'restoring')) {
            static::restoring(function ($model) use ($modelTracker) {
                $modelTracker->trackRestoring($model);
            });
        }

        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) use ($modelTracker) {
                $modelTracker->trackRestored($model);
            });
        }
    }
}