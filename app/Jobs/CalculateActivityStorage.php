<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Activity;
use App\Models\ActivityViewLog;

class CalculateActivityStorage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function handle()
    {
        // Doing nothing for now
    }
}
