<?php

namespace Abdulbaset\ActivityTracker\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Abdulbaset\ActivityTracker\Enums\ActivityEventType;
use Abdulbaset\ActivityTracker\Services\ActivityLogger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

/**
 * Class LogRouteAccessMiddleware
 *
 * This middleware class is used to log the access of routes in the application.
 * It logs the access of routes to the activity log, with the ability to exclude
 * specific routes from being logged.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class LogRouteAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->getStatusCode() >= 400) {
            return $response;
        }

        if ($this->shouldLogRoute($request)) {
            $activityLogger = app(ActivityLogger::class);

            $activityLogger->log(
                ActivityEventType::ROUTE_ACCESSED,
                'Route Accessed',
                "Accessed route: {$request->path()}",
                [
                    'request_method' => $request->method()
                ]
            );
        }

        return $response;
    }

    protected function shouldLogRoute(Request $request): bool
    {
        $excludedPatterns = Config::get('activity-tracker.exclude.routes', []);

        foreach ($excludedPatterns as $pattern) {
            if (Str::is($pattern, $request->path())) {
                return false;
            }
        }

        return true;
    }
}
