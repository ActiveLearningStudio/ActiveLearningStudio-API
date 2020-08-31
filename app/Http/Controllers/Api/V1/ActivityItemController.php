<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ActivityItem;
use App\Http\Resources\V1\ActivityItemResource;
use App\Repositories\ActivityItem\ActivityItemRepositoryInterface;
use App\Repositories\ActivityType\ActivityTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ActivityItemController extends Controller
{
    private $activityItemRepository;
    private $activityTypeRepository;

    /**
     * ActivityItemController constructor.
     *
     * @param ActivityItemRepositoryInterface $activityItemRepository
     */
    public function __construct(
        ActivityItemRepositoryInterface $activityItemRepository,
        ActivityTypeRepositoryInterface $activityTypeRepository
    ) {
        $this->activityItemRepository = $activityItemRepository;
        $this->activityTypeRepository = $activityTypeRepository;

        $this->authorizeResource(ActivityItem::class, 'activityItem');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response([
            'activityItems' => ActivityItemResource::collection($this->activityItemRepository->all()),
        ], 200);
    }

    /**
     * Upload thumb image for activity item
     *
     * @param Request $request
     * @return Response
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid image.']
            ], 400);
        }

        $path = $request->file('image')->store('/public/activity-items');

        return response([
            'image' => Storage::url($path),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'order' => 'integer',
            'activity_type_id' => 'integer',
            'type' => 'required',
            'h5pLib' => 'required',
            'image' => 'string|max:255',
        ]);

        $activityType = $this->activityTypeRepository->find($data['activity_type_id']);
        if (!$activityType) {
            return response([
                'errors' => ['Invalid activity type id.'],
            ], 400);
        }

        $activityItem = $this->activityItemRepository->create($data);

        if ($activityItem) {
            return response([
                'activityItem' => new ActivityItemResource($activityItem),
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity item. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param ActivityItem $activityItem
     * @return Response
     */
    public function show(ActivityItem $activityItem)
    {
        return response([
            'activityItem' => new ActivityItemResource($activityItem),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ActivityItem $activityItem
     * @return Response
     */
    public function update(Request $request, ActivityItem $activityItem)
    {
        if ($request->activity_type_id) {
            $activityType = $this->activityTypeRepository->find($request->activity_type_id);
            if (!$activityType) {
                return response([
                    'errors' => ['Invalid activity type id.'],
                ], 400);
            }
        }

        $is_updated = $this->activityItemRepository->update($request->only([
            'title',
            'description',
            'order',
            'activity_type_id',
            'type',
            'h5pLib',
            'image',
        ]), $activityItem->id);

        if ($is_updated) {
            return response([
                'activityItem' => new ActivityItemResource($this->activityItemRepository->find($activityItem->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity item.'],
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ActivityItem $activityItem
     * @return Response
     */
    public function destroy(ActivityItem $activityItem)
    {
        $is_deleted = $this->activityItemRepository->delete($activityItem->id);

        if ($is_deleted) {
            return response([
                'message' => 'Activity item is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity item.'],
        ], 500);
    }
}
