<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\ProjectUserResource;
use App\Http\Resources\V1\ProjectPlaylistShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPublicResource extends JsonResource
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
            'playlists' => ProjectPlaylistShortResource::collection($this->playlists->sortBy('order')),
            'indexing' => $this->indexing,
            'indexing_text' => $this->indexing_text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
