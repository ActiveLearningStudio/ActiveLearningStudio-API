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

        $userID = $this->user->id;
        $inProgress = \Cache::store('database')->get('in_progress_users') ?? [];// get in progress user IDs

        // process this user if it's not already in-progress and processed by cron
        if ((!isset($inProgress[$userID])) && (count($this->user->projects) < count($starterProjects))) {
            try {
                $inProgress[$userID] = $userID; // append the user ID to in progress array
                \Cache::store('database')->put('in_progress_users', $inProgress); // update the in progress users IDs

                // loop through user organizations
                foreach ($user->organizations as $userOrganization) {
                    // assign all starter projects one by one
                    foreach ($starterProjects as $project) {
                        $projectRepository->clone($this->user, $project, $this->token, $userOrganization->id);
                    }
                }
            } catch
            (\Exception $e) {
                Log::info('Starter Projects Assigning failed:' . $e->getMessage());
                Log::info($this->user);
            }
            // as this job can take some time, so again get the fresh list of InProgress users
            $inProgress = \Cache::store('database')->get('in_progress_users') ?? [];// get in progress user IDs
            // remove the id of this particular user from inProgress and update
            unset($inProgress[$userID]);
            \Cache::store('database')->put('in_progress_users', $inProgress); // update the in progress users IDs
        }
    }

}
