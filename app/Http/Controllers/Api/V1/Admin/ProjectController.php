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
     * Display all projects.
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
        return response(['message' => $this->projectRepository->updateIndexes($request->only('index_projects', 'remove_index_projects'))], 200);
    }

    /**
     * Modify the index of a project
     *
     * @param Project $project
     * @return Application|ResponseFactory|Response
     */
    public function updateIndex(Project $project)
    {
        return response(['message' => $this->projectRepository->updateIndex($project)], 200);
    }

    /**
     * Display the shared project.
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
     * Temporary function for clearing db cache data - will be removed in future releases
     */
    public function clearDBCache(): void
    {
        \Artisan::call('cache:clear database');
        dd('Database cache cleared successfully!');
    }
}
