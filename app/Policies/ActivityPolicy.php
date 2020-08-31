<?php

namespace App\Policies;

use App\Models\Activity;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
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
     * @param Activity $activity
     * @return mixed
     */
    public function view(User $user, Activity $activity)
    {
        // TODO: need to update
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
        // TODO: need to update
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Activity $activity
     * @return mixed
     */
    public function update(User $user, Activity $activity)
    {
        // TODO: need to update
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Activity $activity
     * @return mixed
     */
    public function delete(User $user, Activity $activity)
    {
        // TODO: need to update
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Activity $activity
     * @return mixed
     */
    public function restore(User $user, Activity $activity)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Activity $activity
     * @return mixed
     */
    public function forceDelete(User $user, Activity $activity)
    {
        return $user->isAdmin();
    }
}
