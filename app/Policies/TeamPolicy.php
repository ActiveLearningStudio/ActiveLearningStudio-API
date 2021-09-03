<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Team;
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
     * Determine whether the user can update the team member role.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function updateMemberRole(User $user, Team $team)
    {
        return $user->hasTeamPermissionTo('team:add-team-user', $team);
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
     * @param Organization $suborganization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $suborganization)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can add project in the team.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
     public function addProjects(User $user, Team $team)
     {
         return $user->hasTeamPermissionTo('team:add-project', $team);
     }

     /**
     * Determine whether the user can remove project in the team.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function removeProject(User $user, Team $team)
    {
        return $user->hasTeamPermissionTo('team:remove-project', $team);
    }

     /**
     * Determine whether the user can add users in the team.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function addTeamUsers(User $user, Team $team)
    {
        return $user->hasTeamPermissionTo('team:add-team-user', $team);
    }

    /**
     * Determine whether the user can remove user in the team.
     *
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function removeTeamUsers(User $user, Team $team)
    {
        return $user->hasTeamPermissionTo('team:remove-team-user', $team);
    }
}
