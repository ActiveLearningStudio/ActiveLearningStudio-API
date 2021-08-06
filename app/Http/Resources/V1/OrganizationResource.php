<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'account_id' => $this->account_id,
            'api_key' => $this->api_key,
            'unit_path' => $this->unit_path,
            'parent' =>  new OrganizationResource($this->whenLoaded('parent')),
            'projects' =>  ProjectResource::collection($this->whenLoaded('projects')),
            'children' =>  OrganizationResource::collection($this->whenLoaded('children')),
            'users' =>  UserResource::collection($this->whenLoaded('users')),
            'admins' =>  UserResource::collection($this->whenLoaded('admins')),
            'image' => $this->image,
            'domain' => $this->domain,
            'self_registration' => $this->self_registration,
            'organization_role' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->display_name;
            }),
            'organization_role_id' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->id;
            }),
            'projects_count' => isset($this->projects_count) ? $this->projects_count : 0,
            'suborganization_count' => isset($this->children_count) ? $this->children_count : 0,
            'users_count' => isset($this->users_count) ? $this->users_count : 0,
            'groups_count' => isset($this->groups_count) ? $this->groups_count : 0,
            'teams_count' => isset($this->teams_count) ? $this->teams_count : 0,
        ];
    }
}
