![Thumbnail](docs/thumbnail.png)

# Activity Tracker Package

A comprehensive activity tracking package for Laravel applications that logs various types of activities including authentication events, model changes, exceptions, route access, and database queries.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abdulbaset/activity-tracker.svg)](https://packagist.org/packages/abdulbaset/activity-tracker)
[![Total Downloads](https://img.shields.io/packagist/dt/abdulbaset/activity-tracker.svg)](https://packagist.org/packages/abdulbaset/activity-tracker)
[![License](https://img.shields.io/packagist/l/abdulbaset/activity-tracker.svg)](https://packagist.org/packages/abdulbaset/activity-tracker)

## Features

- 🔐 **Authentication Tracking**: Login, logout, failed attempts, lockouts, registrations, email verifications, and password resets
- 📝 **Model Changes**: Create, update, delete, force delete, and restore operations
- ⚠️ **Exception Logging**: Various HTTP and system exceptions
- 🛣️ **Route Access**: Track accessed routes with request details
- 🔍 **Query Logging**: Monitor database queries with execution time
- 📱 **Device Information**: Browser, operating system, and device type detection
- 🔄 **Flexible Storage**: Store logs in database or files
- ⚙️ **Highly Configurable**: Extensive configuration options for each feature

## Changelog

All notable changes to this project will be documented in the [CHANGELOG.md](docs/CHANGELOG.md) file.  
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Installation

You can install the package via composer:

```bash
composer require abdulbaset/activity-tracker
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Abdulbaset\ActivityTracker\Providers\ActivityTrackerServiceProvider"
```

This will create a `config/activity-tracker.php` file where you can configure various aspects of the package.

## Migrations

After installing the package, you need to run the migrations to create the required database tables. To do this, use the following Artisan command:

```bash
php artisan migrate --path=vendor/abdulbaset/activity-tracker/src/database/migrations
```

## Basic Usage

### Manual Logging

```php
use ActivityTracker;

// Simple activity logging
ActivityTracker::log('User Profile', 'User updated their profile');

// Detailed activity logging
ActivityTracker::log(
    'Order Created',
    'New order #123 was created',
    [
        'properties' => [
            'order_id' => 123,
            'amount' => 99.99
        ]
    ]
);
```

### Model Tracking

Add the `Trackable` trait to your model:

```php
use Abdulbaset\ActivityTracker\Traits\Trackable;

class User extends Model
{
    use Trackable;
}
```

## Configuration Options

### General Configuration

```php
return [
    'enabled' => env('ACTIVITY_TRACKER_ENABLED', true),
    'log_method' => env('ACTIVITY_TRACKER_LOG_METHOD', 'database'), // 'database' or 'file'
    'log_file_path' => storage_path('logs/activity_tracker.log'),
];
```

### Authentication Events

```php
'auth' => [
    'enabled' => true,
    'events' => [
        'login' => true,
        'logout' => true,
        'failed' => true,
        'lockout' => true,
        'registered' => true,
        'verified' => true,
        'password_reset' => true,
    ],
],
```

### Model Events

```php
'models' => [
    'enabled' => true,
    'events' => [
        'creating' => true,
        'created' => true,
        'updating' => true,
        'updated' => true,
        'deleting' => true,
        'deleted' => true,
        'force_deleting' => true,
        'force_deleted' => true,
        'restoring' => true,
        'restored' => true,
    ],
    'full_model_data' => false,
],
```

### Exception Tracking

```php
'exceptions' => [
    'enabled' => true,
    'events' => [
        'not_found' => true,
        'unauthorized' => true,
        'method_not_allowed' => true,
        'too_many_requests' => true,
        'conflict' => true,
        'unprocessable_entity' => true,
        'access_denied' => true,
        'gone' => true,
        'precondition_failed' => true,
        'unsupported_media_type' => true,
        'general' => true,
    ],
    'include_trace' => false,
],
```

### Exclusions

```php
'exclude' => [
    'routes' => [
        'horizon*',
        'nova*',
        '_debugbar*',
    ],
    'models' => [],
    'model_attributes' => [
        'password',
        'remember_token',
        'api_token',
        // ... other sensitive fields
    ],
],
```

## Database Schema

The package creates an `activities` table with the following structure:

| Column           | Type      | Description                  |
| ---------------- | --------- | ---------------------------- |
| id               | bigint    | Primary key                  |
| subject          | string    | Activity subject             |
| causer_type      | string    | Model type of the causer     |
| causer_id        | bigint    | ID of the causer             |
| model_type       | string    | Related model type           |
| model_id         | bigint    | Related model ID             |
| event            | string    | Event type                   |
| before           | json      | Previous state (for updates) |
| after            | json      | New state (for updates)      |
| ip               | string    | IP address                   |
| headers          | json      | Request headers              |
| query_parameters | json      | URL query parameters         |
| request_method   | string    | HTTP method                  |
| browser          | string    | Browser information          |
| device_type      | string    | Device type                  |
| operating_system | string    | OS information               |
| referring_url    | string    | Referrer URL                 |
| current_url      | string    | Current URL                  |
| description      | text      | Activity description         |
| properties       | json      | Additional properties        |
| created_at       | timestamp | Creation timestamp           |
| updated_at       | timestamp | Update timestamp             |

## API Resource

The package includes an API resource for formatting activity logs:

```php
use Abdulbaset\ActivityTracker\Resources\ActivityTrackerResource;

// In your controller
public function index()
{
    $activities = ActivityTracker::latest()->paginate();
    return ActivityTrackerResource::collection($activities);
}
```

## Events

The package tracks the following events:

### Authentication Events

- Login
- Logout
- Failed Login
- Lockout
- Registration
- Email Verification
- Password Reset

### Model Events

- Creating/Created
- Updating/Updated
- Deleting/Deleted
- Force Deleting/Force Deleted
- Restoring/Restored

### Exception Events

- Not Found
- Unauthorized
- Method Not Allowed
- Too Many Requests
- Conflict
- Unprocessable Entity
- Access Denied
- Gone
- Precondition Failed
- Unsupported Media Type
- General Exceptions

## Security

If you discover any security-related issues, please email AbdulbasetRedaSayedHF@Gmail.com instead of using the issue tracker.

## Credits

- [Abdulbaset R. Sayed](https://github.com/abdulbasetsayed)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

For support:

- Email: AbdulbasetRedaSayedHF@Gmail.com
- Create an issue in the GitHub repository

## Donations 💖

Maintaining this package takes time and effort. If you’d like to support its development and keep it growing, you can:

- 🌟 Star this repository
- 📢 Sharing it with others
- 🛠️ Contribute by reporting issues or suggesting features
- ☕ [Buy me a coffee](https://buymeacoffee.com/abdulbaset)
- ❤️ Become a sponsor on [GitHub Sponsors](https://github.com/sponsors/AbdulbasetRS)
- 💵 Make a one-time donation via [PayPal](https://paypal.me/abdulbasetrs)

Your support means a lot to me! Thank you for making this possible. 🙏
