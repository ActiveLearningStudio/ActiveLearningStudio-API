<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\UserResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'thumb_url' => $this->when(isset($this->thumb_url), $this->thumb_url),
            'title' => (isset($this->title) ? $this->title : $this->name ),
            'description' => $this->when(isset($this->description), $this->description),
            'model' => $this->modelType,
            'user' => $this->user,
        ];
    }
}
