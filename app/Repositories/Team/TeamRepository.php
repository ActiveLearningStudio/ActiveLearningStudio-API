<?php

namespace App\Repositories\Team;

use App\Events\TeamCreatedEvent;
use App\Jobs\CloneProject;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamRoleType;
use App\Notifications\InviteToTeamNotification;
use App\Repositories\BaseRepository;
use App\Repositories\InvitedTeamUser\InvitedTeamUserRepositoryInterface;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{

    private $userRepository;
    private $projectRepository;
    private $invitedTeamUserRepository;
    private $organizationRepository;

    /**
     * TeamRepository constructor.
     *
     * @param Team $model
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     * @param InvitedTeamUserRepositoryInterface $invitedTeamUserRepository
     * @param OrganizationRepositoryInterface $organizationRepository
     */
    public function __construct(
        Team $model,
        UserRepositoryInterface $userRepository,
        ProjectRepositoryInterface $projectRepository,
        InvitedTeamUserRepositoryInterface $invitedTeamUserRepository,
        OrganizationRepositoryInterface $organizationRepository
    )
    {
        parent::__construct($model);

        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
        $this->invitedTeamUserRepository = $invitedTeamUserRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Create pivots data on team creation
     *
     * @param $suborganization
     * @param $data
     */
    public function createTeam($suborganization, $data)
    {
        return \DB::transaction(function () use ($suborganization, $data) {

            $auth_user = auth()->user();
            $admin_role = TeamRoleType::whereName('admin')->first();
            $team = $auth_user->teams()->create($data, ['team_role_type_id' => $admin_role->id]);

            $assigned_users = [];
            $valid_users = [];

            foreach ($data['users'] as $user) {
                $con_user = $this->userRepository->find($user['id']);
                $note = array_key_exists('note', $user) ? $user['note'] : '';
                if ($con_user) {
                    $team->users()->attach($con_user, ['team_role_type_id' => $user['role_id']]);
                    $valid_users[] = $con_user;
                    $assigned_users[] = [
                        'user' => $con_user,
                        'note' => $note
                    ];
                }
            }

            $assigned_projects = [];

            if (isset($data['projects'])) {
                foreach ($data['projects'] as $project_id) {
                    $project = $this->projectRepository->find($project_id);
                    if ($project) {
                        // pushed cloning of project in background
                        CloneProject::dispatch($auth_user, $project, $data['bearerToken'], $suborganization->id, $team)
                                    ->delay(now()->addSecond());
                        // $team->projects()->attach($project);
                        // $assigned_projects[] = $project;
                    }
                }
            }

            event(new TeamCreatedEvent($team, $assigned_projects, $assigned_users));
            // $this->setTeamProjectUser($team, $assigned_projects, $valid_users);

            return $team;
        });
    }

    /**
     * Update pivots data on team update
     *
     * @param $suborganization
     * @param $team
     * @param $data
     */
    public function updateTeam($suborganization, $team, $data)
    {
        return \DB::transaction(function () use ($suborganization, $team, $data) {

            $teamData = [];
            $teamData['name'] = $data['name'];
            $teamData['description'] = $data['description'];

            $this->update($teamData, $team->id);

            // $assigned_users = [];
            // $valid_users = [];

            // foreach ($data['users'] as $user) {
            //     $con_user = $this->userRepository->find($user['id']);
            //     $userRow = $team->users()->find($user['id']);
            //     if ($userRow) {
            //         $valid_users[] = $con_user;
            //         continue;
            //     }

            //     $note = array_key_exists('note', $user) ? $user['note'] : '';

            //     if ($con_user) {
            //         $team->users()->attach($con_user, ['team_role_type_id' => $user['role_id']]);
            //         $valid_users[] = $con_user;
            //         $assigned_users[] = [
            //             'user' => $con_user,
            //             'note' => $note
            //         ];
            //     }
            // }

            // $team->projects()->sync($data['projects']);

            // event(new TeamCreatedEvent($team, $data['projects'], $assigned_users));
            // $this->updateTeamProjectUser($team, $data['projects'], $valid_users);

            return $team;
        });
    }

    /**
     * Invite user to the team
     *
     * @param $team
     * @param $user
     * @param $role_id
     */
    public function inviteToTeam($team, $user, $role_id)
    {
        $auth_user = auth()->user();
        $team->users()->attach($user, ['team_role_type_id' => $role_id]);
        $user->notify(new InviteToTeamNotification($auth_user, $team));
    }

    /**
     * Invite members to the team
     *
     * @param $suborganization
     * @param $team
     * @param $data
     * @return bool
     */
    public function inviteMembers($suborganization, $team, $data)
    {
        $auth_user = auth()->user();
        $invited = true;

        foreach ($data['users'] as $user) {
            $con_user = $this->userRepository->find($user['id']);
            $userRow = $team->users()->find($user['id']);
            if ($userRow) {
                $valid_users[] = $con_user;
                continue;
            }
            $note = array_key_exists('note', $data) ? $data['note'] : '';

            if ($con_user) {
                $team->users()->attach($con_user, ['team_role_type_id' => $user['role_id']]);
                $con_user->notify(new InviteToTeamNotification($auth_user, $team, $note));
            }
            else {
                $invited = false;
            }
        }

        return $invited;
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
     * Update Team / Project / User relationship
     *
     * @param $team
     * @param $projects
     * @param $users
     */
    public function updateTeamProjectUser($team, $projects, $users)
    {
        $team = $this->model->find($team->id);

        if ($team) {
            DB::table('team_project_user')->where('team_id', $team->id)->delete();

            foreach ($projects as $projectId) {
                foreach ($users as $user) {
                    DB::table('team_project_user')
                        ->insertOrIgnore([
                            [
                                'team_id' => $team->id,
                                'project_id' => $projectId,
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
     * Remove Team invited user
     *
     * @param $team
     * @param $email
     */
    public function removeInvitedUser($team, $email)
    {
        $team = $this->model->find($team->id);

        if ($team) {
            DB::table('invited_team_users')
                ->where('team_id', $team->id)
                ->where('invited_email', $email)
                ->delete();
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
     * Get Teams data
     *
     * @param $suborganization_id
     * @param $user_id
     * @return mixed
     */
    public function getTeams($suborganization_id, $user_id)
    {
        return Team::whereHas('users', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })
                ->whereOrganizationId($suborganization_id)
                ->get();
    }

    /**
     * Get Organization Teams data
     *
     * @param $suborganization_id
     * @return mixed
     */
    public function getOrgTeams($suborganization_id)
    {
        return Team::whereOrganizationId($suborganization_id)->get();
    }

    /**
     * Get Team detail data
     *
     * @param $teamId
     *
     * @return mixed
     */
    public function getTeamDetail($teamId)
    {
        $authenticated_user = auth()->user();
        $team = $this->model->find($teamId);

        if ($team) {
            foreach ($team->projects as $team_project) {
                $tpu = DB::table('team_project_user')
                    ->where('team_id', $team->id)
                    ->where('project_id', $team_project->id)
                    ->where('user_id', $authenticated_user->id)
                    ->first();

                if ($tpu) {
                    $team_project_users = DB::table('team_project_user')
                        ->where('team_id', $team->id)
                        ->where('project_id', $team_project->id)
                        ->get();

                    $project_users = [];
                    foreach ($team_project_users as $team_project_user) {
                        $user = $this->userRepository->find($team_project_user->user_id);
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
            }

            foreach ($team->users as $team_user) {
                $team_project_users = DB::table('team_project_user')
                    ->where('team_id', $team->id)
                    ->where('user_id', $team_user->id)
                    ->get();

                $user_projects = [];
                foreach ($team_project_users as $team_project_user) {
                    $project = $this->projectRepository->find($team_project_user->project_id);
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

    /**
     * To fetch team user permissions
     *
     * @param User $authenticatedUser
     * @param Team $team
     * @return Model
     */
    public function fetchTeamUserPermissions($authenticatedUser, $team)
    {
        try {
            $teamUserPermissions = $team->userRoles()
            ->wherePivot('user_id', $authenticatedUser->id)
            ->with('permissions')
            ->first();

            $response['activeRole'] = $teamUserPermissions['name'];
            $response['roleId'] = $teamUserPermissions['id'];

            foreach ($teamUserPermissions['permissions'] as $permission) {
                $response[$permission['feature']][] = $permission['name'];
            }
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
