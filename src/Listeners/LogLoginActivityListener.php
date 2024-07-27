<?php

// src/Listeners/LogLoginActivity.php

namespace Abdulbaset\ActivityTracker\Listeners;

use Abdulbaset\ActivityTracker\Facades\ActivityTracker;
use Illuminate\Auth\Events\Login;

class LogLoginActivityListener
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user; // Get the authenticated user
        ActivityTracker::log($user, 'login', 'User logged in');
    }
}
