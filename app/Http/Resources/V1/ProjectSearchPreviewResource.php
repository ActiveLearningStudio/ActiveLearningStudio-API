<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\PlaylistSearchPreviewResource;

class ProjectSearchPreviewResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'thumb_url' => $this->thumb_url,
            'shared' => $this->shared,
            'indexing' => $this->indexing,
            'indexing_text' => $this->indexing_text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'playlists' => PlaylistSearchPreviewResource::collection($this->playlists),
        ];
    }
}
