<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\User;
use App\Models\Project;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
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

        Passport::routes();
    }

    /**
     * Determine whether the user has the permission to the project.
     *
     * @param User $user
     * @param Project $project
     * @param role $role
     * @access private
     * @return boolean
     */
    private function hasPermission(User $user, Project $project, $role = null)
    {
        $project_users = $project->users;
        foreach ($project_users as $project_user) {
            if ($user->id === $project_user->id && (!$role || $role === $project_user->pivot->role)) {
                return true;
            }
        }

        return false;
    }
}
