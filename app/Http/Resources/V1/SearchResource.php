<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\SearchUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->entity_id,
            'project_id' => $this->project_id,
            'playlist_id' => $this->when(isset($this->playlist_id), $this->playlist_id),
            'thumb_url' => $this->thumb_url,
            'title' => $this->name,
            'description' => $this->when(isset($this->description), $this->description),
            'favored' => $this->when(isset($this->favored), $this->favored),
            'model' => $this->entity,
            'created_at' => $this->created_at,
            'user' => $this->first_name . ' ' . $this->last_name
        ];
    }
}
