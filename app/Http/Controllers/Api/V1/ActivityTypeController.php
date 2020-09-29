<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use App\Http\Resources\V1\ActivityItemResource;
use App\Http\Resources\V1\ActivityTypeResource;
use App\Repositories\ActivityType\ActivityTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @group  Activity Type
 *
 * APIs for managing activity types
 */
class ActivityTypeController extends Controller
{
    private $activityTypeRepository;

    /**
     * ActivityTypeController constructor.
     *
     * @param ActivityTypeRepositoryInterface $activityTypeRepository
     */
    public function __construct(ActivityTypeRepositoryInterface $activityTypeRepository)
    {
        $this->activityTypeRepository = $activityTypeRepository;

        $this->authorizeResource(ActivityType::class, 'activityType');
    }

    /**
     * Display a listing of the resource.
     *
     * @responseFile responses/activity-types.json
     *
     * @return Response
     */
    public function index()
    {
        return response([
            'activityTypes' => ActivityTypeResource::collection($this->activityTypeRepository->all()),
        ], 200);
    }

    /**
     * Upload thumb image for activity type
     *
     * @bodyParam thumb image required Thumbnail image
     *
     * @response {
     *   "thumbUrl": "storage/.../1fqwe2f65ewf465qwe46weef5w5eqwq.png"
     * }
     *
     * @response 400 {
     *   "errors": ["Invalid image."]
     * }
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

        $path = $request->file('image')->store('/public/activity-types');

        return response([
            'image' => Storage::url($path),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam title string required The title of a activity type
     * @bodyParam order int The order number of a activity type
     * @bodyParam image string The image url of a activity type
     *
     * @response 201 {
     *   "activityType": {
     *     "id": 1,
     *     "title": "Audio",
     *     "order": 0,
     *     "image": null,
     *     "created_at": "2020-08-25T16:29:35.000000Z",
     *     "updated_at": "2020-08-25T16:29:35.000000Z",
     *     "deleted_at": null
     *   }
     * }
     *
     * @response 500 {
     *   "errors": ["Could not create activity type. Please try again later."]
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'integer',
            'image' => 'string|max:255',
        ]);

        $activityType = $this->activityTypeRepository->create($data);

        if ($activityType) {
            return response([
                'activityType' => new ActivityTypeResource($activityType),
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity type. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam activity_type The Id of a activity type
     *
     * @response {
     *   "activityType": {
     *     "id": 1,
     *     "title": "Audio",
     *     "order": 0,
     *     "image": "\/storage\/uploads\/4kZL5uuExvNPngVsaIdC7JscWmstOTsYO8sBbekx.png",
     *     "created_at": null,
     *     "updated_at": null,
     *     "deleted_at": null
     *   }
     * }
     *
     * @param ActivityType $activityType
     * @return Response
     */
    public function show(ActivityType $activityType)
    {
        return response([
            'activityType' => new ActivityTypeResource($activityType),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam activity_type The Id of a activity type
     *
     * @responseFile responses/activity-items.json
     *
     * @param ActivityType $activityType
     * @return Response
     */
    public function items(ActivityType $activityType)
    {
        return response([
            'activityItems' => ActivityItemResource::collection($activityType->activityItems),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @urlParam activity_type The Id of a activity type
     * @bodyParam title string The title of a activity type
     * @bodyParam order int The order number of a activity type
     * @bodyParam image string The image url of a activity type
     *
     * @response {
     *   "activityType": {
     *     "id": 1,
     *     "title": "Audio",
     *     "order": 0,
     *     "image": "\/storage\/uploads\/4kZL5uuExvNPngVsaIdC7JscWmstOTsYO8sBbekx.png",
     *     "created_at": null,
     *     "updated_at": null,
     *     "deleted_at": null
     *   }
     * }
     *
     * @response 500 {
     *  "errors": ["Failed to update activity type."]
     * }
     *
     * @param Request $request
     * @param ActivityType $activityType
     * @return Response
     */
    public function update(Request $request, ActivityType $activityType)
    {
        $is_updated = $this->activityTypeRepository->update($request->only([
            'title',
            'order',
            'image',
        ]), $activityType->id);

        if ($is_updated) {
            return response([
                'activityType' => new ActivityTypeResource($this->activityTypeRepository->find($activityType->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity type.'],
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @urlParam activity_type The Id of a activity type
     *
     * @response {
     *   "message": "Activity type is deleted successfully."
     * }
     *
     * @response 500 {
     *   "message": ["Failed to delete activity type."]
     * }
     *
     * @param ActivityType $activityType
     * @return Response
     */
    public function destroy(ActivityType $activityType)
    {
        $is_deleted = $this->activityTypeRepository->delete($activityType->id);

        if ($is_deleted) {
            return response([
                'message' => 'Activity type is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity type.'],
        ], 500);
    }
}
