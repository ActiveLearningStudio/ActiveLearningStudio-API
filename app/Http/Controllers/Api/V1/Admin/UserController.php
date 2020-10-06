<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportBulkUsers;
use App\Http\Requests\Admin\StoreUser;
use App\Http\Requests\Admin\UpdateUser;
use App\Http\Resources\V1\Admin\UserResource;
use App\Repositories\Admin\User\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    private $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return UserResource::collection($this->userRepository->getAll($request->all()));
    }

    /**
     * @param $id
     * @return UserResource
     * @throws GeneralException
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        return new UserResource($user);
    }

    /**
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
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->userRepository->destroy($id)], 200);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function reportBasic(Request $request)
    {
        return response($this->userRepository->reportBasic($request->all()), 200);
    }

    /**
     * Temporary function for invoking the starter projects command in background
     * This command will get users with less than 2 projects and assign starter projects
     */
    public function assignStarterProjects(): void
    {
        invoke_starter_projects_command();
        dd("Assign starter projects command invoked successfully.");
    }

    /**
     * Users import sample file
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
     * @param ImportBulkUsers $request
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function bulkImport(ImportBulkUsers $request)
    {
        $validated = $request->validated();
        $response = $this->userRepository->bulkImport($validated);
        // if report is present then set status 206 for partial success and show error messages
        if ($response['report']){
            return response(['errors' => [$response['message']], 'report' => $response['report']], 206);
        }
        return response(['message' => $response['message'], 'report' => $response['report']], 200);
    }
}
