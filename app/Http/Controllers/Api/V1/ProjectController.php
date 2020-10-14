<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProjectRequest;
use App\Http\Requests\V1\ProjectUpdateRequest;
use App\Http\Resources\V1\ProjectDetailResource;
use App\Http\Resources\V1\ProjectResource;
use App\Jobs\CloneProject;
use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @group 3. Project
 *
 * APIs for project management
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
     * Get All Projects
     *
     * Get a list of the projects of a user.
     *
     * @responseFile responses/project/projects.json
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
     * Get All Projects Detail
     *
     * Get a list of the projects of a user with detail.
     *
     * @responseFile responses/project/projects-with-detail.json
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
     * Get Recent Projects
     *
     * Get a list of the recent projects of a user.
     *
     * @responseFile responses/project/projects-with-detail.json
     *
     * @return Response
     */
    public function recent()
    {
        return response([
            'projects' => ProjectDetailResource::collection($this->projectRepository->fetchRecentPublic(5)),
        ], 200);
    }

    /**
     * Get Default Projects
     *
     * Get a list of the default projects.
     *
     * @responseFile responses/project/projects-with-detail.json
     *
     * @return Response
     */
    // TODO: need to update documentation
    public function default()
    {
        $default_email = config('constants.curriki-demo-email');

        if (is_null($default_email)) {
            return response([
                'errors' => ['Please set CURRIKI_DEMO_EMAIL in .env file.'],
            ], 500);
        }

        return response([
            'projects' => $this->projectRepository->fetchDefault($default_email),
        ], 200);
    }

    /**
     * Upload thumbnail
     *
     * Upload thumbnail image for a project
     *
     * @bodyParam thumb image required Thumbnail image to upload Example: (binary)
     *
     * @response {
     *   "thumbUrl": "/storage/projects/1fqwe2f65ewf465qwe46weef5w5eqwq.png"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid image."
     *   ]
     * }
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
     * Create Project
     *
     * Create a new project in storage for a user.
     *
     * @bodyParam name string required Name of a project Example: Test Project
     * @bodyParam description string required Description of a project Example: This is a test project.
     * @bodyParam thumb_url string required Thumbnail Url of a project Example: https://images.pexels.com/photos/2832382
     * @bodyParam is_public boolean required Public status of a project Example: true
     *
     * @responseFile 201 responses/project/project.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create project. Please try again later."
     *   ]
     * }
     *
     * @param ProjectRequest $projectRequest
     * @return Response
     */
    public function store(ProjectRequest $projectRequest)
    {
        $data = $projectRequest->validated();
        $authenticated_user = auth()->user();
        $data['order'] = $this->projectRepository->getOrder($authenticated_user) + 1;
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
     * Get Project
     *
     * Get the specified project detail.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @responseFile 201 responses/project/project.json
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
     * Get Shared Project
     *
     * Get the specified shared project detail.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @responseFile 201 responses/project/project.json
     *
     * @response 400 {
     *   "errors": [
     *     "No shareable Project found."
     *   ]
     * }
     *
     * @param Project $project
     * @return Response
     */
    // TODO: need to update documentation
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
     * Share Project
     *
     * Share the specified project of a user.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @responseFile responses/project/project.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to share project."
     *   ]
     * }
     *
     * @param Project $project
     * @return Response
     */
    public function share(Project $project)
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
     * Remove Share Project
     *
     * Remove share the specified project of a user.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @responseFile responses/project/project-not-shared.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove share project."
     *   ]
     * }
     *
     * @param Project $project
     * @return Response
     */
    public function removeShare(Project $project)
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
     * Update Project
     *
     * Update the specified project of a user.
     *
     * @urlParam project required The Id of a project Example: 1
     * @bodyParam name string required Name of a project Example: Test Project
     * @bodyParam description string required Description of a project Example: This is a test project.
     * @bodyParam thumb_url string required Thumbnail Url of a project Example: https://images.pexels.com/photos/2832382
     *
     * @responseFile responses/project/project.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update project."
     *   ]
     * }
     *
     * @param ProjectUpdateRequest $projectUpdateRequest
     * @param Project $project
     * @return Response
     */
    public function update(ProjectUpdateRequest $projectUpdateRequest, Project $project)
    {
        $data = $projectUpdateRequest->validated();

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
     * Remove Project
     *
     * Remove the specified project of a user.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @response {
     *   "message": "Project has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete project."
     *   ]
     * }
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $is_deleted = $this->projectRepository->delete($project->id);

        if ($is_deleted) {
            return response([
                'message' => 'Project has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete project.'],
        ], 500);
    }

    /**
     * Clone Project
     *
     * Clone the specified project of a user.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @response {
     *   "message": "Project is being cloned|duplicated in background!"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Not a Public Project."
     *   ]
     * }
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function clone(Request $request, Project $project)
    {
        $isDuplicate = $this->projectRepository->checkIsDuplicate(auth()->user(), $project->id);
        // pushed cloning of project in background
        CloneProject::dispatch(auth()->user(), $project, $request->bearerToken())->delay(now()->addSecond());
        return response([
            'message' => ($isDuplicate) ? "Project is being duplicated in background!" : "Project is being cloned in background!"
        ], 200);
    }

    /**
     * @uses One time script to populate all missing order number
     */
    public function populateOrderNumber()
    {
       $this->projectRepository->populateOrderNumber();
    }

    /**
     * Reorder Projects
     *
     * Reorder projects of a user.
     *
     * @bodyParam projects array required projects of a user
     * @responseFile responses/project/projects.json
     * @param Request $request
     * @return Response
     */
    public function reorder(Request $request)
    {
        $authenticated_user = auth()->user();

        $this->projectRepository->saveList($request->projects);

        return response([
            'projects' => ProjectResource::collection($authenticated_user->projects),
        ], 200);
    }
}
