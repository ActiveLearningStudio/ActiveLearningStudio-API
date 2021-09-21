<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\H5pActivityResource;
use App\Models\Activity;
use Djoudi\LaravelH5p\Eloquents\H5pContent;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Djoudi\LaravelH5p\Exceptions\H5PException;
use Djoudi\LaravelH5p\LaravelH5p;
use H5pCore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Repositories\CurrikiGo\ContentUserDataGo\ContentUserDataGoRepositoryInterface;

/**
 * @group 12. H5P
 *
 * APIs for H5P management
 */
class H5pController extends Controller
{

    private $contentUserDataGoRepository;

    /**
     * ActivityController constructor.
     * 
     * @param ContentUserDataGoRepositoryInterface $contentUserDataGoRepository
     */
    public function __construct(ContentUserDataGoRepositoryInterface $contentUserDataGoRepository)
    {
        $this->contentUserDataGoRepository = $contentUserDataGoRepository;
    }

    /**
     * Get H5Ps
     *
     * Get a list of the H5Ps.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = H5pContent::orderBy('h5p_contents.id', 'desc');

        if ($request->query('sf') && $request->query('s')) {
            if ($request->query('sf') == 'title') {
                $query->where('h5p_contents.title', $request->query('s'));
            }
            if ($request->query('sf') == 'creator') {
                $query->leftJoin('users', 'users.id', 'h5p_contents.user_id')->where('users.name', 'like', '%' . $request->query('s') . '%');
            }
        }

        $search_fields = [
            'title' => trans('laravel-h5p.content.title'),
            'creator' => trans('laravel-h5p.content.creator'),
        ];

        $entries = $query->paginate(10);
        $entries->appends(['sf' => $request->query('sf'), 's' => $request->query('s')]);

        return response()->json(compact('entries', 'request', 'search_fields'), 200);
    }

    /**
     * Create H5P Settings
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

        // Prepare form
        $library = 0;
        if ($request->get('machineName') && $request->get('majorVersion') && $request->get('minorVersion')) {
            $library = $request->get('machineName') . ' ' . $request->get('majorVersion') . '.' . $request->get('minorVersion');
        }

        $parameters = '{"params":{},"metadata":{}}';

        $display_options = $core->getDisplayOptionsForEdit(NULL);

        // view Get the file and settings to print from
        $settings = $h5p::get_editor();

        // create event dispatch
        event(new H5pEvent('content', 'new'));

        $user = Auth::user();

        if (is_null($user) && $request->get('api_token')) {
            $user = DB::table('users')->where('api_token', $request->get('api_token'))->first();
        }

        return response([
            'h5p' => compact('settings', 'user', 'library', 'parameters', 'display_options')
        ], 200);
    }

    /**
     * Store H5P
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    // TODO: need to unify error handling
    public function store(Request $request)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        $this->validate(
            $request,
            ['action' => 'required'],
            [],
            ['action' => trans('laravel-h5p.content.action')]
        );

        $oldLibrary = NULL;
        $oldParams = NULL;
        $event_type = 'create';
        $content = array(
            'disable' => H5PCore::DISABLE_NONE,
        );

        try {
            if ($request->get('action') === 'create') {
                $content['library'] = $core->libraryFromString($request->get('library'));
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

                $content['params'] = $request->get('parameters');
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
                $content['id'] = $core->saveContent($content);

                // Move images and find all content dependencies
                $editor->processParameters($content['id'], $content['library'], $params->params, $oldLibrary, $oldParams);
                $return_id = $content['id'];
            } elseif ($request->get('action') === 'upload') {
                $content['uploaded'] = true;

                $this->get_disabled_content_features($core, $content);

                // Handle file upload
                $return_id = $this->handle_upload($content);
                if (intval($return_id) > 0) {
                    return response([
                        'success' => trans('laravel-h5p.content.created'),
                        'id' => $return_id,
                        'type' => 'h5p'
                    ], 201);
                } else {
                    return response([
                        'fail' => trans('laravel-h5p.content.can_not_created')
                    ], 422);
                }
            }

            if ($return_id) {
                return response([
                    'success' => trans('laravel-h5p.content.created'),
                    'id' => $return_id,
                    'title' => $content['metadata']->title,
                    'type' => 'h5p'
                ], 201);
            } else {
                return response([
                    'fail' => trans('laravel-h5p.content.can_not_created')
                ], 400);
            }
        } catch (H5PException $ex) {
            return response([
                'fail' => trans('laravel-h5p.content.can_not_created')
            ], 400);
        }
    }

    /**
     * Get H5P
     *
     * Get the specified H5P
     *
     * @urlParam id required The Id of a H5p
     *
     * @responseFile responses/h5p/h5p-edit.json
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;
        $content = $h5p->load_content($id);

        // Prepare form
        $library = $content['library'] ? H5PCore::libraryToString($content['library']) : 0;
        $parameters = '{"params":' . $core->filterParameters($content) . ',"metadata":' . json_encode((object)$content['metadata']) . '}';
        $display_options = $core->getDisplayOptionsForEdit($content['disable']);

        // view Get the file and settings to print from
        $settings = $h5p::get_editor($content);

        // create event dispatch
        event(new H5pEvent(
            'content',
            'edit',
            $content['id'],
            $content['title'],
            $content['library']['name'],
            $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']
        ));

        $user = Auth::user();
        if (is_null($user) && $request->get('api_token')) {
            $user = DB::table('users')->where('api_token', $request->get('api_token'))->first();
        }

        return response([
            'settings' => $settings,
            'user' => $user,
            'id' => $id,
            'content' => $content,
            'library' => $library,
            'parameters' => $parameters,
            'display_options' => $display_options
        ], 200);
    }

    /**
     * Get H5P based on Activity
     *
     * @urlParam activity required The Id of a activity Example: 1
     * @urlParam visibility The status of visibility
     *
     * @responseFile responses/h5p-activity.json
     *
     * @param Activity $activity
     * @param $visibility
     *
     * @return Response
     */
    public function showByActivity(Activity $activity, $visibility = null)
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

