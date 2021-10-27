<?php

namespace App\Http\Resources\V1\LtiTool;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LtiToolSettingCollection extends ResourceCollection
{
    /**
     * @author        Asim Sarwar
     * Date           11-10-2021
     * Transform the resource collection into an array.     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['lti_tool_settings' => $this->collection];
    }
}
