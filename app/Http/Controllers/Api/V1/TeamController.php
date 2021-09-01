<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TeamAddMemberRequest;
use App\Http\Requests\V1\TeamAddProjectRequest;
use App\Http\Requests\V1\TeamInviteMemberRequest;
use App\Http\Requests\V1\TeamInviteMembersRequest;
use App\Http\Requests\V1\TeamInviteRequest;
use App\Http\Requests\V1\TeamMemberRoleUpdateRequest;
use App\Http\Requests\V1\TeamRemoveMemberRequest;
use App\Http\Requests\V1\TeamRemoveProjectRequest;
use App\Http\Requests\V1\TeamRequest;
use App\Http\Requests\V1\TeamUpdateRequest;
use App\Http\Resources\V1\TeamResource;
use App\Jobs\CloneProject;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamRoleType;
use App\Repositories\InvitedTeamUser\InvitedTeamUserRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Response;

/**
 * @group 14. Team
 *
 * APIs for team management
 */
// TODO: need to reorder API documentation block
class TeamController extends Controller
{

    private $teamRepository;
    private $userRepository;
    private $projectRepository;
    /**
     * TeamController constructor.
     *
     * @param TeamRepositoryInterface $teamRepository
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     * @param InvitedTeamUserRepositoryInterface $invitedTeamUserRepository
     */
    public function __construct(
        TeamRepositoryInterface $teamRepository,
        UserRepositoryInterface $userRepository,
        ProjectRepositoryInterface $projectRepository
    )
    {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Get All Teams
     *
     * Get a list of the teams of a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/team/team.json
     *
     * @return Response
     */
    public function index(Organization $suborganization)
    {
        $this->authorize('viewAny', [Team::class, $suborganization]);

        $user_id = auth()->user()->id;

        $teams = $this->teamRepository->getTeams($suborganization->id, $user_id);

        $teamDetails = [];
        foreach ($teams as $team) {
            $team = $this->teamRepository->getTeamDetail($team->id);
            $teamDetails[] = $team;
        }

        return response([
            'teams' => TeamResource::collection($teamDetails),
        ], 200);
    }

    /**
     * Get All Organization Teams
     *
     * Get a list of the teams of an Organization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/team/team.json
     *
     * @return Response
     */
    public function getOrgTeams(Organization $suborganization)
    {
        $this->authorize('viewAny', [Team::class, $suborganization]);

        $teams = $this->teamRepository->getOrgTeams($suborganization->id);

        $teamDetails = [];
        foreach ($teams as $team) {
            $team = $this->teamRepository->getTeamDetail($team->id);
            $teamDetails[] = $team;
        }

        return response([
            'teams' => TeamResource::collection($teamDetails),
        ], 200);
    }

    /**
     * Team Roles Types
     *
     * Get a list of team role types.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/team/team-roles-types.json
     *
     * @response 500 {
     *   "errors": [
     *     "Something went wrong. Please try again later."
     *   ]
     * }
     *
     * @return Response
     */
    public function teamRoleTypes(Organization $suborganization)
    {
        $this->authorize('viewAny', [Team::class, $suborganization]);

        if ($teamRoleTypes = TeamRoleType::all()) {
            return response([
                'teamRoleTypes' => $teamRoleTypes,
            ], 200);
        }

        return response([
            'errors' => ['Something went wrong. Please try again later.'],
        ], 500);
    }

    /**
     * Get User Team Permissions
     *
     * Get the logged-in user's team permissions in the suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam team required The Id of a team Example: 1
     *
     * @responseFile responses/team/team-user-permissions.json
     *
     * @param Organization $suborganization
     * @param Team $team
     * @return Response
     */
    public function getUserTeamPermissions(Organization $suborganization, Team $team)
    {
        $authenticatedUser = auth()->user();

        return response([
            'teamPermissions' => $this->teamRepository->fetchTeamUserPermissions($authenticatedUser, $team),
        ], 200);
    }

    /**
     * Invite Team
     *
     * Invite a team member while creating a team.
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
     * @param TeamInviteRequest $teamInviteRequest
     * @return Response
     */
    public function inviteTeamMember(TeamInviteRequest $teamInviteRequest)
    {
        $data = $teamInviteRequest->validated();

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
     * Create Team
     *
     * Create a new team in storage for a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @bodyParam name string required Name of a team Example: Test Team
     * @bodyParam description string required Description of a team Example: This is a test team.
     *
     * @responseFile 201 responses/team/team.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create team. Please try again later."
     *   ]
     * }
     *
     * @param TeamRequest $teamRequest
     * @return Response
     */
    public function store(TeamRequest $teamRequest, Organization $suborganization)
    {
        $this->authorize('create', [Team::class, $suborganization]);
        $bearerToken = $teamRequest->bearerToken();
        $data = $teamRequest->validated();
        $data['bearerToken'] = $bearerToken;

        foreach ($data['users'] as $user) {
            $exist_user_id = $suborganization->users()->where('user_id', $user['id'])->first();
            if (!$exist_user_id) {
                return response([
                    'errors' =>
                    ['Team not created,
                    ' . $user['email'] . ' must be added in ' . $suborganization->name . ' organization first.'
                    ],
                ], 500);
            }
        }

        $team = $this->teamRepository->createTeam($suborganization, $data);
        if ($team) {
            return response([
                'team' => new TeamResource($this->teamRepository->getTeamDetail($team->id)),
            ], 201);
        }

        return response([
            'errors' => ['Could not create team. Please try again later.'],
        ], 500);
    }

