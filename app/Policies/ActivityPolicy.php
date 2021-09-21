<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\Organization;
use App\Models\Pivots\TeamProjectUser;
use App\Models\Project;
use App\Models\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param Organization $suborganization
     * @return mixed
     */
    public function viewAny(User $user, Organization $suborganization)
    {
        return $user->hasPermissionTo('activity:view', $suborganization);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:view-activity', $team);
        } else {
            return $user->hasPermissionTo('activity:view', $project->organization);
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function create(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:add-activity', $team);
        } else {
            return $user->hasPermissionTo('activity:create', $project->organization);
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:edit-activity', $team);
        } else {
            return $user->hasPermissionTo('activity:edit', $project->organization);
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:delete-activity', $team);
        } else {
            return $user->hasPermissionTo('activity:delete', $project->organization);
        }
    }

    /**
     * Determine whether the user can share the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function share(User $user, Project $project)
    {
        $team = $project->team;
        if ($team) {
            return $user->hasTeamPermissionTo('team:share-activity', $team);
        } else {
            return $user->hasPermissionTo('activity:share', $project->organization);
        }
    }

    /**
     * Determine whether the user can clone the model.
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

        return $user->hasPermissionTo('activity:duplicate', $project->organization);
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

    private function hasPermission(User $user, Project $project)
    {
        $project_users = $project->users;
        foreach ($project_users as $project_user) {
            if ($user->id === $project_user->id) {
                return true;
            }
        }

        $project_team = $project->team;
        if ($project_team) {
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
