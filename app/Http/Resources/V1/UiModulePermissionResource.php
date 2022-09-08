<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UiModulePermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $organizationRoleType = $this->organizationRoleTypes()->find($request->role);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'selected' => $organizationRoleType ? true : false
        ];
    }
}
