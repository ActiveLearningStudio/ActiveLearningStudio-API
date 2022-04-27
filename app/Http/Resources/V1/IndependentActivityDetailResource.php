<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndependentActivityDetailResource extends JsonResource
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
        $response = [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'description' => $this->description,
            'shared' => $this->shared,
            'order' => $this->order,
            'thumb_url' => $this->thumb_url,
            'subjects' => SubjectResource::collection($this->subjects),
            'education_levels' => EducationLevelResource::collection($this->educationLevels),
            'author_tags' => AuthorTagResource::collection($this->authorTags),
            'h5p' => $this->data['h5p_parameters'],
            'h5p_content' => $this->h5p_content,
            'library_name' => $this->h5p_content->library->name,
            'major_version' => $this->h5p_content->library->major_version,
            'minor_version' => $this->h5p_content->library->minor_version,
            'user_name' => $this->data['user_name'],
            'user_id' => $this->data['user_id'],
            'source_type' => $this->source_type,
            'source_url' => $this->source_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'source_type' => $this->source_type,
            'source_url' => $this->source_url,
        ];

        if ($this->resource->brightcoveData) {
            $response['brightcoveData'] = $this->resource->brightcoveData;
        }

        return $response;
    }
}
