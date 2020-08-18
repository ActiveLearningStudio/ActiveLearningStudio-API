<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Project;
use App\Http\Resources\V1\PlaylistResource;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlaylistController extends Controller
{
    private $playlistRepository;

    /**
     * PlaylistController constructor.
     *
     * @param PlaylistRepositoryInterface $playlistRepository
     */
    public function __construct(PlaylistRepositoryInterface $playlistRepository)
    {
        $this->playlistRepository = $playlistRepository;
    }

    /**
     * Display a listing of the playlist.
     *
     * @param Project $project
     * @return Response
     */
    public function index(Project $project)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to access other user\'s playlists.'],
            ], 403);
        }

        return response([
            'playlists' => PlaylistResource::collection($project->playlists),
        ], 200);
    }

    /**
     * Store a newly created playlist in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'integer',
        ]);

        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to create playlist on other user\'s project.'],
            ], 403);
        }

        $playlist = $project->playlists()->create($data);

        if ($playlist) {
            return response([
                'playlist' => $playlist,
            ], 201);
        }

        return response([
            'errors' => ['Could not create playlist. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified playlist.
     *
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function show(Project $project, Playlist $playlist)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to access to other user\'s playlist.'],
            ], 403);
        }

        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist Id.'],
            ], 400);
        }

        return response([
            'playlist' => new PlaylistResource($playlist),
        ], 200);
    }

    /**
     * Update the specified playlist in storage.
     *
     * @param Request $request
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function update(Request $request, Project $project, Playlist $playlist)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to update other user\'s playlist.'],
            ], 403);
        }

        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist Id.'],
            ], 400);
        }

        $is_updated = $this->playlistRepository->update($request->only([
            'title',
            'order',
        ]), $playlist->id);

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
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    public function destroy(Project $project, Playlist $playlist)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to delete other user\'s playlist.'],
            ], 403);
        }

        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist Id.'],
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

    private function checkPermission(Project $project)
    {
        $authenticated_user = auth()->user();

        $allowed = $authenticated_user->role === 'admin';
        if (!$allowed) {
            $project_users = $project->users;
            foreach ($project_users as $user) {
                if ($user->id === $authenticated_user->id) {
                    $allowed = true;
                }
            }
        }

        return $allowed;
    }
}
