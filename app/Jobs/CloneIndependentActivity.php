<?php

namespace App\Jobs;

use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Models\IndependentActivity;
use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\CloneNotification;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\User;

class CloneIndependentActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var IndependentActivity
     */
    protected $independentActivity;

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
     * @param IndependentActivity $independentActivity
     * @param $token
     */
    public function __construct(
        Organization $organization,
        IndependentActivity $independentActivity,
        $token
    )
    {
        $this->organization = $organization;
        $this->independentActivity = $independentActivity;
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
            $independentActivityRepository->clone($this->organization, $this->independentActivity, $this->token);
            $isDuplicate = ($this->independentActivity->organization_id == $this->organization->id);
            $process = ($isDuplicate) ? "duplicate" : "clone";
            $message = "Your request to $process independent activity [" . $this->independentActivity->title . "] has been completed and available";
            $user_id = $userRepository->parseToken($this->token);
            $user = User::find($user_id);
            $userName = rtrim($user->first_name . ' ' . $user->last_name, ' ');
            $user->notify(new CloneNotification($message, $process, $userName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
