# Changelog

All notable changes to `activity-tracker` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-01-13

### Added
- New `ActivityTrackerResource` for API responses
- Comprehensive exception tracking with detailed configuration
- Enhanced query logging with SQL, bindings, and execution time
- Device detection improvements using `jenssegers/agent`
- Support for Laravel 10
- Type hints and return types across all classes
- Enum support for event types via `ActivityEventType`
- Better configuration structure for auth, models, and exceptions

### Changed
- Improved configuration structure with nested settings
- Enhanced model tracking with before/after state capture
- Optimized database schema
- Refactored service classes for better separation of concerns
- Improved error handling and logging
- Better handling of query parameters and headers

### Fixed
- Fixed issues with model event tracking
- Improved handling of null values in JSON columns
- Better error handling in activity logger
- Fixed inconsistencies in event naming
- Improved type safety throughout the package

### Security
- Enhanced sensitive data filtering
- Improved configuration for excluding sensitive model attributes
- Better handling of request data sanitization

### Dependencies
- Updated minimum PHP version requirement to 7.4
- Added support for Laravel 9 and 10
- Updated `jenssegers/agent` to version 2.6

### Documentation
- Completely rewritten documentation
- Added comprehensive configuration examples
- Improved installation instructions
- Better examples for common use cases

### Migration Guide from 1.x to 2.0

#### Database Changes
1. Run the new migration to update the table structure
2. Some JSON columns have been optimized

#### Configuration Changes
1. Publish the new configuration file:
```bash
php artisan vendor:publish --provider="Abdulbaset\ActivityTracker\Providers\ActivityTrackerServiceProvider"
```

2. Update your environment variables:
```env
ACTIVITY_TRACKER_ENABLED=true
ACTIVITY_TRACKER_LOG_METHOD=database
ACTIVITY_TRACKER_LOG_FILE_PATH=storage/logs/activity_tracker.log
```

### Breaking Changes
1. Removed helper functions in favor of facade
2. Changed database table name
3. Modified database schema
4. Updated configuration structure