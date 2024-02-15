<?php

namespace App\Policies\C2E\Publisher;

use App\Models\C2E\Publisher\Publisher;
use App\Models\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublisherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function viewAny(User $user, Organization $organization)
    {
        if ($user->hasPermissionTo('c2e-publisher:view-author', $organization)) {
            return true;
        }

        return $user->hasPermissionTo('organization:view-c2e-publisher', $organization);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Publisher $publisher
     * @return mixed
     */
    public function view(User $user, Publisher $publisher)
    {
        return $user->hasPermissionTo('organization:view-c2e-publisher', $publisher->organization);
    }

    /**
     * Determine whether the user can create publisher.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:create-c2e-publisher', $organization);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Publisher $publisher
     * @return mixed
     */
    public function update(User $user, Publisher $publisher)
    {
        return $user->hasPermissionTo('organization:edit-c2e-publisher', $publisher->organization);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Publisher $publisher
     * @return mixed
     */
    public function delete(User $user, Publisher $publisher)
    {
        return $user->hasPermissionTo('organization:delete-c2e-publisher', $publisher->organization);
    }

    /**
     * Determine whether the "author" user can view stores.
     *
     * @param User $user
     * @param Publisher $publisher
     * @return mixed
     */
    public function viewAnyStore(User $user, Publisher $publisher)
    {
        return $user->hasPermissionTo('c2e-publisher:view-stores', $publisher->organization);
    }

    /**
     * Determine whether the user can publish independent activity
     *
     * @param User $user
     * @param Publisher $publisher
     * @return mixed
     */
    public function publishIndependentActivity(User $user, Publisher $publisher)
    {
        return $user->hasPermissionTo('c2e-publisher:publish-independent-activity', $publisher->organization);
    }
}
