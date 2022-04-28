<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\IndependentActivityCreateRequest;
use App\Http\Requests\V1\IndependentActivityEditRequest;
use App\Http\Resources\V1\IndependentActivityResource;
use App\Http\Resources\V1\IndependentActivityDetailResource;
use App\Http\Resources\V1\H5pIndependentActivityResource;
use App\Jobs\CloneIndependentActivity;
use App\Models\IndependentActivity;
use App\Models\ActivityItem;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Repositories\ActivityItem\ActivityItemRepositoryInterface;
use App\Repositories\H5pContent\H5pContentRepositoryInterface;
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

/**
 * @group 5. Independent Activity
 *
 * APIs for independent activity management
 */
class IndependentActivityController extends Controller
{

    private $independentActivityRepository;
    private $h5pContentRepository;

    /**
     * IndependentActivityController constructor.
     *
     * @param IndependentActivityRepositoryInterface $independentActivityRepository
     * @param H5pContentRepositoryInterface $h5pContentRepository
     * @param ActivityItemRepositoryInterface $activityItemRepository
     */
    public function __construct(
        IndependentActivityRepositoryInterface $independentActivityRepository,
        H5pContentRepositoryInterface $h5pContentRepository,
        ActivityItemRepositoryInterface $activityItemRepository
    )
    {
        $this->independentActivityRepository = $independentActivityRepository;
        $this->h5pContentRepository = $h5pContentRepository;
        $this->activityItemRepository = $activityItemRepository;
    }

    /**
     * Get Independent Activities
     *
     * Get a list of independent activities
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile responses/independent-activity/independent-activities.json
     *
     * @param Organization $suborganization
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Organization $suborganization)
    {
        $this->authorize('viewAny', [IndependentActivity::class, $suborganization]);

        return response([
            'independent-activities' => IndependentActivityResource::collection($suborganization->independentActivities),
        ], 200);
    }

    /**
     * Upload Independent Activity thumbnail
     *
     * Upload thumbnail image for an independent activity
     *
     * @bodyParam thumb image required Thumbnail image to upload Example: (binary)
     *
     * @response {
     *   "thumbUrl": "/storage/independent-activities/1fqwe2f65ewf465qwe46weef5w5eqwq.png"
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

        $path = $request->file('thumb')->store('/public/independent-activities');

        return response([
            'thumbUrl' => Storage::url($path),
        ], 200);
    }

    /**
     * Create Independent Activity
     *
     * Create a new independent activity.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam title string required The title of a activity Example: Science of Golf: Why Balls Have Dimples
     * @bodyParam type string required The type of a activity Example: h5p
     * @bodyParam content string required The content of a activity Example:
     * @bodyParam order int The order number of a activity Example: 2
     * @bodyParam h5p_content_id int The Id of H5p content Example: 59
     * @bodyParam thumb_url string The image url of thumbnail Example: null
     * @bodyParam subject_id array The Ids of a subject Example: [1, 2]
     * @bodyParam education_level_id array The Ids of a education level Example: [1, 2]
     * @bodyParam author_tag_id array The Ids of a author tag Example: [1, 2]
     * @bodyParam organization_visibility_type_id int required Id of the organization visibility type Example: 1
     *
     * @responseFile 201 responses/independent-activity/independent-activity.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create independent activity. Please try again later."
     *   ]
     * }
     *
     * @param IndependentActivityCreateRequest $request
     * @param Organization $suborganization
     * @return Response
     * @throws AuthorizationException
     */
    public function store(IndependentActivityCreateRequest $request, Organization $suborganization)
    {
        $this->authorize('create', [IndependentActivity::class, $suborganization]);

        $data = $request->validated();
        $authenticatedUser = auth()->user();

        $independentActivity = $this->independentActivityRepository->createIndependentActivity($authenticatedUser, $suborganization, $data);

        if ($independentActivity) {
            return response([
                'independent-activity' => new IndependentActivityResource($independentActivity),
            ], 201);
        }

        return response([
            'errors' => ['Could not create independent activity. Please try again later.'],
        ], 500);
    }

