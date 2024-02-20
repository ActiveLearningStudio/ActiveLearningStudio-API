<?php

namespace App\Http\Resources\V1\C2E\MediaCatalog;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaCatalogAPISettingResource extends JsonResource
{
    /**
     * @author        Asim Sarwar
     * Transform the resource into an array.     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
