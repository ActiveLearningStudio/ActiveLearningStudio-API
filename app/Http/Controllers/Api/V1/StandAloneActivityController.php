<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ActivityEditRequest;
use App\Http\Requests\V1\StandAloneActivityCreateRequest;
use App\Http\Resources\V1\ActivityDetailResource;
use App\Http\Resources\V1\H5pActivityResource;
use App\Http\Resources\V1\StandAloneActivityResource;
use App\Jobs\CloneStandAloneActivity;
use App\Models\Activity;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Djoudi\LaravelH5p\Exceptions\H5PException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use H5pCore;
use App\Models\Organization;
use App\Models\H5pBrightCoveVideoContents;
use App\Repositories\Integration\BrightcoveAPISettingRepository;
use App\CurrikiGo\Brightcove\Client;
use App\CurrikiGo\Brightcove\Videos\UpdateVideoTags;

/**
 * @group 5. Activity
 *
 * APIs for stand alone activity management
 */
class StandAloneActivityController extends Controller
{
    private $activityRepository;

    /**
     * StandAloneActivityController constructor.
     *
     * @param ActivityRepositoryInterface $activityRepository
     * @param BrightcoveAPISettingRepository $brightcoveAPISettingRepository
     */
    public function __construct(
        ActivityRepositoryInterface $activityRepository,
        BrightcoveAPISettingRepository $brightcoveAPISettingRepository
    )
    {
        $this->activityRepository = $activityRepository;
        $this->bcAPISettingRepository = $brightcoveAPISettingRepository;
    }

    /**
     * Get Stand Alone Activities
     *
     * Get a list of stand alone activities
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile responses/activity/activities.json
     *
     * @param Organization $suborganization
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Organization $suborganization, Request $request)
    {
        return StandAloneActivityResource::collection($this->activityRepository->getStandAloneActivities($suborganization->id, $request->all()));
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
     * @urlParam Organization suborganization required The Id of a organization Example: 1
     * @bodyParam title string required The title of a activity Example: Science of Golf: Why Balls Have Dimples
     * @bodyParam content string required The content of a activity Example:
     * @bodyParam h5p_content_id integer The Id of H5p content Example: 59
     * @bodyParam order int The order number of a activity Example: 2
     * @bodyParam shared bool The status of share of a activity Example: false
     * @bodyParam thumb_url string The image url of thumbnail Example: null
     * @bodyParam subject_id array The Ids of a subject Example: [1, 2]
     * @bodyParam education_level_id array The Ids of a education level Example: [1, 2]
     * @bodyParam author_tag_id array The Ids of a author tag Example: [1, 2]
     *
     * @responseFile 201 responses/activity/activity.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create activity. Please try again later."
     *   ]
     * }
     *
     * @param StandAloneActivityCreateRequest $request
     * @param Organization $suborganization
     * @return Response
     * @throws AuthorizationException
     */
    public function store(StandAloneActivityCreateRequest $request, Organization $suborganization)
    {
        $data = $request->validated();
        $data['type'] = 'h5p_standalone';
        $data['user_id'] = auth()->user()->id;

        return \DB::transaction(function () use ($data) {

            $attributes = Arr::except($data, ['subject_id', 'education_level_id', 'author_tag_id']);
            $activity = $this->activityRepository->create($attributes);

            if ($activity) {
                if (isset($data['subject_id'])) {
                    $activity->subjects()->attach($data['subject_id']);
                }
                if (isset($data['education_level_id'])) {
                    $activity->educationLevels()->attach($data['education_level_id']);
                }
                if (isset($data['author_tag_id'])) {
                    $activity->authorTags()->attach($data['author_tag_id']);
                }
                return response([
                    'activity' => new StandAloneActivityResource($activity),
                ], 201);
            }

            return response([
                'errors' => ['Could not create activity. Please try again later.'],
            ], 500);

        });
    }

