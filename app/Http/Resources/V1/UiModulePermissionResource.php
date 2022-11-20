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

        // to set all permissions as "View" selected in case of to show all permissions
        if (isset($request->view) && $request->view === 'all') {
            if ($this->title === 'View') {
                $selected = true;
            } else {
                $selected = false;
            }
        }
        else {
            $selected = $organizationRoleType ? true : false;
        }
        // ended

        return [
            'id' => $this->id,
            'title' => $this->title,
            'selected' => $selected
        ];
    }
}
