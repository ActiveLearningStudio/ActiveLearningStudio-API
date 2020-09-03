<?php

namespace App\Policies;

use App\Models\ActivityItem;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityItemPolicy
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
     * @param ActivityItem $activityItem
     * @return mixed
     */
    public function view(User $user, ActivityItem $activityItem)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param ActivityItem $activityItem
     * @return mixed
     */
    public function update(User $user, ActivityItem $activityItem)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ActivityItem $activityItem
     * @return mixed
     */
    public function delete(User $user, ActivityItem $activityItem)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ActivityItem $activityItem
     * @return mixed
     */
    public function restore(User $user, ActivityItem $activityItem)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ActivityItem $activityItem
     * @return mixed
     */
    public function forceDelete(User $user, ActivityItem $activityItem)
    {
        return $user->isAdmin();
    }
}
