<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndependentH5pActivityResource extends JsonResource
{
    private $h5p_data = null;

    public function __construct($resource, $h5p_data)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->h5p_data = $h5p_data;
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
            'subject_id' => $this->subject_id,
            'education_level_id' => $this->education_level_id,
            'h5p' => $this->h5p_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->resource->brightcoveData) {
            $response['brightcoveData'] = $this->resource->brightcoveData;
        }

        return $response;
    }
}