    /**
     * Get Team
     *
     * Get the specified team detail.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam team required The Id of a team Example: 1
     *
     * @responseFile 201 responses/team/team.json
     *
     * @param Team $team
     * @return Response
     */
    public function show(Organization $suborganization, Team $team)
    {
        $this->authorize('view', [Team::class, $suborganization]);

        return response([
            'team' => new TeamResource($this->teamRepository->getTeamDetail($team->id)),
        ], 200);
    }

    /**
     * Invite Team Member
     *
     * Invite a team member to the team.
     *
     * @bodyParam email string required The email of the user Example: abby@curriki.org
     *
     * @response {
     *   "message": "User has been invited to the team successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to invite user to the team."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to invite user to the team."
     *   ]
     * }
     *
     * @param TeamInviteMemberRequest $inviteMemberRequest
     * @param Team $team
     * @return Response
     */
    public function inviteMember(TeamInviteMemberRequest $inviteMemberRequest, Team $team)
    {
        $this->authorize('addTeamUsers', [Team::class, $team]);

        $data = $inviteMemberRequest->validated();
        $user = $this->userRepository->findByField('email', $data['email']);

        if ($user) {
            $exist_user_id = $team->organization->users()->where('user_id', $user['id'])->first();
            if (!$exist_user_id) {
                return response([
                    'errors' => ['This user must be added in ' . $team->organization->name . ' organization first.'],
                ], 500);
            }
            $this->teamRepository->inviteToTeam($team, $user, $data['role_id']);

            return response([
                'message' => 'User has been invited to the team successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to invite user to the team.'],
        ], 500);
    }

    /**
     * Invite Team Members
     *
     * Invite a bundle of users to the team.
     *
     * @bodyParam users array required The array of the users Example:
     * [{id: 1, first_name: Jean, last_name: Erik, name: "Jean Erik"}, {id: "Kairo@Seed.com", email: "Kairo@Seed.com"}]
     *
     * @response {
     *   "message": "Users have been invited to the team successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to invite users to the team."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to invite users to the team."
     *   ]
     * }
     *
     * @param TeamInviteMembersRequest $inviteMembersRequest
     * @param Organization $suborganization
     * @param Team $team
     * @return Response
     */
    public function inviteMembers(
        TeamInviteMembersRequest $inviteMembersRequest,
        Organization $suborganization,
        Team $team
    )
    {
        $this->authorize('addTeamUsers', [Team::class, $team]);
        $data = $inviteMembersRequest->validated();

        foreach ($data['users'] as $user) {
            $exist_user_id = $suborganization->users()->where('user_id', $user['id'])->first();
            if (!$exist_user_id) {
                return response([
                    'errors' => [$user['email'] . ' must be added in ' . $suborganization->name . ' organization first.'],
                ], 500);
            }
        }

        $invited = $this->teamRepository->inviteMembers($suborganization, $team, $data);

        if ($invited) {
            return response([
                'message' => 'Users have been invited to the team successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to invite users to the team.'],
        ], 500);
    }

