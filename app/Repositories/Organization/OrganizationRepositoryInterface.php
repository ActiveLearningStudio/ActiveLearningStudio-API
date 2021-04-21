<?php

namespace App\Repositories\Organization;

use App\User;
use App\Models\Organization;
use App\Repositories\EloquentRepositoryInterface;

interface OrganizationRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * To fetch suborganizations
     *
     * @param $data
     * @param Organization $organization
     * @return Organization $organizations
     */
    public function fetchSuborganizations($data, $organization);

    /**
     * Get ids for nested suborganizations
     *
     * @param Organization $organization
     * @param array $organizationIds
     * @return array $ids
     */
    public function getSuborganizationIds($organization, $organizationIds = []);

    /**
     * To create a suborganization
     *
     * @param Organization $organization
     * @param $data
     */
    public function createSuborganization($organization, $data);

    /**
     * To delete a suborganization
     *
     * @param $id
     */
    public function deleteSuborganization($id);

    /**
     * To get the users to add in specific suborganization
     *
     * @param $data
     * @param Organization $organization
     * @return mixed
     */
    public function getMemberOptions($data, $organization);

    /**
     * Add user for the specified role in default suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function addUser($organization, $data);

    /**
     * Update user for the specified role in particular suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function updateUser($organization, $data);

    /**
     * Delete the specified user in a particular suborganization
     *
     * @param $id
     * @param array $data
     * @return Model
     */
    public function deleteUser($id, $data);

    /**
     * To fetch organization users
     *
     * @param $data
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationUsers($data, $organization);

    /**
     * Get admin
     *
     * @param Organization $organization
     * @return Model
     */
    public function getAdmin($organization);

    /**
     * Invite member to the organization
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @param $data
     * @return bool
     */
    public function inviteMember($authenticatedUser, $organization, $data);

    /**
     * To fetch organization user permissions
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationUserPermissions($authenticatedUser, $organization);

    /**
     * To fetch organization data
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationData($authenticatedUser, $organization);
}
