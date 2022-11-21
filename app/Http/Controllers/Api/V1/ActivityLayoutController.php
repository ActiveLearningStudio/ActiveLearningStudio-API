<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ActivityLayoutUploadThumbRequest;
use App\Http\Requests\V1\SearchActivityLayout;
use App\Http\Requests\V1\StoreActivityLayout;
use App\Http\Requests\V1\UpdateActivityLayout;
use App\Http\Resources\V1\ActivityLayoutResource;
use App\Models\ActivityLayout;
use App\Models\Organization;
use App\Repositories\ActivityLayout\ActivityLayoutRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * @group 7. Activity Layout
 *
 * APIs for activity layout management
 */
class ActivityLayoutController extends Controller
{
    private $activityLayoutRepository;

    /**
     * ActivityLayoutController constructor.
     *
     * @param ActivityLayoutRepositoryInterface $activityLayoutRepository
     */
    public function __construct(
        ActivityLayoutRepositoryInterface $activityLayoutRepository
    )
    {
        $this->activityLayoutRepository = $activityLayoutRepository;
    }

    /**
     * Get Activity Layouts
     *
     * Get a list of the activity layouts.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * 
     * @responseFile responses/activity-layout/activity-layouts.json

     * @param SearchActivityLayout $request
     * @param Organization $suborganization
     *
     * @return Response
     */
    public function index(SearchActivityLayout $request, Organization $suborganization)
    {
        return ActivityLayoutResource::collection(
            $this->activityLayoutRepository->getAll($suborganization, $request->all())
        );
    }

    /**
     * Upload Thumbnail
     *
     * Upload thumbnail image for a activity layout
     *
     * @bodyParam thumb image required Thumbnail image
     *
     * @response {
     *   "image": "/storage/activity-layouts/1fqwe2f65ewf465qwe46weef5w5eqwq.png"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid image."
     *   ]
     * }
     *
     * @param ActivityLayoutUploadThumbRequest $request
     * @return Response
     */
    public function uploadImage(ActivityLayoutUploadThumbRequest $request)
    {
        $path = $request->file('image')->store('/public/activity-layouts');

        return response([
            'image' => Storage::url($path),
        ], 200);
    }

    /**
     * Create Activity Layout
     *
     * Create a new activity layout.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam title string required The title of a activity layout Example: Audio Recorder
     * @bodyParam description string required The description of a activity layout Example: Record your voice and play back or download a .wav file of your recording.
     * @bodyParam order int The order number of a activity layout Example: 1
     * @bodyParam activity_type_id int The Id of a activity type Example: 1
     * @bodyParam type any required The type of a activity layout Example: h5p
     * @bodyParam h5pLib any required The H5pLib of a activity layout Example: H5P.AudioRecorder 1.0
     * @bodyParam image string The image url of a activity layout Example: /storage/activity-layouts/zGUwGiarxX5Xt0UDFMMHtJ3ICGy1F9W68cO0Ukm6.png
     *
     * @responseFile 201 responses/activity-layout/activity-layout.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid activity type id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create activity layout. Please try again later."
     *   ]
     * }
     *
     * @param StoreActivityLayout $request
     * @param Organization $suborganization
     *
     * @return Response
     */
    public function store(StoreActivityLayout $request, Organization $suborganization)
    {
        $data = $request->validated();
        $activityLayout = $this->activityLayoutRepository->create($data);

        if ($activityLayout) {
            return response([
                'activityLayout' => new ActivityLayoutResource($activityLayout),
                'message' => 'Activity layout created successfully!',
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity layout. Please try again later.'],
        ], 500);
    }

    /**
     * Get Activity Item
     *
     * Get the specified activity layout.
     *
     * @urlParam suborganization required The Id of a organization Example: 1
     * @urlParam activityLayout required The Id of a activity layout Example: 1
     *
     * @responseFile responses/activity-layout/activity-layout.json
     *
     * @param Organization $suborganization
     * @param ActivityLayout $activityLayout

     * @return Response
     */
    public function show(Organization $suborganization, ActivityLayout $activityLayout)
    {
        if ($suborganization->id !== $activityLayout->organization_id) {
            return response([
                'message' => 'Invalid activity layout or organization',
            ], 400);
        }

        return  response([
            'activityLayout' => new ActivityLayoutResource($activityLayout),
        ], 200);
    }

    /**
     * Update Activity Item
     *
     * Update the specified activity layout.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam activityLayout required The Id of a activity layout Example: 1
     * @bodyParam title string required The title of a activity layout Example: Audio Recorder
     * @bodyParam description string required The description of a activity layout Example: Record your voice and play back or download a .wav file of your recording.
     * @bodyParam order int The order number of a activity layout Example: 1
     * @bodyParam activity_type_id int The Id of a activity type Example: 1
     * @bodyParam type any required The type of a activity layout Example: h5p
     * @bodyParam h5pLib any required The H5pLib of a activity layout Example: H5P.AudioRecorder 1.0
     * @bodyParam image string The image url of a activity layout Example: /storage/activity-layouts/zGUwGiarxX5Xt0UDFMMHtJ3ICGy1F9W68cO0Ukm6.png
     *
     * @responseFile responses/activity-layout/activity-layout.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid activity type id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update activity layout."
     *   ]
     * }
     *
     * @param UpdateActivityLayout $request
     * @param Organization $suborganization
     * @param ActivityLayout $activityLayout
     * @return Response
     */
    public function update(UpdateActivityLayout $request, Organization $suborganization,
        ActivityLayout $activityLayout)
    {
        $data = $request->validated();
        $isUpdated = $this->activityLayoutRepository->update($activityLayout->id, $data);

        if ($isUpdated) {
            return response([
                'activityLayout' => new ActivityLayoutResource(
                    $this->activityLayoutRepository->find($activityLayout->id)
                ),
                'message' => 'Activity layout updated successfully!',
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity layout.'],
        ], 500);
    }

    /**
     * Remove Activity Item
     *
     * Remove the specified activity layout.
     *
     * @urlParam suborganization required The Id of a organization Example: 1
     * @urlParam activityLayout required The Id of a activity layout Example: 1
     *
     * @response {
     *   "message": "Activity layout has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete activity layout."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param ActivityLayout $activityLayout

     * @return Response
     */
    public function destroy(Organization $suborganization, ActivityLayout $activityLayout)
    {
        if ($suborganization->id !== $activityLayout->organization_id) {
            return response([
                'message' => 'Invalid activity layout or organization',
            ], 400);
        }

        $isDeleted = $this->activityLayoutRepository->delete($activityLayout->id);

         if  ($isDeleted) {
            return response([
                'message' => 'Activity layout has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity layout.'],
        ], 500);
    }
}
