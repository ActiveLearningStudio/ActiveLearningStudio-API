<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'thumb_url' => (isset($this->thumb_url) ? $this->thumb_url : $this->thumbUrl),
            'title' => (isset($this->title) ? $this->title : $this->name),
            'description' => $this->when(isset($this->description), $this->description),
            'favored' => $this->when(isset($this->favored), $this->favored),
            'model' => $this->modelType,
            'user' => new UserResource($this->user)
        ];
    }
}
