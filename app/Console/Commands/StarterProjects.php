<?php

namespace App\Console\Commands;

use App\Repositories\Admin\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StarterProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:starters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is for assigning the starter projects to the users with less than 2 projects.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @return void
     */
    public function handle(ProjectRepositoryInterface $projectRepository)
    {
        // get starter projects
        $starterProjects = resolve(ProjectRepository::class)->getStarterProjects(); // get all starter projects
        $users = User::has('projects', '<=', 1)->get(); // get users with 1 or 0 projects

        \Log::info('Starter Project found: ' . $starterProjects->count());
        \Log::info('Users found with less than 2 projects: ' . $users->count());
        \Log::info('Starter Project script started at: ' . \Carbon::now());
        foreach ($users as $user) {
            // loop through starter projects
            foreach ($starterProjects as $project) {
                try {
                    $projectRepository->clone($user, $project, $user->createToken('auth_token')->accessToken);
                } catch (\Exception $e) {
                    Log::info($user);
                    Log::info($project);
                    Log::Error('Starter Projects Assigning failed:' . $e->getMessage());
                }
            }
        }
        \Log::info('Starter Project script Finished at: ' . \Carbon::now());
    }
}
