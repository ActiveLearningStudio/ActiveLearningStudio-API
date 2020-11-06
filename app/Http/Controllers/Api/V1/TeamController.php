<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TeamCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TeamAddMemberRequest;
use App\Http\Requests\V1\TeamAddProjectRequest;
use App\Http\Requests\V1\TeamInviteMemberRequest;
use App\Http\Requests\V1\TeamInviteMembersRequest;
use App\Http\Requests\V1\TeamInviteRequest;
use App\Http\Requests\V1\TeamRemoveMemberRequest;
use App\Http\Requests\V1\TeamRemoveProjectRequest;
use App\Http\Requests\V1\TeamRequest;
use App\Http\Requests\V1\TeamUpdateRequest;
use App\Http\Resources\V1\TeamResource;
use App\Models\Project;
use App\Models\Team;
use App\Notifications\InviteToTeamNotification;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        // $this->authorizeResource(Team::class, 'teams');
    }

    /**
     * Get All Teams
     *
     * Get a list of the teams of a user.
     *
     * @responseFile responses/team/teams.json
     *
     * @return Response
     */
    public function index()
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->isAdmin()) {
            $teams = $this->teamRepository->all();
        } else {
            $teams = $authenticated_user->teams;
        }

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
     * Invite Team Member
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
    public function store(TeamRequest $teamRequest)
    {
        $data = $teamRequest->validated();

        $authenticated_user = auth()->user();
        $team = $authenticated_user->teams()->create($data, ['role' => 'owner']);

        if ($team) {
            $assigned_users = [];
            foreach ($data['users'] as $user_id) {
                $user = $this->userRepository->find($user_id);
                if ($user) {
                    $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                    $team->users()->attach($user, ['role' => 'collaborator', 'token' => $token]);
                    $user->token = $token;
                    $assigned_users[] = $user;
                }
            }

            $assigned_projects = [];
            foreach ($data['projects'] as $project_id) {
                $project = $this->projectRepository->find($project_id);
                if ($project) {
                    $team->projects()->attach($project);
                    $assigned_projects[] = $project;
                }
            }

            event(new TeamCreatedEvent($team, $assigned_projects, $assigned_users));

            $this->teamRepository->setTeamProjectUser($team, $assigned_projects, $assigned_users);

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
     * @urlParam team required The Id of a team Example: 1
     *
     * @responseFile 201 responses/team/team.json
     *
     * @param Team $team
     * @return Response
     */
    public function show(Team $team)
    {
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
        $data = $inviteMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $team->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $user = $this->userRepository->findByField('email', $data['email']);

            if ($user) {
                $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                $team->users()->attach($user, ['role' => 'collaborator', 'token' => $token]);

                $user->notify(new InviteToTeamNotification($auth_user, $team, $token));

                return response([
                    'message' => 'User has been invited to the team successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to invite user to the team.',
            ], 403);
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
     * @bodyParam users array required The array of the users Example: [{id: 1, first_name: Jean, last_name: Erik, name: "Jean Erik"}, {id: "Kairo@Seed.com", email: "Kairo@Seed.com"}]
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
     * @param Team $team
     * @return Response
     */
    public function inviteMembers(TeamInviteMembersRequest $inviteMembersRequest, Team $team)
    {
        $data = $inviteMembersRequest->validated();
        $auth_user = auth()->user();
        $owner = $team->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $invited = true;
            foreach ($data['users'] as $user) {
                $conUser = $this->userRepository->findByField('email', $user['email']);
                $note = array_key_exists('note', $data) ? $data['note'] : '';

                if ($conUser) {
                    $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                    $team->users()->attach($conUser, ['role' => 'collaborator', 'token' => $token]);
                    $conUser->notify(new InviteToTeamNotification($auth_user, $team, $token, $note));
                } elseif ($user['email']) {
                    // TODO: need to send mail to unregistered person and do whatever for that person
                    $token = Hash::make((string)Str::uuid() . date('D M d, Y G:i'));
                    $tempUser = new User(['email' => $user['email']]);
                    $tempUser->notify(new InviteToTeamNotification($auth_user, $team, $token, $note));
                } else {
                    $invited = false;
                }
            }

            if ($invited) {
                return response([
                    'message' => 'Users have been invited to the team successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to invite users to the team.',
            ], 403);
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
        $data = $removeMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $team->getUserAttribute();

        // TODO: need to add leave team functionality
        if ($owner->id === $auth_user->id || $data['id'] === $auth_user->id) {
            $user = $this->userRepository->find($data['id']);

            if ($user) {
                $team->users()->detach($user);

                $this->teamRepository->removeTeamProjectUser($team, $user);

                // TODO: need to add remove notification
                // $user->notify(new InviteToTeamNotification($auth_user, $team));

                return response([
                    'message' => 'User has been removed from the team successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to remove user from the team.',
            ], 403);
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
        $data = $addProjectRequest->validated();
        $auth_user = auth()->user();
        $owner = $team->getUserAttribute();
        $assigned_projects = [];

        if ($owner->id === $auth_user->id) {
            foreach ($data['ids'] as $project_id) {
                $project = $this->projectRepository->find($project_id);
                if ($project) {
                    $team->projects()->attach($project);
                    $assigned_projects[] = $project;
                }
            }

            $this->teamRepository->setTeamProjectUser($team, $assigned_projects, []);

            return response([
                'message' => 'Projects have been added to the team successfully.',
            ], 200);
        } elseif ($owner->id !== $auth_user->id) {
            return response([
                'message' => 'You do not have permission to add projects to the team.',
            ], 403);
        }

        return response([
            'errors' => ['Failed to add projects to the team.'],
        ], 500);
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
        $data = $removeProjectRequest->validated();
        $auth_user = auth()->user();
        $owner = $team->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $project = $this->projectRepository->find($data['id']);

            if ($project) {
                $team->projects()->detach($project);

                $this->teamRepository->removeTeamUserProject($team, $project);

                return response([
                    'message' => 'Project has been removed from the team successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to remove project from the team.',
            ], 403);
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
        $data = $addMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $team->getUserAttribute();
        $assigned_members = [];

        if ($owner->id === $auth_user->id) {
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
        } elseif ($owner->id !== $auth_user->id) {
            return response([
                'message' => 'You do not have permission to add members to the team project.',
            ], 403);
        }

        return response([
            'errors' => ['Failed to add members to the team project.'],
        ], 500);
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
    public function removeMemberFromProject(TeamRemoveMemberRequest $removeMemberRequest, Team $team, Project $project)
    {
        $data = $removeMemberRequest->validated();
        $auth_user = auth()->user();
        $owner = $team->getUserAttribute();

        if ($owner->id === $auth_user->id) {
            $user = $this->userRepository->find($data['id']);

            if ($user) {
                $this->teamRepository->removeMemberFromTeamProject($team, $project, $user);

                return response([
                    'message' => 'Member has been removed from the team project successfully.',
                ], 200);
            }
        } else {
            return response([
                'message' => 'You do not have permission to remove member from the team project.',
            ], 403);
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
    public function update(TeamUpdateRequest $teamUpdateRequest, Team $team)
    {
        $data = $teamUpdateRequest->validated();

        $is_updated = $this->teamRepository->update($data, $team->id);

        if ($is_updated) {
            return response([
                'team' => new TeamResource($this->teamRepository->getTeamDetail($team->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update team.'],
        ], 500);
    }

    /**
     * Remove Team
     *
     * Remove the specified team of a user.
     *
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
    public function destroy(Team $team)
    {
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
