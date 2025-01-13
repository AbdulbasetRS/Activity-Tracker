<?php

namespace Abdulbaset\ActivityTracker\Services;

use Abdulbaset\ActivityTracker\Enums\ActivityEventType;
use Illuminate\Support\Facades\Config;

/**
 * Class ModelTracker
 *
 * This class is used to track model events and log them to the activity log.
 * It provides a simple interface for tracking model events, with the ability to specify
 * which events should be tracked and the source of the tracking.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ModelTracker
{
    protected $logger;
    protected $trackingSource;

    public function __construct(ActivityLogger $logger, string $trackingSource = 'observer')
    {
        $this->logger = $logger;
        $this->trackingSource = $trackingSource;
    }

    protected function shouldTrackModelEvent(string $event): bool
    {
        return Config::get('activity-tracker.tracking.models.enabled') &&
               Config::get("activity-tracker.tracking.models.events.{$event}", true);
    }

    protected function filterAttributes(array $attributes, $model)
    {
        // Get excluded attributes from config
        $excludedAttributes = Config::get('activity-tracker.exclude.model_attributes', []);
        
        // Remove excluded attributes
        $filteredAttributes = array_diff_key(
            $attributes, 
            array_flip($excludedAttributes)
        );

        if (!Config::get('activity-tracker.tracking.models.full_model_data', false)) {
            $changed = array_keys($attributes);
            return array_intersect_key($filteredAttributes, array_flip($changed));
        }

        return $filteredAttributes;
    }

    protected function getDescription(string $action, $model): string
    {
        return sprintf(
            "%s %s #%s (via %s)",
            $action,
            class_basename($model),
            $model->id ?? 'new',
            $this->trackingSource
        );
    }

    public function trackCreating($model)
    {
        if (!$this->shouldTrackModelEvent('creating')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_CREATING,
            'Model Creating',
            $this->getDescription('Creating', $model),
            [
                'model_type' => get_class($model),
            ]
        );
    }

    public function trackCreated($model)
    {
        if (!$this->shouldTrackModelEvent('created')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_CREATED,
            'Model Created',
            $this->getDescription('Created', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'after' => $this->filterAttributes($model->getAttributes(), $model),
            ]
        );
    }

    public function trackUpdating($model)
    {
        if (!$this->shouldTrackModelEvent('updating')) {
            return;
        }

        if ($this->isRestoring($model)) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_UPDATING,
            'Model Updating',
            $this->getDescription('Updating', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'before' => $this->filterAttributes($model->getOriginal(), $model),
                'after' => $this->filterAttributes($model->getDirty(), $model),
            ]
        );
    }

    public function trackUpdated($model)
    {
        if (!$this->shouldTrackModelEvent('updated')) {
            return;
        }

        if ($this->isRestoring($model)) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_UPDATED,
            'Model Updated',
            $this->getDescription('Updated', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'before' => $this->filterAttributes($model->getOriginal(), $model),
                'after' => $this->filterAttributes($model->getAttributes(), $model),
            ]
        );
    }

    public function trackDeleting($model)
    {
        if (!$this->shouldTrackModelEvent('deleting')) {
            return;
        }

        if ($this->isForceDeleting($model)) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_DELETING,
            'Model Deleting',
            $this->getDescription('Soft Deleting', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
            ]
        );
    }

    public function trackDeleted($model)
    {
        if (!$this->shouldTrackModelEvent('deleted')) {
            return;
        }

        if ($this->isForceDeleting($model)) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_DELETED,
            'Model Deleted',
            $this->getDescription('Soft Deleted', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'before' => $this->filterAttributes($model->getOriginal(), $model),
            ]
        );
    }

    public function trackForceDeleting($model)
    {
        if (!$this->shouldTrackModelEvent('force_deleting')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_FORCE_DELETING,
            'Model Force Deleting',
            $this->getDescription('Force Deleting', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
            ]
        );
    }

    public function trackForceDeleted($model)
    {
        if (!$this->shouldTrackModelEvent('force_deleted')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_FORCE_DELETED,
            'Model Force Deleted',
            $this->getDescription('Force Deleted', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'before' => $this->filterAttributes($model->getOriginal(), $model),
            ]
        );
    }

    public function trackRestoring($model)
    {
        if (!$this->shouldTrackModelEvent('restoring')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_RESTORING,
            'Model Restoring',
            $this->getDescription('Restoring', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
            ]
        );
    }

    public function trackRestored($model)
    {
        if (!$this->shouldTrackModelEvent('restored')) {
            return;
        }

        $this->logger->log(
            ActivityEventType::MODEL_RESTORED,
            'Model Restored',
            $this->getDescription('Restored', $model),
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
            ]
        );
    }

    protected function isRestoring($model): bool
    {
        return method_exists($model, 'getDeletedAtColumn') &&
               $model->isDirty($model->getDeletedAtColumn()) &&
               is_null($model->deleted_at);
    }
    
    protected function isForceDeleting($model): bool
    {
        return method_exists($model, 'isForceDeleting') && $model->isForceDeleting();
    }
}