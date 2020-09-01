<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GCTopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'course_id' => $this->courseId,
            'topic_id' => $this->topicId,
            'name'  => $this->name,
            'course_work' => (isset($this->course_work) ? $this->course_work : [])
        ];
    }
}
