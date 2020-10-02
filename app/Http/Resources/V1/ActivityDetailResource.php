<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\ActivityPlaylistResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityDetailResource extends JsonResource
{

    public function __construct($resource, $data)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);

        $this->resource = $resource;
        $this->data = $data;
    }

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
            'playlist' => new ActivityPlaylistResource($this->playlist),
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'shared' => $this->shared,
            'order' => $this->order,
            'thumb_url' => $this->thumb_url,
            'subject_id' => $this->subject_id,
            'education_level_id' => $this->education_level_id,
            'h5p' => $this->data['h5p_parameters'],
            'h5p_content' => $this->h5p_content,
            'library_name' => $this->h5p_content->library->name,
            'major_version' => $this->h5p_content->library->major_version,
            'minor_version' => $this->h5p_content->library->minor_version,
            'user_name' => $this->data['user_name'],
            'user_id' => $this->data['user_id'],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