        if ($user && is_null($visibility)) {
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
        } else {
            $user_data = null;
        }

        $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];
        return response([
            'h5p_activity' => new H5pActivityResource($activity, $h5p_data),
        ], 200);
    }

    /**
     * Update H5P
     *
     * Update the specified H5P
     *
     * @urlParam id required The Id of a H5p Example 5
     *
     * @response 200 {
     *   "success": "Content updated.",
     *   "id": 5
     * }
     *
     * @response 400 {
     *   "fail": "Can not update."
     * }
     *
     * @param Request $request
     * @param $id
     * @return Response
     * @throws ValidationException
     */
    // TODO: need to unify error handling
    public function update(Request $request, $id)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        $this->validate(
            $request,
            ['action' => 'required'],
            [],
            [
                'title' => trans('laravel-h5p.content.title'),
                'action' => trans('laravel-h5p.content.action'),
            ]
        );

        $event_type = 'update';
        $content = $h5p->load_content($id);
        $content['disable'] = H5PCore::DISABLE_NONE;

        $oldLibrary = $content['library'];
        $oldParams = json_decode($content['params']);

        try {
            if ($request->get('action') === 'create') {
                $content['library'] = $core->libraryFromString($request->get('library'));
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

                $content['params'] = $request->get('parameters');
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
     * Get H5P
     *
     * Get the specified H5P
     *
     * @urlParam id required The Id of a H5p Example: 1
     *
     * @responseFile responses/h5p/h5p-get.json
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        $content = $h5p->load_content($id);
        $content['disable'] = 8; // always enable H5P frame which include 'Reuse' and 'Embed' links
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];
        $user = Auth::user();
        if (is_null($user) && $request->get('api_token')) {
            $user = DB::table('users')->where('api_token', $request->get('api_token'))->first();
        }

        // create event dispatch
        event(new H5pEvent(
            'content',
            NULL,
            $content['id'],
            $content['title'],
            $content['library']['name'],
            $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']
        ));

        return response([
            'settings' => $settings,
            'user' => $user,
            'embed_code' => $embed_code
        ], 200);
    }

    /**
     * Get H5P Embed
     *
     * Get the specified H5P embed parameters
     *
     * @urlParam id required The Id of a H5p Example: 1
     *
     * @responseFile responses/h5p/h5p-embed.json
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function embed(Request $request, $id)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        $content = $h5p->get_content($id);
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];
        return response(compact('settings', 'embed_code'), 200);
    }

    /**
     * Remove H5P
     *
     * Remove the specified H5P
     *
     * @urlParam id required The Id of a H5P Example: 1
     *
     * @param Request $request
     * @param $id
     * @return Response|string
     */
    public function destroy(Request $request, $id)
    {
        try {
            $content = H5pContent::findOrFail($id);
            return $content->delete();
        } catch (Exception $ex) {
            return trans('laravel-h5p.content.can_not_delete');
        }
    }

    private function get_disabled_content_features($core, &$content)
    {
        $set = array(
            H5PCore::DISPLAY_OPTION_FRAME => filter_input(INPUT_POST, 'frame', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_DOWNLOAD => filter_input(INPUT_POST, 'download', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_EMBED => filter_input(INPUT_POST, 'embed', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_COPYRIGHT => filter_input(INPUT_POST, 'copyright', FILTER_VALIDATE_BOOLEAN),
        );
        $content['disable'] = $core->getStorableDisplayOptions($set, $content['disable']);
    }

    private function handle_upload($content = NULL, $only_upgrade = NULL, $disable_h5p_security = false)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $validator = $h5p::$validator;
        $interface = $h5p::$interface;
        $storage = $h5p::$storage;

        if ($disable_h5p_security) {
            // Make it possible to disable file extension check
            $core->disableFileCheck = (filter_input(INPUT_POST, 'h5p_disable_file_check', FILTER_VALIDATE_BOOLEAN) ? TRUE : FALSE);
        }

        // Move so core can validate the file extension.
        rename($_FILES['h5p_file']['tmp_name'], $interface->getUploadedH5pPath());

        $skipContent = ($content === NULL);

        if ($validator->isValidPackage($skipContent, $only_upgrade)) {
            $tmpDir = $interface->getUploadedH5pFolderPath();

            if (!$skipContent) {
                foreach ($validator->h5pC->mainJsonData['preloadedDependencies'] as $dep) {
                    if ($dep['machineName'] === $validator->h5pC->mainJsonData['mainLibrary']) {
                        if ($validator->h5pF->libraryHasUpgrade($dep)) {
                            // We do not allow storing old content due to security concerns
                            $interface->setErrorMessage(__("You're trying to upload content of an older version of H5P. Please upgrade the content on the server it originated from and try to upload again or turn on the H5P Hub to have this server upgrade it for you automatically.", $this->plugin_slug));
                            H5PCore::deleteFileTree($tmpDir);
                            return FALSE;
                        }
                    }
                }

                if (empty($content['metadata']) || empty($content['metadata']['title'])) {
                    // Fix for legacy content upload to work.
                    // Fetch title from h5p.json or use a default string if not available
                    $content['metadata']['title'] = empty($validator->h5pC->mainJsonData['title']) ? 'Uploaded Content' : $validator->h5pC->mainJsonData['title'];
                }
            }

            if (function_exists('check_upload_size')) {
                // Check file sizes before continuing!
                $error = self::check_upload_sizes($tmpDir);
                if ($error !== NULL) {
                    // Didn't meet space requirements, cleanup tmp dir.
                    $interface->setErrorMessage($error);
                    H5PCore::deleteFileTree($tmpDir);
                    return FALSE;
                }
            }

            // No file size check errors
            if (isset($content['id'])) {
                $interface->deleteLibraryUsage($content['id']);
            }

            $storage->savePackage($content, NULL, $skipContent);

            // Clear cached value for dirsize.
            return $storage->contentId;
        }

        // The uploaded file was not a valid H5P package
        @unlink($interface->getUploadedH5pPath());
        return FALSE;
    }
    
    public function contentUserData(Request $request)
    {
        $contentId =$request->get('content_id');
        $dataId =$request->get('data_type');
        $subContentId =$request->get('sub_content_id');
        $userId = $request->get('gcuid');
        $data = $request->input("data");
        $preload = $request->input("preload");
        $invalidate = $request->input("invalidate");
        $submissionId = $request->get('submissionid');

        if ($contentId === NULL ||
            $dataId === NULL ||
            $subContentId === NULL ||
            $userId === NULL || $submissionId === NULL) {
        return; // Missing parameters
        }
        
        if ($data === NULL) {
            return response()->json(["data" => false, "success" => true]);
        }
        
        if ($request->get('gcuid')) {
            if ($data === '0') {
                $records = $this->contentUserDataGoRepository->deleteComposite($contentId, $userId, $subContentId, $dataId, $submissionId);
            }else {
                $records = $this->contentUserDataGoRepository->fetchByCompositeKey($contentId, $userId, $subContentId, $dataId, $submissionId);
                
                if ($records->count() === 0) {
                    $this->contentUserDataGoRepository->create([
                        'content_id' => $contentId,
                        'user_id' => $userId,
                        'sub_content_id' => $subContentId,
                        'data_id' => $dataId,
                        'data' => $data,
                        'preload' => $preload,
                        'invalidate' => $invalidate,
                        'go_integration' => 'google-classroom',
                        'submission_id' => $submissionId,
                    ]);
                }else {
                    $this->contentUserDataGoRepository->updateComposite([
                        'data' => $data,
                        'preload' => $preload,
                        'invalidate' => $invalidate,
                    ], $contentId, $userId, $subContentId, $dataId, $submissionId);
                }
            }

            // Inserted, updated or deleted
            H5PCore::ajaxSuccess();
            exit;
        }
        return response()->json($request->all());
    }
}
