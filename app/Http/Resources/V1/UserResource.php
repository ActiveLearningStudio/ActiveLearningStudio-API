<?php

namespace App\Http\Resources\V1;

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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            // 'name' => $this->name,
            'email' => $this->email,
            'organization_name' => $this->organization_name,
            'organization_type' => $this->organization_type,
            'job_title' => $this->job_title,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'website' => $this->website,
            'subscribed' => $this->subscribed,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'organization_role' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->display_name;
            }),
            'organization_role_id' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->role->id;
            }),
            'organization_joined_at' => $this->whenPivotLoaded('organization_user_roles', function () {
                return $this->pivot->created_at ? $this->pivot->created_at->format(config('constants.default-date-format')) : $this->pivot->created_at;
            }),
            'projects_count' => isset($this->projects_count) ? $this->projects_count : 0,
            'teams_count' => isset($this->teams_count) ? $this->teams_count : 0,
            'groups_count' => isset($this->groups_count) ? $this->groups_count : 0
        ];
    }
}
