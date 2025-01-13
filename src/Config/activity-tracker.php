<?php

return [
    'enabled' => env('ACTIVITY_TRACKER_ENABLED', true),
    
    'log_method' => env('ACTIVITY_TRACKER_LOG_METHOD', 'file'),

    'log_file_path' => env('ACTIVITY_TRACKER_LOG_FILE_PATH', storage_path('logs/activity_tracker.log')),

    'database' => [
        'connection' => env('ACTIVITY_TRACKER_DB_CONNECTION', env('DB_CONNECTION', 'mysql')),

        'table' => env('ACTIVITY_TRACKER_TABLE', 'activities'),
    ],
    
    'tracking' => [
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
        'routes' => true,
        'queries' => false,
    ],

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
            'auth_token',
            'access_token',
            'refresh_token',
            'token',
            'secret',
            'password_confirmation',
            'current_password',
            'new_password',
        ],
    ],
];