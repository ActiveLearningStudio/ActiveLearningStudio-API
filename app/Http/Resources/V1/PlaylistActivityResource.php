<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'shared' => $this->shared,
            'order' => $this->order,
            'thumb_url' => $this->thumb_url,
            'subject_id' => $this->subject_id,
            'education_level_id' => $this->education_level_id,
            'h5p_content' => $this->h5p_content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
