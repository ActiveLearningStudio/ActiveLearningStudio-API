<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Group\GroupAddMemberRequest;
use App\Http\Requests\V1\Group\GroupAddProjectRequest;
use App\Http\Requests\V1\Group\GroupInviteMemberRequest;
use App\Http\Requests\V1\Group\GroupInviteMembersRequest;
use App\Http\Requests\V1\Group\GroupInviteRequest;
use App\Http\Requests\V1\Group\GroupRemoveMemberRequest;
use App\Http\Requests\V1\Group\GroupRemoveProjectRequest;
use App\Http\Requests\V1\Group\GroupUpdateRequest;
use App\Http\Requests\V1\Group\GroupRequest;
use App\Http\Resources\V1\GroupResource;
use App\Models\Project;
use App\Models\Group;
use App\Models\Organization;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Response;

/**
 * @group 14. Group
 *
 * APIs for group management
 */
// TODO: need to reorder API documentation block
class GroupController extends Controller
{

    private $groupRepository;
    private $userRepository;
    private $projectRepository;

    /**
     * GroupController constructor.
     *
     * @param GroupRepositoryInterface $groupRepository
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        UserRepositoryInterface $userRepository,
        ProjectRepositoryInterface $projectRepository
    )
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Get All Groups
     *
     * Get a list of the groups of a user.
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/group/group.json
     *
     * @return Response
     */
    public function index(Organization $suborganization)
    {
        $this->authorize('viewAny', [Group::class, $suborganization]);

        $user_id = auth()->user()->id;
        $groups = $this->groupRepository->getGroups($suborganization->id, $user_id);

        $groupDetails = [];
        foreach ($groups as $group) {
            $group = $this->groupRepository->getGroupDetail($group->id);
            $groupDetails[] = $group;
        }

        return response([
            'groups' => GroupResource::collection($groupDetails),
        ], 200);
    }

    /**
     * Get All Organization Groups
     *
     * Get a list of the groups of an organization.
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/group/group.json
     *
     * @return Response
     */
    public function getOrgGroups(Organization $suborganization)
    {
        $this->authorize('viewAny', [Group::class, $suborganization]);

        $groups = $this->groupRepository->getOrgGroups($suborganization->id);

        $groupDetails = [];
        foreach ($groups as $group) {
            $group = $this->groupRepository->getGroupDetail($group->id);
            $groupDetails[] = $group;
        }

        return response([
            'groups' => GroupResource::collection($groupDetails),
        ], 200);
    }

    /**
     * Invite Group Member
     *
     * Invite a group member while creating a group.
     *
     * @bodyParam id number required The ID of a user Example: 1
     * @bodyParam email string required The email corresponded to the user Example: abby@curriki.org
     *
     * @response {
     *   "invited": true
     * }
     *
     * @response 400 {
     *   "invited": false
     * }
     *
     * @param GroupInviteRequest $groupInviteRequest
     * @return Response
     */
    public function inviteGroupMember(GroupInviteRequest $groupInviteRequest)
    {
        $data = $groupInviteRequest->validated();

        $user = $this->userRepository->find($data['id']);

        if ($user && $user->email === $data['email']) {
            return response([
                'invited' => true,
            ], 200);
        }

        return response([
            'invited' => false,
        ], 400);
    }

    /**
     * Create Group
     *
     * Create a new group in storage for a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required Name of a group Example: Test Group
     * @bodyParam description string required Description of a group Example: This is a test group.
     *
     * @responseFile 201 responses/group/group.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create group. Please try again later."
     *   ]
     * }
     *
     * @param GroupRequest $groupRequest
     * @return Response
     */
    public function store(GroupRequest $groupRequest, Organization $suborganization)
    {
        $this->authorize('create', [Group::class, $suborganization]);

        $data = $groupRequest->validated();

        $auth_user = auth()->user();
        $group = $auth_user->groups()->create($data, ['role' => 'owner']);

        if ($group) {
            $this->groupRepository->createGroup($suborganization, $group, $data);

            return response([
                'group' => new GroupResource($this->groupRepository->getGroupDetail($group->id)),
            ], 201);
        }

        return response([
            'errors' => ['Could not create group. Please try again later.'],
        ], 500);
    }

    /**
     * Get Group
     *
     * Get the specified group detail.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam group required The Id of a group Example: 1
     *
     * @responseFile 201 responses/group/group.json
     *
     * @param Group $group
     * @return Response
     */
    public function show(Organization $suborganization, Group $group)
    {
        $this->authorize('view', [Group::class, $suborganization]);

        return response([
            'group' => new GroupResource($this->groupRepository->getGroupDetail($group->id)),
        ], 200);
    }

