<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGroup;
use App\Http\Requests\Admin\UpdateUser;
use App\Http\Resources\V1\Admin\GroupResource;
use App\Repositories\Admin\Group\GroupRepository;
use App\Models\Group;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @group 1001. Admin/Groups
 *
 * APIs for groups on admin panel.
 */
class GroupController extends Controller
{
    private $GroupRepository;

    /**
     * GroupController constructor.
     *
     * @param GroupRepository $GroupRepository
     */
    public function __construct(GroupRepository $GroupRepository)
    {
        $this->GroupRepository = $GroupRepository;
    }

    /**
     * Get All Groups
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/user/users.json
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return GroupResource::collection($this->GroupRepository->getAll($request->all()));
    }

    public function show()
    {
        //
    }
    /**
     * Create Group
     *
     * Creates the new Group in database.
     *
     * @response {
     *   "message": "Group created successfully!",
     *   "data": ["Created Group Data Array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to create Group, please try again later."
     *   ]
     * }
     *
     * @param StoreGroup $request
     * @return GroupResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreGroup $request)
    {
        $validated = $request->validated();
        $response = $this->GroupRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new GroupResource($response['data'])], 200);
    }

    /**
     * Update User
     *
     * Updates the user data in database.
     *
     * @urlParam user required The Id of a user Example: 1
     *
     * @response {
     *   "message": "User data updated successfully!",
     *   "data": ["Updated User Data Array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to update user, please try again later."
     *   ]
     * }
     *
     * @param UpdateUser $request
     * @param $id
     * @return UserResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(UpdateUser $request, $id)
    {
        $validated = $request->validated();
        $response = $this->userRepository->update($id, $validated, $request->clone_project_id);
        return response(['message' => $response['message'], 'data' => new UserResource($response['data'])], 200);
    }

    /**
     * Delete Group
     *
     * Deletes the group record from database.
     *
     * @urlParam user required The Id of a user Example: 1
     *
     * @response {
     *   "message": "Group deleted successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to delete group, please try again later."
     *   ]
     * }
     *
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->GroupRepository->destroy($id)], 200);
    }

    /**
     * Users Basic Report
     *
     * Returns the paginated response of the users with basic reporting (DataTables are fully supported - All Params).
     *
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/user/users_report.json
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function reportBasic(Request $request)
    {
        return response($this->userRepository->reportBasic($request->all()), 200);
    }

    /**
     * Download Sample File
     *
     * Download import sample file for users.
     *
     * @response {
     *   "file": "Downloadable sample file."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Sample file not found!"
     *   ]
     * }
     *
     * @return BinaryFileResponse
     * @throws GeneralException
     */
    public function downloadSampleFile(): BinaryFileResponse
    {
        $sampleFile = config('constants.users.sample-file');
        if ($sampleFile) {
            return response()->download($sampleFile, 'users-import-sample.csv', ['Content-Type' => 'text/csv']);
        }
        throw new GeneralException('Sample file not found!');
    }

    /**
     * Bulk Import
     *
     * Bulk import the users from CSV file.
     *
     * @response 206 {
     *   "errors": ["Failed to import some rows data, please download detailed error report."],
     *   "report": "https://currikistudio.org/api/storage/temporary/users-import-report.csv"
     * }
     *
     * @response 200 {
     *   "message": "All users data imported successfully!"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Empty or bad formatted file, please download sample file for proper format."
     *   ]
     * }
     *
     * @param ImportBulkUsers $request
     * @return Application|ResponseFactory|Response
     * @throws GeneralException|\Throwable
     */
    public function bulkImport(ImportBulkUsers $request)
    {
        $validated = $request->validated();
        $response = $this->userRepository->bulkImport($validated);
        // if report is present then set status 206 for partial success and show error messages
        if ($response['report']) {
            return response(['errors' => [$response['message']], 'report' => $response['report']], 206);
        }
        return response(['message' => $response['message'], 'report' => $response['report']], 200);
    }

    /**
     * Change User Role
     *
     * Make any user admin or remove from admin.
     *
     * @urlParam user required The Id of a user. Example: 1
     * @urlParam role required Role 0 or 1, 1 for making admin, 0 for removing from admin. Example: 1
     *
     * @response 200 {
     *   "message": "User role is changed successfully!"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "You cannot change the role of yourself."
     *   ]
     * }
     *
     * @param User $user
     * @param $role
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function updateRole(User $user, $role)
    {
        return response(['message' => $this->userRepository->updateRole($user, (bool)$role)], 200);
    }
}
