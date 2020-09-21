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
 * @group Project Administration
 * @authenticated
 *
 * Apis for administration of projects
 */
class ProjectController extends Controller
{

    private $projectRepository;

    /**
     * ProjectController constructor.
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
//        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display all projects.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return ProjectResource::collection($this->projectRepository->getProjects());
    }

    /**
     * Modify the index of a project
     *
     * @urlParam id required The id means user_id.
     *
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function updateIndexes(Request $request, $id)
    {
        return response(['message' => $this->projectRepository->updateIndexes($request->projects, $id)], 200);
    }

    /**
     * @param Project $project
     * @return Application|ResponseFactory|Response
     */
    public function updateIndex(Project $project)
    {
        \Log::info($project);
        return response(['message' => $this->projectRepository->updateIndex($project)], 200);
    }

    /**
     * Display the shared project.
     *
     * @param Project $project
     * @return Response
     */
    public function loadShared(Project $project)
    {
        return response([
            'data' => resolve(ProjectRepositoryInterface::class)->getProjectForPreview($project),
        ], 200);
    }

}
