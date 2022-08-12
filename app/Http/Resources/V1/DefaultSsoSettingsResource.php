<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultSsoSettingsResource extends JsonResource
{


    public function __construct($resource, $safe = false)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);

        $this->resource = $resource;
        $this->safe = $safe;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'lms_url' => $this->lms_url,
            'lms_access_token' => $this->lms_access_token,
            'site_name' => $this->site_name,
            'lms_name' => $this->lms_name,
            'lms_access_key' => $this->lms_access_key,
            'description' => $this->description,
            'lti_client_id' => $this->lti_client_id,
            'guid' => $this->guid,
            'published' => $this->published,
            'organization' =>  $this->organization,
            'role' =>  $this->role,
        ];

        if ($this->safe)
            return $array;

        $array['lms_access_secret'] = $this->lms_access_secret;
        return $array;
    }
}
