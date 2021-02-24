<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'organization_id' => $this->organization_id,
            'name' => $this->name,
            'description' => $this->description,
            'indexing' => $this->indexing,
            'users' => GroupUserResource::collection($this->users),
            'invited_emails' => $this->invitedUsers,
            'projects' => GroupProjectResource::collection($this->projects),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
