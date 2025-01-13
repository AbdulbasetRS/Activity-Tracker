<?php

namespace Abdulbaset\ActivityTracker\Providers;

use Illuminate\Support\ServiceProvider;
use Abdulbaset\ActivityTracker\Services\ActivityLogger;
use Abdulbaset\ActivityTracker\ActivityTracker;
use Illuminate\Contracts\Http\Kernel;

/**
 * Class ActivityTrackerServiceProvider
 *
 * This service provider class is used to register the ActivityTracker service with Laravel's service container.
 * It also registers the ActivityTrackerExceptionHandler as the exception handler for the application.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityTrackerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Abdulbaset\ActivityTracker\Handlers\ActivityTrackerExceptionHandler::class
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/activity-tracker.php',
            'activity-tracker'
        );

        $this->app->singleton(ActivityTracker::class, function ($app) {
            return new ActivityTracker(
                $app->make(ActivityLogger::class),
            );
        }); 
    }

    public function boot(Kernel $kernel)
    {
        
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/activity-tracker.php' => config_path('activity-tracker.php'),
            ], 'config');
        }
        
        $this->registerEventSubscribers();
        $kernel->pushMiddleware(\Abdulbaset\ActivityTracker\Middleware\LogRouteAccessMiddleware::class);

    }

    protected function registerEventSubscribers()
    {
        $this->app['events']->subscribe(\Abdulbaset\ActivityTracker\Subscribers\AuthEventSubscriber::class);
        $this->app['events']->subscribe(\Abdulbaset\ActivityTracker\Subscribers\QueryEventSubscriber::class);
    }
}