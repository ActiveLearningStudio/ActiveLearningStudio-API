<?php

namespace App\Http\Resources\V1\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class QueueMonitorJobResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'queue' => $this->queue,
            'payload' => get_job_from_payload($this->payload),
            'failed' => $this->failed_at ?? false,
            // ' in ' needle is only get the message of exception not the full string
            'exception' =>  isset($this->exception) ? (substr($this->exception, 0, strpos($this->exception, ' in '))) : 'N/A',
            'time' => isset($this->created_at) ? Carbon::parse($this->created_at)->diffForHumans() : Carbon::parse($this->failed_at)->diffForHumans(),
        ];
    }
}
