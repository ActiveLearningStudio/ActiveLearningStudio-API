<?php

namespace App\Policies;

use App\Models\IndependentActivity;
use App\Models\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Repositories\Organization\OrganizationRepositoryInterface;

class IndependentActivityPolicy
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
        return $user->hasPermissionTo('independent-activity:view', $organization);
    }

    /**
     * Determine whether the "author" user can view any models.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function viewAuthor(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('independent-activity:view-author', $organization);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @return mixed
     */
    public function view(User $user, IndependentActivity $independentActivity)
    {
        if ($user->hasPermissionTo('independent-activity:view-author', $independentActivity->organization)) {
            return true;
        }

        return $user->hasPermissionTo('independent-activity:view', $independentActivity->organization);
    }

    /**
     * Determine whether the user can create independent activity.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        if ($user->hasPermissionTo('independent-activity:edit-author', $organization)) {
            return true;
        }

        return $user->hasPermissionTo('independent-activity:create', $organization);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @return mixed
     */
    public function update(User $user, IndependentActivity $independentActivity)
    {
        if ($user->hasPermissionTo('organization:view', $independentActivity->organization)) {
            return true;
        }

        if ($user->hasPermissionTo('independent-activity:edit-author', $independentActivity->organization)) {
            return true;
        }

        return $user->hasPermissionTo('independent-activity:edit', $independentActivity->organization);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @return mixed
     */
    public function delete(User $user, IndependentActivity $independentActivity)
    {
        if ($user->hasPermissionTo('independent-activity:edit-author', $independentActivity->organization)) {
            return true;
        }

        return $user->hasPermissionTo('independent-activity:delete', $independentActivity->organization);
    }

    /**
     * Determine whether the user can share the model.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @return mixed
     */
    public function share(User $user, IndependentActivity $independentActivity)
    {
        if ($user->hasPermissionTo('independent-activity:edit-author', $independentActivity->organization)) {
            return true;
        }

        return $user->hasPermissionTo('independent-activity:share', $independentActivity->organization);
    }

    /**
     * Determine whether the user can preview the independent activity in search.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @param Organization $organization
     * @return mixed
     */
    public function searchPreview(User $user, IndependentActivity $independentActivity, Organization $organization)
    {
        if ($user->hasPermissionTo('organization:view', $organization)) {
            return true;
        }

        if ($user->hasPermissionTo('independent-activity:view-author', $organization)) {
            return true;
        }

        if ($independentActivity->user->id === $user->id) {
            return true;
        }

        if ($independentActivity->indexing === (int)config('constants.indexing-approved')) {
            if ($independentActivity->organization_visibility_type_id === (int)config('constants.public-organization-visibility-type-id')) {
                return true;
            }

            if ($independentActivity->organization_visibility_type_id === (int)config('constants.protected-organization-visibility-type-id')) {
                if ($independentActivity->organization_id === $organization->id) {
                    return true;
                }
            }

            if ($independentActivity->organization_visibility_type_id === (int)config('constants.global-organization-visibility-type-id')) {
                $organizationRepository = resolve(OrganizationRepositoryInterface::class);
                $organizationParentChildrenIds = $organizationRepository->getParentChildrenOrganizationIds($organization);

                if (in_array($independentActivity->organization_id, $organizationParentChildrenIds)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can clone the model.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @return mixed
     */
    public function clone(User $user, IndependentActivity $independentActivity)
    {
        if (
            $independentActivity->indexing === (int)config('constants.indexing-approved')
            && $independentActivity->organization_visibility_type_id === (int)config('constants.public-organization-visibility-type-id')
        ) {
            return true;
        }

        if ($user->hasPermissionTo('independent-activity:edit-author', $independentActivity->organization)) {
            return true;
        }

        return $user->hasPermissionTo('independent-activity:duplicate', $independentActivity->organization);
    }

    /**
     * Determine whether the user can export the activity.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @return mixed
     */
    public function export(User $user, IndependentActivity $independentActivity)
    {
        return $user->hasPermissionTo('independent-activity:export', $independentActivity->organization);
    }

    /**
     * Determine whether the user can import the activity.
     *
     * @param User $user
     * @param IndependentActivity $independentActivity
     * @return mixed
     */
    public function import(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('independent-activity:import', $organization);
    }
}
