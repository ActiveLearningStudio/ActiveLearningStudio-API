<?php

namespace App\Jobs;

use App\Repositories\Admin\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignStarterProjects implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var
     */
    protected $token;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $token
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     * Get the starter projects
     * Assign the starter projects to the user
     * @param ProjectRepositoryInterface $projectRepository
     * @return void
     */
    public function handle(ProjectRepositoryInterface $projectRepository): void
    {
        // get starter projects
        $starterProjects = resolve(ProjectRepository::class)->getStarterProjects();
        \Log::info('Starter Project JOB started at: ' . now());
        DB::transaction(function () use ($starterProjects, $projectRepository) {
            // assign all starter projects one by one
            try {
                foreach ($starterProjects as $project) {
                    $projectRepository->clone($this->user, $project, $this->token);
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::info('Starter Projects Assigning failed:' . $e->getMessage());
                Log::info($this->user);
            }
        });
        \Log::info('Starter Project JOB Finished at: ' . now());
    }

}
