<?php

namespace App\Http\Resources\V1\C2E\MediaCatalog;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCatalogAPISettingCollection extends ResourceCollection
{
    /**
     * @author        Asim Sarwar
     * Transform the resource collection into an array.     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
