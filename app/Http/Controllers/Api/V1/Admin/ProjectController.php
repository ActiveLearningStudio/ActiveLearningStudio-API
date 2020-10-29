<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\ProjectResource;
use App\Models\Project;
use App\Repositories\Admin\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group 1004. Admin/Projects
 *
 * APIs for projects on admin panel.
 */
class ProjectController extends Controller
{

    private $projectRepository;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Get All Projects.
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @queryParam mode 1 for starter projects, 0 for non-starter. Default all. Example: 1
     * @queryParam indexing Integer value, 1 => 'REQUESTED', 2 => 'NOT APPROVED', 3 => 'APPROVED'. Default None. Example: 1
     * @queryParam exclude_starter Boolean value to exclude the user starter projects. Default false. Example: 0
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/project/projects.json
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return ProjectResource::collection($this->projectRepository->getAll($request->all()));
    }

    /**
     * Projects indexing Bulk
     *
     * Modify the index value of a projects in bulk.
     *
     * @bodyParam index_projects array required Projects Ids array. Example: [1,2,3]
     * @bodyParam index int required New Integer Index Value, 1 => 'REQUESTED', 2 => 'NOT APPROVED', 3 => 'APPROVED'. Example: 3
     *
     * @response {
     *   "message": "Indexes updated successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to update indexes, please try again later!"
     *   ]
     * }
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function updateIndexes(Request $request)
    {
        return response(['message' => $this->projectRepository->updateIndexes($request->only('index_projects', 'index'))], 200);
    }

    /**
     * Project Indexing
     *
     * Modify the index value of a project.
     *
     * @urlParam  project required Project Id. Example: 1
     * @urlParam  index required New Integer Index Value, 1 => 'REQUESTED', 2 => 'NOT APPROVED', 3 => 'APPROVED'. Example: 3
     *
     * @response {
     *   "message": "Index status changed successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Invalid index value provided."
     *   ]
     * }
     *
     * @param Project $project
     * @param $index
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function updateIndex(Project $project, $index)
    {
        return response(['message' => $this->projectRepository->updateIndex($project, $index)], 200);
    }

    /**
     * Get the shared project
     *
     * Get the specified project data.
     *
     * @urlParam project required The Id of a project. Example: 1
     *
     * @responseFile responses/admin/project/project-shared.json
     *
     * @param Project $project
     * @return Response
     */
    public function loadShared(Project $project): Response
    {
        return response([
            'data' => resolve(ProjectRepositoryInterface::class)->getProjectForPreview($project),
        ], 200);
    }

    /**
     * Starter Project Toggle
     *
     * Toggle the starter flag of any project
     *
     * @bodyParam projects array required Projects Ids array. Example: [1,2,3]
     * @bodyParam flag bool required Selected projects remove or make starter. Example: 1
     *
     * @response {
     *   "message": "Starter Projects status updated successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Choose at-least one project."
     *   ]
     * }
     *
     * @param Request $request
     * @param $flag
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function toggleStarter(Request $request, $flag)
    {
        return response(['message' => $this->projectRepository->toggleStarter($request->projects, $flag)], 200);
    }
}
