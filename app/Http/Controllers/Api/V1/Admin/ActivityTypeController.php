<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivityType;
use App\Http\Resources\V1\ActivityTypeResource;
use App\Http\Resources\V1\Admin\LmsSettingResource;
use App\Repositories\Admin\ActivityType\ActivityTypeRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * @group 1002. Admin/Activity Types
 *
 * APIs for activity types on admin panel.
 */
class ActivityTypeController extends Controller
{
    private $repository;

    /**
     * ActivityTypeController constructor.
     * @param ActivityTypeRepository $repository
     */
    public function __construct(ActivityTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get All Activity Types
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/activity-type/activity-types.json
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $collections = $this->repository->getAll($request->all());
        return ActivityTypeResource::collection($collections);
    }

    /**
     * Get Specified Activity Type
     *
     * Get the specified Activity Type data.
     *
     * @urlParam activity_type required The Id of a activity type Example: 1
     *
     * @responseFile responses/admin/activity-type/activity-type.json
     *
     * @param $id
     * @return ActivityTypeResource
     * @throws GeneralException
     */
    public function show($id)
    {
        $type = $this->repository->find($id);
        return new ActivityTypeResource($type);
    }

    /**
     * Create New Activity Type
     *
     * Creates the new activity type in database.
     *
     * @response {
     *   "message": "Activity type created successfully!",
     *   "data": ["Created activity type data array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to create activity type, please try again later!"
     *   ]
     * }
     *
     * @param StoreActivityType $request
     * @return LmsSettingResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreActivityType $request)
    {
        $validated = $request->validated();
        $response = $this->repository->create($validated);
        return response(['message' => $response['message'], 'data' => new ActivityTypeResource($response['data'])], 200);
    }

    /**
     * Update Specified Activity Type
     *
     * Updates the activity type in database.
     *
     * @urlParam activity_type required The Id of a activity type Example: 1
     *
     * @response {
     *   "message": "Activity type data updated successfully!",
     *   "data": ["Updated activity type data array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to update activity type, please try again later."
     *   ]
     * }
     *
     * @param StoreActivityType $request
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(StoreActivityType $request, $id)
    {
        $validated = $request->validated();
        $response = $this->repository->update($id, $validated);
        return response(['message' => $response['message'], 'data' => new ActivityTypeResource($response['data'])], 200);
    }

    /**
     * Delete Activity Type
     *
     * Deletes the activity type from database.
     *
     * @urlParam activity_type required The Id of a activity type Example: 1
     *
     * @response {
     *   "message": "Activity type deleted successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to delete activity type, please try again later."
     *   ]
     * }
     *
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->repository->destroy($id)], 200);
    }
}
