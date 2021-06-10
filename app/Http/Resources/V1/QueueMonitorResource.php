<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class QueueMonitorResource extends JsonResource
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
            'job_id' => $this->job_id,
            'name' => $this->getBasename(),
            'queue' => $this->queue,
            'started_at' => $this->started_at->diffForHumans(),
            'is_finished' => $this->isFinished(),
            'time_elapsed' => sprintf('%02.2f', (float) $this->time_elapsed). ' s',
            'failed' => $this->failed,
            'attempt' => $this->attempt,
            'exception_message' => $this->exception_message,
        ];
    }
}
