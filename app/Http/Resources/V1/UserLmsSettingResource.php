<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLmsSettingResource extends JsonResource
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
            'lms_url' => $this->lms_url,
            'site_name' => $this->site_name,
            'description' => $this->description,
            'lti_client_id' => $this->lti_client_id,
            'lms_login_id' => $this->lms_login_id,
            'lms_name' => $this->lms_name,
            'lms_access_key' => $this->lms_access_key,
            'lms_access_secret' => $this->lms_access_secret,
            'description' => $this->description,
            'published' => $this->published,
        ];
    }
}
