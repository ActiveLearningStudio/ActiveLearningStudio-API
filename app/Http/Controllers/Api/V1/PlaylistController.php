<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Project;
use App\Http\Requests\V1\PlaylistRequest;
use App\Http\Resources\V1\PlaylistResource;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group  Playlist
 *
 * APIs for managing playlists
 */
class PlaylistController extends Controller
{

    private $playlistRepository;

    private $activityRepository;

    /**
     * PlaylistController constructor.
     *
     * @param PlaylistRepositoryInterface $playlistRepository
     */
    public function __construct(PlaylistRepositoryInterface $playlistRepository, ActivityRepositoryInterface $activityRepository)
    {
        $this->playlistRepository = $playlistRepository;
        $this->activityRepository = $activityRepository;

        // $this->middleware('can:view,project');
        $this->authorizeResource(Playlist::class, 'playlist');
    }

    /**
     * Display a listing of the playlist.
     *
     * @urlParam projectId required The Id of a project
     *
     * @responseFile responses/playlists.get.json
     *
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);

        return response([
            'playlists' => PlaylistResource::collection($project->playlists()->orderBy('order')->get()),
        ], 200);
    }

    /**
     * Store a newly created playlist in storage.
     *
     * @urlParam project required The Id of a project
     * @bodyParam title string required The title of a playlist
     * @bodyParam order int required The order number of a playlist
     *
     * @responseFile 201 responses/playlist.get.json
     *
     * @response 500 {
     *   "errors": ["Could not create playlist. Please try again later."]
     * }
     *
     * @param PlaylistRequest $playlistRequest
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function store(PlaylistRequest $playlistRequest, Project $project)
    {
        $this->authorize('view', $project);

        $data = $playlistRequest->validated();
        $data['order'] = $this->playlistRepository->getOrder($project) + 1;
        $data['is_public'] = $project->is_public;

        $playlist = $project->playlists()->create($data);

        if ($playlist) {
            return response([
                'playlist' => new PlaylistResource($playlist),
            ], 201);
        }

        return response([
            'errors' => ['Could not create playlist. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified playlist.
     *
     * @urlParam projectId required The Id of a project
     * @urlParam playlistId required The Id of a playlist
     *
     * @responseFile responses/playlist.get.json
     *
     * @response 400 {
     *   "errors": ["Invalid project or playlist id."]
     * }
     *
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function show(Project $project, Playlist $playlist)
    {
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
     * Display the specified shared playlist.
     *
     * @urlParam playlistId required The Id of a playlist
     *
     * @responseFile responses/playlist.get.json
     *
     * @response {
     *   "errors": ["No shareable Project found."]
     * }
     *
     * @param Playlist $playlist
     * @return Response
     */
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
     * Display the lti playlist.
     *
     * @urlParam playlistId required The Id of a playlist
     *
     * @responseFile responses/playlist.get.json
     *
     * @param Playlist $playlist
     * @return Response
     */
    public function loadLti(Playlist $playlist)
    {
        return response([
            'playlist' => new PlaylistResource($this->playlistRepository->getPlaylistWithProject($playlist)),
        ], 200);
    }

    /**
     * Reorder playlists in storage.
     *
     * @urlParam projectId required The Id of a project
     * @bodyParam playlists required Playlists of a project
     *
     * @response {
     *   "playlists": [
     *     {
     *       "id": 3898,
     *       "order": 0,
     *       "activities": []
     *     },
     *     {
     *       "id": 3806,
     *       "order": 1,
     *       "activities": []
     *     },
     *     {
     *       "id": 3900,
     *       "order": 2,
     *       "activities": []
     *     }
     *   ]
     * }
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function reorder(Request $request, Project $project)
    {
        $this->playlistRepository->saveList($request->playlists);

        return response([
            'playlists' => PlaylistResource::collection($project->playlists()->orderBy('order')->get()),
        ], 200);
    }

    /**
     * Update the specified playlist in storage.
     *
     * @urlParam projectId required The Id of a project
     * @urlParam playlistId required The Id of a playlist
     * @bodyParam title string required The title of a playlist
     * @bodyParam order int required The order number of a playlist
     *
     * @responseFile responses/playlist.get.json
     *
     * @response 400 {
     *  "errors": ["Invalid project or playlist id."]
     * }
     *
     * @response 500 {
     *  "errors": ["Failed to update playlist."]
     * }
     *
     * @param PlaylistRequest $playlistRequest
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function update(PlaylistRequest $playlistRequest, Project $project, Playlist $playlist)
    {
        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist id.'],
            ], 400);
        }

        $data = $playlistRequest->validated();
        $is_updated = $this->playlistRepository->update($data, $playlist->id);

        if ($is_updated) {
            return response([
                'playlist' => new PlaylistResource($this->playlistRepository->find($playlist->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update playlist.'],
        ], 500);
    }

    /**
     * Remove the specified playlist from storage.
     *
     * @urlParam projectId required The Id of a project
     * @urlParam playlistId required The Id of a playlist
     *
     * @response 200 {
     *   "message": "Playlist is deleted successfully."
     * }
     *
     * @response 400 {
     *   "errors": ["Invalid project or playlist id."]
     * }
     *
     * @response 500 {
     *   "errors": ["Failed to delete playlist."]
     * }
     *
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function destroy(Project $project, Playlist $playlist)
    {
        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist id.'],
            ], 400);
        }

        $is_deleted = $this->playlistRepository->delete($playlist->id);

        if ($is_deleted) {
            return response([
                'message' => 'Playlist is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete playlist.'],
        ], 500);
    }

    /**
     * Clone a playlist.
     *
     * @urlParam projectId required The Id of a project
     * @urlParam playlistId required The Id of a playlist
     *
     * @response {
     *  "message": "Playlist is cloned successfully"
     * }
     *
     * @response 500 {
     *   "errors": ["Not a Public Playlist."]
     * }
     *
     * @param Request $request
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function clone(Request $request, Project $project, Playlist $playlist)
    {
        if (!$playlist->is_public) {
            return response([
                'errors' => ['Not a public Playlist.'],
            ], 500);
        }

        $this->playlistRepository->clone($project, $playlist, $request->bearerToken());

        return response([
            'message' => 'Playlist is cloned successfully.',
        ], 200);
    }

}
