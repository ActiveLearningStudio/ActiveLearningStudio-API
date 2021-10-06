<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'playlist_id' => $this->playlist_id,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'shared' => $this->shared,
            'order' => $this->order,
            'thumb_url' => $this->thumb_url,
            'subject_id' => $this->subject_id,
            'education_level_id' => $this->education_level_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Feature added after the fact for optimization
        if ($request->skipContent === "true") {
            return $data;
        }

        $data['h5p_content'] = $this->h5p_content;
        return $data;
    }
}
