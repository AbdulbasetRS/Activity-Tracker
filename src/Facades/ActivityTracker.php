<?php

namespace Abdulbaset\ActivityTracker\Facades;

use Illuminate\Support\Facades\Facade;

class ActivityTracker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'activity-tracker';
    }
}
