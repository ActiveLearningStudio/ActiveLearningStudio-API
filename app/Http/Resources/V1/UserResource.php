<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            // 'name' => $this->name,
            'email' => $this->email,
            'organization_name' => $this->organization_name,
            'organization_type' => $this->organization_type,
            'job_title' => $this->job_title,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'website' => $this->website,
            'subscribed' => $this->subscribed,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
