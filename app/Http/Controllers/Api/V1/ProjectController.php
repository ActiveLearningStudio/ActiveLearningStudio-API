<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Activity\ActivityRepositoryInterface;

class ProjectController extends Controller {

    private $projectRepository;
    private $activityRepository;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(ProjectRepositoryInterface $projectRepository,ActivityRepositoryInterface $activityRepository) {
        $this->projectRepository = $projectRepository;
        $this->activityRepository = $activityRepository;

        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the project.
     *
     * @return Response
     */
    public function index() {
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
     * Upload thumb image for project
     *
     * @param Request $request
     * @return Response
     */
    public function uploadThumb(Request $request) {
        $validator = Validator::make($request->all(), [
                    'thumb' => 'required|image',
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
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'thumb_url' => 'required',
        ]);

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
     * @param Project $project
     * @return Response
     */
    public function show(Project $project) {
        return response([
            'project' => new ProjectResource($project),
                ], 200);
    }

    /**
     * Share the specified project.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function share(Request $request, Project $project) {
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
    public function removeShare(Request $request, Project $project) {
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
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function update(Request $request, Project $project) {
        $is_updated = $this->projectRepository->update($request->only([
                    'name',
                    'description',
                    'thumb_url',
                ]), $project->id);

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
    public function destroy(Project $project) {
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

    public function clone(Request $request, Project $project) {

        if ($project->is_public) {
            return response([
                'errors' => ['Not a Public Project.'],
                    ], 500);
        }

        $authenticated_user = auth()->user();
        $data = [
            'name' => $project->name,
            'description' => $project->description,
            'thumb_url' => $project->thumb_url,
            //'shared' => $project->shared,
            'starter_project' => $project->starter_project,
            'is_public' => $project->is_public,
        ];

        $clonned_project = $authenticated_user->projects()->create($data, ['role' => 'owner']);
        if (!$clonned_project) {
            return response([
                'errors' => ['Could not create project. Please try again later.'],
                    ], 500);
        }


        $playlists = $project->playlists;
        foreach ($playlists as $playlist) {
            $play_list_data = ['title' => $playlist->title,
                'order' => $playlist->order,
                'is_public' => $playlist->is_public
            ];
            $cloned_playlist = $clonned_project->playlists()->create($play_list_data);

            $activites = $playlist->activities;
            foreach ($activites as $activity) {
                $activity_data = [
                    'title' => $activity->title,
                    'type' => $activity->type,
                    'content' => $activity->content,
                    'playlist_id' => $cloned_playlist->id,
                    'order' => $activity->order,
                    'h5p_content_id' => $activity->h5p_content_id,
                    'thumb_url' => $activity->thumb_url,
                    'subject_id' => $activity->subject_id,
                    'education_level_id' => $activity->education_level_id,
                ];

                $cloned_activity = $this->activityRepository->create($activity_data);
            }
        }
    }

}
