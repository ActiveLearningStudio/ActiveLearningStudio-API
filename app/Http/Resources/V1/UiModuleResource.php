<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UiModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subModules = UiSubModuleResource::collection($this->uiSubModules)->resolve();
        $count = count($subModules);
        $general = '';
        $viewCount = $editCount = $noneCount = 0;
        foreach ($subModules as $row) {
            if ($row['selected'] === 'View') {
                $viewCount++;
            }
            if ($row['selected'] === 'Edit' ) {
                $editCount++;
            }
        }

        if ($viewCount === $count) {
            $general = 'View';
        } else if ($editCount === $count) {
            $general = 'Edit';
        }

        return [
            'title' => $this->title,
            'general' => $general,
            'ui_sub_modules' => UiSubModuleResource::collection($this->uiSubModules)
        ];
    }
}
