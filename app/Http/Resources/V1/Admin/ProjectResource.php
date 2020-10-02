<?php

namespace App\Http\Resources\V1\Admin;

use App\Http\Resources\V1\Admin\ProjectUserResource;
use App\Http\Resources\V1\ProjectPlaylistResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'thumb_url' => $this->thumb_url,
            'shared' => $this->shared,
            'starter_project' => $this->starter_project,
            'cloned_from' => $this->cloned_from,
            'clone_ctr' => $this->clone_ctr,
            'users' => ProjectUserResource::collection($this->whenLoaded('users')),
            'playlists' => ProjectPlaylistResource::collection($this->whenLoaded('playlists')),
            'is_public' => $this->is_public,
            'elasticsearch' => $this->elasticsearch,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
