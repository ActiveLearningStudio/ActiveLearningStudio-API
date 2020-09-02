<?php

namespace App\Http\Resources\V1\CurrikiGo;

use Illuminate\Http\Resources\Json\JsonResource;

class CanvasPlaylistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'position' => $this->position,
            'type' => $this->type,
            'module_id' => $this->module_id,
            'content_id' => $this->content_id,
            'html_url' => $this->html_url,
            'url' => $this->url,
            'external_url' => $this->external_url,
            'new_tab' => $this->new_tab,
            'completion_requirement' => (array) $this->completion_requirement,
            'published' => $this->published,
            'indent' => $this->indent
        ];
    }
}