    /**
     * Remove Team Member
     *
     * remove a team member to the team.
     *
     * @bodyParam id integer required The Id of the user Example: 1
     *
     * @response {
     *   "message": "User has been removed from the team successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to remove user from the team."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove user from the team."
     *   ]
     * }
     *
     * @param TeamRemoveMemberRequest $removeMemberRequest
     * @param Team $team
     * @return Response
     */
    public function removeMember(TeamRemoveMemberRequest $removeMemberRequest, Team $team)
    {
        $this->authorize('removeTeamUsers', [Team::class, $team]);
        $data = $removeMemberRequest->validated();

        $user = $this->userRepository->find($data['id']);
        // delete invited outside user if not registered
        if (isset($data['email']) && $data['email'] != '') {
            $this->teamRepository->removeInvitedUser($team, $data['email']);
        }

        if ($user) {
            $team->users()->detach($user);
            $this->teamRepository->removeTeamProjectUser($team, $user);

            return response([
                'message' => 'User has been removed from the team successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove user from the team.'],
        ], 500);
    }

    /**
     * Add Projects to the Team
     *
     * Add projects to the team.
     *
     * @bodyParam ids array required The list of the project Ids to add Example: [1]
     *
     * @response {
     *   "message": "Projects have been added to the team successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to add projects to the team."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to add projects to the team."
     *   ]
     * }
     *
     * @param TeamAddProjectRequest $addProjectRequest
     * @param Team $team
     * @return Response
     */
    public function addProjects(TeamAddProjectRequest $addProjectRequest, Team $team)
    {
        $this->authorize('addProjects', [Team::class, $team]);
        $data = $addProjectRequest->validated();
        $auth_user = auth()->user();
        // $assigned_projects = [];

        foreach ($data['ids'] as $project_id) {
            $project = $this->projectRepository->find($project_id);
            if ($project) {
                // $team->projects()->attach($project);
                // $assigned_projects[] = $project;
                // pushed cloning of project in background
                CloneProject::dispatch($auth_user,
                                       $project,
                                       $addProjectRequest->bearerToken(),
                                       $team->organization->id, $team
                                       )
                            ->delay(now()->addSecond());
            }
        }
        // $this->teamRepository->setTeamProjectUser($team, $assigned_projects, []);

        return response([
            'message' =>
            "Your request to add [$project->name] project in team has been received and is being processed.
            You will receive an email notice as soon as it is available.",
        ], 200);
    }

    /**
     * Remove Project from the Team
     *
     * Remove a project from the team.
     *
     * @bodyParam id integer required The Id of the project to remove Example: 1
     *
     * @response {
     *   "message": "Project has been removed from the team successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to remove project from the team."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove project from the team."
     *   ]
     * }
     *
     * @param TeamRemoveProjectRequest $removeProjectRequest
     * @param Team $team
     * @return Response
     */
    public function removeProject(TeamRemoveProjectRequest $removeProjectRequest, Team $team)
    {
        $this->authorize('removeProject', [Team::class, $team]);
        $data = $removeProjectRequest->validated();
        $project = $this->projectRepository->find($data['id']);

        if ($project) {
            return \DB::transaction(function () use ($team, $project) {
                $team->projects()->detach($project);
                $project->delete();
                // $this->teamRepository->removeTeamUserProject($team, $project);

                return response([
                    'message' => 'Project has been removed from the team successfully.',
                ], 200);
            });
        }

        return response([
            'errors' => ['Failed to remove project from the team.'],
        ], 500);
    }

    /**
     * Add Members to the Team Project
     *
     * Add members to a specified project of specified team.
     *
     * @bodyParam ids array required The list of the member Ids to add Example: [1]
     *
     * @response {
     *   "message": "Members have been added to the team project successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to add members to the team project."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to add members to the team project."
     *   ]
     * }
     *
     * @param TeamAddMemberRequest $addMemberRequest
     * @param Team $team
     * @param Project $project
     * @return Response
     */
    public function addMembersToProject(TeamAddMemberRequest $addMemberRequest, Team $team, Project $project)
    {
        $this->authorize('addTeamUsers', [Team::class, $team]);
        $data = $addMemberRequest->validated();
        $assigned_members = [];
        $suborganization = $team->organization;

        foreach ($data['ids'] as $member_id) {
            $exist_user_id = $suborganization->users()->where('user_id', $member_id)->first();
            if (!$exist_user_id) {
                return response([
                    'errors' =>
                    ['All selected members must be added in ' . $suborganization->name . ' organization first.'],
                ], 500);
            }
        }

        foreach ($data['ids'] as $member_id) {
            $member = $this->userRepository->find($member_id);
            if ($member) {
                $assigned_members[] = $member;
            }
        }
        $this->teamRepository->assignMembersToTeamProject($team, $project, $assigned_members);

        return response([
            'message' => 'Members have been added to the team project successfully.',
        ], 200);
    }

