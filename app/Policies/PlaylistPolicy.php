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
     * @param $team
     * @return mixed
     */
    public function create(User $user, Organization $organization, $team)
    {
        return $user->hasPermissionTo('playlist:create', $organization) || $user->hasTeamPermissionTo('team:add-playlist', $team);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Organization $organization
     * @param $team
     * @return mixed
     */
    public function update(User $user, Organization $organization, $team)
    {
        return $user->hasPermissionTo('playlist:edit', $organization) || $user->hasTeamPermissionTo('team:edit-playlist', $team);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Organization $organization
     * @param $team
     * @return mixed
     */
    public function delete(User $user, Organization $organization, $team)
    {
        return $user->hasPermissionTo('playlist:delete', $organization) || $user->hasTeamPermissionTo('team:delete-playlist', $team);
    }

    /**
     * Determine whether the user can clone the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function clone(User $user, Project $project)
    {
        if (
            $project->indexing === (int)config('constants.indexing-approved')
            && $project->organization_visibility_type_id === (int)config('constants.public-organization-visibility-type-id')
        ) {
            return true;
        }

        return $user->hasPermissionTo('playlist:duplicate', $project->organization);
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
