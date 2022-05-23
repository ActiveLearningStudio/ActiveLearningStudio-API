<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ExportedIndependentActivitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (!isset($this->data['file_name'])) return; // skip if file_name param not exist in table

        if (!file_exists(storage_path('app/public/exports/' . $this->data['file_name']))) return; // skip if file not exist in directory
        
        return [
            'id' => $this->id,
            'project' =>isset($this->data['project']) ? $this->data['project'] : "",
            'created_at'=> Carbon::parse($this->created_at)
            ->format(config('constants.default-date-format')),
            'will_expire_on'=> Carbon::parse($this->created_at)->addDays(config('constants.default-exported-independent-activities-days-limit'))
            ->format(config('constants.default-date-format')),
            'link'=>isset($this->data['link']) ? $this->data['link'] : "",
            'organization_id' => $this->organization_id

        ];
    }

}
