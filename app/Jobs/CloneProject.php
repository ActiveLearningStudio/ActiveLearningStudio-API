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
        $projectRepository->clone($this->user, $this->project, $this->token);
    }
}
