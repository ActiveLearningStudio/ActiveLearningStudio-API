<?php

namespace App\Policies\C2E\MediaCatalog;

use App\Models\C2E\MediaCatalog\MediaCatalogAPISetting;
use App\Models\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaCatalogAPISettingPolicy
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
        if ($user->hasPermissionTo('c2e-media-catalog:view-author', $organization)) {
            return true;
        }

        return $user->hasPermissionTo('organization:view-c2e-media-catalog-api-setting', $organization);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param MediaCatalogAPISetting $setting
     * @return mixed
     */
    public function view(User $user, MediaCatalogAPISetting $setting)
    {
        return $user->hasPermissionTo('organization:view-c2e-media-catalog-api-setting', $setting->organization);
    }

    /**
     * Determine whether the user can create setting.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        return $user->hasPermissionTo('organization:create-c2e-media-catalog-api-setting', $organization);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param MediaCatalogAPISetting $setting
     * @return mixed
     */
    public function update(User $user, MediaCatalogAPISetting $setting)
    {
        return $user->hasPermissionTo('organization:edit-c2e-media-catalog-api-setting', $setting->organization);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param MediaCatalogAPISetting $setting
     * @return mixed
     */
    public function delete(User $user, MediaCatalogAPISetting $setting)
    {
        return $user->hasPermissionTo('organization:delete-c2e-media-catalog-api-setting', $setting->organization);
    }

}
