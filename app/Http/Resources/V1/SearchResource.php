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
        if (isset($this->playlist_id)) {
            $author = $this->playlist->project->user;
            $projectId = $this->playlist->project_id;
        } elseif (isset($this->project_id)) {
            $author = $this->project->user;
            $projectId = $this->project_id;
        } else {
            $author = $this->user;
            $projectId = $this->id;
        }

        return [
            'id' => $this->id,
            'project_id' => $projectId,
            'playlist_id' => $this->when(isset($this->playlist_id), $this->playlist_id),
            'thumb_url' => (isset($this->thumb_url) ? $this->thumb_url : $this->thumbUrl),
            'title' => (isset($this->title) ? $this->title : $this->name),
            'description' => $this->when(isset($this->description), $this->description),
            'content' => $this->when(isset($this->content), $this->content),
            'favored' => $this->when(isset($this->favored), $this->favored),
            'model' => $this->modelType,
            'created_at' => $this->created_at,
            'author' =>  new SearchUserResource($author)
        ];
    }
}
