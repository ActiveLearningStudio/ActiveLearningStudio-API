<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExportResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'organization_name' => $this->organization_name,
            'organization_type' => $this->organization_type,
            'job_title' => $this->job_title,
            'address' => $this->address,
            'website' => $this->website,
            'organization_role' => $this->whenPivotLoaded('organization_user_roles', function () {
                return new OrganizationRoleResource($this->pivot->role);
            })        ];
    }
}
