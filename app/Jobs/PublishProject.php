<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\MicrosoftTeam\MicrosoftTeamRepositoryInterface;
use App\Models\Project;
use App\User;
use App\Notifications\ProjectPublishNotification;


class PublishProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Project
     */
    protected $project;

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
    public function __construct(User $user, Project $project, $classId)
    {
        $this->user = $user;
        $this->project = $project;
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
                $response = $microsoftTeamRepository->createMsTeamClass($this->user->msteam_access_token, ['displayName'=>$this->project->name]);    
                \Log::info($response);
                $class = json_decode($response, true);
                $this->classId = $class['classId'];
                $this->aSyncUrl = $class['aSyncURL'];
            }
            
            $microsoftTeamRepository->createMSTeamAssignment($this->user->msteam_access_token, $this->classId, $this->project, $this->aSyncUrl);
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
           
            $this->user->notify(new ProjectPublishNotification($userName, $this->project->name));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
