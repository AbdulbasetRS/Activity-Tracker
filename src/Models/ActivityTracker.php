<?php

namespace Abdulbaset\ActivityTracker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class ActivityTracker
 *
 * This class is the model for the activity log table in the database.
 * It is used to interact with the activity log table in the database, allowing for the
 * retrieval and storage of activity log records.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityTracker extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = Config::get('activity-tracker.database.table', 'activities');
    }

    protected $table = 'activities';

    protected $guarded = [];

    protected $casts = [
        'headers' => 'json',
        'query_parameters' => 'json',
        'before' => 'json',
        'after' => 'json',
        'properties' => 'json',
    ];

    public function causer()
    {
        return $this->morphTo();
    }

    public function model()
    {
        return $this->morphTo();
    }
}