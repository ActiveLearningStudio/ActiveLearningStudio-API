<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\SearchUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchPostgreSqlResource extends JsonResource
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
            'project_id' => $this->project_id,
            'playlist_id' => $this->when(isset($this->playlist_id), $this->playlist_id),
            'thumb_url' => $this->thumb_url,
            'title' => $this->name,
            'description' => $this->when(isset($this->description), $this->description),
            'favored' => $this->when(isset($this->favored), $this->favored),
            'model' => $this->entity,
            'created_at' => $this->created_at,
            'team_name' => $this->when(isset($this->team_name), $this->team_name),
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
