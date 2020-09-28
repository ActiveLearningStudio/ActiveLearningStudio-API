<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProjectResource;
use App\Http\Resources\V1\ProjectDetailResource;
use App\Models\Project;
use App\Http\Requests\V1\ProjectRequest;
use App\Http\Requests\V1\ProjectEditRequest;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\H5pController;

/**
 * @group Project management
 *
 * APIs for managing projects
 */
class ProjectController extends Controller
{

    private $projectRepository;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;

        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the project.
     *
     * @return Response
     */
    public function index()
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->isAdmin()) {
            return response([
                'projects' => ProjectResource::collection($this->projectRepository->all()),
            ], 200);
        }

        return response([
            'projects' => ProjectResource::collection($authenticated_user->projects),
        ], 200);
    }

    /**
     * Display a listing of the project with detail.
     *
     * @return Response
     */
    public function detail()
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->isAdmin()) {
            return response([
                'projects' => ProjectDetailResource::collection($this->projectRepository->all()),
            ], 200);
        }

        return response([
            'projects' => ProjectDetailResource::collection($authenticated_user->projects),
        ], 200);
    }

    /**
     * Display a listing of the recent projects.
     *
     * @return Response
     */
    public function recent()
    {
        return response([
            'projects' => $this->projectRepository->fetchRecentPublic(5),
        ], 200);
    }

    /**
     * Display a listing of the default projects.
     *
     * @return Response
     */
    public function default()
    {
        $defaultEmail = config('constants.curriki-demo-email');

        if (is_null($defaultEmail)) {
            return response([
                'errors' => ['please set CURRIKI_DEMO_EMAIL in .env'],
            ], 500);
        }

        return response([
            'projects' => $this->projectRepository->fetchDefault($defaultEmail),
        ], 200);
    }

    /**
     * Upload thumb image for project
     *
     * @param Request $request
     * @return Response
     */
    public function uploadThumb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thumb' => 'required|image|max:102400',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid image.']
            ], 400);
        }

        $path = $request->file('thumb')->store('/public/projects');

        return response([
            'thumbUrl' => Storage::url($path),
        ], 200);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param ProjectRequest $projectRequest
     * @return Response
     */
    public function store(ProjectRequest $projectRequest)
    {
        $data = $projectRequest->validated();

        $authenticated_user = auth()->user();
        $project = $authenticated_user->projects()->create($data, ['role' => 'owner']);

        if ($project) {
            return response([
                'project' => new ProjectResource($project),
            ], 201);
        }

        return response([
            'errors' => ['Could not create project. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified project.
     *
     * @queryParam projectId required This is id of a project.
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project)
    {
        return response([
            'project' => new ProjectResource($project),
        ], 200);
    }

    /**
     * Display the specified project.
     *
     * @param Project $project
     * @return Response
     */
    public function loadShared(Project $project)
    {
        if ($project->shared || $project->is_public) {
            return response([
                'project' => $this->projectRepository->getProjectForPreview($project),
            ], 200);
        }

        return response([
            'errors' => ['No shareable Project found.'],
        ], 400);
    }

    /**
     * Share the specified project.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function share(Request $request, Project $project)
    {
        $is_updated = $this->projectRepository->update([
            'shared' => true,
        ], $project->id);

        if ($is_updated) {
            return response([
                'project' => new ProjectResource($this->projectRepository->find($project->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to share project.'],
        ], 500);
    }

    /**
     * Remove share specified project.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function removeShare(Request $request, Project $project)
    {
        $is_updated = $this->projectRepository->update([
            'shared' => false,
        ], $project->id);

        if ($is_updated) {
            return response([
                'project' => new ProjectResource($this->projectRepository->find($project->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove share project.'],
        ], 500);
    }

    /**
     * Update the specified project in storage.
     *
     * @param ProjectEditRequest $projectEditRequest
     * @param Project $project
     * @return Response
     */
    public function update(ProjectEditRequest $projectEditRequest, Project $project)
    {
        $data = $projectEditRequest->validated();

        $is_updated = $this->projectRepository->update($data, $project->id);

        if ($is_updated) {
            return response([
                'project' => new ProjectResource($this->projectRepository->find($project->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update project.'],
        ], 500);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $is_deleted = $this->projectRepository->delete($project->id);

        if ($is_deleted) {
            return response([
                'message' => 'Project is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete project.'],
        ], 500);
    }

    /**
     * @apiResourceCollection  App\Http\Resources\V1\ProjectResource
     * @apiResourceModel  App\Models\Project
     *
     * @response  {
     *  "message": "Project is cloned successfully",
     * },
     *  {
     *  "errors": "Not a Public Project",
     * }
     */
    public function clone(Request $request, Project $project)
    {
        if (!$project->is_public) {
            return response([
                'errors' => ['Not a Public Project.'],
            ], 500);
        }

        $this->projectRepository->clone(auth()->user(), $project, $request->bearerToken());
        return response([
            'message' => 'Project is cloned successfully.',
        ], 200);
    }

}
