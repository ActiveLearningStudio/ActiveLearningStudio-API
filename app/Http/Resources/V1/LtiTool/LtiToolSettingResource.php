<?php

namespace App\Http\Resources\V1\LtiTool;

use Illuminate\Http\Resources\Json\JsonResource;

class LtiToolSettingResource extends JsonResource
{
    /**
     * @author        Asim Sarwar
     * Date           11-10-2021
     * Transform the resource into an array.     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
