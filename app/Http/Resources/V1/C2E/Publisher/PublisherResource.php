<?php

namespace App\Http\Resources\V1\C2E\Publisher;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\UserResource;

class PublisherResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->url,
            'key' => $this->key,
            'organization' =>  $this->organization,
            'user' =>  new UserResource($this->user),
            'project_visibility' =>  $this->project_visibility,
            'playlist_visibility' =>  $this->playlist_visibility,
            'activity_visibility' =>  $this->activity_visibility,
        ];
    }
}
