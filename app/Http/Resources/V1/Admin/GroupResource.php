<?php

namespace App\Http\Resources\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class GroupResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->format('d-M-Y') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d-M-Y') : null,
        ];
    }
}
