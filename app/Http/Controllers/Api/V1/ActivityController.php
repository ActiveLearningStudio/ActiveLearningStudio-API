<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PlaylistResource;
use App\Models\Activity;
use App\Http\Resources\V1\ActivityResource;
use App\Http\Resources\V1\ActivityDetailResource;
use App\Models\Project;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\H5pActivityResource;

class ActivityController extends Controller
{
    private $activityRepository;

    /**
     * ActivityController constructor.
     *
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Display a listing of the activity.
     *
     * @return Response
     */
    public function index()
    {
        return response([
            'activities' => ActivityResource::collection($this->activityRepository->all()),
        ], 200);
    }

    /**
     * Upload thumb image for activity
     *
     * @param Request $request
     * @return Response
     */
    public function uploadThumb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thumb' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid image.']
            ], 400);
        }

        $path = $request->file('thumb')->store('/public/activities');

        return response([
            'thumbUrl' => Storage::url($path),
        ], 200);
    }

    /**
     * Store a newly created activity in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'playlist_id' => 'integer',
            'order' => 'integer',
            'h5p_content_id' => 'integer',
            'thumb_url' => 'string',
            'subject_id' => 'string',
            'education_level_id' => 'string',
        ]);

        $activity = $this->activityRepository->create($data);

        if ($activity) {
            return response([
                'activity' => new ActivityResource($activity),
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified activity.
     *
     * @param Activity $activity
     * @return Response
     */
    public function show(Activity $activity)
    {
        return response([
            'activity' => new ActivityResource($activity),
        ], 200);
    }

    /**
     * Update the specified activity in storage.
     *
     * @param Request $request
     * @param Activity $activity
     * @return Response
     */
    public function update(Request $request, Activity $activity)
    {
        $is_updated = $this->activityRepository->update($request->only([
            'playlist_id',
            'title',
            'type',
            'content',
            'shared',
            'order',
            'thumb_url',
            'subject_id',
            'education_level_id',
            'h5p_content_id',
        ]), $activity->id);

        if ($is_updated) {
            return response([
                'activity' => new ActivityResource($this->activityRepository->find($activity->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity.'],
        ], 500);
    }

    /**
     * Display the specified activity in detail.
     *
     * @param Activity $activity
     * @return Response
     */
    public function detail(Activity $activity)
    {
        $data = ['h5p_parameters' => null, 'user_name' => null, 'user_id' => null];

        if ($activity->playlist->project->user) {
            $data['user_name'] = $activity->playlist->project->user;
            $data['user_id'] = $activity->playlist->project->id;
        }

        if ($activity->type === 'h5p') {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($activity->h5p_content_id);
            $library = $content['library'] ? \H5PCore::libraryToString($content['library']) : 0;
            $data['h5p_parameters'] = '{"params":' . $core->filterParameters($content) . ',"metadata":' . json_encode((object)$content['metadata']) . '}';
        }

        return response([
            'activity' => new ActivityDetailResource($activity, $data),
        ], 200);
    }

    /**
     * Remove the specified activity from storage.
     *
     * @param Activity $activity
     * @return Response
     */
    public function destroy(Activity $activity)
    {
        $is_deleted = $this->activityRepository->delete($activity->id);

        if ($is_deleted) {
            return response([
                'message' => 'Activity is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity.'],
        ], 500);
    }

    /**
     * Activity H5P
     *
     * @param Activity $activity
     *
     * @return Response
     */
    public function h5p(Activity $activity)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        $content = $h5p->load_content($activity->h5p_content_id);
        $content['disable'] = config('laravel-h5p.h5p_preview_flag');
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];
        $user = Auth::user();
        
        // create event dispatch
        event(new H5pEvent(
            'content',
            NULL,
            $content['id'],
            $content['title'],
            $content['library']['name'],
            $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']
        ));
        $user_data = $user->only(['id', 'name', 'email']);

        $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];
        return response([
            'activity' => new H5pActivityResource($activity, $h5p_data),
            'playlist' => new PlaylistResource($activity->playlist),
        ], 200);
    }

    /**
     * Get H5P Resource Settings for Activity
     *
     * @param Activity $activity
     * @return Response
     */
    public function getH5pResourceSettings(Activity $activity)
    {
        $authenticated_user = auth()->user();

        if (!$authenticated_user->isAdmin() && !$this->hasPermission($activity)) {
            return response([
                'errors' => ["Activity doesn't belong to this user"]
            ], 400);
        }

        if ($activity->type === 'h5p') {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($activity->h5p_content_id);
        }

        return response([
            'h5p' => $content,
            'activity' => new ActivityResource($activity),
            'playlist' => new PlaylistResource($activity->playlist),
        ], 200);
    }

    /**
     * Get H5P Resource Settings for Activity
     *
     * @param Activity $activity
     * @return Response
     */
    public function getH5pResourceSettingsOpen(Activity $activity)
    {
        if ($activity->type === 'h5p') {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($activity->h5p_content_id);
        }

        $activity->shared = isset($activity->shared) ? $activity->shared : false;

        return response([
            'h5p' => $content,
            'activity' => new ActivityResource($activity),
            'playlist' => new PlaylistResource($activity->playlist),
        ], 200);
    }

    /**
     * Get H5P Resource Settings for Activity
     *
     * @param Activity $activity
     * @return Response
     */
    public function getH5pResourceSettingsShared(Activity $activity)
    {
        if (!$activity->shared) {
            return response([
                'errors' => ['Activity not found.']
            ], 400);
        }

        if ($activity->type === 'h5p') {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($activity->h5p_content_id);
        }

        return response([
            'h5p' => $content,
            'activity' => new ActivityResource($activity),
            'playlist' => new PlaylistResource($activity->playlist),
        ], 200);
    }

    private function hasPermission(Activity $activity)
    {
        $authenticated_user = auth()->user();
        $project_users = $activity->playlist->project->users;

        foreach ($project_users as $project_user) {
            if ($authenticated_user->id === $project_user->id) {
                return true;
            }
        }

        return false;
    }
}
