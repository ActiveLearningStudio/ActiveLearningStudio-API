<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Playlist;
use App\Http\Resources\V1\ActivityResource;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivityController extends Controller
{
    private $projectRepository;

    private $activityRepository;

    /**
     * ActivityController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        ActivityRepositoryInterface $activityRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * Display a listing of the activity.
     *
     * @param Playlist $playlist
     * @return Response
     */
    public function index(Playlist $playlist)
    {
        $allowed = $this->checkPermission($playlist);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to access other user\'s activities.'],
            ], 403);
        }

        return response([
            'activities' => ActivityResource::collection($playlist->activities),
        ], 200);
    }

    /**
     * Store a newly created activity in storage.
     *
     * @param Request $request
     * @param Playlist $playlist
     * @return Response
     */
    public function store(Request $request, Playlist $playlist)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'order' => 'integer',
        ]);

        $allowed = $this->checkPermission($playlist);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to create activity on other user\'s playlist.'],
            ], 403);
        }

        $activity = $playlist->activities()->create($data);

        if ($activity) {
            return response([
                'activity' => $activity,
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified activity.
     *
     * @param Playlist $playlist
     * @param Activity $activity
     * @return Response
     */
    public function show(Playlist $playlist, Activity $activity)
    {
        $allowed = $this->checkPermission($playlist);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to access to other user\'s activity.'],
            ], 403);
        }

        if ($activity->playlist_id !== $playlist->id) {
            return response([
                'errors' => ['Invalid playlist or activity Id.'],
            ], 400);
        }

        return response([
            'activity' => new ActivityResource($activity),
        ], 200);
    }

    /**
     * Update the specified activity in storage.
     *
     * @param Request $request
     * @param Playlist $playlist
     * @param Activity $activity
     * @return Response
     */
    public function update(Request $request, Playlist $playlist, Activity $activity)
    {
        $allowed = $this->checkPermission($playlist);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to update other user\'s activity.'],
            ], 403);
        }

        if ($activity->playlist_id !== $playlist->id) {
            return response([
                'errors' => ['Invalid playlist or activity Id.'],
            ], 400);
        }

        $is_updated = $this->activityRepository->update($request->only([
            'title',
            'type',
            'content',
            'shared',
            'order',
        ]), $activity->id);

        if ($is_updated) {
            return response([
                'activity' => new ActivityResource($this->activityRepository->find($activity->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity.'],
        ], 500);
    }

    /**
     * Remove the specified activity from storage.
     *
     * @param Playlist $playlist
     * @param Activity $activity
     * @return Response
     */
    public function destroy(Playlist $playlist, Activity $activity)
    {
        $allowed = $this->checkPermission($playlist);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to delete other user\'s activity.'],
            ], 403);
        }

        if ($activity->playlist_id !== $playlist->id) {
            return response([
                'errors' => ['Invalid playlist or activity Id.'],
            ], 400);
        }

        $is_deleted = $this->activityRepository->delete($activity->id);

        if ($is_deleted) {
            return response([
                'message' => 'Activity is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity.'],
        ], 500);
    }

    private function checkPermission(Playlist $playlist)
    {
        $authenticated_user = auth()->user();

        $allowed = $authenticated_user->role === 'admin';
        if (!$allowed) {
            $project = $playlist->project;
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
