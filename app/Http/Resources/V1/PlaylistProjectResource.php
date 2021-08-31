<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\ProjectUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistProjectResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'users' => ProjectUserResource::collection($this->users),
            'team' => $this->team,
        ];
    }
}
