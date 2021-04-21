<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Pivots\TeamProjectUser;
use App\Models\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any teams.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function viewAny(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('team:view', $suborganization);
    }

    /**
     * Determine whether the user can view the team.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function view(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('team:view', $suborganization);
    }

    /**
     * Determine whether the user can create team.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function create(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('team:create', $suborganization);
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function update(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('team:edit', $suborganization);
    }

    /**
     * Determine whether the user can share the team.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function share(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('team:share', $suborganization);
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function delete(User $user,  Organization $suborganization)
    {
        return $user->hasPermissionTo('team:delete', $suborganization);
    }

    /**
     * Determine whether the user can permanently delete the team.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function forceDelete(User $user, Project $project)
    {
        return $user->isAdmin();
    }
}
