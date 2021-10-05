<?php

namespace App\Policies;

use App\Models\DefaultSsoIntegrationSettings;
use App\Models\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultSsoIntegrationSettingsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @param  Organization $organization
     * @return mixed
     */
    public function viewAny(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:view-default-sso', $organization);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:view-default-sso', $organization);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:create-default-sso', $organization);
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
        return $user->hasPermissionTo('organization:update-default-sso', $organization);
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
        return $user->hasPermissionTo('organization:delete-default-sso', $organization);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function restore(User $user, Organization $organization)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $organization)
    {
        //
    }
}
