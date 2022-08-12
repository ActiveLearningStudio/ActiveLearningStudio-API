<?php

namespace App\Jobs;

use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Models\IndependentActivity;
use App\Models\Organization;
use App\Models\Playlist;
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

class ConvertActvityIntoIndependentActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var IndependentActivity
     */
    protected $activity;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var
     */
    protected $token;
    
    /**
     * Create a new job instance.
     *
     * @param Organization $organization
     * @param Activity $activity
     * @param $token
     */
    public function __construct(
        Organization $organization,
        Activity $activity,
        $token
    )
    {
        $this->organization = $organization;
        $this->activity = $activity;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @param IndependentActivityRepositoryInterface $independentActivityRepository
     * @param UserRepositoryInterface $userRepository
     * @return void
     */
    public function handle(
        IndependentActivityRepositoryInterface $independentActivityRepository, 
        UserRepositoryInterface $userRepository
    )
    {
        try {
            $independentActivityRepository->convertIntoIndependentActivity($this->organization, $this->activity, $this->token);
            $message = "Your request to copy activity [" . $this->activity->title . "] into 
                            Independent activity has been completed and available";
            $userId = $userRepository->parseToken($this->token);
            $user = User::find($userId);
            $userName = rtrim($user->first_name . ' ' . $user->last_name, ' ');
            $process = "Copy Activity into Independent Activity";
            $user->notify(new CloneNotification($message, $process, $userName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
