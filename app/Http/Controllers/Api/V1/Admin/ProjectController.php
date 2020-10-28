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
     * Get All Projects for listing.
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
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
     * Modify the index of a projects
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
     * Modify the index of a project
     *
     * @param Project $project
     * @param $index
     * @return Application|ResponseFactory|Response
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
     * @urlParam project required The Id of a lms-setting Example: 1
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
     * Update public status of project
     *
     * @param Project $project
     * @return Application|ResponseFactory|Response
     */
    public function togglePublicStatus(Project $project)
    {
        return response(['message' => $this->projectRepository->togglePublicStatus($project)], 200);
    }

    /**
     * Toggle the starter projects flag
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

    /**
     * CUR - 612 => Update existing project rows for is_user_starter flag
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function updateUserStarterFlag()
    {
        return response(['message' => $this->projectRepository->updateUserStarterFlag()], 200);
    }
}
