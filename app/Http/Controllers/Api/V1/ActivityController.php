<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ActivityUpdatedEvent;
use App\Events\PlaylistUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ActivityRequest;
use App\Http\Resources\V1\ActivityResource;
use App\Http\Resources\V1\ActivityDetailResource;
use App\Http\Resources\V1\H5pActivityResource;
use App\Http\Resources\V1\PlaylistResource;
use App\Jobs\CloneActivity;
use App\Models\Activity;
use App\Models\Pivots\TeamProjectUser;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Djoudi\LaravelH5p\Exceptions\H5PException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use H5pCore;

/**
 * @group 5. Activity
 *
 * APIs for activity management
 */
class ActivityController extends Controller
{

    private $playlistRepository;
    private $activityRepository;

    /**
     * ActivityController constructor.
     *
     * @param PlaylistRepositoryInterface $playlistRepository
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(
        PlaylistRepositoryInterface $playlistRepository,
        ActivityRepositoryInterface $activityRepository
    )
    {
        $this->playlistRepository = $playlistRepository;
        $this->activityRepository = $activityRepository;

        // $this->authorizeResource(Activity::class, 'activity');
    }

    /**
     * Get Activities
     *
     * Get a list of activities
     *
     * @urlParam playlist required The Id of a playlist Example: 1
     *
     * @responseFile responses/activity/activities.json
     *
     * @param Playlist $playlist
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Playlist $playlist)
    {
        $this->authorize('viewAny', [Project::class, $playlist->project->organization]);

        return response([
            'activities' => ActivityResource::collection($playlist->activities),
        ], 200);
    }

    /**
     * Upload Activity thumbnail
     *
     * Upload thumbnail image for a activity
     *
     * @bodyParam thumb image required Thumbnail image to upload Example: (binary)
     *
     * @response {
     *   "thumbUrl": "/storage/activities/1fqwe2f65ewf465qwe46weef5w5eqwq.png"
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

        $path = $request->file('thumb')->store('/public/activities');

        return response([
            'thumbUrl' => Storage::url($path),
        ], 200);
    }

    /**
     * Create Activity
     *
     * Create a new activity.
     *
     * @urlParam playlist required The Id of a playlist Example: 1
     * @bodyParam title string required The title of a activity Example: Science of Golf: Why Balls Have Dimples
     * @bodyParam type string required The type of a activity Example: h5p
     * @bodyParam content string required The content of a activity Example:
     * @bodyParam order int The order number of a activity Example: 2
     * @bodyParam h5p_content_id int The Id of H5p content Example: 59
     * @bodyParam thumb_url string The image url of thumbnail Example: null
     * @bodyParam subject_id string The Id of a subject Example: null
     * @bodyParam education_level_id string The Id of a education level Example: null
     *
     * @responseFile 201 responses/activity/activity.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create activity. Please try again later."
     *   ]
     * }
     *
     * @param ActivityRequest $request
     * @param Playlist $playlist
     * @return Response
     * @throws AuthorizationException
     */
    public function store(ActivityRequest $request, Playlist $playlist)
    {
        $this->authorize('create', [Project::class, $playlist->project->organization]);

        $data = $request->validated();

        $data['order'] = $this->activityRepository->getOrder($playlist->id) + 1;
        $activity = $playlist->activities()->create($data);

        if ($activity) {
            $updated_playlist = new PlaylistResource($this->playlistRepository->find($playlist->id));
            event(new PlaylistUpdatedEvent($updated_playlist->project, $updated_playlist));

            return response([
                'activity' => new ActivityResource($activity),
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity. Please try again later.'],
        ], 500);
    }

    /**
     * Get Activity
     *
     * Get the specified activity.
     *
     * @urlParam playlist required The Id of a playlist Example: 1
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid playlist or activity id."
     *   ]
     * }
     *
     * @param Playlist $playlist
     * @param Activity $activity
     * @return Response
     */
    public function show(Playlist $playlist, Activity $activity)
    {
        if ($activity->playlist_id !== $playlist->id) {
            return response([
                'errors' => ['Invalid playlist or activity id.'],
            ], 400);
        }

        return response([
            'activity' => new ActivityResource($activity),
        ], 200);
    }

    /**
     * Update Activity
     *
     * Update the specified activity.
     *
     * @urlParam playlist required The Id of a playlist Example: 1
     * @urlParam activity required The Id of a activity Example: 1
     * @bodyParam title string required The title of a activity Example: Science of Golf: Why Balls Have Dimples
     * @bodyParam type string required The type of a activity Example: h5p
     * @bodyParam content string required The content of a activity Example:
     * @bodyParam shared bool The status of share of a activity Example: false
     * @bodyParam order int The order number of a activity Example: 2
     * @bodyParam h5p_content_id int The Id of H5p content Example: 59
     * @bodyParam thumb_url string The image url of thumbnail Example: null
     * @bodyParam subject_id string The Id of a subject Example: null
     * @bodyParam education_level_id string The Id of a education level Example: null
     *
     * @responseFile responses/activity/activity.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid playlist or activity id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update activity."
     *   ]
     * }
     *
     * @param ActivityRequest $request
     * @param Playlist $playlist
     * @param Activity $activity
     * @return Response
     */
    public function update(ActivityRequest $request, Playlist $playlist, Activity $activity)
    {
        if ($activity->playlist_id !== $playlist->id) {
            return response([
                'errors' => ['Invalid playlist or activity id.'],
            ], 400);
        }

        $data = $request->validated();
        $is_updated = $this->activityRepository->update($data, $activity->id);

        if ($is_updated) {
            $this->update_h5p($data, $activity->h5p_content_id);

            $updated_activity = new ActivityResource($this->activityRepository->find($activity->id));
            $playlist = new PlaylistResource($updated_activity->playlist);
            event(new ActivityUpdatedEvent($playlist->project, $playlist, $updated_activity));

            return response([
                'activity' => $updated_activity,
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
     * @return mixed
     * @throws H5PException
     */
    public function update_h5p($request, $id)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;
        $request['action'] = 'create';
        $event_type = 'update';
        $content = $h5p->load_content($id);
        $content['disable'] = H5PCore::DISABLE_NONE;

        $oldLibrary = $content['library'];
        $oldParams = json_decode($content['params']);

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
        $set = array(
            H5PCore::DISPLAY_OPTION_FRAME => filter_input(INPUT_POST, 'frame', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_DOWNLOAD => filter_input(INPUT_POST, 'download', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_EMBED => filter_input(INPUT_POST, 'embed', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_COPYRIGHT => filter_input(INPUT_POST, 'copyright', FILTER_VALIDATE_BOOLEAN),
        );
        $content['disable'] = $core->getStorableDisplayOptions($set, $content['disable']);
        // Save new content
        $core->saveContent($content);
        // Move images and find all content dependencies
        $editor->processParameters($content['id'], $content['library'], $params->params, $oldLibrary, $oldParams);

        return $content['id'];
    }

    /**
     * Get Activity Detail
     *
     * Get the specified activity in detail.
     *
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity-with-detail.json
     *
     * @param Activity $activity
     * @return Response
     */
    public function detail(Activity $activity)
    {
        $this->authorize('view', [Project::class, $activity->playlist->project->organization]);

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
     * Share Activity
     *
     * Share the specified activity.
     *
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity-shared.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to share activity."
     *   ]
     * }
     *
     * @param Activity $activity
     * @return Response
     */
    public function share(Activity $activity)
    {
        $this->authorize('view', [Project::class, $activity->playlist->project->organization]);

        $is_updated = $this->activityRepository->update([
            'shared' => true,
        ], $activity->id);

        if ($is_updated) {
            $updated_activity = new ActivityResource($this->activityRepository->find($activity->id));
            $playlist = new PlaylistResource($updated_activity->playlist);
            event(new ActivityUpdatedEvent($playlist->project, $playlist, $updated_activity));

            return response([
                'activity' => $updated_activity,
            ], 200);
        }

        return response([
            'errors' => ['Failed to share activity.'],
        ], 500);
    }

    /**
     * Remove Share Activity
     *
     * Remove share the specified activity.
     *
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove share activity."
     *   ]
     * }
     *
     * @param Activity $activity
     * @return Response
     */
    public function removeShare(Activity $activity)
    {
        $this->authorize('view', [Project::class, $activity->playlist->project->organization]);

        $is_updated = $this->activityRepository->update([
            'shared' => false,
        ], $activity->id);

        if ($is_updated) {
            $updated_activity = new ActivityResource($this->activityRepository->find($activity->id));
            $playlist = new PlaylistResource($updated_activity->playlist);
            event(new ActivityUpdatedEvent($playlist->project, $playlist, $updated_activity));

            return response([
                'activity' => $updated_activity,
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove share activity.'],
        ], 500);
    }

    /**
     * Remove Activity
     *
     * Remove the specified activity.
     *
     * @urlParam playlist required The Id of a playlist Example: 1
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @response {
     *   "message": "Activity has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete activity."
     *   ]
     * }
     *
     * @param Playlist $playlist
     * @param Activity $activity
     * @return Response
     */
    public function destroy(Playlist $playlist, Activity $activity)
    {
        if ($activity->playlist_id !== $playlist->id) {
            return response([
                'errors' => ['Invalid playlist or activity id.'],
            ], 400);
        }

        $is_deleted = $this->activityRepository->delete($activity->id);

        if ($is_deleted) {
            return response([
                'message' => 'Activity has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity.'],
        ], 500);
    }

    /**
     * Clone Activity
     *
     * Clone the specified activity of a playlist.
     *
     * @urlParam playlist required The Id of a playlist Example: 1
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @response {
     *   "message": "Activity is being cloned|duplicated in background!"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Not a Public Activity."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to clone activity."
     *   ]
     * }
     *
     * @param Request $request
     * @param Playlist $playlist
     * @param Activity $activity
     * @return Response
     */
    public function clone(Request $request, Playlist $playlist, Activity $activity)
    {
        $this->authorize('view', [Project::class, $activity->playlist->project->organization]);

        CloneActivity::dispatch($playlist, $activity, $request->bearerToken())->delay(now()->addSecond());
        $isDuplicate = ($activity->playlist_id == $playlist->id);
        $process = ($isDuplicate) ? "duplicate" : "clone";
        return response([
            "message" => "Your request to $process  activity [$activity->title] has been received and is being processed. You will receive an email notice as soon as it is available.",
        ], 200);
    }

    /**
     * H5P Activity
     *
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity-playlists.json
     *
     * @param Activity $activity
     * @return Response
     */
    public function h5p(Activity $activity)
    {
        $this->authorize('view', [Project::class, $activity->playlist->project->organization]);

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
     * Get H5P Resource Settings
     *
     * Get H5P Resource Settings for a activity
     *
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/h5p/h5p-resource-settings-open.json
     *
     * @response 500 {
     *   "errors": [
     *     "Activity doesn't belong to this user."
     *   ]
     * }
     *
     * @param Activity $activity
     * @return Response
     */
    public function getH5pResourceSettings(Activity $activity)
    {
        $authenticated_user = auth()->user();

        if (!$authenticated_user->isAdmin() && !$this->hasPermission($activity)) {
            return response([
                'errors' => ["Activity doesn't belong to this user."]
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
     * Get H5P Resource Settings (Open)
     *
     * Get H5P Resource Settings for a activity
     *
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/h5p/h5p-resource-settings-open.json
     *
     * @param Activity $activity
     * @return Response
     */
    public function getH5pResourceSettingsOpen(Activity $activity)
    {
        $this->authorize('view', [Project::class, $activity->playlist->project->organization]);

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
     * Get H5P Resource Settings (Shared)
     *
     * Get H5P Resource Settings for a shared activity
     *
     * @urlParam activity required The Id of a activity
     *
     * @responseFile responses/h5p/h5p-resource-settings-open.json
     *
     * @response 400 {
     *   "errors": [
     *     "Activity not found."
     *   ]
     * }
     *
     * @param Activity $activity
     * @return Response
     */
    public function getH5pResourceSettingsShared(Activity $activity)
    {
        // 3 is for indexing approved - see Project Model @indexing property
        if ($activity->shared || ($activity->playlist->project->indexing === config('constants.indexing-approved'))) {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $settings = $h5p::get_editor();
            $content = $h5p->load_content($activity->h5p_content_id);
            $content['disable'] = config('laravel-h5p.h5p_preview_flag');
            $embed = $h5p->get_embed($content, $settings);
            $embed_code = $embed['embed'];
            $settings = $embed['settings'];
            $user_data = null;
            $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];

            return response([
                'h5p' => $h5p_data,
                'activity' => new ActivityResource($activity),
                'playlist' => new PlaylistResource($activity->playlist),
            ], 200);
        }

        return response([
            'errors' => ['Activity not found.']
        ], 400);
    }

    /**
     * Check permission
     *
     * @param Activity $activity
     * @return bool
     */
    private function hasPermission(Activity $activity)
    {
        $authenticated_user = auth()->user();
        $project = $activity->playlist->project;
        $project_users = $project->users;
        foreach ($project_users as $project_user) {
            if ($authenticated_user->id === $project_user->id) {
                return true;
            }
        }

        $project_teams = $project->teams;
        foreach ($project_teams as $project_team) {
            $team_project_user = TeamProjectUser::where('team_id', $project_team->id)
                ->where('project_id', $project->id)
                ->where('user_id', $authenticated_user->id)
                ->exists();
            if ($team_project_user) {
                return true;
            }
        }

        return false;
    }

    /**
     * @uses One time script to populate all missing order number
     */
    public function populateOrderNumber()
    {
        $this->activityRepository->populateOrderNumber();
    }

}

