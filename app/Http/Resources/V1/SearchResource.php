<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\SearchUserResource;
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
        if ($this->playlist && $this->playlist->project) {
            $user = $this->playlist->project->users()->first();
        } elseif ($this->project) {
            $user = $this->project->users()->first();
        } elseif ($this->users) {
            $user = $this->users->first();
        }

        return [
            'id' => $this->id,
            'project_id' => isset($this->playlist) ? $this->playlist->project_id : (isset($this->project_id) ? $this->project_id : $this->id),
            'playlist_id' => $this->when(isset($this->playlist_id), $this->playlist_id),
            'thumb_url' => (isset($this->thumb_url) ? $this->thumb_url : $this->thumbUrl),
            'title' => (isset($this->title) ? $this->title : $this->name),
            'description' => $this->when(isset($this->description), $this->description),
            'content' => $this->when(isset($this->content), $this->content),
            'favored' => $this->when(isset($this->favored), $this->favored),
            'model' => $this->modelType,
            'created_at' => $this->created_at,
            'user' => isset($user) ? new SearchUserResource($user) : null
        ];
    }
}
