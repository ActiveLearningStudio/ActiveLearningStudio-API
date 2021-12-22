<?php

namespace App\Jobs;

use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\CloneNotification;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\User;

class CloneStandAloneActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var
     */
    protected $token;

    /**
     * Create a new job instance.
     *
     * @param Activity $activity
     * @param $token
     */
    public function __construct(Activity $activity, $token)
    {
        $this->activity = $activity;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @param ActivityRepositoryInterface $activityRepository
     * @return void
     */
    public function handle(ActivityRepositoryInterface $activityRepository, UserRepositoryInterface $userRepository)
    {
        try {
            $activityRepository->cloneStandAloneActivity($this->activity, $this->token);
            $process = "clone";
            $message = "Your request to clone interactive video [" . $this->activity->title . "] has been completed and available";
            $user_id = $userRepository->parseToken($this->token);
            $user = User::find($user_id);
            $userName = rtrim($user->first_name . ' ' . $user->last_name, ' ');
            $user->notify(new CloneNotification($message, $process, $userName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
