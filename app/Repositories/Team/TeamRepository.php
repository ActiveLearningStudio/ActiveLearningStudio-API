<?php

namespace App\Repositories\Team;

use App\Http\Resources\V1\UserResource;
use App\Models\Project;
use App\Models\Team;
use App\Repositories\BaseRepository;
use App\Repositories\Team\TeamRepositoryInterface;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    /**
     * TeamRepository constructor.
     *
     * @param Team $model
     */
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }

    /**
     * Set Team / Project / User relationship
     *
     * @param $team
     * @param $projects
     * @param $users
     */
    public function setTeamProjectUser($team, $projects, $users)
    {
        $team = $this->model->find($team->id);
        $auth_user = auth()->user();

        if ($team) {
            foreach ($projects as $project) {
                DB::table('team_project_user')
                    ->insertOrIgnore([
                        [
                            'team_id' => $team->id,
                            'project_id' => $project->id,
                            'user_id' => $auth_user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ]);

                foreach ($users as $user) {
                    DB::table('team_project_user')
                        ->insertOrIgnore([
                            [
                                'team_id' => $team->id,
                                'project_id' => $project->id,
                                'user_id' => $user->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ],
                        ]);
                }
            }
        }
    }

    /**
     * Remove Team / Project / User relationship
     *
     * @param $team
     * @param $user
     */
    public function removeTeamProjectUser($team, $user)
    {
        $team = $this->model->find($team->id);

        if ($team) {
            foreach ($team->projects as $project) {
                DB::table('team_project_user')->where('team_id', $team->id)
                    ->where('project_id', $project->id)
                    ->where('user_id', $user->id)
                    ->delete();
            }
        }
    }

    /**
     * Remove Team / User / Project relationship
     *
     * @param $team
     * @param $project
     */
    public function removeTeamUserProject($team, $project)
    {
        $team = $this->model->find($team->id);

        if ($team) {
            foreach ($team->users as $user) {
                DB::table('team_project_user')->where('team_id', $team->id)
                    ->where('project_id', $project->id)
                    ->where('user_id', $user->id)
                    ->delete();
            }
        }
    }

    /**
     * Assign members to the team project
     *
     * @param $team
     * @param $project
     * @param $users
     */
    public function assignMembersToTeamProject($team, $project, $users)
    {
        $team = $this->model->find($team->id);

        if ($team) {
            foreach ($users as $user) {
                DB::table('team_project_user')
                    ->insertOrIgnore([
                        [
                            'team_id' => $team->id,
                            'project_id' => $project->id,
                            'user_id' => $user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ]);
            }
        }
    }

    /**
     * Remove member from the team project
     *
     * @param $team
     * @param $project
     * @param $user
     */
    public function removeMemberFromTeamProject($team, $project, $user)
    {
        $team = $this->model->find($team->id);

        if ($team) {
            DB::table('team_project_user')->where('team_id', $team->id)
                ->where('project_id', $project->id)
                ->where('user_id', $user->id)
                ->delete();
        }
    }

    /**
     * Get Team detail data
     *
     * @param $teamId
     * @return mixed
     */
    public function getTeamDetail($teamId)
    {
        $team = $this->model->find($teamId);

        if ($team) {
            foreach ($team->projects as $team_project) {
                $team_project_users = DB::table('team_project_user')
                    ->where('team_id', $team->id)
                    ->where('project_id', $team_project->id)
                    ->get();

                $project_users = [];
                foreach ($team_project_users as $team_project_user) {
                    $user = User::find($team_project_user->user_id);
                    if ($user) {
                        $project_users[] = [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                        ];
                    }
                }

                $team_project->users = $project_users;
            }

            foreach ($team->users as $team_user) {
                $team_project_users = DB::table('team_project_user')
                    ->where('team_id', $team->id)
                    ->where('user_id', $team_user->id)
                    ->get();

                $user_projects = [];
                foreach ($team_project_users as $team_project_user) {
                    $project = Project::find($team_project_user->project_id);
                    if ($project) {
                        $user_projects[] = [
                            'id' => $project->id,
                            'name' => $project->name,
                            'description' => $project->description,
                        ];
                    }
                }

                $team_user->projects = $user_projects;
            }
        }

        return $team;
    }
}
