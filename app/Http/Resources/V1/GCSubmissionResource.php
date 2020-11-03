<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GCSubmissionResource extends JsonResource
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
            'coursework_id' => $this->courseWorkId,
            'state' => $this->state,
            'assigned_grade' => $this->assignedGrade
        ];
    }
}
