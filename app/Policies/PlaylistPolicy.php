<?php

namespace App\Policies;

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
     * @param Project $project
     * @return mixed
     */
    public function viewAny(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:view-playlist', $team);
        } else {
            return $user->hasPermissionTo('playlist:view', $project->organization);
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:view-playlist', $team);
        } else {
            return $user->hasPermissionTo('playlist:view', $project->organization);
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function create(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:add-playlist', $team);
        } else {
            return $user->hasPermissionTo('playlist:create', $project->organization);
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:edit-playlist', $team);
        } else {
            return $user->hasPermissionTo('playlist:edit', $project->organization);
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:delete-playlist', $team);
        } else {
            return $user->hasPermissionTo('playlist:delete', $project->organization);
        }
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
     * @param Playlist $playlist
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
