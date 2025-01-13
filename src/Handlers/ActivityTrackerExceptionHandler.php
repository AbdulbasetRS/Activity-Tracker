<?php

namespace Abdulbaset\ActivityTracker\Handlers;

use Abdulbaset\ActivityTracker\Enums\ActivityEventType;
use Abdulbaset\ActivityTracker\Services\ActivityLogger;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Config;
use Throwable;

/**
 * Class ActivityTrackerExceptionHandler
 *
 * This class extends Laravel's default exception handler to provide additional functionality
 * for logging exceptions to the activity log.
 * It allows for the logging of exceptions to the activity log, with the ability to specify
 * which exceptions should be logged and the event type to use when logging them.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityTrackerExceptionHandler extends ExceptionHandler
{
    protected $activityLogger;

    public function __construct(ActivityLogger $activityLogger)
    {
        parent::__construct(app());
        $this->activityLogger = $activityLogger;
    }

    public function report(Throwable $exception)
    {
        if (Config::get('activity-tracker.tracking.exceptions')) {
            $this->handleSpecificExceptions($exception);
        }

        parent::report($exception);
    }

    protected function handleSpecificExceptions(Throwable $exception)
    {
        $exceptionMappings = [
            'route_not_found' => [
                \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
                ActivityEventType::EXCEPTION_NOT_FOUND,
                'Route Not Found',
            ],
            'unauthorized_exception' => [
                \Illuminate\Auth\Access\AuthorizationException::class, 
                ActivityEventType::EXCEPTION_UNAUTHORIZED, 
                'Unauthorized Access'
            ],
            'method_not_allowed' => [
                \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class, 
                ActivityEventType::EXCEPTION_METHOD_NOT_ALLOWED, 
                'Method Not Allowed'
            ],
            'too_many_requests' => [
                \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException::class, 
                ActivityEventType::EXCEPTION_TOO_MANY_REQUESTS, 
                'Too Many Requests'
            ],
            'conflict_exception' => [
                \Symfony\Component\HttpKernel\Exception\ConflictHttpException::class, 
                ActivityEventType::EXCEPTION_CONFLICT, 
                'Conflict Exception'
            ],
            'unprocessable_entity' => [
                \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException::class, 
                ActivityEventType::EXCEPTION_UNPROCESSABLE_ENTITY, 
                'Unprocessable Entity'
            ],
            'access_denied' => [
                \Symfony\Component\Security\Core\Exception\AccessDeniedException::class, 
                ActivityEventType::EXCEPTION_ACCESS_DENIED, 
                'Access Denied'
            ],
            'gone_exception' => [
                \Symfony\Component\HttpKernel\Exception\GoneHttpException::class, 
                ActivityEventType::EXCEPTION_GONE, 
                'Gone Exception'
            ],
            'precondition_failed' => [
                \Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException::class, 
                ActivityEventType::EXCEPTION_PRECONDITION_FAILED, 
                'Precondition Failed'
            ],
            'unsupported_media_type' => [
                \Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException::class, 
                ActivityEventType::EXCEPTION_UNSUPPORTED_MEDIA_TYPE, 
                'Unsupported Media Type'
            ],
            'other_exceptions' => [
                Throwable::class, 
                ActivityEventType::EXCEPTION_GENERAL, 
                'General Exception'
            ],
        ];

        foreach ($exceptionMappings as $configKey => [$exceptionClass, $eventType, $message]) {
            if ($this->shouldTrackException($configKey) && $exception instanceof $exceptionClass) {
                $this->logException($exception, $eventType, $message);
                return;
            }
        }
    }

    protected function logException(Throwable $exception, $eventType, $message)
    {
        $this->activityLogger->log(
            $eventType,
            $message,
            $exception->getMessage(),
            [
                'properties' => $this->getExceptionData($exception)
            ]
        );
    }

    protected function shouldTrackException(string $event): bool
    {
        return Config::get('activity-tracker.tracking.exceptions.enabled') &&
               Config::get("activity-tracker.tracking.exceptions.events.{$event}", true);
    }


    protected function getExceptionData(Throwable $exception): array
    {
        $data = [
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        if (Config::get('activity-tracker.tracking.exceptions.include_trace')) {
            $data['trace'] = $exception->getTrace();
            $data['trace_as_string'] = $exception->getTraceAsString();
        }

        return $data;
    }
}
