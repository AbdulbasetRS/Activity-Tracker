<?php

// src/Listeners/LogLoginFailedActivityListener.php

namespace Abdulbaset\ActivityTracker\Listeners;

use Abdulbaset\ActivityTracker\Facades\ActivityTracker;
use Illuminate\Auth\Events\Failed;

class LogLoginFailedActivityListener
{
    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $credentials = $event->credentials;

        unset($credentials['password']);

        $identifier = json_encode($credentials);

        ActivityTracker::log(null,'login_failed', 'Failed login attempt with [ ' . $identifier . ' ]');
    }
}