    /**
     * Get Stand Alone Activity
     *
     * Get the specified stand alone activity.
     *
     * @urlParam suborganization required The Id of a organization Example: 1
     * @urlParam standAloneActivity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid activity id."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param Activity $standAloneActivity
     * @return Response
     */
    public function show(Organization $suborganization, Activity $standAloneActivity)
    {
        return response([
            'activity' => new StandAloneActivityResource($standAloneActivity),
        ], 200);
    }

    /**
     * Update Activity
     *
     * Update the specified activity.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam activity required The Id of a activity Example: 1
     * @bodyParam title string required The title of a activity Example: Science of Golf: Why Balls Have Dimples
     * @bodyParam content string required The content of a activity Example:
     * @bodyParam shared bool The status of share of a activity Example: false
     * @bodyParam order int The order number of a activity Example: 2
     * @bodyParam h5p_content_id integer The Id of H5p content Example: 59
     * @bodyParam thumb_url string The image url of thumbnail Example: null
     * @bodyParam subject_id array The Ids of a subject Example: [1, 2]
     * @bodyParam education_level_id array The Ids of a education level Example: [1, 2]
     * @bodyParam author_tag_id array The Ids of a author tag Example: [1, 2]
     *
     * @responseFile responses/activity/activity.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid activity id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update activity."
     *   ]
     * }
     *
     * @param ActivityEditRequest $request
     * @param Organization $suborganization
     * @param Activity $standAloneActivity
     * @return Response
     */
    public function update(ActivityEditRequest $request, Organization $suborganization,
        Activity $standAloneActivity)
    {
        $validated = $request->validated();

        return \DB::transaction(function () use ($validated, $standAloneActivity) {

            $attributes = Arr::except($validated, ['data', 'subject_id', 'education_level_id', 'author_tag_id']);
            $is_updated = $this->activityRepository->update($attributes, $standAloneActivity->id);

            if ($is_updated) {
                if (isset($validated['subject_id'])) {
                    $standAloneActivity->subjects()->sync($validated['subject_id']);
                }
                if (isset($validated['education_level_id'])) {
                    $standAloneActivity->educationLevels()->sync($validated['education_level_id']);
                }
                if (isset($validated['author_tag_id'])) {
                    $standAloneActivity->authorTags()->sync($validated['author_tag_id']);
                }
                // H5P meta is in 'data' index of the payload.
                $this->update_h5p($validated['data'], $standAloneActivity->h5p_content_id);

                $updated_activity = new StandAloneActivityResource($this->activityRepository->find($standAloneActivity->id));
                return response([
                    'activity' => $updated_activity,
                ], 200);
            }

            return response([
                'errors' => ['Failed to update interactive video.'],
            ], 500);

        });
    }

    /**
     * Update H5P
     * 
     * Update H5P Detail
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
     * @urlParam suborganization required The Id of a organization Example: 1
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity-with-detail.json
     *
     * @param Organization $suborganization
     * @param Activity $activity
     * @return Response
     */
    public function detail(Organization $suborganization, Activity $activity)
    {
        $data = ['h5p_parameters' => null, 'user_name' => null, 'user_id' => null];

        if ($activity->user) {
            $data['user_name'] = $activity->user;
            $data['user_id'] = $activity->user->id;
        }

        if ($activity->type === 'h5p_standalone') {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($activity->h5p_content_id);
            $library = $content['library'] ? \H5PCore::libraryToString($content['library']) : 0;
            $data['h5p_parameters'] = '{"params":' . $core->filterParameters($content) . ',"metadata":' . json_encode((object)$content['metadata']) . '}';
        }

        $brightcoveContentData = H5pBrightCoveVideoContents::where('h5p_content_id', $activity->h5p_content_id)->first();
        $brightcoveData = null;
        if ($brightcoveContentData && $brightcoveContentData->brightcove_api_setting_id) {
            $bcAPISettingRepository = $this->bcAPISettingRepository->find($brightcoveContentData->brightcove_api_setting_id);
            $brightcoveData = [
                'videoId' => $brightcoveContentData->brightcove_video_id,
                'accountId' => $bcAPISettingRepository->account_id,
                'apiSettingId' => $brightcoveContentData->brightcove_api_setting_id
            ];
            $activity->brightcoveData = $brightcoveData;
        }

        return response([
            'activity' => new ActivityDetailResource($activity, $data),
        ], 200);
    }

