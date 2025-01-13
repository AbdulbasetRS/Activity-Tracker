<?php

namespace Abdulbaset\ActivityTracker\Enums;

/**
 * Enum representing different types of activity events in the system.
 *
 * This enum includes various events related to user authentication, model changes,
 * exception handling, route access, and database queries.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Enums
 */
enum ActivityEventType: string
{
    /**
     * Authentication events for user login and logout.
     */
    case AUTH_LOGIN = 'auth.login';
    case AUTH_LOGOUT = 'auth.logout';
    case AUTH_LOGIN_FAILED = 'auth.login_failed';
    case AUTH_LOCKOUT = 'auth.lockout';
    case AUTH_REGISTERED = 'auth.registered';
    case AUTH_VERIFIED = 'auth.verified';
    case AUTH_PASSWORD_RESET = 'auth.password_reset';

    /**
     * Model events for tracking changes to models in the application.
     */
    case MODEL_CREATING = 'model.creating';
    case MODEL_CREATED = 'model.created';
    case MODEL_UPDATING = 'model.updating';
    case MODEL_UPDATED = 'model.updated';
    case MODEL_DELETING = 'model.deleting';
    case MODEL_DELETED = 'model.deleted';
    case MODEL_FORCE_DELETING = 'model.force_deleting';
    case MODEL_FORCE_DELETED = 'model.force_deleted';
    case MODEL_RESTORING = 'model.restoring';
    case MODEL_RESTORED = 'model.restored';

    /**
     * Exception events for tracking various types of exceptions in the system.
     */
    case EXCEPTION_NOT_FOUND = 'exception.not_found';
    case EXCEPTION_UNAUTHORIZED = 'exception.unauthorized';
    case EXCEPTION_MODEL_NOT_FOUND = 'exception.model_not_found';
    case EXCEPTION_METHOD_NOT_ALLOWED = 'exception.method_not_allowed';
    case EXCEPTION_TOO_MANY_REQUESTS = 'exception.too_many_requests';
    case EXCEPTION_CONFLICT = 'exception.conflict';
    case EXCEPTION_UNPROCESSABLE_ENTITY = 'exception.unprocessable_entity';
    case EXCEPTION_ACCESS_DENIED = 'exception.access_denied';
    case EXCEPTION_GONE = 'exception.gone';
    case EXCEPTION_PRECONDITION_FAILED = 'exception.precondition_failed';
    case EXCEPTION_UNSUPPORTED_MEDIA_TYPE = 'exception.unsupported_media_type';
    case EXCEPTION_GENERAL = 'exception.general';

    /**
     * Route event for when a route is accessed in the system.
     */
    case ROUTE_ACCESSED = 'route.accessed';

    /**
     * Query event for when a database query is executed.
     */
    case QUERY_EXECUTED = 'query.executed';
}
