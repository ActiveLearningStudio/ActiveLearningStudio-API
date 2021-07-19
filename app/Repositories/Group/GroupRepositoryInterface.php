<?php

namespace App\Repositories\Group;

use App\Repositories\EloquentRepositoryInterface;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface GroupRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Create pivots data on group creation
     *
     * @param $suborganization
     * @param $group
     * @param $data
     */
    public function createGroup($suborganization, $group, $data);

    /**
     * Update pivots data on group creation
     *
     * @param $suborganization
     * @param $group
     * @param $data
     */
    public function updateGroup($suborganization, $group, $data);

    /**
     * Invite user to the group
     *
     * @param $group
     * @param $user
     */
    public function inviteToGroup($group, $user);

    /**
     * Invite members to the group
     *
     * @param $group
     * @param $data
     * @return bool
     */
    public function inviteMembers($suborganization, $group, $data);

    /**
     * Set Group / Project / User relationship
     *
     * @param $group
     * @param $projects
     * @param $users
     */
    public function setGroupProjectUser($group, $projects, $users);

    /**
     * Remove Group / Project / User relationship
     *
     * @param $group
     * @param $user
     */
    public function removeGroupProjectUser($group, $user);

    /**
     * Remove invited user
     *
     * @param $group
     * @param $email
     */
    public function removeInvitedUser($group, $email);

    /**
     * Remove Group / User / Project relationship
     *
     * @param $group
     * @param $project
     */
    public function removeGroupUserProject($group, $project);

    /**
     * Assign members to the group project
     *
     * @param $group
     * @param $project
     * @param $users
     */
    public function assignMembersToGroupProject($group, $project, $users);

    /**
     * Remove member from the group project
     *
     * @param $group
     * @param $project
     * @param $user
     */
    public function removeMemberFromGroupProject($group, $project, $user);
    
    /**
     * Get Groups data
     *
     * @param $suborganization_id
     * @param $user_id
     * @return mixed
     */
    public function getGroups($suborganization_id, $user_id);

    /**
     * Get Organization Groups data
     *
     * @param $suborganization_id
     * @return mixed
     */
    public function getOrgGroups($suborganization_id);
    
    /**
     * Get Group detail data
     *
     * @param $groupId
     * @return mixed
     */
    public function getGroupDetail($groupId);

}
