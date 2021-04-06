<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportBulkUsers;
use App\Http\Requests\Admin\StoreUser;
use App\Http\Requests\Admin\UpdateUser;
use App\Http\Resources\V1\Admin\UserResource;
use App\Repositories\Admin\User\UserRepository;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @group 1001. Admin/Users
 *
 * APIs for users on admin panel.
 */
class UserController extends Controller
{
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get All Users List
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
        return UserResource::collection($this->userRepository->getAll($request->all()));
    }

    /**
     * Get Specified User 
     *
     * Get the specified user data.
     *
     * @urlParam user required The Id of a user Example: 1
     *
     * @responseFile responses/admin/user/user.json
     *
     * @param $id
     * @return UserResource
     * @throws GeneralException
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);
        return new UserResource($user);
    }

    /**
     * Create User
     *
     * Creates the new user in database.
     *
     * @response {
     *   "message": "User created successfully!",
     *   "data": ["Created User Data Array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to create user, please try again later."
     *   ]
     * }
     *
     * @param StoreUser $request
     * @return UserResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreUser $request)
    {
        $validated = $request->validated();
        $response = $this->userRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new UserResource($response['data'])], 200);
    }

    /**
     * Update Specified User
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
     * Delete Specified User
     *
     * Deletes the user record from database.
     *
     * @urlParam user required The Id of a user Example: 1
     *
     * @response {
     *   "message": "User deleted successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "You cannot delete your own user."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to delete user, please try again later."
     *   ]
     * }
     *
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->userRepository->destroy($id)], 200);
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
