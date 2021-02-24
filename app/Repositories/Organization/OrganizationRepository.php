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
     * @param $parent_id
     * @return Organization $organizations
     */
    public function fetchSuborganizations($parent_id)
    {
        return $this->model->where('parent_id', $parent_id)->get();
    }

    /**
     * To create a suborganization
     *
     * @param $data
     * @return Response
     * @throws GeneralException
     */
    public function createSuborganization($data)
    {
        $user = $this->userRepository->find($data['admin_id']);

        try {
            return $user->organizations()->create(Arr::except($data, ['admin_id']), ['organization_role_type_id' => 1]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Update suborganization
     *
     * @param $id
     * @param array $data
     * @return Model
     */
    public function update($id, $data)
    {
        $organization = $this->find($id);
        try {
            $is_updated = $organization->update(Arr::except($data, ['admin_id']));
            // update the organization data
            if ($is_updated) {
                $oldAdmin = $organization->users()->wherePivot('organization_role_type_id', 1)->first();

                if ($oldAdmin->id != $data['admin_id']) {
                    $organization->users()->detach($oldAdmin->id);

                    $organization->users()->attach($data['admin_id'], ['organization_role_type_id' => 1]);
                }
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
     * @param $id
     * @return mixed
     */
    public function getMemberOptions($data, $id)
    {
        if ($organization = $this->model->whereId($id)->first()) {
            $userNotInIds = $organization->users->modelKeys();

            $userInIds = $this->getParentOrganizationUserIds([], $organization);

            $userInIds = array_diff($userInIds, $userNotInIds);

            $this->query = $this->userRepository->model->when($data['query'] ?? null, function ($query) use ($data) {
                $query->search(['email'], $data['query']);
                return $query;
            });

            return $this->query->whereNotIn('id', $userNotInIds)->whereIn('id', $userInIds)->orderBy('first_name', 'asc')->paginate();
        }
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
        if ($parentOrganization = $organization->parent) {
            $ids = $parentOrganization->users->modelKeys();
            $userIds = array_merge($userIds, $ids);

            $userIds = $this->getParentOrganizationUserIds($userIds, $parentOrganization);
        }

        return $userIds;
    }

    /**
     * Add user for the specified role in particular suborganization
     *
     * @param $id
     * @param array $data
     * @return Model
     */
    public function addUser($id, $data)
    {
        $organization = $this->find($id);

        try {
            $organization->users()->attach($data['user_id'], ['organization_role_type_id' => $data['role_id']]);

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * Update user for the specified role in particular suborganization
     *
     * @param $id
     * @param array $data
     * @return Model
     */
    public function updateUser($id, $data)
    {
        $organization = $this->find($id);

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
     * @param $id
     * @param array $data
     * @return Model
     */
    public function deleteUser($id, $data)
    {
        $organization = $this->find($id);

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
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationUsers($organization)
    {
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
        ->paginate();
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
                return $organization->users()->wherePivot('organization_role_type_id', 1)->first();
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
            $organization->users()->attach($user, ['organization_role_type_id' => $data['role_id']]);
            $user->notify(new OrganizationInvite($authenticatedUser, $organization, $token, $note));
        } elseif ($data['email']) {
            $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
            $temp_user = new User(['email' => $data['email']]);
            $temp_user->notify(new OrganizationInvite($authenticatedUser, $organization, $token, $note));

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
}
