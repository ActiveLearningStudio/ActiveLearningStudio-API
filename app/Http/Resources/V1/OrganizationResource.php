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
            'parent' =>  new OrganizationResource($this->whenLoaded('parent')),
            'projects' =>  ProjectResource::collection($this->whenLoaded('projects')),
            'children' =>  OrganizationResource::collection($this->whenLoaded('children')),
            'users' =>  UserResource::collection($this->whenLoaded('users')),
            'admins' =>  UserResource::collection($this->whenLoaded('admins')),
            'image' => $this->image,
            'domain' => $this->domain,
            'organization_role' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->display_name;
            }),
            'default_organization' => $this->when(auth()->user() && auth()->user()->default_organization == $this->id, true),
            'projects_count' => $this->when($this->projects_count, $this->projects_count),
            'suborganization_count' => $this->when($this->children_count, $this->children_count),
            'users_count' => $this->when($this->users_count, $this->users_count),
        ];
    }
}
