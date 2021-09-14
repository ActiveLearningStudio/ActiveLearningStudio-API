<?php

namespace App\Repositories\Team;

use App\Repositories\EloquentRepositoryInterface;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface TeamRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Create pivots data on team creation
     *
     * @param $suborganization
     * @param $data
     */
    public function createTeam($suborganization, $data);

    /**
     * Update pivots data on team update
     *
     * @param $suborganization
     * @param $team
     * @param $data
     */
    public function updateTeam($suborganization, $team, $data);

    /**
     * Invite user to the team
     *
     * @param $team
     * @param $user
     * @param $role_id
     */
    public function inviteToTeam($team, $user, $role_id);

    /**
     * Invite members to the team
     *
     * @param $suborganization
     * @param $team
     * @param $data
     * @return bool
     */
    public function inviteMembers($suborganization, $team, $data);

    /**
     * Set Team / Project / User relationship
     *
     * @param $team
     * @param $projects
     * @param $users
     */
    public function setTeamProjectUser($team, $projects, $users);

    /**
     * Remove Team / Project / User relationship
     *
     * @param $team
     * @param $user
     */
    public function removeTeamProjectUser($team, $user);

    /**
     * Remove Team / User / Project relationship
     *
     * @param $team
     * @param $project
     */
    public function removeTeamUserProject($team, $project);

    /**
     * Remove invited user
     *
     * @param $team
     * @param $email
     */
    public function removeInvitedUser($team, $email);

    /**
     * Assign members to the team project
     *
     * @param $team
     * @param $project
     * @param $users
     */
    public function assignMembersToTeamProject($team, $project, $users);

    /**
     * Remove member from the team project
     *
     * @param $team
     * @param $project
     * @param $user
     */
    public function removeMemberFromTeamProject($team, $project, $user);

    /**
     * Get Teams data
     *
     * @param $suborganization_id
     * @param $user_id
     * @return mixed
     */
    public function getTeams($suborganization_id, $user_id);

    /**
     * Get Organization Teams data
     *
     * @param $suborganization_id
     * @return mixed
     */
    public function getOrgTeams($suborganization_id);

    /**
     * Get Team detail data
     *
     * @param $teamId
     * @return mixed
     */
    public function getTeamDetail($teamId);

    /**
     * To fetch team user permissions
     *
     * @param User $authenticatedUser
     * @param Team $team
     * @return Model
     */
    public function fetchTeamUserPermissions($authenticatedUser, $team);
}