    /**
     * Invite Group Member
     *
     * Invite a group member to the group.
     *
     * @bodyParam email string required The email of the user Example: abby@curriki.org
     *
     * @response {
     *   "message": "User has been invited to the group successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to invite user to the group."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to invite user to the group."
     *   ]
     * }
     *
     * @param GroupInviteMemberRequest $inviteMemberRequest
     * @param Group $group
     * @return Response
     */
    public function inviteMember(GroupInviteMemberRequest $inviteMemberRequest, Group $group)
    {
        $data = $inviteMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $group->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $user = $this->userRepository->findByField('email', $data['email']);
            if ($user) {
                $this->groupRepository->inviteToGroup($group, $user);

                return response([
                    'message' => 'User has been invited to the group successfully.',
                ], 200);
            }

            return response([
                'errors' => ['Failed to invite user to the group.'],
            ], 500);
        }

        return response([
            'message' => 'You do not have permission to invite user to the group.',
        ], 403);
    }

    /**
     * Invite Group Members
     *
     * Invite a bundle of users to the group.
     *
     * @bodyParam users array required The array of the users Example: [{id: 1, first_name: Jean, last_name: Erik, name: "Jean Erik"}, {id: "Kairo@Seed.com", email: "Kairo@Seed.com"}]
     *
     * @response {
     *   "message": "Users have been invited to the group successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to invite users to the group."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to invite users to the group."
     *   ]
     * }
     *
     * @param GroupInviteMembersRequest $inviteMembersRequest
     * @param Organization $suborganization
     * @param Group $group
     * @return Response
     */
    public function inviteMembers(GroupInviteMembersRequest $inviteMembersRequest, Organization $suborganization, Group $group)
    {
        $data = $inviteMembersRequest->validated();
        $auth_user = auth()->user();
        $owner = $group->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $invited = $this->groupRepository->inviteMembers($suborganization, $group, $data);

            if ($invited) {
                return response([
                    'message' => 'Users have been invited to the group successfully.',
                ], 200);
            }

            return response([
                'errors' => ['Failed to invite users to the group.'],
            ], 500);
        }

        return response([
            'message' => 'You do not have permission to invite users to the group.',
        ], 403);
    }

    /**
     * Remove Group Member
     *
     * remove a group member to the group.
     *
     * @bodyParam id integer required The Id of the user Example: 1
     *
     * @response {
     *   "message": "User has been removed from the group successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to remove user from the group."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove user from the group."
     *   ]
     * }
     *
     * @param GroupRemoveMemberRequest $removeMemberRequest
     * @param Group $group
     * @return Response
     */
    public function removeMember(GroupRemoveMemberRequest $removeMemberRequest, Group $group)
    {
        $data = $removeMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $group->getUserAttribute();

        // TODO: need to add leave group functionality
        if ($owner->id === $auth_user->id || $data['id'] === $auth_user->id) {
            $user = $this->userRepository->find($data['id']);

            // delete invited outside user if not registered
            if (isset($data['email']) && $data['email'] != '') {
                $this->groupRepository->removeInvitedUser($group, $data['email']);
            }

            if ($user) {
                $result = $group->users()->detach($user);
                $this->groupRepository->removeGroupProjectUser($group, $user);

                // TODO: need to add remove notification
                // $user->notify(new InviteToGroupNotification($auth_user, $group));

                return response([
                    'message' => 'User has been removed from the group successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to remove user from the group.',
            ], 403);
        }

        return response([
            'errors' => ['Failed to remove user from the group.'],
        ], 500);
    }

    /**
     * Add Projects to the Group
     *
     * Add projects to the group.
     *
     * @bodyParam ids array required The list of the project Ids to add Example: [1]
     *
     * @response {
     *   "message": "Projects have been added to the group successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to add projects to the group."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to add projects to the group."
     *   ]
     * }
     *
     * @param GroupAddProjectRequest $addProjectRequest
     * @param Group $group
     * @return Response
     */
    public function addProjects(GroupAddProjectRequest $addProjectRequest, Group $group)
    {
        $data = $addProjectRequest->validated();
        $auth_user = auth()->user();
        $owner = $group->getUserAttribute();
        $assigned_projects = [];

        if ($owner->id === $auth_user->id) {
            foreach ($data['ids'] as $project_id) {
                $project = $this->projectRepository->find($project_id);
                if ($project) {
                    $group->projects()->attach($project);
                    $assigned_projects[] = $project;
                }
            }

            $this->groupRepository->setGroupProjectUser($group, $assigned_projects, []);

            return response([
                'message' => 'Projects have been added to the group successfully.',
            ], 200);
        } elseif ($owner->id !== $auth_user->id) {
            return response([
                'message' => 'You do not have permission to add projects to the group.',
            ], 403);
        }

        return response([
            'errors' => ['Failed to add projects to the group.'],
        ], 500);
    }

    /**
     * Remove Project from the Group
     *
     * Remove a project from the group.
     *
     * @bodyParam id integer required The Id of the project to remove Example: 1
     *
     * @response {
     *   "message": "Project has been removed from the group successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to remove project from the group."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove project from the group."
     *   ]
     * }
     *
     * @param GroupRemoveProjectRequest $removeProjectRequest
     * @param Group $group
     * @return Response
     */
    public function removeProject(GroupRemoveProjectRequest $removeProjectRequest, Group $group)
    {
        $data = $removeProjectRequest->validated();
        $auth_user = auth()->user();
        $owner = $group->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $project = $this->projectRepository->find($data['id']);

            if ($project) {
                $group->projects()->detach($project);

                $this->groupRepository->removeGroupUserProject($group, $project);

                return response([
                    'message' => 'Project has been removed from the group successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to remove project from the group.',
            ], 403);
        }

        return response([
            'errors' => ['Failed to remove project from the group.'],
        ], 500);
    }

    /**
     * Add Members to the Group Project
     *
     * Add members to a specified project of specified group.
     *
     * @bodyParam ids array required The list of the member Ids to add Example: [1]
     *
     * @response {
     *   "message": "Members have been added to the group project successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to add members to the group project."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to add members to the group project."
     *   ]
     * }
     *
     * @param GroupAddMemberRequest $addMemberRequest
     * @param Group $group
     * @param Project $project
     * @return Response
     */
    public function addMembersToProject(GroupAddMemberRequest $addMemberRequest, Group $group, Project $project)
    {
        $data = $addMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $group->getUserAttribute();
        $assigned_members = [];

        if ($owner->id === $auth_user->id) {
            foreach ($data['ids'] as $member_id) {
                $member = $this->userRepository->find($member_id);
                if ($member) {
                    $assigned_members[] = $member;
                }
            }

            $this->groupRepository->assignMembersToGroupProject($group, $project, $assigned_members);

            return response([
                'message' => 'Members have been added to the group project successfully.',
            ], 200);
        } elseif ($owner->id !== $auth_user->id) {
            return response([
                'message' => 'You do not have permission to add members to the group project.',
            ], 403);
        }

        return response([
            'errors' => ['Failed to add members to the group project.'],
        ], 500);
    }

    /**
     * Remove Member from the Group Project
     *
     * Remove member from a specified project of specified group.
     *
     * @bodyParam id integer required The Id of the member to remove Example: 1
     *
     * @response {
     *   "message": "Member has been removed from the group project successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to remove member from the group project."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove member from the group project."
     *   ]
     * }
     *
     * @param GroupRemoveMemberRequest $removeMemberRequest
     * @param Group $group
     * @param Project $project
     * @return Response
     */
    public function removeMemberFromProject(GroupRemoveMemberRequest $removeMemberRequest, Group $group, Project $project)
    {
        $data = $removeMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $group->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $user = $this->userRepository->find($data['id']);

            if ($user) {
                $this->groupRepository->removeMemberFromGroupProject($group, $project, $user);

                return response([
                    'message' => 'Member has been removed from the group project successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to remove member from the group project.',
            ], 403);
        }

        return response([
            'errors' => ['Failed to remove member from the group project.'],
        ], 500);
    }

    /**
     * Update Group
     *
     * Update the specified group of a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam group required The Id of a group Example: 1
     * @bodyParam name string required Name of a group Example: Test Group
     * @bodyParam description string required Description of a group Example: This is a test group.
     *
     * @responseFile responses/group/group.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update group."
     *   ]
     * }
     *
     * @param GroupUpdateRequest $groupUpdateRequest
     * @param Group $group
     * @return Response
     */
    public function update(GroupUpdateRequest $groupUpdateRequest, Organization $suborganization, Group $group)
    {
        $this->authorize('update', [Group::class, $suborganization]);

        $data = $groupUpdateRequest->validated();

        $groupData = [];
        $groupData['name'] = $data['name'];
        $groupData['description'] = $data['description'];

        $is_updated = $this->groupRepository->update($groupData, $group->id);

        if ($is_updated) {
            $this->groupRepository->updateGroup($suborganization, $group, $data);

            return response([
                'group' => new GroupResource($this->groupRepository->getGroupDetail($group->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update group.'],
        ], 500);
    }

    /**
     * Remove Group
     *
     * Remove the specified group of a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam group required The Id of a group Example: 1
     *
     * @response {
     *   "message": "Group has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete group."
     *   ]
     * }
     *
     * @param Group $group
     * @return Response
     */
    public function destroy(Organization $suborganization, Group $group)
    {
        $this->authorize('delete', [Group::class, $suborganization]);

        $is_deleted = $this->groupRepository->delete($group->id);

        if ($is_deleted) {
            return response([
                'message' => 'Group has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete group.'],
        ], 500);
    }

    /**
     * Indexing Request
     *
     * Make the indexing request for a group.
     *
     * @urlParam group required The Id of a group Example: 1
     *
     * @response {
     *   "message": "Indexing request for this group has been made successfully!"
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [Group] Id"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Indexing value is already set. Current indexing state of this group: CURRENT_STATE_OF_PROJECT_INDEX"
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Group must be finalized before requesting the indexing."
     *   ]
     * }
     *
     * @param Group $group
     * @return Response
     */
    public function indexing(Group $group)
    {
        $this->groupRepository->indexing($group);

        return response([
            'message' => 'Indexing request for this group has been made successfully!'
        ], 200);
    }

}
