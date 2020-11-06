<?php

namespace App\Policies;

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
     * @param Playlist $playlist
     * @return mixed
     */
    public function view(User $user, Playlist $playlist)
    {
        return $user->isAdmin() || $this->hasPermission($user, $playlist->project);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function update(User $user, Playlist $playlist)
    {
        return $user->isAdmin() || $this->hasPermission($user, $playlist->project);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function delete(User $user, Playlist $playlist)
    {
        return $user->isAdmin() || $this->hasPermission($user, $playlist->project);
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

    private function hasPermission(User $user, Project $project)
    {
        $project_users = $project->users;
        foreach ($project_users as $project_user) {
            if ($user->id === $project_user->id) {
                return true;
            }
        }

        $project_teams = $project->teams;
        foreach ($project_teams as $project_team) {
            $team_project_user = TeamProjectUser::where('team_id', $project_team->id)
                ->where('project_id', $project->id)
                ->where('user_id', $user->id)
                ->first();
            if ($team_project_user) {
                return true;
            }
        }

        return false;
    }
}
