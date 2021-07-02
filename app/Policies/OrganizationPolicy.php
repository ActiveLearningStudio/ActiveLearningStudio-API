<?php

namespace App\Policies;

use App\Models\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any suborganization.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function viewAny(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:view', $organization);
    }

    /**
     * Determine whether the user can view the suborganization.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:view', $organization);
    }

    /**
     * Determine whether the user can create suborganization.
     *
     * @param  User  $user
     * @param  Organization $organization
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:create', $organization);
    }

    /**
     * Determine whether the user can upload thumb.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function uploadThumb(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:upload-thumb', $organization);
    }

    /**
     * Determine whether the user can view member options.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function viewMemberOptions(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:view', $organization);
    }

    /**
     * Determine whether the user can add user.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function addUser(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:add-user', $organization);
    }

    /**
     * Determine whether the user can add role.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function addRole(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:add-role', $organization);
    }

    /**
     * Determine whether the user can update role.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function updateRole(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:edit-role', $organization);
    }

    /**
     * Determine whether the user can invite members.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function inviteMembers(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:invite-members', $organization);
    }

    /**
     * Determine whether the user can update user.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function updateUser(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:update-user', $organization);
    }

    /**
     * Determine whether the user can delete a user for all organizations.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function deleteUser(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:delete-user', $organization);
    }

    /**
     * Determine whether the user can remove a user from a particular organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function removeUser(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:remove-user', $organization);
    }

    /**
     * Determine whether the user can view any organization's user.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function viewAnyUser(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:view-user', $organization);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:edit', $organization);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:delete', $organization);
    }

    /**
     * Determine whether the user can do advance search in suborganization.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function advanceSearch(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('search:advance', $organization);
    }

    /**
     * Determine whether the user can do dashboard search in suborganization.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function dashboardSearch(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('search:dashboard', $organization);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function restore(User $user, Organization $organization)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $organization)
    {
        //
    }
}
