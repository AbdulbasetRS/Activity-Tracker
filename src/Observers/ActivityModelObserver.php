<?php

namespace Abdulbaset\ActivityTracker\Observers;

use Abdulbaset\ActivityTracker\Services\ModelTracker;

/**
 * Class ActivityModelObserver
 *
 * This class is used to observe the model events of the application.
 * It is used to track the model events and log them to the activity log.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityModelObserver
{
    protected $modelTracker;

    public function __construct(ModelTracker $modelTracker)
    {
        $this->modelTracker = $modelTracker;
    }
    
    public function creating($model)
    {
        $this->modelTracker->trackCreating($model);
    }

    public function created($model)
    {
        $this->modelTracker->trackCreated($model);
    }

    public function updating($model)
    {
        $this->modelTracker->trackUpdating($model);
    }

    public function updated($model)
    {
        $this->modelTracker->trackUpdated($model);
    }

    public function deleting($model)
    {
        $this->modelTracker->trackDeleting($model);
    }

    public function deleted($model)
    {
        $this->modelTracker->trackDeleted($model);
    }

    public function forceDeleting($model)
    {
        $this->modelTracker->trackForceDeleting($model);
    }

    public function forceDeleted($model)
    {
        $this->modelTracker->trackForceDeleted($model);
    }

    public function restoring($model)
    {
        $this->modelTracker->trackRestoring($model);
    }

    public function restored($model)
    {
        $this->modelTracker->trackRestored($model);
    }
}