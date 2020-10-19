<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TeamCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TeamInviteRequest;
use App\Http\Requests\V1\TeamRequest;
use App\Http\Requests\V1\TeamUpdateRequest;
use App\Http\Resources\V1\TeamResource;
use App\Models\Team;
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
     */
    public function __construct(
        TeamRepositoryInterface $teamRepository,
        UserRepositoryInterface $userRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
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
                    $team->users()->attach($user, ['role' => 'collaborator']);
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

            new TeamCreatedEvent($team, $assigned_projects, $assigned_users);

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
