<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultSsoSettingsResource extends JsonResource
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
            'lms_url' => $this->lms_url,
            'lms_access_token' => $this->lms_access_token,
            'site_name' => $this->site_name,
            'lms_name' => $this->lms_name,
            'lms_access_key' => $this->lms_access_key,
            'lms_access_secret' => $this->lms_access_secret,
            'description' => $this->description,
            'lti_client_id' => $this->lti_client_id,
            'guid' => $this->guid,
            'published' => $this->published,
            'organization' =>  $this->organization,
            'role' =>  $this->role,
        ];
    }
}
