<?php 

namespace Abdulbaset\ActivityTracker\Observers;

use Abdulbaset\ActivityTracker\Facades\ActivityTracker;
use Illuminate\Database\Eloquent\Model;

class ActivityTrackerObserver
{
    /**
     * Log when a new model is created.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return void
     */
    public function created(Model $entity)
    {
        ActivityTracker::log($entity, 'created', 'Entity created From Observer');
    }

    /**
     * Log when a model is updated.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return void
     */
    public function updated(Model $entity)
    {
        ActivityTracker::log($entity, 'updated', 'Entity updated From Observer');
    }

    /**
     * Log when a model is deleted.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return void
     */
    public function deleted(Model $entity)
    {
        ActivityTracker::log($entity, 'deleted', 'Entity deleted From Observer');
    }

    /**
     * Log when a model is restored from soft delete.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return void
     */
    public function restored(Model $entity)
    {
        ActivityTracker::log($entity, 'restored', 'Entity restored From Observer');
    }

    /**
     * Log when a model is force deleted.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return void
     */
    public function forceDeleted(Model $entity)
    {
        ActivityTracker::log($entity, 'forceDeleted', 'Entity force deleted From Observer');
    }
}
