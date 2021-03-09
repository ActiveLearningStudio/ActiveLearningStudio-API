<?php

namespace App\Policies;

use App\Models\Organization;
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
     * @param Organization $suborganization
     * @return mixed
     */
    public function viewAny(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 2, 3]);
    }

    /**
     * Determine whether the user can view the project.
     *
     * @param User $user
     * @param Project $project
     * @param Organization $suborganization
     * @return mixed
     */
    public function view(User $user, Organization $suborganization)
    {
       return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 2, 3]);
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function create(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 2]);
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function update(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 2]);
    }

    /**
     * Determine whether the user can share the project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function share(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 2]);
    }

    /**
     * Determine whether the user can clone the project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function clone(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 3]);
    }

    /**
     * Determine whether the user can get favorite project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function favorite(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 3]);
    }

    /**
     * Determine whether the user can get recent project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function recent(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 3]);
    }

    /**
     * Determine whether the user can upload thumb.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function uploadThumb(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 3]);
    }

    /**
     * Determine whether the user can mark OR un-mark favorite project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function mark_favorite(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1, 3]);
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
    public function delete(User $user, Organization $suborganization)
    {
        return in_array($this->getUserDefaultOrganizationRole($user, $suborganization), [1]);
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
        if (!($project->organization_id == $user->default_organization)) {
            return false;
        }

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

    /**
     * Get user default organization role
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    private function getUserDefaultOrganizationRole(User $user, Organization  $organization)
    {
        $defaultOrganization = $user->organizations()->wherePivot('organization_id', $organization->id)->first();

        if ($defaultOrganization) {
            return $defaultOrganization->pivot->organization_role_type_id;
        } else if ($organization->parent) {
            return $this->getUserDefaultOrganizationRole($user, $organization->parent);
        }

        return 0;
    }
}
