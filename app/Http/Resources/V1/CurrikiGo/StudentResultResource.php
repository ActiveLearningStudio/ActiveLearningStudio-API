<?php

namespace App\Http\Resources\V1\CurrikiGo;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
