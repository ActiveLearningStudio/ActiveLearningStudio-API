<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\PlaylistUpdatedEvent;
use App\Events\ProjectUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PlaylistRequest;
use App\Http\Resources\V1\PlaylistResource;
use App\Http\Resources\V1\ProjectResource;
use App\Jobs\ClonePlayList;
use App\Models\Playlist;
use App\Models\Project;
use App\Models\Team;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group 4. Playlist
 *
 * APIs for playlist management
 */
class PlaylistController extends Controller
{

    private $projectRepository;
    private $playlistRepository;
    private $activityRepository;

    /**
     * PlaylistController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @param PlaylistRepositoryInterface $playlistRepository
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        PlaylistRepositoryInterface $playlistRepository,
        ActivityRepositoryInterface $activityRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->playlistRepository = $playlistRepository;
        $this->activityRepository = $activityRepository;

        // $this->middleware('can:view,project');
        // $this->authorizeResource(Playlist::class, 'playlist');
    }

    /**
     * Get Playlists
     *
     * Get a list of the playlists of a project.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @responseFile responses/playlist/playlists.json
     *
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Project $project)
    {
        $this->authorize('viewAny', [Playlist::class, $project]);

        return response([
            'playlists' => PlaylistResource::collection($project->playlists()->orderBy('order')->get()),
        ], 200);
    }

    /**
     * Create Playlist
     *
     * Create a new playlist of a project.
     *
     * @urlParam project required The Id of a project Example 1
     * @bodyParam title string required The title of a playlist Example: Math Playlist
     * @bodyParam order int The order number of a playlist Example: 0
     *
     * @responseFile 201 responses/playlist/playlist.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create playlist. Please try again later."
     *   ]
     * }
     *
     * @param PlaylistRequest $playlistRequest
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function store(PlaylistRequest $playlistRequest, Project $project)
    {
        $this->authorize('create', [Playlist::class, $project]);

        $data = $playlistRequest->validated();
        $data['order'] = $this->playlistRepository->getOrder($project) + 1;

        $playlist = $project->playlists()->create($data);

        if ($playlist) {
            $updated_project = new ProjectResource($this->projectRepository->find($project->id));
            event(new ProjectUpdatedEvent($updated_project));

            return response([
                'playlist' => new PlaylistResource($playlist),
            ], 201);
        }

        return response([
            'errors' => ['Could not create playlist. Please try again later.'],
        ], 500);
    }

    /**
     * Get Playlist
     *
     * Get the specified playlist of a project.
     *
     * @urlParam project required The Id of a project Example: 1
     * @urlParam playlist required The Id of a playlist Example: 1
     *
     * @responseFile 200 responses/playlist/playlist.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid project or playlist id."
     *   ]
     * }
     *
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function show(Project $project, Playlist $playlist)
    {
        $this->authorize('view', [Playlist::class, $project]);

        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist id.'],
            ], 400);
        }

        return response([
            'playlist' => new PlaylistResource($playlist),
        ], 200);
    }

    /**
     * Get Shared Playlist
     *
     * Get the specified shared playlist of a Project.
     *
     * @urlParam playlist required The Id of a playlist Example: 1
     *
     * @responseFile responses/playlist/playlist.json
     *
     * @response 400 {
     *   "errors": [
     *     "No shareable Project found."
     *   ]
     * }
     *
     * @param Playlist $playlist
     * @return Response
     */
    // TODO: need to update
    public function loadShared(Playlist $playlist)
    {
        if (!$playlist->project->shared) {
            return response([
                'errors' => ['No shareable Project found.'],
            ], 400);
        }

        return response([
            'playlist' => $this->playlistRepository->getPlaylistForPreview($playlist),
        ], 200);
    }

