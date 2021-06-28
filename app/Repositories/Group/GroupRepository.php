<?php

namespace App\Repositories\Group;

use App\Events\GroupCreatedEvent;
use App\Models\Project;
use App\Models\Group;
use App\Notifications\InviteToGroupNotification;
use App\Repositories\BaseRepository;
use App\Repositories\InvitedGroupUser\InvitedGroupUserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{

    private $userRepository;
    private $projectRepository;
    private $invitedGroupUserRepository;
    private $organizationRepository;

    /**
     * GroupRepository constructor.
     *
     * @param Group $model
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     * @param InvitedGroupUserRepositoryInterface $invitedGroupUserRepository
     * @param OrganizationRepositoryInterface $organizationRepository
     */
    public function __construct(
        Group $model,
        UserRepositoryInterface $userRepository,
        ProjectRepositoryInterface $projectRepository,
        InvitedGroupUserRepositoryInterface $invitedGroupUserRepository,
        OrganizationRepositoryInterface $organizationRepository
    )
    {
        parent::__construct($model);

        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
        $this->invitedGroupUserRepository = $invitedGroupUserRepository;
        $this->organizationRepository = $organizationRepository;

    }

    /**
     * Create pivots data on group creation
     *
     * @param $suborganization
     * @param $group
     * @param $data
     */
    public function createGroup($suborganization, $group, $data)
    {
        $assigned_users = [];
        $valid_users = [];
        $invited_users = [];
        $auth_user = auth()->user();

        foreach ($data['users'] as $user) {
            $con_user = $this->userRepository->find($user['id']);

            $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
            if ($con_user) {
                $group->users()->attach($con_user, ['role' => 'collaborator', 'token' => $token]);
                $con_user->token = $token;
                $valid_users[] = $con_user;
                $assigned_users[] = [
                    'user' => $con_user,
                    'note' => $user['note']
                ];
            } elseif ($user['email']) {
                $temp_user = new User(['email' => $user['email']]);
                $temp_user->token = $token;

                // added org invitation for outside users
                $inviteData['role_id'] = config('constants.member-role-id');
                $inviteData['email'] = $user['email'];
                $inviteData['note'] = $user['note'];
                $invited = $this->organizationRepository->inviteMember($auth_user, $suborganization, $inviteData);
                // ended org invitation for outside users

                $assigned_users[] = [
                    'user' => $temp_user,
                    'note' => $user['note']
                ];

                $invited_users[] = [
                    'invited_email' => $user['email'],
                    'group_id' => $group->id,
                    'token' => $token
                ];
            }
        }

        foreach ($invited_users as $invited_user) {
            $this->invitedGroupUserRepository->create($invited_user);
        }

        $assigned_projects = [];
        foreach ($data['projects'] as $project_id) {
            $project = $this->projectRepository->find($project_id);
            if ($project) {
                $group->projects()->attach($project);
                $assigned_projects[] = $project;
            }
        }

        event(new GroupCreatedEvent($group, $assigned_projects, $assigned_users));

        $this->setGroupProjectUser($group, $assigned_projects, $valid_users);
    }

    /**
     * Update pivots data on group update
     *
     * @param $suborganization
     * @param $group
     * @param $data
     */
    public function updateGroup($suborganization, $group, $data)
    {
        $assigned_users = [];
        $valid_users = [];
        $invited_users = [];
        $auth_user = auth()->user();

        foreach ($data['users'] as $user) {
            $con_user = $this->userRepository->find($user['id']);
            $userRow = $group->users()->find($user['id']);
            if ($userRow) {
                $valid_users[] = $con_user;
                continue;
            }

            $note = array_key_exists('note', $user) ? $user['note'] : '';
            $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));

            if ($con_user) {
                $group->users()->attach($con_user, ['role' => 'collaborator', 'token' => $token]);
                $con_user->token = $token;
                $valid_users[] = $con_user;
                $assigned_users[] = [
                    'user' => $con_user,
                    'note' => $note
                ];
            } elseif ($user['email']) {
                $temp_user = new User(['email' => $user['email']]);
                $temp_user->token = $token;

                // added org invitation for outside users
                $inviteData['role_id'] = config('constants.member-role-id');
                $inviteData['email'] = $user['email'];
                $inviteData['note'] = $note;
                $invited = $this->organizationRepository->inviteMember($auth_user, $suborganization, $inviteData);
                // ended org invitation for outside users

                $invited_users[] = [
                    'invited_email' => $user['email'],
                    'group_id' => $group->id,
                    'token' => $token
                ];
            }
        }

        foreach ($invited_users as $invited_user) {
            $this->invitedGroupUserRepository->create($invited_user);
        }

        $group->projects()->sync($data['projects']);

        event(new GroupCreatedEvent($group, $data['projects'], $assigned_users));

        $this->updateGroupProjectUser($group, $data['projects'], $valid_users);
    }

    /**
     * Invite user to the group
     *
     * @param $group
     * @param $user
     */
    public function inviteToGroup($group, $user)
    {
        $auth_user = auth()->user();
        $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
        $group->users()->attach($user, ['role' => 'collaborator', 'token' => $token]);
        $user->notify(new InviteToGroupNotification($auth_user, $group, $token));
    }

    /**
     * Invite members to the group
     *
     * @param $group
     * @param $data
     * @return bool
     */
    public function inviteMembers($suborganization, $group, $data)
    {
        $auth_user = auth()->user();

        $invited = true;
        foreach ($data['users'] as $user) {
            $con_user = $this->userRepository->findByField('email', $user['email']);
            $note = array_key_exists('note', $data) ? $data['note'] : '';

            if ($con_user) {
                if (!$suborganization->users->where("id", $con_user->id)->first()) {
                    $suborganization->users()->attach($con_user, ['organization_role_type_id' => config('constants.member-role-id')]);
                }
                $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                $group->users()->attach($con_user, ['role' => 'collaborator', 'token' => $token]);
                $con_user->notify(new InviteToGroupNotification($auth_user, $group, $token, $note));
            } elseif ($user['email']) {
                $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                $temp_user = new User(['email' => $user['email']]);

                // added org invitation for outside users
                $inviteData['role_id'] = config('constants.member-role-id');
                $inviteData['email'] = $user['email'];
                $inviteData['note'] = $note;
                $invited = $this->organizationRepository->inviteMember($auth_user, $suborganization, $inviteData);
                // ended org invitation for outside users

                // $temp_user->notify(new InviteToGroupNotification($auth_user, $group, $token, $note));

                $invited_user = array(
                    'invited_email' => $user['email'],
                    'group_id' => $group->id,
                    'token' => $token,
                );

                $result = $this->invitedGroupUserRepository->create($invited_user);

                if (!$result) {
                    $invited = false;
                }
            } else {
                $invited = false;
            }
        }

        return $invited;
    }

    /**
     * Set Group / Project / User relationship
     *
     * @param $group
     * @param $projects
     * @param $users
     */
    public function setGroupProjectUser($group, $projects, $users)
    {
        $group = $this->model->find($group->id);
        $auth_user = auth()->user();

        if ($group) {
            foreach ($projects as $project) {
                DB::table('group_project_user')
                    ->insertOrIgnore([
                        [
                            'group_id' => $group->id,
                            'project_id' => $project->id,
                            'user_id' => $auth_user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ]);

                foreach ($users as $user) {
                    DB::table('group_project_user')
                        ->insertOrIgnore([
                            [
                                'group_id' => $group->id,
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
     * Update Group / Project / User relationship
     *
     * @param $group
     * @param $projects
     * @param $users
     */
    public function updateGroupProjectUser($group, $projects, $users)
    {
        $group = $this->model->find($group->id);

        if ($group) {
            DB::table('group_project_user')->where('group_id', $group->id)->delete();

            foreach ($projects as $projectId) {
                foreach ($users as $user) {
                    DB::table('group_project_user')
                        ->insertOrIgnore([
                            [
                                'group_id' => $group->id,
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
     * Remove Group / Project / User relationship
     *
     * @param $group
     * @param $user
     */
    public function removeGroupProjectUser($group, $user)
    {
        $group = $this->model->find($group->id);

        if ($group) {
            foreach ($group->projects as $project) {
                DB::table('group_project_user')->where('group_id', $group->id)
                    ->where('project_id', $project->id)
                    ->where('user_id', $user->id)
                    ->delete();
            }
        }
    }

    /**
     * Remove invited user
     *
     * @param $group
     * @param $email
     */
    public function removeInvitedUser($group, $email)
    {
        $group = $this->model->find($group->id);

        if ($group) {
            DB::table('invited_group_users')
                ->where('group_id', $group->id)
                ->where('invited_email', $email)
                ->delete();
        }
    }

    /**
     * Remove Group / User / Project relationship
     *
     * @param $group
     * @param $project
     */
    public function removeGroupUserProject($group, $project)
    {
        $group = $this->model->find($group->id);

        if ($group) {
            foreach ($group->users as $user) {
                DB::table('group_project_user')->where('group_id', $group->id)
                    ->where('project_id', $project->id)
                    ->where('user_id', $user->id)
                    ->delete();
            }
        }
    }

    /**
     * Assign members to the group project
     *
     * @param $group
     * @param $project
     * @param $users
     */
    public function assignMembersToGroupProject($group, $project, $users)
    {
        $group = $this->model->find($group->id);

        if ($group) {
            foreach ($users as $user) {
                DB::table('group_project_user')
                    ->insertOrIgnore([
                        [
                            'group_id' => $group->id,
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
     * Remove member from the group project
     *
     * @param $group
     * @param $project
     * @param $user
     */
    public function removeMemberFromGroupProject($group, $project, $user)
    {
        $group = $this->model->find($group->id);

        if ($group) {
            DB::table('group_project_user')->where('group_id', $group->id)
                ->where('project_id', $project->id)
                ->where('user_id', $user->id)
                ->delete();
        }
    }

    /**
     * Get Groups
     *
     * @param $suborganization_id
     * @param $user_id
     * @return mixed
     */
    public function getGroups($suborganization_id, $user_id)
    {
        return  Group::whereHas('users', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })
                ->whereOrganizationId($suborganization_id)
                ->get();
    }

    /**
     * Get Organization Groups
     *
     * @param $suborganization_id
     * @return mixed
     */
    public function getOrgGroups($suborganization_id)
    {
        return  Group::whereOrganizationId($suborganization_id)->get();
    }

    /**
     * Get Group detail data
     *
     * @param $groupId
     * @return mixed
     */
    public function getGroupDetail($groupId)
    {
        $authenticated_user = auth()->user();
        $group = $this->model->find($groupId);

        if ($group) {
            foreach ($group->projects as $group_project) {
                $tpu = DB::table('group_project_user')
                    ->where('group_id', $group->id)
                    ->where('project_id', $group_project->id)
                    ->where('user_id', $authenticated_user->id)
                    ->first();

                if ($tpu) {
                    $group_project_users = DB::table('group_project_user')
                        ->where('group_id', $group->id)
                        ->where('project_id', $group_project->id)
                        ->get();

                    $project_users = [];
                    foreach ($group_project_users as $group_project_user) {
                        $user = $this->userRepository->find($group_project_user->user_id);
                        if ($user) {
                            $project_users[] = [
                                'id' => $user->id,
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'email' => $user->email,
                            ];
                        }
                    }

                    $group_project->users = $project_users;
                }
            }

            foreach ($group->users as $group_user) {
                $group_project_users = DB::table('group_project_user')
                    ->where('group_id', $group->id)
                    ->where('user_id', $group_user->id)
                    ->get();

                $user_projects = [];
                foreach ($group_project_users as $group_project_user) {
                    $project = $this->projectRepository->find($group_project_user->project_id);
                    if ($project) {
                        $user_projects[] = [
                            'id' => $project->id,
                            'name' => $project->name,
                            'description' => $project->description,
                        ];
                    }
                }

                $group_user->projects = $user_projects;
            }
        }

        return $group;
    }
}
