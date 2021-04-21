<?php

namespace App\Http\Resources\V1\Admin;

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
            'image' => $this->image,
            'domain' => $this->domain,
        ];
    }
}
