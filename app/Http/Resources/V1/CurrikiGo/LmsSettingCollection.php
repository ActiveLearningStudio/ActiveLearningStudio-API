<?php

namespace App\Http\Resources\V1\CurrikiGo;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LmsSettingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['settings' => $this->collection];
    }
}
