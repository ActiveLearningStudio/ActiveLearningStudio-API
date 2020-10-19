<?php

namespace App\Policies;

use App\Models\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
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
     * @param Team $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        return $user->isAdmin() || $this->hasPermission($user, $team);
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
     * @param Team $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        return $user->isAdmin() || $this->hasPermission($user, $team);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        return $user->isAdmin() || $this->hasPermission($user, $team, 'owner');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function restore(User $user, Team $team)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function forceDelete(User $user, Team $team)
    {
        return $user->isAdmin();
    }

    private function hasPermission(User $user, Team $team, $role = null)
    {
        $team_users = $team->users;
        foreach ($team_users as $team_user) {
            if ($user->id === $team_user->id && (!$role || $role === $team_user->pivot->role)) {
                return true;
            }
        }

        return false;
    }
}
