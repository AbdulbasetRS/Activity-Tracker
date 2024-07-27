<?php

namespace Abdulbaset\ActivityTracker\Providers;

use Illuminate\Support\ServiceProvider;
use Abdulbaset\ActivityTracker\ActivityTracker;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Abdulbaset\ActivityTracker\Listeners\LogLoginActivityListener;
use Abdulbaset\ActivityTracker\Listeners\LogLoginFailedActivityListener;
use Abdulbaset\ActivityTracker\Listeners\LogLogoutActivityListener;

class ActivityTrackerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register login event listener if enabled in config
        if (config('activity-tracker.log_login_auth')) {
            Event::listen(Login::class, LogLoginActivityListener::class);
            Event::listen(Failed::class, LogLoginFailedActivityListener::class);
        }
        
        // Register logout event listener if enabled in config
        if (config('activity-tracker.log_logout_auth')) {
            Event::listen(Logout::class, LogLogoutActivityListener::class);
        }

        // Publish config file
        $this->publishes([
            __DIR__.'/../Config/activity-tracker.php' => config_path('activity-tracker.php'),
        ]);

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/activity-tracker.php', 'activity-tracker'
        );
        $this->app->singleton('activity-tracker', function ($app) {
            return new ActivityTracker();
        });
    }
}
