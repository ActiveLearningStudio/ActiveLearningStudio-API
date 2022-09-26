<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UiSubModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = UiModulePermissionResource::collection($this->uiModulePermissions)->resolve();
        $selected = '';
        foreach ($data as $row) {
            if ($row['selected'] === true) {
                $selected = $row['title'];
            }
        }

        return [
            'title' => $this->title,
            'selected' => $selected,
            'ui_module_permissions' => UiModulePermissionResource::collection($this->uiModulePermissions)
        ];
    }
}
