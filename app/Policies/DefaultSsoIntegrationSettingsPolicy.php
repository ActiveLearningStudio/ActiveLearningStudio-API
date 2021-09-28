<?php

namespace App\Policies;

use App\Models\DefaultSsoIntegrationSettings;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultSsoIntegrationSettingsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\DefaultSsoIntegrationSettings  $defaultSsoIntegrationSettings
     * @return mixed
     */
    public function view(User $user, DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings)
    {
        return $user->isAdmin() || $user->id === $defaultSsoIntegrationSettings->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\DefaultSsoIntegrationSettings  $defaultSsoIntegrationSettings
     * @return mixed
     */
    public function update(User $user, DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings)
    {
        return $user->isAdmin() || $user->id === $defaultSsoIntegrationSettings->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DefaultSsoIntegrationSettings  $defaultSsoIntegrationSettings
     * @return mixed
     */
    public function delete(User $user, DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings)
    {
        return $user->isAdmin() || $user->id === $defaultSsoIntegrationSettings->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\DefaultSsoIntegrationSettings  $defaultSsoIntegrationSettings
     * @return mixed
     */
    public function restore(User $user, DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DefaultSsoIntegrationSettings  $defaultSsoIntegrationSettings
     * @return mixed
     */
    public function forceDelete(User $user, DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings)
    {
        return $user->isAdmin();
    }
}
