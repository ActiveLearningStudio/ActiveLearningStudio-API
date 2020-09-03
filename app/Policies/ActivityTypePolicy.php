<?php

namespace App\Policies;

use App\Models\ActivityType;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityTypePolicy
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
     * @param ActivityType $activityType
     * @return mixed
     */
    public function view(User $user, ActivityType $activityType)
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
     * @param ActivityType $activityType
     * @return mixed
     */
    public function update(User $user, ActivityType $activityType)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ActivityType $activityType
     * @return mixed
     */
    public function delete(User $user, ActivityType $activityType)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ActivityType $activityType
     * @return mixed
     */
    public function restore(User $user, ActivityType $activityType)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ActivityType $activityType
     * @return mixed
     */
    public function forceDelete(User $user, ActivityType $activityType)
    {
        return $user->isAdmin();
    }
}
