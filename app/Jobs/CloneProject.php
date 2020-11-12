<?php

namespace App\Jobs;

use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\CloneNotification;

class CloneProject implements ShouldQueue
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
     * @var
     */
    protected $token;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param Project $project
     * @param $token
     */
    public function __construct(User $user, Project $project, $token)
    {
        $this->user = $user;
        $this->project = $project;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @return void
     */
    public function handle(ProjectRepositoryInterface $projectRepository)
    {
        try {
            $projectRepository->clone($this->user, $this->project, $this->token);
            $isDuplicate = $projectRepository->checkIsDuplicate($this->user, $this->project->id);
            $process = ($isDuplicate) ? "duplicate" : "clone";
            $message = "Your request to $process project [" . $this->project->name . "] has been completed and available";
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
            $this->user->notify(new CloneNotification($message, $process, $userName));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