    /**
     * Remove Member from the Team Project
     *
     * Remove member from a specified project of specified team.
     *
     * @bodyParam id integer required The Id of the member to remove Example: 1
     *
     * @response {
     *   "message": "Member has been removed from the team project successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to remove member from the team project."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove member from the team project."
     *   ]
     * }
     *
     * @param TeamRemoveMemberRequest $removeMemberRequest
     * @param Team $team
     * @param Project $project
     * @return Response
     */
    public function removeMemberFromProject(
        TeamRemoveMemberRequest $removeMemberRequest,
        Team $team,
        Project $project
        )
    {
        $this->authorize('removeTeamUsers', [Team::class, $team]);
        $data = $removeMemberRequest->validated();
        $user = $this->userRepository->find($data['id']);

        if ($user) {
            $this->teamRepository->removeMemberFromTeamProject($team, $project, $user);
            return response([
                'message' => 'Member has been removed from the team project successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove member from the team project.'],
        ], 500);
    }

    /**
     * Update Team
     *
     * Update the specified team of a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam team required The Id of a team Example: 1
     * @bodyParam name string required Name of a team Example: Test Team
     * @bodyParam description string required Description of a team Example: This is a test team.
     *
     * @responseFile responses/team/team.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update team."
     *   ]
     * }
     *
     * @param TeamUpdateRequest $teamUpdateRequest
     * @param Team $team
     * @return Response
     */
    public function update(TeamUpdateRequest $teamUpdateRequest, Organization $suborganization, Team $team)
    {
        $this->authorize('update', [Team::class, $suborganization]);

        $data = $teamUpdateRequest->validated();

        // foreach ($data['users'] as $user) {
        //     $exist_user_id = $suborganization->users()->where('user_id', $user['id'])->first();
        //     if (!$exist_user_id) {
        //         return response([
        //             'errors' =>
        //                ['Team not created,
        //                  ' . $user['email'] . ' must be added in ' . $suborganization->name . ' organization first.'
        //                ],
        //         ], 500);
        //     }
        // }

        $team = $this->teamRepository->updateTeam($suborganization, $team, $data);
        if ($team) {
            return response([
                'team' => new TeamResource($this->teamRepository->getTeamDetail($team->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update team.'],
        ], 500);
    }

    /**
     * Update Team Member Role
     *
     * Update the specified user role of a team.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam team required The Id of a team Example: 1
     * @bodyParam role_id inetger required id of a team role Example: 1
     * @bodyParam user_id inetger required id of a user Example: 12
     *
     * @responseFile responses/team/team.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update team member role."
     *   ]
     * }
     *
     * @param TeamUpdateRequest $teamUpdateRequest
     * @param Team $team
     * @return Response
     */
    public function updateTeamMemberRole(TeamMemberRoleUpdateRequest $request, Organization $suborganization, Team $team)
    {
        $this->authorize('updateMemberRole', [Team::class, $team]);

        $data = $request->validated();

        $is_updated = $team->users()->syncWithoutDetaching([$data['user_id'] => ['team_role_type_id' => $data['role_id']]]);
        if ($is_updated) {
            $user = $this->userRepository->find($data['user_id']);
            return response([
                'teamPermissions' => $this->teamRepository->fetchTeamUserPermissions($user, $team),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update team member role.'],
        ], 500);
    }

    /**
     * Remove Team
     *
     * Remove the specified team of a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam team required The Id of a team Example: 1
     *
     * @response {
     *   "message": "Team has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete team."
     *   ]
     * }
     *
     * @param Team $team
     * @return Response
     */
    public function destroy(Organization $suborganization, Team $team)
    {
        $this->authorize('delete', [Team::class, $suborganization]);

        $is_deleted = $this->teamRepository->delete($team->id);

        if ($is_deleted) {
            return response([
                'message' => 'Team has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete team.'],
        ], 500);
    }

    /**
     * Indexing Request
     *
     * Make the indexing request for a team.
     *
     * @urlParam team required The Id of a team Example: 1
     *
     * @response {
     *   "message": "Indexing request for this team has been made successfully!"
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [Team] Id"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Indexing value is already set. Current indexing state of this team: CURRENT_STATE_OF_PROJECT_INDEX"
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Team must be finalized before requesting the indexing."
     *   ]
     * }
     *
     * @param Team $team
     * @return Response
     */
    public function indexing(Team $team)
    {
        $this->teamRepository->indexing($team);

        return response([
            'message' => 'Indexing request for this team has been made successfully!'
        ], 200);
    }

}
