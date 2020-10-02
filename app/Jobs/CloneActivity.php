<?php

namespace App\Jobs;

use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Models\Activity;
use App\Models\Playlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloneActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * @var Activity
     */
    protected $activity;
    
    /**
     * @var Playlist
     */
    protected $playlist;
    
    /**
     * @var
     */
    protected $token;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist, Activity $activity, $token)
    {
        $this->playlist = $playlist;
        $this->activity = $activity;
        $this->token = $token;
    }

    /**
     * Execute the job.
     * @param ActivityRepositoryInterface $activityRepository
     * @return void
     */
    public function handle(ActivityRepositoryInterface $activityRepository)
    {
        $activityRepository->clone($this->playlist, $this->activity, $this->token);
    }
}
