<?php

namespace App\Repositories\Team;

use App\Events\TeamCreatedEvent;
use App\Models\Project;
use App\Models\Team;
use App\Notifications\InviteToTeamNotification;
use App\Repositories\BaseRepository;
use App\Repositories\InvitedTeamUser\InvitedTeamUserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Gnello\Mattermost\Laravel\Facades\Mattermost;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{

    private $userRepository;
    private $projectRepository;
    private $invitedTeamUserRepository;
    private $matDriver;

    /**
     * TeamRepository constructor.
     *
     * @param Team $model
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     * @param InvitedTeamUserRepositoryInterface $invitedTeamUserRepository
     */
    public function __construct(
        Team $model,
        UserRepositoryInterface $userRepository,
        ProjectRepositoryInterface $projectRepository,
        InvitedTeamUserRepositoryInterface $invitedTeamUserRepository
    )
    {
        parent::__construct($model);

        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
        $this->invitedTeamUserRepository = $invitedTeamUserRepository;

        $this->matDriver = Mattermost::server('default');
    }

    /**
     * Create pivots data on team creation
     *
     * @param $team
     * @param $data
     */
    public function createTeam($team, $data)
    {
        $assigned_users = [];
        $valid_users = [];
        $invited_users = [];

        foreach ($data['users'] as $user) {
            $con_user = $this->userRepository->find($user['id']);

            $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
            if ($con_user) {
                $team->users()->attach($con_user, ['role' => 'collaborator', 'token' => $token]);
                $con_user->token = $token;
                $valid_users[] = $con_user;
                $assigned_users[] = [
                    'user' => $con_user,
                    'note' => $user['note']
                ];
            } elseif ($user['email']) {
                $temp_user = new User(['email' => $user['email']]);
                $temp_user->token = $token;

                $assigned_users[] = [
                    'user' => $temp_user,
                    'note' => $user['note']
                ];

                $invited_users[] = [
                    'invited_email' => $user['email'],
                    'team_id' => $team->id,
                    'token' => $token
                ];
            }
        }

        foreach ($invited_users as $invited_user) {
            $this->invitedTeamUserRepository->create($invited_user);
        }

        $assigned_projects = [];
        foreach ($data['projects'] as $project_id) {
            $project = $this->projectRepository->find($project_id);
            if ($project) {
                $team->projects()->attach($project);
                $assigned_projects[] = $project;
            }
        }

        event(new TeamCreatedEvent($team, $assigned_projects, $assigned_users));

        $this->setTeamProjectUser($team, $assigned_projects, $valid_users);
    }

    /**
     * Invite user to the team
     *
     * @param $team
     * @param $user
     */
    public function inviteToTeam($team, $user)
    {
        $auth_user = auth()->user();
        $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
        $team->users()->attach($user, ['role' => 'collaborator', 'token' => $token]);
        $user->notify(new InviteToTeamNotification($auth_user, $team, $token));
    }

    /**
     * Invite members to the team
     *
     * @param $team
     * @param $data
     * @return bool
     */
    public function inviteMembers($team, $data)
    {
        $auth_user = auth()->user();

        $invited = true;
        foreach ($data['users'] as $user) {
            $con_user = $this->userRepository->findByField('email', $user['email']);
            $note = array_key_exists('note', $data) ? $data['note'] : '';

            if ($con_user) {
                $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                $team->users()->attach($con_user, ['role' => 'collaborator', 'token' => $token]);
                $con_user->notify(new InviteToTeamNotification($auth_user, $team, $token, $note));
            } elseif ($user['email']) {
                $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                $temp_user = new User(['email' => $user['email']]);
                $temp_user->notify(new InviteToTeamNotification($auth_user, $team, $token, $note));

                $invited_user = array(
                    'invited_email' => $user['email'],
                    'team_id' => $team->id,
                    'token' => $token,
                );
                $this->invitedTeamUserRepository->create($invited_user);
            } else {
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
        $authenticated_user = auth()->user();
        $team = $this->model->find($teamId);

        if ($team) {
            $team_projects = [];
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
                    $team_projects[] = $team_project;
                }
            }

            $team->projects = $team_projects;

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
