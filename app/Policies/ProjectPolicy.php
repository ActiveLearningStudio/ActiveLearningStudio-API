<?php

namespace App\Policies;

use App\Models\Pivots\TeamProjectUser;
use App\Models\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any projects.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        return $user->isAdmin() || $this->hasPermission($user, $project);
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $user->isAdmin() || $this->hasPermission($user, $project);
    }

    /**
     * Determine whether the user can share the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function share(User $user, Project $project)
    {
        return $user->isAdmin() || $this->hasPermission($user, $project);
    }

    /**
     * Determine whether the user can remove share the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function removeShare(User $user, Project $project)
    {
        return $user->isAdmin() || $this->hasPermission($user, $project);
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return $user->isAdmin() || $this->hasPermission($user, $project, 'owner');
    }

    /**
     * Determine whether the user can restore the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function restore(User $user, Project $project)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function forceDelete(User $user, Project $project)
    {
        return $user->isAdmin();
    }

    private function hasPermission(User $user, Project $project, $role = null)
    {
        $project_users = $project->users;
        foreach ($project_users as $project_user) {
            if ($user->id === $project_user->id && (!$role || $role === $project_user->pivot->role)) {
                return true;
            }
        }

        $project_teams = $project->teams;
        foreach ($project_teams as $project_team) {
            $team_project_user = TeamProjectUser::where('team_id', $project_team->id)
                ->where('project_id', $project->id)
                ->where('user_id', $user->id)
                ->first();
            if ($team_project_user) {
                return true;
            }
        }

        return false;
    }
}
