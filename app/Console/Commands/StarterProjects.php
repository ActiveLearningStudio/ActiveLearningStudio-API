<?php

namespace App\Console\Commands;

use App\Exceptions\GeneralException;
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
        $inProgress = \Cache::store('database')->get('in_progress_users') ?? [];// get in progress user IDs
        // get users with 1 or 0 projects and not already in queue - limit of 50
        $users = User::has('projects', '<=', 1)->with('projects:id,cloned_from')->whereNotIn('id', $inProgress)->limit(50)->get();

        if (count($users)) {
            $releaseProgress = []; // array for containing the users IDs for which starter projects processed
            $inProgress += $users->pluck('id', 'id')->all();
            \Cache::store('database')->put('in_progress_users', $inProgress); // update the in progress users IDs

            Log::info('Starter Projects: ' . $starterProjects->count() . ' & Users found: ' . $users->count());

            foreach ($users as $user) {
                // loop through user organizations
                foreach ($user->organizations as $userOrganization) {
                    // loop through starter projects
                    foreach ($starterProjects as $project) {
                        // if current starter project is already assigned then skip and move to the next starter project
                        if (in_array($project->id, $user->projects()->where('organization_id', '=', $userOrganization->id)->pluck('cloned_from')->toArray())) {
                            continue;
                        }
                        try {
                            $projectRepository->clone($user, $project, $user->createToken('auth_token')->accessToken, $userOrganization->id);
                        } catch (\Exception $e) {
                            Log::Error('Starter Projects Assigning failed: ' . $user->email . ' ' . $e->getMessage());
                        }
                    }
                }
                $releaseProgress[$user->id] = $user->id;
                Log::info('Projects are assigned to the USER: ' . $user->email . ' timestamp: ' . now());
            }

            Log::info('Starter Project script Finished at: ' . now());
            // as this cron can take some time, so again get the fresh list of InProgress users
            $inProgress = \Cache::store('database')->get('in_progress_users') ?? [];// get in progress user IDs
            // update the in progress users IDs excluding the completed
            \Cache::store('database')->put('in_progress_users', array_diff($inProgress, $releaseProgress));
        }
    }
}
