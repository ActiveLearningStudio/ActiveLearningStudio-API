<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Pivots\TeamProjectUser;
use App\Models\Playlist;
use App\Models\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('playlist:view', $organization);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('playlist:create', $organization);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('playlist:edit', $organization);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('playlist:delete', $organization);
    }

    /**
     * Determine whether the user can clone the model.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function clone(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('playlist:clone', $organization);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function restore(User $user, Playlist $playlist)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function forceDelete(User $user, Playlist $playlist)
    {
        return $user->isAdmin();
    }

}
