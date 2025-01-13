<?php

namespace Abdulbaset\ActivityTracker\Subscribers;

use Abdulbaset\ActivityTracker\Enums\ActivityEventType;
use Abdulbaset\ActivityTracker\Services\ActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Config;

/**
 * Class AuthEventSubscriber
 *
 * This class is used to subscribe to auth events and log them to the activity log.
 * It provides a simple interface for logging auth events, with the ability to specify
 * which events should be tracked and the source of the tracking.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class AuthEventSubscriber
{
    protected $logger;

    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    protected function shouldTrackAuthEvent(string $event): bool
    {
        return Config::get('activity-tracker.tracking.auth.enabled') &&
               Config::get("activity-tracker.tracking.auth.events.{$event}", true);
    }

    public function handleLogin(Login $event)
    {
        if (!$this->shouldTrackAuthEvent('login')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::AUTH_LOGIN,
            'User Login',
            "User {$event->user->email} logged in successfully"
        );
    }

    public function handleLogout(Logout $event)
    {
        if (!$this->shouldTrackAuthEvent('logout')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::AUTH_LOGOUT,
            'User Logout',
            "User {$event->user->email} logged out"
        );
    }

    public function handleFailed(Failed $event)
    {
        if (!$this->shouldTrackAuthEvent('failed')) {
            return;
        }

        $identifier = $this->getLoginIdentifier($event->credentials);

        $this->logger->log(
            ActivityEventType::AUTH_LOGIN_FAILED,
            'Login Failed',
            "Failed login attempt for user {$identifier}"
        );
    }

    public function handleLockout($event)
    {
        if (!$this->shouldTrackAuthEvent('lockout')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::AUTH_LOCKOUT,
            'Account Lockout',
            "Account locked due to too many failed attempts"
        );
    }

    public function handleRegistered(Registered $event)
    {
        if (!$this->shouldTrackAuthEvent('registered')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::AUTH_REGISTERED,
            'User Registered',
            "New user registered: {$event->user->email}"
        );
    }

    public function handleVerified(Verified $event)
    {
        if (!$this->shouldTrackAuthEvent('verified')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::AUTH_VERIFIED,
            'Email Verified',
            "User email verified: {$event->user->email}"
        );
    }

    public function handlePasswordReset(PasswordReset $event)
    {
        if (!$this->shouldTrackAuthEvent('password_reset')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::AUTH_PASSWORD_RESET,
            'Password Reset',
            "Password reset for user: {$event->user->email}"
        );
    }

    protected function getLoginIdentifier(array $credentials): string
    {
        $possibleFields = ['email', 'phone', 'username'];

        foreach ($possibleFields as $field) {
            if (isset($credentials[$field])) {
                return $credentials[$field];
            }
        }

        return 'unknown identifier';
    }

    public function subscribe($events)
    {
        $events->listen(Login::class, [self::class, 'handleLogin']);
        $events->listen(Logout::class, [self::class, 'handleLogout']);
        $events->listen(Failed::class, [self::class, 'handleFailed']);
        $events->listen(Lockout::class, [self::class, 'handleLockout']);
        $events->listen(Registered::class, [self::class, 'handleRegistered']);
        $events->listen(Verified::class, [self::class, 'handleVerified']);
        $events->listen(PasswordReset::class, [self::class, 'handlePasswordReset']);
    }
}