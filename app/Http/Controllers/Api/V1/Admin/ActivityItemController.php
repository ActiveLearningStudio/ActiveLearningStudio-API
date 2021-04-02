<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivityItem;
use App\Http\Resources\V1\ActivityItemResource;
use App\Repositories\Admin\ActivityItem\ActivityItemRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * @group 1003. Admin/Activity Items
 *
 * APIs for activity items on admin panel.
 */
class ActivityItemController extends Controller
{
    private $repository;

    /**
     * ActivityItemController constructor.
     *
     * @param ActivityItemRepository $repository
     */
    public function __construct(ActivityItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get All Activity Items
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/activity-item/activity-items.json
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $collections = $this->repository->getAll($request->all());
        return ActivityItemResource::collection($collections);
    }

    /**
     * Get Specified Activity Item
     *
     * Get the specified Activity Item data.
     *
     * @urlParam activity_item required The Id of a activity item Example: 1
     *
     * @responseFile responses/admin/activity-item/activity-item.json
     *
     * @param $id
     * @return ActivityItemResource
     * @throws GeneralException
     */
    public function show($id)
    {
        $item = $this->repository->find($id);
        return new ActivityItemResource($item);
    }

    /**
     * Create New Activity Item
     *
     * Creates the new activity item in database.
     *
     * @response {
     *   "message": "Activity item created successfully!",
     *   "data": ["Created activity item data array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to create activity item, please try again later!"
     *   ]
     * }
     *
     * @param StoreActivityItem $request
     * @return ActivityItemResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreActivityItem $request)
    {
        $validated = $request->validated();
        $response = $this->repository->create($validated);
        return response(['message' => $response['message'], 'data' => new ActivityItemResource($response['data'])], 200);
    }

    /**
     * Update Specified Activity Item
     *
     * Updates the activity item in database.
     *
     * @urlParam activity_item required The Id of a activity item Example: 1
     *
     * @response {
     *   "message": "Activity item data updated successfully!",
     *   "data": ["Updated activity item data array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to update activity item, please try again later."
     *   ]
     * }
     *
     * @param StoreActivityItem $request
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(StoreActivityItem $request, $id)
    {
        $validated = $request->validated();
        $response = $this->repository->update($id, $validated);
        return response(['message' => $response['message'], 'data' => new ActivityItemResource($response['data'])], 200);
    }

    /**
     * Delete Activity Item
     *
     * Deletes the activity item from database.
     *
     * @urlParam activity_item required The Id of a activity item Example: 1
     *
     * @response {
     *   "message": "Activity item deleted successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to delete activity item, please try again later."
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
