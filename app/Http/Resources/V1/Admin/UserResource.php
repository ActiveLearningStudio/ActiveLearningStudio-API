<?php

namespace App\Http\Resources\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
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
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'job_title' => $this->job_title,
            'organization_type' => $this->organization_type,
            'is_admin' => $this->isAdmin(), // needed to show on admin weather user is admin or not
            'organization_name' => $this->organization_name,
            'projects' =>  ProjectResource::collection($this->whenLoaded('projects')),
            'organization_role' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->display_name;
            }),
            'organization_role_id' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->id;
            }),
            'created_at' => $this->created_at ? $this->created_at->format('d-M-Y') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d-M-Y') : null,
        ];
    }
}
