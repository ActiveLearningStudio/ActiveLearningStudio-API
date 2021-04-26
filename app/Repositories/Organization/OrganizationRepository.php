<?php

namespace App\Repositories\Organization;

use App\Models\Organization;
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

class OrganizationRepository extends BaseRepository implements OrganizationRepositoryInterface
{
    private $userRepository;
    private $invitedOrganizationUserRepository;

    /**
     * Organization Repository constructor.
     *
     * @param Organization $model
     * @param UserRepositoryInterface $userRepository
     * @param InvitedOrganizationUserRepositoryInterface $invitedOrganizationUserRepository
     */
    public function __construct(
        Organization $model,
        UserRepositoryInterface $userRepository,
        InvitedOrganizationUserRepositoryInterface $invitedOrganizationUserRepository
    )
    {
        $this->userRepository = $userRepository;
        parent::__construct($model);
        $this->invitedOrganizationUserRepository = $invitedOrganizationUserRepository;
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
        if (isset($data['query']) && !empty($data['query'])) {
            $parentIds = $this->getSuborganizationIds($organization);
        } else {
            $parentIds[] = $organization->id;
        }

        return $this->model
                ->with(['parent', 'admins'])
                ->withCount(['projects', 'children', 'users', 'groups', 'teams'])
                ->whereIn('parent_id', $parentIds)
                ->when($data['query'] ?? null, function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['query'] . '%');
                    return $query;
                })
                ->get();
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
     * To create a suborganization
     *
     * @param Organization $organization
     * @param $data
     * @return Response
     * @throws GeneralException
     */
    public function createSuborganization($organization, $data)
    {
        $userRoles = array_fill_keys($data['admins'], ['organization_role_type_id' => config('constants.admin-role-id')]);

        foreach ($data['users'] as $user) {
            $userRoles[$user['user_id']] = ['organization_role_type_id' => $user['role_id']];
        }

        try {
            DB::beginTransaction();

            $suborganization = $organization->children()->create(Arr::except($data, ['admins', 'users']));

            if ($suborganization) {
                $suborganization->users()->sync($userRoles);
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
        $userRoles = array_fill_keys($data['admins'], ['organization_role_type_id' => config('constants.admin-role-id')]);

        foreach ($data['users'] as $user) {
            $userRoles[$user['user_id']] = ['organization_role_type_id' => $user['role_id']];
        }

        try {
            DB::beginTransaction();

            $is_updated = $organization->update(Arr::except($data, ['admins', 'users']));
            // update the organization data
            if ($is_updated) {
                $organization->users()->sync($userRoles);
                DB::commit();
            }

            return $is_updated;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
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
        $organizationUserIds = $organization->users()->wherePivot('organization_role_type_id', '<>', config('constants.admin-role-id'))->get()->modelKeys();
        $organizationAdminUserIds = $organization->users()->wherePivot('organization_role_type_id', config('constants.admin-role-id'))->get()->modelKeys();
        $parentOrganizationUserIds = $this->getParentOrganizationUserIds([], $organization, $organizationAdminUserIds);

        if ($data['page'] === 'create') {
            $userInIds = array_merge($organizationUserIds, $parentOrganizationUserIds);
        } else if ($data['page'] === 'update') {
            $userInIds = array_diff($parentOrganizationUserIds, $organizationUserIds);
        }

        $this->query = $this->userRepository->model->when($data['query'] ?? null, function ($query) use ($data) {
            $query->search(['email'], $data['query']);
            return $query;
        });

        return $this->query->whereIn('id', $userInIds)->orderBy('first_name', 'asc')->paginate();
    }

    /**
     * Get the parent organizations user ids
     *
     * @param $userIds
     * @param $organization
     * @param $organizationAdminUserIds
     * @return array
     */
    public function getParentOrganizationUserIds($userIds, $organization, $organizationAdminUserIds)
    {
        $parentOrganization = $organization->parent;

        if ($parentOrganization) {
            $ids = $parentOrganization->users()->wherePivot('organization_role_type_id', '<>', config('constants.admin-role-id'))->get()->modelKeys();
            $adminIds = $parentOrganization->users()->wherePivot('organization_role_type_id', config('constants.admin-role-id'))->get()->modelKeys();
            $ids = array_diff($ids, $organizationAdminUserIds);
            $organizationAdminUserIds = array_merge($organizationAdminUserIds, $adminIds);
            $userIds = array_merge($userIds, $ids);

            $userIds = $this->getParentOrganizationUserIds($userIds, $parentOrganization, $organizationAdminUserIds);
        }

        return $userIds;
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

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }

        return false;
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
            $organization->users()->updateExistingPivot($data['user_id'], ['organization_role_type_id' => $data['role_id']]);

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
        try {
            $organization->users()->detach($data['user_id']);

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
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

        return $organization->users()->withCount([
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
            $query->where('email', 'like', '%' . $data['query'] . '%');
            return $query;
        })
        ->paginate($perPage);
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
                return $organization->users()->wherePivot('organization_role_type_id', config('constants.admin-role-id'))->first();
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
            $temp_user->notify(new OrganizationInvite($authenticatedUser, $organization, 'register', $note, $data['email']));

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
}
