<?php

namespace App\Http\Resources\V1\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class LmsSettingResource extends JsonResource
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
            'lti_client_id' => $this->lti_client_id,
            'lms_login_id' => $this->lms_login_id,
            'lms_name' => $this->lms_name,
            'lms_access_key' => $this->lms_access_key,
            'lms_access_secret' => $this->lms_access_secret,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'user' =>  new UserResource($this->user),
        ];
    }
}
