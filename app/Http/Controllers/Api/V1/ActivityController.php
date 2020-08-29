<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Http\Resources\V1\ActivityResource;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivityController extends Controller
{
    private $activityRepository;

    /**
     * ActivityController constructor.
     *
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(ActivityRepositoryInterface $activityRepository) {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Display a listing of the activity.
     *
     * @return Response
     */
    public function index()
    {
        return response([
            'activities' => ActivityResource::collection($this->activityRepository->all()),
        ], 200);
    }

    /**
     * Store a newly created activity in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'playlist_id' => 'integer',
            'order' => 'integer',
        ]);

        $activity = $this->activityRepository->create($data);

        if ($activity) {
            return response([
                'activity' => new ActivityResource($activity),
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified activity.
     *
     * @param Activity $activity
     * @return Response
     */
    public function show(Activity $activity)
    {
        return response([
            'activity' => new ActivityResource($activity),
        ], 200);
    }

    /**
     * Update the specified activity in storage.
     *
     * @param Request $request
     * @param Activity $activity
     * @return Response
     */
    public function update(Request $request, Activity $activity)
    {
        $is_updated = $this->activityRepository->update($request->only([
            'title',
            'type',
            'playlist_id',
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
     * @param Activity $activity
     * @return Response
     */
    public function destroy(Activity $activity)
    {
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
}