    /**
     * Remove Standalone Activity
     *
     * Remove the specified activity.
     *
     * @urlParam Organization required The Id of an organization Example: 1
     * @urlParam standAloneActivity required The Id of an activity Example: 1
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
     * @param Organization $suborganization
     * @param Activity $standAloneActivity
     * @return Response
     */
    public function destroy(Organization $suborganization, Activity $standAloneActivity)
    {
        $user = auth()->user();
        if ($user->id !== $standAloneActivity->user_id) {
            return response([
                'errors' => ['Invalid user or interactive video id.'],
            ], 400);
        }

        return \DB::transaction(function () use ($standAloneActivity) {

            // Implement Command Design Pattern to access Update Brightcove Video API
            $bcVideoContentsRow = H5pBrightCoveVideoContents::where('h5p_content_id', $standAloneActivity->h5p_content_id)->first();
            if ($bcVideoContentsRow) {
                $bcAPISetting = $this->bcAPISettingRepository->find($bcVideoContentsRow->brightcove_api_setting_id);
                $bcAPIClient = new Client($bcAPISetting);
                $bcInstance = new UpdateVideoTags($bcAPIClient);
                $bcInstance->fetch($bcAPISetting, $bcVideoContentsRow->brightcove_video_id, 'curriki', true);
            } else {
                return response([
                    'message' => 'Failed to remove brightcove video tags.',
                ], 500);
            }

            $isDeleted = $this->activityRepository->delete($standAloneActivity->id);
            if ($isDeleted) {
                return response([
                    'message' => 'Interactive video has been deleted successfully.',
                ], 200);
            }
            return response([
                'errors' => ['Failed to delete interactive video.'],
            ], 500);

        });
    }

    /**
     * H5P Activity
     * 
     * Get H5P Activity
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam activity required The Id of a activity Example: 1
     *
     * @responseFile responses/activity/activity-playlists.json
     *
     * @param Organization $suborganization
     * @param Activity $activity
     * @return Response
     */
    public function h5p(Organization $suborganization, Activity $activity)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor($content = null, 'preview');
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

        $brightcoveContentData = H5pBrightCoveVideoContents::where('h5p_content_id', $activity->h5p_content_id)->first();
        $brightcoveData = null;
        if ($brightcoveContentData && $brightcoveContentData->brightcove_api_setting_id) {
            $bcAPISettingRepository = $this->bcAPISettingRepository->find($brightcoveContentData->brightcove_api_setting_id);
            $brightcoveData = ['videoId' => $brightcoveContentData->brightcove_video_id, 'accountId' => $bcAPISettingRepository->account_id];
            $activity->brightcoveData = $brightcoveData;
        }

        return response([
            'activity' => new H5pActivityResource($activity, $h5p_data),
        ], 200);
    }

    /**
     * Clone Stand Alone Activity
     *
     * Clone the specified activity of a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam activity required The Id of an activity Example: 1
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
     * @param Organization $suborganization
     * @param Activity $activity
     * @return Response
     */
    public function clone(Request $request, Organization $suborganization, Activity $activity)
    {
        CloneStandAloneActivity::dispatch($activity, $request->bearerToken())->delay(now()->addSecond());
        return response([
            "message" => "Your request to clone interactive video [$activity->title] has been received and is being processed. <br>
            You will be alerted in the notification section in the title bar when complete.",
        ], 200);
    }

}

