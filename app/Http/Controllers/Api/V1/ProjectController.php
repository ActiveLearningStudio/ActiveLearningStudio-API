<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ProjectUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProjectRequest;
use App\Http\Requests\V1\ProjectUpdateRequest;
use App\Http\Requests\V1\ProjectUploadThumbRequest;
use App\Http\Resources\V1\ProjectDetailResource;
use App\Http\Resources\V1\ProjectResource;
use App\Jobs\CloneProject;
use App\Models\Organization;
use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

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

        // $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Get All Projects
     *
     * Get a list of the projects of a user.
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/project/projects.json
     *
     * @return Response
     */
    public function index(Organization $suborganization)
    {
        $this->authorize('viewAny', [Project::class, $suborganization]);

        $authenticated_user = auth()->user();
/*
        This returns all projects if the user is admin and breaks the frontend for that user given the number of projects
        Something like it might be implemented in admin API but not here
        if ($authenticated_user->isAdmin()) {
            return response([
                'projects' => ProjectResource::collection($this->projectRepository->all()),
            ], 200);
        }
*/
        return response([
            'projects' => ProjectResource::collection($authenticated_user->projects()->where('organization_id', $suborganization->id)->get()),
        ], 200);
    }

    /**
     * Get All Projects Detail
     *
     * Get a list of the projects of a user with detail.
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/project/projects-with-detail.json
     *
     * @return Response
     */
    public function detail(Organization $suborganization)
    {
       $this->authorize('view', [Project::class, $suborganization]);

        $authenticated_user = auth()->user();

        if ($authenticated_user->isAdmin()) {
            return response([
                'projects' => ProjectDetailResource::collection($this->projectRepository->all()),
            ], 200);
        }

        return response([
            'projects' => ProjectDetailResource::collection($authenticated_user->projects()->where('organization_id', $suborganization->id)->get()),
        ], 200);
    }

    /**
     * Get Recent Projects
     *
     * Get a list of the recent projects of a user.
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @responseFile responses/project/projects-with-detail.json
     *
     * @return Response
     */
    public function recent(Organization $suborganization)
    {
        $this->authorize('recent', [Project::class, $suborganization]);

        return response([
            'projects' => ProjectDetailResource::collection($this->projectRepository->fetchRecentPublic(config('constants.default-pagination-limit-recent-projects'), $suborganization->id)),
        ], 200);
    }

    /**
     * Get Default Projects
     *
     * Get a list of the default projects.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile responses/project/projects-with-detail.json
     *
     * @return Response
     */
    // TODO: need to update documentation
    public function default(Organization $suborganization)
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
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam thumb image required Thumbnail image to upload Example: (binary)
     *
     * @response {
     *   "thumbUrl": "/storage/projects/1fqwe2f65ewf465qwe46weef5w5eqwq.png"
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "thumb": [
     *       "The thumb must be an image."
     *     ]
     *   }
     * }
     *
     * @param ProjectUploadThumbRequest $projectUploadThumbRequest
     * @return Response
     */
    public function uploadThumb(ProjectUploadThumbRequest $projectUploadThumbRequest, Organization $suborganization)
    {
        $this->authorize('uploadThumb', [Project::class, $suborganization]);

        $data = $projectUploadThumbRequest->validated();

        $path = $projectUploadThumbRequest->file('thumb')->store('/public/projects');

        return response([
            'thumbUrl' => Storage::url($path),
        ], 200);
    }

    /**
     * Create Project
     *
     * Create a new project in storage for a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @bodyParam name string required Name of a project Example: Test Project
     * @bodyParam description string required Description of a project Example: This is a test project.
     * @bodyParam thumb_url string required Thumbnail Url of a project Example: https://images.pexels.com/photos/2832382
     * @bodyParam organization_visibility_type_id int required Id of the organization visibility type Example: 1
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
    public function store(ProjectRequest $projectRequest, Organization $suborganization)
    {
        $this->authorize('create', [Project::class, $suborganization]);

        $data = $projectRequest->validated();
        $authenticated_user = auth()->user();
        $data['order'] = $this->projectRepository->getOrder($authenticated_user) + 1;
        $data['organization_id'] = $suborganization->id;
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
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile 201 responses/project/project.json
     *
     * @param Project $project
     * @return Response
     */
    public function show(Organization $suborganization, Project $project)
    {
        $this->authorize('view', [Project::class, $suborganization]);

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
        // 3 is for indexing approved - see Project Model @indexing property
        if ($project->shared || ($project->indexing === 3)) {
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
     * @urlParam suborganization required The Id of a suborganization Example: 1
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
    public function share(Organization $suborganization, Project $project)
    {
        $this->authorize('share', [Project::class, $suborganization]);

        $is_updated = $this->projectRepository->update([
            'shared' => true,
        ], $project->id);

        if ($is_updated) {
            $updated_project = new ProjectResource($this->projectRepository->find($project->id));
            event(new ProjectUpdatedEvent($updated_project));

            return response([
                'project' => $updated_project,
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
     * @urlParam suborganization required The Id of a suborganization Example: 1
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
    public function removeShare(Organization $suborganization, Project $project)
    {
        $this->authorize('share', [Project::class, $suborganization]);

        $is_updated = $this->projectRepository->update([
            'shared' => false,
        ], $project->id);

        if ($is_updated) {
            $updated_project = new ProjectResource($this->projectRepository->find($project->id));
            event(new ProjectUpdatedEvent($updated_project));

            return response([
                'project' => $updated_project,
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
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required Name of a project Example: Test Project
     * @bodyParam description string required Description of a project Example: This is a test project.
     * @bodyParam thumb_url string required Thumbnail Url of a project Example: https://images.pexels.com/photos/2832382
     * @bodyParam organization_visibility_type_id int required Id of the organization visibility type Example: 1
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
    public function update(ProjectUpdateRequest $projectUpdateRequest, Organization $suborganization, Project $project)
    {
        $this->authorize('update', [Project::class, $suborganization]);

        $data = $projectUpdateRequest->validated();

        $is_updated = $this->projectRepository->update($data, $project->id);

        if ($is_updated) {
            $updated_project = new ProjectResource($this->projectRepository->find($project->id));
            event(new ProjectUpdatedEvent($updated_project));

            return response([
                'project' => $updated_project,
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
     * @urlParam suborganization required The Id of a suborganization Example: 1
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
    public function destroy(Organization $suborganization, Project $project)
    {
        $this->authorize('delete', [Project::class, $suborganization]);

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
     * @urlParam suborganization required The Id of a suborganization Example: 1
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
    public function clone(Request $request, Organization $suborganization, Project $project)
    {
        $this->authorize('clone', [Project::class, $suborganization]);

        $isDuplicate = $this->projectRepository->checkIsDuplicate(auth()->user(), $project->id);
        $process = ($isDuplicate) ? "duplicate" : "clone";
        // pushed cloning of project in background
        CloneProject::dispatch(auth()->user(), $project, $request->bearerToken())->delay(now()->addSecond());
        return response([
            'message' =>  "Your request to $process project [$project->name] has been received and is being processed. You will receive an email notice as soon as it is available.",
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

    /**
     * Indexing Request
     *
     * Make the indexing request for a project.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @response {
     *   "message": "Indexing request for this project has been made successfully!"
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [Project] Id"
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Indexing value is already set. Current indexing state of this project: CURRENT_STATE_OF_PROJECT_INDEX"
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Project must be finalized before requesting the indexing."
     *   ]
     * }
     *
     *
     * @param Project $project
     * @return Application|ResponseFactory|Response
     */
    public function indexing(Project $project)
    {
        $this->projectRepository->indexing($project);
        return response([
            'message' => 'Indexing request for this project has been made successfully!'
        ], 200);
    }

    /**
     * Status Update
     *
     * Update the status of the project, draft to final or vice versa.
     *
     * @urlParam project required The Id of a project Example: 1
     *
     * @response {
     *   "message": "Status of this project has been updated successfully!"
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [Project] Id"
     * }
     *
     * @param Project $project
     * @return Application|ResponseFactory|Response
     */
    public function statusUpdate(Project $project)
    {
        $this->projectRepository->statusUpdate($project);
        return response([
            'message' => 'Status of this project has been updated successfully!'
        ], 200);
    }

    /**
     * Favorite/Unfavorite Project
     *
     * Favorite/Unfavorite the specified project for a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam project required The Id of a project Example: 1
     *
     * @response {
     *   "message": "This resource will be removed from your Favorites. You will no longer be able to reuse/remix its contents into your projects."
     * }
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function favorite(Request $request, Organization $suborganization, Project $project)
    {
        $this->authorize('markFavorite', [Project::class, $suborganization]);

        $updateStatus = $this->projectRepository->favoriteUpdate(auth()->user(), $project, $suborganization->id);

        if ($updateStatus) {
            $message = 'This resource will be removed from your Favorites. ';
            $message .= 'You will no longer be able to reuse/remix its contents into your projects.';
        } else {
            $message = 'This resource has been added to your favorites! ';
            $message .= 'Once a resource has been added to your favorites, ';
            $message .= 'you can preview and add them to your own projects.';
        }

        return response([
            'message' => $message
        ], 200);
    }

    /**
     * Get All Favorite Projects
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * Get a list of the favorite projects of a user.
     *
     * @responseFile responses/project/projects.json
     *
     * @return Response
     */
    public function getFavorite(Organization $suborganization)
    {
        $this->authorize('favorite', [Project::class, $suborganization]);

        $authenticated_user = auth()->user();

        $favoriteProjects = $authenticated_user->favoriteProjects()
                            ->wherePivot('organization_id', $suborganization->id)
                            ->get();

        return response([
            'projects' => ProjectResource::collection($favoriteProjects),
        ], 200);
    }
}
