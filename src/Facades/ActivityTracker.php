<?php

namespace Abdulbaset\ActivityTracker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for ActivityTracker.
 *
 * This class acts as a facade for the ActivityTracker service, allowing easy access
 * to the core functionality of the ActivityTracker class in a static manner.
 * It uses Laravel's Facade system to provide a simple and expressive syntax.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityTracker extends Facade
{
    /**
     * Get the facade accessor for the ActivityTracker service.
     *
     * This method returns the class name of the service to be resolved by Laravel's service container.
     * The service container will bind the class to an instance, which is then accessible via the facade.
     *
     * @return string The class name of the service to resolve.
     */
    protected static function getFacadeAccessor()
    {
        return \Abdulbaset\ActivityTracker\ActivityTracker::class;
    }
}
