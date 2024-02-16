<?php

namespace App\Http\Resources\V1\C2E\Publisher;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PublisherCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
