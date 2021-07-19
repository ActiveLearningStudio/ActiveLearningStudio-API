<?php

namespace App\Policies;

use App\Models\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any groups.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function viewAny(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('group:view', $suborganization);
    }

    /**
     * Determine whether the user can view the group.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function view(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('group:view', $suborganization);
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('group:create', $suborganization);
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function update(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('group:edit', $suborganization);
    }

    /**
     * Determine whether the user can share the group.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function share(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('group:share', $suborganization);
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function delete(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('group:delete', $suborganization);
    }

    /**
     * Determine whether the user can permanently delete the group.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $suborganization)
    {
        return $user->isAdmin();
    }

}
