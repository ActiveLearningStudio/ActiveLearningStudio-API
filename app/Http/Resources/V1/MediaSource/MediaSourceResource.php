<?php

namespace App\Http\Resources\V1\MediaSource;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaSourceResource extends JsonResource
{
    /**
     * Transform the resource into an array
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
