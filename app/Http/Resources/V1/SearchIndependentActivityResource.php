<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\SearchUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchIndependentActivityResource extends JsonResource
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
            'id' => $this->entity_id,
            'thumb_url' => $this->thumb_url,
            'title' => $this->name,
            'description' => $this->when(isset($this->description), $this->description),
            'activity_type' => $this->activity_title,
            'created_at' => $this->created_at,
            'user' => [
                "email" => $this->email,
                "first_name" => $this->first_name,
                "id" => $this->user_id,
                "last_name" => $this->last_name
            ],
            'organization' => [
                "id" => $this->org_id,
                "name" => $this->organization_name,
                "description" => $this->org_description,
                "image" => $this->org_image
            ]
        ];
    }
}
