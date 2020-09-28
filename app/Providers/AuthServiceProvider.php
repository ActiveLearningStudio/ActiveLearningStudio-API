<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\ActivityItem;
use App\Models\ActivityType;
use App\Models\Playlist;
use App\Models\Project;
use App\Policies\ActivityItemPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\ActivityTypePolicy;
use App\Policies\PlaylistPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
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
            return $user->isAdmin() || $this->hasPermission($user, $project);
        });

        Gate::define('fetch-lms-course', function ($user, $project) {
            return $user->isAdmin() || $this->hasPermission($user, $project);
        });

        Passport::routes();
    }

    /**
     * Determine whether the user has the permission to the project.
     *
     * @param User $user
     * @param Project $project
     * @param $role
     * @return boolean
     * @access private
     */
    private function hasPermission(User $user, Project $project, $role = null)
    {
        $projectUsers = $project->users;
        foreach ($projectUsers as $projectUser) {
            if ($user->id === $projectUser->id && (!$role || $role === $projectUser->pivot->role)) {
                return true;
            }
        }

        return false;
    }
}
