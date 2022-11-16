<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\MicrosoftTeam\MicrosoftTeamRepositoryInterface;
use App\Models\IndependentActivity;
use App\Models\Activity;
use App\User;
use App\Notifications\IndependentActivityPublishNotification;


class PublishIndependentActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var string
     */
    protected $classId;

    /**
     * @var string
     */
    protected $aSyncUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Activity $activity, $classId)
    {
        $this->user = $user;
        $this->activity = $activity;
        $this->classId = $classId;
        $this->aSyncUrl = '';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MicrosoftTeamRepositoryInterface $microsoftTeamRepository)
    {
        try {
            if(empty($this->classId)) {
                $response = $microsoftTeamRepository->createMsTeamClass($this->user->msteam_access_token, ['displayName'=>$this->activity->title]);    
                \Log::info($response);
                $class = json_decode($response, true);
                $this->classId = $class['classId'];
                $this->aSyncUrl = $class['aSyncURL'];
            }
            
            $microsoftTeamRepository->createMSTeamIndependentActivityAssignment($this->user->msteam_access_token, $this->classId, $this->activity, $this->aSyncUrl);
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
           
            $this->user->notify(new IndependentActivityPublishNotification($userName, $this->activity->title));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