    /**
     * Get Independent Activity
     *
     * Get the specified independent activity.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam independent_activity required The Id of a independent activity Example: 1
     *
     * @responseFile responses/independent-activity/independent-activity.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid organization or independent activity id."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function show(Organization $suborganization, IndependentActivity $independent_activity)
    {
        $this->authorize('view', $independent_activity);

        if ($independent_activity->organization_id !== $suborganization->id) {
            return response([
                'errors' => ['Invalid organization or independent activity id.'],
            ], 400);
        }

        return response([
            'independent-activity' => new IndependentActivityResource($independent_activity),
        ], 200);
    }

    /**
     * Update Independent Activity
     *
     * Update the specified independent activity.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam independent_activity required The Id of a independent activity Example: 1
     * @bodyParam title string required The title of a activity Example: Science of Golf: Why Balls Have Dimples
     * @bodyParam type string required The type of a activity Example: h5p
     * @bodyParam content string required The content of a activity Example:
     * @bodyParam shared bool The status of share of a activity Example: false
     * @bodyParam order int The order number of a activity Example: 2
     * @bodyParam h5p_content_id int The Id of H5p content Example: 59
     * @bodyParam thumb_url string The image url of thumbnail Example: null
     * @bodyParam subject_id array The Ids of a subject Example: [1, 2]
     * @bodyParam education_level_id array The Ids of a education level Example: [1, 2]
     * @bodyParam author_tag_id array The Ids of a author tag Example: [1, 2]
     * @bodyParam organization_visibility_type_id int required Id of the organization visibility type Example: 1
     *
     * @responseFile responses/independent-activity/independent-activity.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid organization or independent activity id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update independent activity."
     *   ]
     * }
     *
     * @param IndependentActivityEditRequest $request
     * @param Organization $suborganization
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function update(
        IndependentActivityEditRequest $request,
        Organization $suborganization,
        IndependentActivity $independent_activity
    )
    {
        $this->authorize('update', $independent_activity);

        if ($independent_activity->organization_id !== $suborganization->id) {
            return response([
                'errors' => ['Invalid organization or independent activity id.'],
            ], 400);
        }
        $validated = $request->validated();

        return \DB::transaction(function () use ($validated, $independent_activity) {

            $attributes = Arr::except($validated, ['data', 'subject_id', 'education_level_id', 'author_tag_id']);
            $is_updated = $this->independentActivityRepository->update($attributes, $independent_activity->id);

            if ($is_updated) {
                if (isset($validated['subject_id'])) {
                    $independent_activity->subjects()->sync($validated['subject_id']);
                }
                if (isset($validated['education_level_id'])) {
                    $independent_activity->educationLevels()->sync($validated['education_level_id']);
                }
                if (isset($validated['author_tag_id'])) {
                    $independent_activity->authorTags()->sync($validated['author_tag_id']);
                }

                // H5P meta is in 'data' index of the payload.
                $this->update_h5p($validated['data'], $independent_activity->h5p_content_id);

                $updated_independent_activity = new IndependentActivityResource($this->independentActivityRepository->find($independent_activity->id));

                return response([
                    'independent-activity' => $updated_independent_activity,
                ], 200);
            }

            return response([
                'errors' => ['Failed to update independent activity.'],
            ], 500);

        });
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
     * Get Independent Activity Detail
     *
     * Get the specified independent activity in detail.
     *
     * @urlParam independent_activity required The Id of a independent activity Example: 1
     *
     * @responseFile responses/independent-activity/independent-activity-with-detail.json
     *
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function detail(IndependentActivity $independent_activity)
    {
        $this->authorize('view', $independent_activity);

        $data = ['h5p_parameters' => null, 'user_name' => null, 'user_id' => null];

        if ($independent_activity->user) {
            $data['user_name'] = $independent_activity->user;
            $data['user_id'] = $independent_activity->user->id;
        }

        if ($independent_activity->type === 'h5p') {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($independent_activity->h5p_content_id);
            $library = $content['library'] ? \H5PCore::libraryToString($content['library']) : 0;
            $data['h5p_parameters'] = '{"params":' . $core->filterParameters($content) . ',"metadata":' . json_encode((object)$content['metadata']) . '}';
        }

        return response([
            'independent-activity' => new IndependentActivityDetailResource($independent_activity, $data),
        ], 200);
    }

    /**
     * Share Independent Activity
     *
     * Share the specified independent activity.
     *
     * @urlParam independent_activity required The Id of a independent activity Example: 1
     *
     * @responseFile responses/independent-activity/independent-activity-shared.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to share independent activity."
     *   ]
     * }
     *
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function share(IndependentActivity $independent_activity)
    {
        $this->authorize('share', $independent_activity);

        $is_updated = $this->independentActivityRepository->update([
            'shared' => true,
        ], $independent_activity->id);

        if ($is_updated) {
            $updated_independent_activity = new IndependentActivityResource($this->independentActivityRepository->find($independent_activity->id));

            return response([
                'independent-activity' => $updated_independent_activity,
            ], 200);
        }

        return response([
            'errors' => ['Failed to share independent activity.'],
        ], 500);
    }

    /**
     * Remove Share Independent Activity
     *
     * Remove share the specified independent activity.
     *
     * @urlParam independent_activity required The Id of a independent activity Example: 1
     *
     * @responseFile responses/independent-activity/independent-activity.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove share independent activity."
     *   ]
     * }
     *
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function removeShare(IndependentActivity $independent_activity)
    {
        $this->authorize('share', $independent_activity);

        $is_updated = $this->independentActivityRepository->update([
            'shared' => false,
        ], $independent_activity->id);

        if ($is_updated) {
            $updated_independent_activity = new IndependentActivityResource($this->independentActivityRepository->find($independent_activity->id));

            return response([
                'independent-activity' => $updated_independent_activity,
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove share independent activity.'],
        ], 500);
    }

    /**
     * Remove Independent Activity
     *
     * Remove the specified independent activity.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam independent_activity required The Id of a independent activity Example: 1
     *
     * @response {
     *   "message": "Independent activity has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete independent activity."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function destroy(Organization $suborganization, IndependentActivity $independent_activity)
    {
        $this->authorize('delete', $independent_activity);

        if ($independent_activity->organization_id !== $suborganization->id) {
            return response([
                'errors' => ['Invalid organization or independent activity id.'],
            ], 400);
        }

        $is_deleted = $this->independentActivityRepository->delete($independent_activity->id);

        if ($is_deleted) {
            return response([
                'message' => 'Independent activity has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete independent activity.'],
        ], 500);
    }

    /**
     * Clone Independent Activity
     *
     * Clone the specified independent activity of an suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam independent_activity required The Id of a independent activity Example: 1
     *
     * @response {
     *   "message": "Independent Activity is being cloned|duplicated in background!"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Not a Public Independent Activity."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to clone independent activity."
     *   ]
     * }
     *
     * @param Request $request
     * @param Organization $suborganization
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function clone(Request $request, Organization $suborganization, IndependentActivity $independent_activity)
    {
        $this->authorize('clone', $independent_activity);

        CloneIndependentActivity::dispatch($suborganization, $independent_activity, $request->bearerToken())->delay(now()->addSecond());
        $isDuplicate = ($independent_activity->organization_id == $suborganization->id);
        $process = ($isDuplicate) ? "duplicate" : "clone";
        return response([
            "message" => "Your request to $process independent activity [$independent_activity->title] has been received and is being processed. <br>
            You will be alerted in the notification section in the title bar when complete.",
        ], 200);
    }

    /**
     * H5P Independent Activity
     *
     * @urlParam independent_activity required The Id of an independent activity Example: 1
     *
     * @responseFile responses/independent-activity/independent-activity-h5p.json
     *
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function h5p(IndependentActivity $independent_activity)
    {
        $this->authorize('view', $independent_activity);

        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor($content = null, 'preview');
        $content = $h5p->load_content($independent_activity->h5p_content_id);
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
            'independent-activity' => new H5pIndependentActivityResource($independent_activity, $h5p_data)
        ], 200);
    }

    /**
     * Get H5P Resource Settings
     *
     * Get H5P Resource Settings for an independent activity
     *
     * @urlParam independent_activity required The Id of an independent activity Example: 1
     *
     * @responseFile responses/h5p/independent-h5p-resource-settings-open.json
     *
     * @response 500 {
     *   "errors": [
     *     "Independent Activity doesn't belong to this user."
     *   ]
     * }
     *
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function getH5pResourceSettings(IndependentActivity $independent_activity)
    {
        $this->authorize('view', $independent_activity);

        if ($independent_activity->type === 'h5p') {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;
            $content = $h5p->load_content($independent_activity->h5p_content_id);
        }

        return response([
            'h5p' => $content,
            'independent-activity' => new IndependentActivityResource($independent_activity)
        ], 200);
    }

    /**
     * Get H5P Resource Settings (Shared)
     *
     * Get H5P Resource Settings for a shared independent activity
     *
     * @urlParam independent_activity required The Id of an independent activity
     *
     * @responseFile responses/h5p/independent-h5p-resource-settings-open.json
     *
     * @response 400 {
     *   "errors": [
     *     "Independent Activity not found."
     *   ]
     * }
     *
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function getH5pResourceSettingsShared(IndependentActivity $independent_activity)
    {
        // 3 is for indexing approved - see IndependentActivity Model @indexing property
        if ($independent_activity->shared || ($independent_activity->indexing === (int)config('constants.indexing-approved'))) {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $settings = $h5p::get_editor();
            $content = $h5p->load_content($independent_activity->h5p_content_id);
            $content['disable'] = config('laravel-h5p.h5p_preview_flag');
            $embed = $h5p->get_embed($content, $settings);
            $embed_code = $embed['embed'];
            $settings = $embed['settings'];
            $user_data = null;
            $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];

            return response([
                'h5p' => $h5p_data,
                'independent-activity' => new IndependentActivityResource($independent_activity)
            ], 200);
        }

        return response([
            'errors' => ['Independent Activity not found.']
        ], 400);
    }

    /**
     * @uses One time script to populate all missing order number
     */
    public function populateOrderNumber()
    {
        $this->activityRepository->populateOrderNumber();
    }

    /**
     * Get Independent Activity Search Preview
     *
     * Get the specified independent activity search preview.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam independent_activity required The Id of an independent activity Example: 1
     *
     * @responseFile responses/h5p/independent-h5p-resource-settings-open.json
     *
     * @param Organization $suborganization
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function searchPreview(Organization $suborganization, IndependentActivity $independent_activity)
    {
        $this->authorize('searchPreview', [$independent_activity, $suborganization]);

        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        $content = $h5p->load_content($independent_activity->h5p_content_id);
        $content['disable'] = config('laravel-h5p.h5p_preview_flag');
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];
        $user_data = null;
        $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];

        return response([
            'h5p' => $h5p_data,
            'activity' => new IndependentActivityResource($independent_activity),
        ], 200);
    }
}