    /**
     * Get Lti Playlist
     *
     * Get the lti playlist of a project.
     *
     * @urlParam project required The Id of a project Example 1
     *
     * @responseFile responses/playlist/playlist.json
     *
     * @param Playlist $playlist
     * @return Response
     */
    public function loadLti(Playlist $playlist)
    {
        $availablePlaylist = $this->playlistRepository->getPlaylistWithProject($playlist);
        if ($availablePlaylist) {
            return response([
                'playlist' => new PlaylistResource($availablePlaylist),
            ], 200);
        }

        return response([
            'message' => 'Playlist is not available.',
        ], 404);
    }

    /**
     * Reorder Playlists
     *
     * Reorder playlists of a project.
     *
     * @urlParam project required The Id of a project Example: 1
     * @bodyParam playlists array required Playlists of a project
     *
     * @responseFile responses/playlist/playlists.json
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function reorder(Request $request, Project $project)
    {
        $this->playlistRepository->saveList($request->playlists);

        $updated_project = new ProjectResource($this->projectRepository->find($project->id));
        event(new ProjectUpdatedEvent($updated_project));

        return response([
            'playlists' => PlaylistResource::collection($project->playlists()->orderBy('order')->get()),
        ], 200);
    }

    /**
     * Update Playlist
     *
     * Update the specified playlist of a project.
     *
     * @urlParam project required The Id of a project Example: 1
     * @urlParam playlist required The Id of a playlist Example: 1
     * @bodyParam title string required The title of a playlist Example: Math Playlist
     * @bodyParam order int The order number of a playlist Example: 0
     *
     * @responseFile responses/playlist/playlist.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid project or playlist id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update playlist."
     *   ]
     * }
     *
     * @param PlaylistRequest $playlistRequest
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function update(PlaylistRequest $playlistRequest, Project $project, Playlist $playlist)
    {
        $this->authorize('update', [Playlist::class, $project]);

        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist id.'],
            ], 400);
        }

        $data = $playlistRequest->validated();
        $is_updated = $this->playlistRepository->update($data, $playlist->id);

        if ($is_updated) {
            $updated_playlist = new PlaylistResource($this->playlistRepository->find($playlist->id));
            event(new PlaylistUpdatedEvent($project, $updated_playlist));

            return response([
                'playlist' => $updated_playlist,
            ], 200);
        }

        return response([
            'errors' => ['Failed to update playlist.'],
        ], 500);
    }

    /**
     * Remove Playlist
     *
     * Remove the specified playlist of a project.
     *
     * @urlParam project required The Id of a project Example: 1
     * @urlParam playlist required The Id of a playlist Example: 1
     *
     * @response 200 {
     *   "message": "Playlist has been deleted successfully."
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid project or playlist id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete playlist."
     *   ]
     * }
     *
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function destroy(Project $project, Playlist $playlist)
    {
        $this->authorize('delete', [Playlist::class, $project]);

        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist id.'],
            ], 400);
        }

        $is_deleted = $this->playlistRepository->delete($playlist->id);

        if ($is_deleted) {
            return response([
                'message' => 'Playlist has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete playlist.'],
        ], 500);
    }

    /**
     * Clone Playlist
     *
     * Clone a playlist of a project.
     *
     * @urlParam project required The Id of a project Example: 1
     * @urlParam playlist required The Id of a playlist Example: 1
     *
     * @response {
     *   "message": "Playlist is being cloned|duplicated in background!"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Not a Public Playlist."
     *   ]
     * }
     *
     * @param Request $request
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function clone(Request $request, Project $project, Playlist $playlist)
    {
        $this->authorize('clone', [Playlist::class, $project]);
        // pushed cloning of project in background
        ClonePlayList::dispatch($project, $playlist, $request->bearerToken())->delay(now()->addSecond());
        $isDuplicate = ($playlist->project_id == $project->id);
        $process = ($isDuplicate) ? "duplicate" : "clone";
        return response([
            "message" =>  "Your request to $process playlist [$playlist->title] has been received and is being processed. You will receive an email notice as soon as it is available.",
        ], 200);
    }

    /**
     * @uses One time script to populate all missing order number
     */
    public function populateOrderNumber()
    {
        $this->playlistRepository->populateOrderNumber();
    }
}
