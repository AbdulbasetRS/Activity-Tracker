<?php 
// src/Listeners/LogLogoutActivity.php

namespace Abdulbaset\ActivityTracker\Listeners;

use Abdulbaset\ActivityTracker\Facades\ActivityTracker;
use Illuminate\Auth\Events\Logout;

class LogLogoutActivityListener
{
    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $user = $event->user; // Get the user who logged out
        ActivityTracker::log($user, 'logout', 'User logged out');
    }
}
