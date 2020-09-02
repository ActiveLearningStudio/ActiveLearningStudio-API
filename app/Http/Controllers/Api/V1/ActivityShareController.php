<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Http\Resources\V1\ActivityResource;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivityShareController extends Controller
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
     * Share the specified activity.
     *
     * @param Request $request
     * @param Activity $activity
     * @return Response
     */
    public function share(Activity $activity)
    {
        $is_updated = $this->activityRepository->update([
            'shared' => true,
        ], $activity->id);

        if ($is_updated) {
            return response([
                'activity' => new ActivityResource($this->activityRepository->find($activity->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to share activity.'],
        ], 500);
    }

    /**
     * Remove share specified activity.
     *
     * @param Request $request
     * @param Activity $activity
     * @return Response
     */
    public function removeShare(Activity $activity)
    {
        $is_updated = $this->activityRepository->update([
            'shared' => false,
        ], $activity->id);

        if ($is_updated) {
            return response([
                'activity' => new ActivityResource($this->activityRepository->find($activity->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove share activity.'],
        ], 500);
    }

}
