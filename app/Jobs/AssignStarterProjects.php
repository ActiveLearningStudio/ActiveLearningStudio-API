<?php

namespace App\Jobs;

use App\Repositories\Admin\Project\ProjectRepository;
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

    protected $user;
    protected $projectRepository;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     * Get the starter projects
     * Assign the starter projects to the user
     * @param ProjectRepository $projectRepository
     * @return void
     */
    public function handle(ProjectRepository $projectRepository): void
    {
        // get starter projects
        $starterProjects = $projectRepository->getStarterProjects();
        DB::transaction(function () use ($starterProjects, $projectRepository) {
            // assign all starter projects one by one
            try {
                foreach ($starterProjects as $project) {
                    // this logic is not finalized yet
//                    $projectRepository->clone($this->user, $project->id);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::info('Starter Projects Assigning failed:' . $e->getMessage());
                Log::info($this->user);
            }
        }, 3);
    }

}
