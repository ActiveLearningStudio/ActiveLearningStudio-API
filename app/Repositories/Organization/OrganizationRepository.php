<?php

namespace App\Repositories\Organization;

use App\Models\Activity;
use App\Models\ActivityItem;
use App\Models\ActivityLayout;
use App\Models\ActivityType;
use App\Models\AuthorTag;
use App\Models\EducationLevel;
use App\Models\Organization;
use App\Models\OrganizationPermissionType;
use App\Models\OrganizationRoleType;
use App\Models\Pivots\GroupProjectUser;
use App\Models\Pivots\TeamProjectUser;
use App\Models\Project;
use App\Models\SsoLogin;
use App\Models\Subject;
use App\Models\TeamUserRole;
use App\Models\UserLogin;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Arr;
use App\Notifications\OrganizationInvite;
use App\Repositories\InvitedOrganizationUser\InvitedOrganizationUserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;

class OrganizationRepository extends BaseRepository implements OrganizationRepositoryInterface
{
    private $userRepository;
    private $invitedOrganizationUserRepository;
    private $projectRepository;

    /**
     * Organization Repository constructor.
     *
     * @param Organization $model
     * @param UserRepositoryInterface $userRepository
     * @param InvitedOrganizationUserRepositoryInterface $invitedOrganizationUserRepository
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(
        Organization $model,
        UserRepositoryInterface $userRepository,
        InvitedOrganizationUserRepositoryInterface $invitedOrganizationUserRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->userRepository = $userRepository;
        parent::__construct($model);
        $this->invitedOrganizationUserRepository = $invitedOrganizationUserRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * To fetch suborganizations
     *
     * @param $data
     * @param Organization $organization
     * @return Organization $organizations
     */
    public function fetchSuborganizations($data, $organization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        if (isset($data['query']) && !empty($data['query'])) {
            $parentIds = $this->getSuborganizationIds($organization);
        } else {
            $parentIds[] = $organization->id;
        }

        $query =  $this->model
            ->with(['parent', 'admins'])
            ->withCount(['projects', 'children', 'users', 'groups', 'teams'])
            ->whereIn('parent_id', $parentIds)
            ->when($data['query'] ?? null, function ($query) use ($data) {
                $query->where('name', 'ilike', '%' . $data['query'] . '%');
                return $query;
            });

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query->orderBy($data['order_by_column'], $orderByType);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get ids for nested suborganizations
     *
     * @param Organization $organization
     * @param array $organizationIds
     * @return array $ids
     */
    public function getSuborganizationIds($organization, $organizationIds = [])
    {
        $organizationIds[] = $organization->id;
        foreach ($organization->children as $child) {
            $organizationIds = $this->getSuborganizationIds($child, $organizationIds);
        }

        return $organizationIds;
    }

    /**
     * Get ids for parent organizations
     *
     * @param Organization $organization
     * @param array $organizationIds
     * @return array $ids
     */
    public function getParentOrganizationIds($organization, $organizationIds = [])
    {
        $organizationIds[] = $organization->id;

        if ($organization->parent) {
            $organizationIds = $this->getParentOrganizationIds($organization->parent, $organizationIds);
        }

        return $organizationIds;
    }

    /**
     * Get ids for parent organizations
     *
     * @param Organization $organization
     * @param array $organizationIds
     * @return array $ids
     */
    public function getParentChildrenOrganizationIds($organization)
    {
        $parentIds = $this->getParentOrganizationIds($organization);
        $childrenIds = $this->getSuborganizationIds($organization);

        $parentChildrenOrganizationIds = $parentIds + $childrenIds;

        return $parentChildrenOrganizationIds;
    }

    /**
     * To create a suborganization
     *
     * @param Organization $organization
     * @param $data
     * @param User $authenticatedUser
     * @return Response
     * @throws GeneralException
     */
    public function createSuborganization($organization, $data, $authenticatedUser)
    {
        $organizationUserRoles = $organization->userRoles()
            ->wherePivot('user_id', $authenticatedUser->id)
            ->whereHas('permissions', function (Builder $query) {
                $query->where('name', '=', 'organization:create');
            })->get();

        $organizationUserAdminRolesids = $organizationUserRoles->pluck('id')->toArray();

        $organizationUserRolesNonAdmin = $organization->roles()
            ->whereNotIn('id', $organizationUserAdminRolesids)
            ->get();

        if (isset($data['admins'])) {
            $userRoles = array_fill_keys($data['admins'],
                ['organization_role_type_id' => config('constants.admin-role-id')]);
        }

        if (isset($data['users'])) {
            foreach ($data['users'] as $user) {
                $userRoles[$user['user_id']] = ['organization_role_type_id' => $user['role_id']];
            }
        }

        try {
            DB::beginTransaction();

            $suborganization = $organization->children()->create(Arr::except($data, ['admins', 'users']));

            $subOrganizationUserRoles = [];

            foreach ($organizationUserRoles as $organizationUserRole) {
                if (!isset($subOrganizationUserRoles[$organizationUserRole->id])) {
                    $subOrganizationUserRolesData['name'] = $organizationUserRole->name;
                    $subOrganizationUserRolesData['display_name'] = $organizationUserRole->display_name;
                    $subOrganizationUserRolesData['permissions'] = $organizationUserRole->permissions->pluck('id')->toArray();
                    $subOrganizationUserRoles[$organizationUserRole->id] = $organizationUserRole->id;

                    $role = $this->addRole($suborganization, $subOrganizationUserRolesData);

                    if ($role) {
                        $organizationUserRoleUsers = $organizationUserRole->users()
                            ->wherePivot('organization_id', $organization->id)
                            ->get();

                        foreach ($organizationUserRoleUsers as $organizationUserRoleUser) {
                            $userRoles[$organizationUserRoleUser->id] = ['organization_role_type_id' => $role->id];
                        }
                    }
                }
            }

            foreach ($organizationUserRolesNonAdmin as $organizationUserRole) {
                if (!isset($subOrganizationUserRoles[$organizationUserRole->id])) {
                    $subOrganizationUserRolesData['name'] = $organizationUserRole->name;
                    $subOrganizationUserRolesData['display_name'] = $organizationUserRole->display_name;
                    $subOrganizationUserRolesData['permissions'] = $organizationUserRole->permissions->pluck('id')->toArray();
                    $subOrganizationUserRoles[$organizationUserRole->id] = $organizationUserRole->id;

                    $role = $this->addRole($suborganization, $subOrganizationUserRolesData);
                }
            }

            if ($suborganization) {
                if (isset($userRoles)) {
                    $suborganization->users()->sync($userRoles);
                }

                $this->assignDefaultActivityContents($suborganization->id, $organization->id);
                $this->assignDefaultMediaSources($suborganization, $organization);

                DB::commit();
            }

            return $suborganization;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    /**
     * Update suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function update($organization, $data)
    {
        if (isset($data['admins'])) {
            $userRoles = array_fill_keys($data['admins'],
                ['organization_role_type_id' => config('constants.admin-role-id')]);
        }

        if (isset($data['users'])) {
            foreach ($data['users'] as $user) {
                $userRoles[$user['user_id']] = ['organization_role_type_id' => $user['role_id']];
            }
        }

        try {
            DB::beginTransaction();

            $is_updated = $organization->update(Arr::except($data, ['admins', 'users']));
            // update the organization data
            if ($is_updated) {
                if (isset($userRoles)) {
                    $organization->users()->sync($userRoles);
                }

                DB::commit();
            }

            return $is_updated;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    /**
     * Delete suborganization
     *
     * @param $id
     * @return Model
     */
    public function deleteSuborganization($id)
    {
        $organization = $this->find($id);

        try {
            foreach ($organization->children as $suborganization) {
                $this->deleteSuborganization($suborganization->id);
            }

            $is_deleted = $this->delete($id);
            return $is_deleted;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Get the member options to add in specific suborganization
     *
     * @param $data
     * @param Organization $organization
     * @return mixed
     */
    public function getMemberOptions($data, $organization)
    {
        $organizationUserIds = $organization->users()->get()->modelKeys();
        $parentOrganizationUserIds = $this->getParentOrganizationUserIds([], $organization);

        if ($data['page'] === 'create') {
            $userInIds = array_merge($organizationUserIds, $parentOrganizationUserIds);
        } else {
            if ($data['page'] === 'update') {
                $userInIds = array_diff($parentOrganizationUserIds, $organizationUserIds);
            }
        }

        $this->query = $this->userRepository->model->when($data['query'] ?? null, function ($query) use ($data) {
            $query->search(['email'], $data['query']);
            return $query;
        });

        return $this->query->whereIn('id', $userInIds)->orderBy('first_name', 'asc')->paginate();
    }

    /**
     * Get a list of the organization users.
     *
     * @param $data
     * @param Organization $organization
     * @return mixed
     */
    public function getOrgUsers($data, $organization)
    {
        $organizationUserIds = $organization->users()->get()->modelKeys();
        $childOrganizationUserIds = $this->getChildrenOrganizationUserIds($organization);

        $userInIds = array_merge($organizationUserIds, $childOrganizationUserIds);

        $this->query = $this->userRepository->model->when($data['search'] ?? null, function ($query) use ($data) {
            $query->search(['email'], $data['search']);
            return $query;
        });

        return $this->query->whereIn('id', $userInIds)->orderBy('email', 'asc')->paginate();
    }

    /**
     * Get the parent organizations user ids
     *
     * @param $userIds
     * @param $organization
     * @return array
     */
    public function getParentOrganizationUserIds($userIds, $organization)
    {
        $parentOrganization = $organization->parent;

        if ($parentOrganization) {
            $ids = $parentOrganization->users()->get()->modelKeys();
            $userIds = array_merge($userIds, $ids);
            $userIds = $this->getParentOrganizationUserIds($userIds, $parentOrganization);
        }

        return $userIds;
    }

    /**
     * Get the children organizations user ids
     *
     * @param $organization
     * @return array
     */
    public function getChildrenOrganizationUserIds($organization)
    {
        $ids = [];
        $childOrganizations = $organization->children;

        if ($childOrganizations) {
            foreach ($childOrganizations as $childOrganization) {
                $ids = $childOrganization->users()->get()->modelKeys();
            }
        }
        return $ids;
    }

    /**
     * Add user for the specified role in particular suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function addUser($organization, $data)
    {
        try {
            $organization->users()->attach($data['user_id'], ['organization_role_type_id' => $data['role_id']]);

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * Add role for particular organization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function addRole($organization, $data)
    {
        try {
            DB::beginTransaction();

            $role = $organization->roles()->create([
                'name' => $data['name'],
                'display_name' => $data['display_name'],
            ]);

            $role->permissions()->attach($data['permissions']);

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }

        return false;
    }

    /**
     * Update role for particular organization
     *
     * @param array $data
     * @return Model
     */
    public function updateRole($data)
    {
        $role = OrganizationRoleType::find($data['role_id']);
        return $role->permissions()->sync($data['permissions']);
    }

    /**
     * Update user for the specified role in particular suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function updateUser($organization, $data)
    {
        try {
            $organization->users()->updateExistingPivot($data['user_id'],
                ['organization_role_type_id' => $data['role_id']]);

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * Delete the specified user in a particular suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function deleteUser($organization, $data)
    {
        $userOrganizations = $organization->whereHas('users', function (Builder $query) use ($data) {
            $query->where('id', '=', $data['user_id']);
        })->get();

        try {
            DB::beginTransaction();

            foreach ($userOrganizations as $userOrganization) {
                $organizationUserRole = $userOrganization->userRoles()
                    ->withPivot('user_id')
                    ->whereHas('permissions', function (Builder $query) {
                        $query->where('name', '=', 'organization:delete-user');
                    })->first();

                $organizationAdminUserId = $organizationUserRole->pivot->user_id;

                $organizationProjects = $userOrganization->projects()->whereHas('users',
                    function (Builder $query) use ($data) {
                        $query->where('id', '=', $data['user_id']);
                    })->get();

                $organizationTeams = $userOrganization->teams()->whereHas('users',
                    function (Builder $query) use ($data) {
                        $query->where('id', '=', $data['user_id']);
                    })->get();

                $organizationGroups = $userOrganization->groups()->whereHas('users',
                    function (Builder $query) use ($data) {
                        $query->where('id', '=', $data['user_id']);
                    })->get();

                $userOrganization->users()->detach($data['user_id']);

                foreach ($organizationProjects as $organizationProject) {
                    if (isset($data['preserve_data']) && $data['preserve_data'] == true) {
                        $organizationProject->original_user = $data['user_id'];
                        $organizationProject->save();
                        $organizationProject->users()->detach($data['user_id']);
                        $organizationProject->users()->attach($organizationAdminUserId, ['role' => 'owner']);
                    } else {
                        $this->projectRepository->delete($organizationProject->id);
                    }
                }

                foreach ($organizationTeams as $organizationTeam) {
                    if (isset($data['preserve_data']) && $data['preserve_data'] == true) {
                        $organizationTeam->original_user = $data['user_id'];
                        $organizationTeam->save();
                        $organizationTeam->users()->detach($data['user_id']);
                        $organizationTeam->users()->attach($organizationAdminUserId, ['role' => 'owner']);
                    } else {
                        resolve(TeamRepositoryInterface::class)->delete($organizationTeam->id);
                    }
                }

                foreach ($organizationGroups as $organizationGroup) {
                    if (isset($data['preserve_data']) && $data['preserve_data'] == true) {
                        $organizationGroup->original_user = $data['user_id'];
                        $organizationGroup->save();
                        $organizationGroup->users()->detach($data['user_id']);
                        $organizationGroup->users()->attach($organizationAdminUserId, ['role' => 'owner']);
                    } else {
                        resolve(GroupRepositoryInterface::class)->delete($organizationGroup->id);
                    }
                }
            }

            $this->userRepository->delete($data['user_id']);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error($e->getMessage());
            DB::rollBack();
        }

        return false;
    }

    /**
     * Remove the specified user from a particular organization
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function removeUser($authenticatedUser, $organization, $data)
    {
        try {
            DB::beginTransaction();

            $organizationTeams = $organization->teams()->whereHas('users', function (Builder $query) use ($data) {
                $query->where('id', '=', $data['user_id']);
            })->get();

            foreach ($organizationTeams as $organizationTeam) {
                if (isset($data['preserve_data']) && $data['preserve_data'] == true) {
                    $organizationTeam->original_user = $data['user_id'];
                    $organizationTeam->save();
                    $organizationTeam->users()->detach($data['user_id']);
                    $organizationTeam->users()->attach($authenticatedUser->id, ['team_role_type_id' => 1]);
                } else {
                    $organizationTeam->users()->detach($data['user_id']);

                    $allTeamUsers = $organizationTeam->users()->wherePivot('user_id', '<>', $data['user_id'])->get();

                    $teamProjects = Project::where('team_id', $organizationTeam->id)->pluck('id')->toArray();

                    if (count($allTeamUsers) > 0) {
                        $allTeamUserIds = [];
                        foreach ($allTeamUsers as $allTeamUserRow) {
                            $allTeamUserIds[] = $allTeamUserRow->id;
                        }

                        if (!in_array($authenticatedUser->id, $allTeamUserIds)) {
                            $organizationTeam->original_user = $data['user_id'];
                            $organizationTeam->save();
                            $organizationTeam->users()->attach($authenticatedUser->id, ['team_role_type_id' => 1]);
                        }
                        if (count($teamProjects) > 0) {
                            DB::table('user_project')
                            ->where('user_id', $data['user_id'])
                            ->whereIn('project_id', $teamProjects)
                            ->delete();
                        }

                    } else {
                        TeamProjectUser::where('user_id', $data['user_id'])->forceDelete();
                        if (count($teamProjects) > 0) {
                            DB::table('user_project')
                            ->where('user_id', $data['user_id'])
                            ->whereIn('project_id', $teamProjects)
                            ->delete();
                        }
                        $organizationTeam->projects()->detach();
                        Project::where('team_id', $organizationTeam->id)->forceDelete();
                        resolve(TeamRepositoryInterface::class)->forceDelete($organizationTeam);
                    }
                }
            }

            $organizationGroups = $organization->groups()->whereHas('users', function (Builder $query) use ($data) {
                $query->where('id', '=', $data['user_id']);
            })->get();

            foreach ($organizationGroups as $organizationGroup) {
                if (isset($data['preserve_data']) && $data['preserve_data'] == true) {
                    $organizationGroup->original_user = $data['user_id'];
                    $organizationGroup->save();
                    $organizationGroup->users()->detach($data['user_id']);
                    $organizationGroup->users()->attach($authenticatedUser->id, ['role' => 'owner']);
                } else {
                    GroupProjectUser::where('user_id', $data['user_id'])->forceDelete();
                    $organizationGroup->users()->detach($data['user_id']);
                    resolve(GroupRepositoryInterface::class)->forceDelete($organizationGroup);
                }
            }

            $organizationProjects = $organization->projects()
                                    ->whereHas('users', function (Builder $query) use ($data) {
                                        $query->where('id', '=', $data['user_id']);
                                    })
                                    ->whereNull('team_id')->get();

            foreach ($organizationProjects as $organizationProject) {
                if (isset($data['preserve_data']) && $data['preserve_data'] == true) {
                    $organizationProject->original_user = $data['user_id'];
                    $organizationProject->save();
                    $organizationProject->users()->detach($data['user_id']);
                    $organizationProject->users()->attach($authenticatedUser->id, ['role' => 'owner']);
                } else {
                    $organizationProject->users()->detach($data['user_id']);
                    $this->projectRepository->forceDelete($organizationProject);
                }
            }

            $user = $organization->users()->where('user_id', $data['user_id'])->first();

            // check if user exists to other organizations
            $userOrganizations = $user->organizations()->where('organization_id', '<>', $organization->id)->get();

            $organization->users()->detach($data['user_id']);

            if(count($userOrganizations) == 0) {
                UserLogin::where('user_id', $data['user_id'])->forceDelete();
                $user->favoriteProjects()->detach();
                $user->lmssetting()->forceDelete();
                $user->ssoLogin()->forceDelete();
                $user->organizations()->detach();
                TeamUserRole::where('user_id', $data['user_id'])->forceDelete();
                TeamProjectUser::where('user_id', $data['user_id'])->forceDelete();
                GroupProjectUser::where('user_id', $data['user_id'])->forceDelete();
                UserLogin::where('user_id', $data['user_id'])->forceDelete();
                DB::table('user_team')->where('user_id', $data['user_id'])->delete();

                $this->userRepository->forceDelete($user);
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error($e->getMessage());
            DB::rollBack();
        }

        return false;
    }

    /**
     * To fetch organization users
     *
     * @param $data
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationUsers($data, $organization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        $organizationUsers = $organization->users();

        if (isset($data['role'])) {
            $organizationUsers = $organizationUsers->wherePivot('organization_role_type_id', $data['role']);
        }

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $organizationUsers = $organizationUsers->orderBy($data['order_by_column'], $orderByType);
        }

        return $organizationUsers->withCount([
            'projects' => function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            },
            'teams' => function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            },
            'groups' => function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            }
        ])
            ->when($data['query'] ?? null, function ($query) use ($data) {
                $query->where('email', 'like', '%' . str_replace("_", "\_", strtolower($data['query'])) . '%');
                return $query;
            })
            ->paginate($perPage)->withQueryString();
    }

    /**
     * Get admin
     *
     * @param Organization $organization
     * @return Model
     */
    public function getAdmin($organization)
    {
        try {
            if ($organization) {
                return $organization->users()->wherePivot('organization_role_type_id',
                    config('constants.admin-role-id'))->first();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Invite member to the organization
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @param $data
     * @return bool
     */
    public function inviteMember($authenticatedUser, $organization, $data)
    {
        $invited = true;

        $user = $this->userRepository->findByField('email', $data['email']);
        $note = array_key_exists('note', $data) ? $data['note'] : '';

        if ($user) {
            $organization->users()->syncWithoutDetaching([$user->id => ['organization_role_type_id' => $data['role_id']]]);
            $user->notify(new OrganizationInvite($authenticatedUser, $organization, 'login', $note, null));
        } elseif ($data['email']) {
            $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
            $temp_user = new User(['email' => $data['email']]);
            $temp_user->notify(new OrganizationInvite($authenticatedUser, $organization, 'register', $note,
                $data['email']));

            $invited_user = array(
                'invited_email' => $data['email'],
                'organization_id' => $organization->id,
                'organization_role_type_id' => $data['role_id'],
                'token' => $token,
            );
            $this->invitedOrganizationUserRepository->create($invited_user);
        } else {
            $invited = false;
        }

        return $invited;
    }

    /**
     * To fetch organization user permissions
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationUserPermissions($authenticatedUser, $organization)
    {
        try {
            $orgUsrPermissions = $organization->userRoles()
                ->wherePivot('user_id', $authenticatedUser->id)
                ->with('permissions')
                ->first();

            $response['activeRole'] = $orgUsrPermissions['name'];
            $response['roleId'] = $orgUsrPermissions['id'];

            foreach ($orgUsrPermissions['permissions'] as $permission) {
                $response[$permission['feature']][] = $permission['name'];
            }
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * To fetch organization default permissions
     *
     * @return Model
     */
    public function fetchOrganizationDefaultPermissions()
    {
        try {
            return OrganizationPermissionType::where('feature', '<>', 'Group')->get()->groupBy('feature');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * To fetch organization data
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationData($authenticatedUser, $organization)
    {
        $userOrganization = $authenticatedUser->organizations()->find($organization->id);

        if (!$userOrganization) {
            $userOrganization = $organization;
        }

        return $userOrganization->load('parent')->loadCount(['projects', 'children', 'users', 'groups', 'teams']);
    }

    /**
     * Get the root organization
     *
     * @return mixed
     */
    public function getRootOrganization()
    {
        return $this->model->orderBy('id', 'asc')->first();
    }

    public function searchOrganizationByName($data)
    {
        return $this->model
            ->when($data['query'] ?? null, function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['query'] . '%');
                return $query;
            })
            ->get();
    }

    /**
     * Duplicate role for particular organization
     *
     * @param Organization $organization
     * @param string $roleName
     * @return Model
     */
    public function duplicateRole($organization, $roleName)
    {
        try {
            DB::beginTransaction();

            $topOrg = $this->model->whereHas('roles', function (Builder $query) use ($roleName) {
                $query->where('name', '=', $roleName);
            })->first();

            $topOrgRole = $topOrg->roles()->where('name', $roleName)->first();

            $subOrganizationUserRolesData['name'] = $topOrgRole->name;
            $subOrganizationUserRolesData['display_name'] = $topOrgRole->display_name;
            $subOrganizationUserRolesData['permissions'] = $topOrgRole->permissions->pluck('id')->toArray();

            $role = $this->addRole($organization, $subOrganizationUserRolesData);

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }

        return false;
    }

    /**
     * Assign default activity contents to organization
     *
     * @param $organization_id
     * @param $parent_id
     *
     * @return bool
     */
    public function assignDefaultActivityContents($organization_id, $parent_id)
    {
        // assign subjects
        $parentSubjects = Subject::where('organization_id', $parent_id)->get();

        foreach ($parentSubjects as $parentSubject) {
            $subject = [
                'name' => $parentSubject->name,
                'order' => $parentSubject->order,
                'created_at' => now(),
                'organization_id' => $organization_id,
            ];

            Subject::insertOrIgnore($subject);
        }

        // assign education levels
        $parentEducationLevels = EducationLevel::where('organization_id', $parent_id)->get();

        foreach ($parentEducationLevels as $parentEducationLevel) {
            $educationLevel = [
                'name' => $parentEducationLevel->name,
                'order' => $parentEducationLevel->order,
                'created_at' => now(),
                'organization_id' => $organization_id,
            ];

            EducationLevel::insertOrIgnore($educationLevel);
        }

        // assign activity layouts
        $parentActivityLayouts = ActivityLayout::where('organization_id', $parent_id)->get();

        foreach ($parentActivityLayouts as $parentActivityLayout) {
            $activityLayout = [
                'title' => $parentActivityLayout->title,
                'description' => $parentActivityLayout->description,
                'type' => $parentActivityLayout->type,
                'h5pLib' => $parentActivityLayout->h5pLib,
                'order' => $parentActivityLayout->order,
                'image' => $parentActivityLayout->image,
                'created_at' => now(),
                'organization_id' => $organization_id,
                'demo_activity_id' => $parentActivityLayout->demo_activity_id,
                'demo_video_id' => $parentActivityLayout->demo_video_id,
            ];

            ActivityLayout::insertOrIgnore($activityLayout);
        }

        // assign activity types
        $parentActivityTypes = ActivityType::where('organization_id', $parent_id)->get();

        foreach ($parentActivityTypes as $parentActivityType) {
            $activityType = [
                'title' => $parentActivityType->title,
                'order' => $parentActivityType->order,
                'image' => $parentActivityType->image,
                'created_at' => now(),
                'organization_id' => $organization_id,
            ];

            ActivityType::insertOrIgnore($activityType);
        }

        // assign activity items
        $parentActivityItems = ActivityItem::where('organization_id', $parent_id)->get();

        $newActivityTypes = DB::table('activity_types')->whereOrganizationId($organization_id)->pluck('id', 'title');
        $swapActivityTypes = DB::table('activity_types')->whereOrganizationId($parent_id)->pluck('title', 'id');

        foreach ($parentActivityItems as $parentActivityItem) {

            $activityTypeName = $swapActivityTypes[$parentActivityItem->activity_type_id];
            $activityTypeId = $newActivityTypes[$activityTypeName];

            $activityItem = [
                'title' => $parentActivityItem->title,
                'order' => $parentActivityItem->order,
                'image' => $parentActivityItem->image,
                'description' => $parentActivityItem->description,
                'activity_type_id' => $activityTypeId,
                'type' => $parentActivityItem->type,
                'h5pLib' => $parentActivityItem->h5pLib,
                'created_at' => now(),
                'demo_activity_id' => $parentActivityItem->demo_activity_id,
                'demo_video_id' => $parentActivityItem->demo_video_id,
                'organization_id' => $organization_id,
            ];

            ActivityItem::insertOrIgnore($activityItem);
        }

        // assign autor tags
        $parentAuthorTags = AuthorTag::where('organization_id', $parent_id)->get();

        foreach ($parentAuthorTags as $parentAuthorTag) {
            $authorTag = [
                'name' => $parentAuthorTag->name,
                'order' => $parentAuthorTag->order,
                'created_at' => now(),
                'organization_id' => $organization_id,
            ];

            AuthorTag::insertOrIgnore($authorTag);
        }
    }

    /**
     * Assign default media sources to organization
     *
     * @param $subOrganization
     * @param $parentOrganization
     *
     * @return bool
     */
    public function assignDefaultMediaSources($subOrganization, $parentOrganization)
    {
        $parentMediaSources = $parentOrganization->mediaSources()->pluck('media_source_id');

        $subOrganization->mediaSources()->sync($parentMediaSources);
    }

}
