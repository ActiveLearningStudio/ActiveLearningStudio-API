<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProjectResource extends JsonResource
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
            'organization_visibility_type_id' => $this->organization_visibility_type_id,
            'name' => $this->name,
            'description' => $this->description,
            'thumb_url' => $this->thumb_url,
            'shared' => $this->shared,
            'starter_project' => $this->starter_project,
            'order' => $this->order,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'indexing' => $this->indexing,
            'is_user_starter' => $this->is_user_starter,
            'indexing_text' => $this->indexing_text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'users' => $this->users,
            'team' => $this->team,
        ];
    }
}
