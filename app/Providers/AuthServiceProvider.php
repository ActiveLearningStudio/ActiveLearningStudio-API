<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\ActivityItem;
use App\Models\ActivityType;
use App\Models\Group;
use App\Models\Playlist;
use App\Models\Project;
use App\Models\Organization;
use App\Models\Team;
use App\Policies\ActivityItemPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\ActivityTypePolicy;
use App\Policies\GroupPolicy;
use App\Policies\PlaylistPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\TeamPolicy;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        Playlist::class => PlaylistPolicy::class,
        Activity::class => ActivityPolicy::class,
        ActivityType::class => ActivityTypePolicy::class,
        ActivityItem::class => ActivityItemPolicy::class,
        Organization::class => OrganizationPolicy::class,
        Group::class => GroupPolicy::class,
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Adding Gates for Publishing
        Gate::define('publish-to-lms', function ($user, $project) {
            // If the project is either a) indexed-approved, and its visibility is public, or
            // b) if user is the owner, then allow to publish
            if (
                ($project->indexing === (int)config('constants.indexing-approved')
                && $project->organization_visibility_type_id === (int)config('constants.public-organization-visibility-type-id'))
                || $this->hasPermission($user, $project)
                || $user->hasPermissionTo('project:publish', $project->organization)
            ) {
                return true;
            }

            return false;

        });

        Gate::define('fetch-lms-course', function ($user, $project) {
            if (
                ($project->indexing === (int)config('constants.indexing-approved')
                && $project->organization_visibility_type_id === (int)config('constants.public-organization-visibility-type-id'))
                || $this->hasPermission($user, $project)
                || $user->hasPermissionTo('project:publish', $project->organization)
            ) {
                return true;
            }

            return false;
        });

        Passport::routes();

        // Implicitly grant "Super Admin" role all permissions
        // Gate::before(function ($user, $ability) {
        //     return $user->isAdmin() ? true : null;
        // });
    }

    /**
     * Determine whether the user has the permission to the project.
     *
     * @param User $user
     * @param Project $project
     * @return boolean
     * @access private
     */
    private function hasPermission(User $user, Project $project)
    {
        $project_users = $project->users;
        foreach ($project_users as $project_user) {
            if ($user->id === $project_user->id) {
                return true;
            }
        }

        return false;
    }
}
