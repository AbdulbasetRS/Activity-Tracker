<?php

namespace Abdulbaset\ActivityTracker\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ActivityTrackerResource
 *
 * This resource class is used to transform the activity log records into a JSON format.
 * It is used to format the activity log records into a JSON format, allowing for easy
 * retrieval and display of the activity log records.
 *
 * @link https://github.com/AbdulbasetRS/Activity-Tracker Link to the GitHub repository for more details.
 * @link https://www.linkedin.com/in/abdulbaset-r-sayed Link to my LinkedIn profile for professional inquiries.
 * @author Abdulbaset R. Sayed
 * @license MIT License
 * @package Abdulbaset\ActivityTracker\Facades
 */
class ActivityTrackerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'causer' => $this->whenLoaded('causer'),
            'model' => $this->whenLoaded('model'),
            'event' => $this->event,
            'before' => $this->before,
            'after' => $this->after,
            'ip' => $this->ip,
            'headers' => $this->headers,
            'query_parameters' => $this->query_parameters,
            'request_method' => $this->request_method,
            'browser' => $this->browser,
            'device_type' => $this->device_type,
            'operating_system' => $this->operating_system,
            'referring_url' => $this->referring_url,
            'current_url' => $this->current_url,
            'description' => $this->description,
            'properties' => $this->properties,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
