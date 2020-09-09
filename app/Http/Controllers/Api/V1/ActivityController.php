<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PlaylistResource;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Http\Resources\V1\ActivityResource;
use App\Http\Resources\V1\ActivityDetailResource;
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
use H5pCore;

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
        $data['is_public'] = $this->activityRepository->getPlaylistIsPublicValue($data['playlist_id']);
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
            $this->update_h5p($request->get('data'), $activity->h5p_content_id);
            return response([
                'activity' => new ActivityResource($this->activityRepository->find($activity->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity.'],
        ], 500);
    }


    /**
     * Update H5P
     *
     * @param $request
     * @param int $id
     */
    public function update_h5p($request, $id)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;
        $request['action'] = 'create';
        /*$this->validate(
            $request,
            ['action' => 'required'],
            [],
            [
                'title' => trans('laravel-h5p.content.title'),
                'action' => trans('laravel-h5p.content.action'),
            ]
        );*/

        $event_type = 'update';
        $content = $h5p->load_content($id);
        $content['disable'] = H5PCore::DISABLE_NONE;

        $oldLibrary = $content['library'];
        $oldParams = json_decode($content['params']);

        try {
            if ($request['action'] === 'create') {
                $content['library'] = $core->libraryFromString($request['library']);
                if (!$content['library']) {
                    throw new H5PException('Invalid library.');
                }

                // Check if library exists.
                $content['library']['libraryId'] = $core->h5pF->getLibraryId(
                    $content['library']['machineName'],
                    $content['library']['majorVersion'],
                    $content['library']['minorVersion']
                );
                if (!$content['library']['libraryId']) {
                    throw new H5PException('No such library');
                }

                $content['params'] = $request['parameters'];
                $params = json_decode($content['params']);
                // $content['title'] = $params->metadata->title;

                if ($params === NULL) {
                    throw new H5PException('Invalid parameters');
                }

                $content['params'] = json_encode($params->params);
                $content['metadata'] = $params->metadata;

                // Trim title and check length
                $trimmed_title = empty($content['metadata']->title) ? '' : trim($content['metadata']->title);
                if ($trimmed_title === '') {
                    throw new H5PException('Missing title');
                }

                if (strlen($trimmed_title) > 255) {
                    throw new H5PException('Title is too long. Must be 256 letters or shorter.');
                }

                // Set disabled features
                $this->get_disabled_content_features($core, $content);

                // Save new content
                $core->saveContent($content);

                // Move images and find all content dependencies
                $editor->processParameters($content['id'], $content['library'], $params->params, $oldLibrary, $oldParams);

                $return_id = $content['id'];
            } elseif ($request->get('action') === 'upload') {
                $content['uploaded'] = true;

                $this->get_disabled_content_features($core, $content);

                // Handle file upload
                $return_id = $this->handle_upload($content);
            }

            if ($return_id) {
                return response([
                    'success' => trans('laravel-h5p.content.updated'),
                    'id' => $return_id
                ], 200);
            } else {
                return response(['fail' => trans('laravel-h5p.content.can_not_updated')], 400);
            }
        } catch (H5PException $ex) {
            return response(['fail' => trans('laravel-h5p.content.can_not_updated')], 400);
        }
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
     *
     * @apiResourceCollection  App\Http\Resources\V1\PlaylistResource
     * @apiResourceCollection  App\Http\Resources\V1\ActivityResource
     * @apiResourceModel  App\Models\Playlist
     * @apiResourceModel  App\Models\Activity
     *
     *  @response  {
     *  "message": "Activity is cloned successfully",
     * },
     *  {
     *  "errors": "Not a Public PlayList",
     * },
     *  {
     *  "errors": "Failed to clone activity.",
     * }
     */
    public function clone(Request $request, Playlist $playlist, Activity $activity)
    {
        if (!$activity->is_public) {
            return response([
                'errors' => ['Not a Public Activity.'],
            ], 500);
        }

        $cloned_activity = $this->activityRepository->clone($request, $playlist, $activity);

        if ($cloned_activity) {
            return response([
                'message' => 'Activity is cloned successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to clone activity.'],
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
