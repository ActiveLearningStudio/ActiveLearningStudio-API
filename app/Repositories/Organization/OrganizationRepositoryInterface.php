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
     * Get ids for parent organizations
     *
     * @param Organization $organization
     * @param array $organizationIds
     * @return array $ids
     */
    public function getParentOrganizationIds($organization, $organizationIds = []);

    /**
     * Get ids for parent organizations
     *
     * @param Organization $organization
     * @param array $organizationIds
     * @return array $ids
     */
    public function getParentChildrenOrganizationIds($organization);

    /**
     * To create a suborganization
     *
     * @param Organization $organization
     * @param $data
     * @param User $authenticatedUser
     */
    public function createSuborganization($organization, $data, $authenticatedUser);

    /**
     * Update suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function update($organization, $data);

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
     * Add role for the specified suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function addRole($organization, $data);

     /**
     * Update permissions for the specified role in particular suborganization
     *
     * @param array $data
     * @return Model
     */
    public function updateRole($data);

    /**
     * Delete the specified user in a particular suborganization
     *
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function deleteUser($organization, $data);

    /**
     * Remove the specified user from a particular organization
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @param array $data
     * @return Model
     */
    public function removeUser($authenticatedUser, $organization, $data);

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
     * To fetch organization default permissions
     *
     * @return Model
     */
    public function fetchOrganizationDefaultPermissions();

    /**
     * To fetch organization data
     *
     * @param User $authenticatedUser
     * @param Organization $organization
     * @return Model
     */
    public function fetchOrganizationData($authenticatedUser, $organization);

    /**
     * Get the root organization
     *
     * @return mixed
     */
    public function getRootOrganization();

    /**
     * Get a list of the organization users.
     *
     * @param $data
     * @param Organization $organization
     * @return mixed
     */
    public function getOrgUsers($data, $organization);

    /**
     * Get the children organizations user ids
     *
     * @param $organization
     * @return array
     */
    public function getChildrenOrganizationUserIds($organization);
}
