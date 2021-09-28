<?php

namespace App\Policies;

use App\Models\Organization;
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
        return $user->hasPermissionTo('project:view', $suborganization);
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
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:view-project', $team);
        } else {
            return $user->hasPermissionTo('project:view', $project->organization);
        }
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
        return $user->hasPermissionTo('project:create', $suborganization);
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
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:edit-project', $team);
        } else {
            return $user->hasPermissionTo('project:edit', $project->organization);
        }
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
        if ($project->indexing !== (int)config('constants.indexing-approved')) {
            return false;
        }

        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:share-project', $team);
        } else {
            return $user->hasPermissionTo('project:share', $project->organization);
        }
    }

    /**
     * Determine whether the user can clone the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function clone(User $user, Project $project)
    {
        if (
            $project->indexing === (int)config('constants.indexing-approved')
            && $project->organization_visibility_type_id === (int)config('constants.public-organization-visibility-type-id')
        ) {
            return true;
        }

        return $user->hasPermissionTo('project:clone', $project->organization);
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
        return $user->hasPermissionTo('project:favorite', $suborganization);
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
        return $user->hasPermissionTo('project:recent', $suborganization);
    }

    /**
     * Determine whether the user can upload thumb.
     *
     * @param User $user
     * @param Organization $organization
     * @param $project_id
     * @return mixed
     */
    public function uploadThumb(User $user, Organization $suborganization, $project_id)
    {
        if ($project_id) {
            $project = Project::find($project_id);
            $team = $project->team;
            if ($team) {
                return $user->hasTeamPermissionTo('team:edit-project', $team);
            }
        }

        return $user->hasPermissionTo('project:upload-thumb', $suborganization);
    }

    /**
     * Determine whether the user can mark OR un-mark favorite project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function markFavorite(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('project:favorite', $suborganization);
    }

    /**
     * Determine whether the user can remove share the project.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function removeShare(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('project:share', $suborganization);
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
        return $user->hasPermissionTo('project:delete', $suborganization);
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

    /**
     * Determine whether the user can export the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function export(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('project:export', $suborganization);
    }

    /**
     * Determine whether the user can import the project.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function import(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('project:import', $suborganization);
    }

}
