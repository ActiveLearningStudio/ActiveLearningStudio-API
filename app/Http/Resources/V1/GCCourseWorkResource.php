<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GCCourseWorkResource extends JsonResource
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
            'id' => $this->id,
            'course_id' => $this->courseId,
            'description' => $this->description,
            'topic_id' => $this->topicId,
            'title'  => $this->title,
            'state' => $this->state,
            'work_type' => $this->workType,
            'materials' => $this->materials,
            'max_points' => $this->maxPoints
        ];
    }
}
